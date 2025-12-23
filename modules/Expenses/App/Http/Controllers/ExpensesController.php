<?php

namespace Modules\Expenses\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Expenses\App\Models\Expense;
use Modules\Expenses\App\Models\ExpenseCategory;
use Modules\Expenses\App\Models\ExpenseItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Expenses\App\Exports\ExpenseReportExport;
use Carbon\Carbon;

class ExpensesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:expenses view', ['only' => ['index', 'report', 'show']]);
        $this->middleware('permission:expenses create', ['only' => ['create', 'store']]);
        $this->middleware('permission:expenses update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:expenses delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::orderBy('date', 'desc')->get();

        return view('expenses::index', [
            'expenses' => $expenses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ExpenseCategory::where('status', 1)->get();

        // Generate next reference number
        $lastExpense = Expense::orderBy('id', 'desc')->first();
        $nextRefNumber = $lastExpense ? 'EXP-' . str_pad($lastExpense->id + 1, 5, '0', STR_PAD_LEFT) : 'EXP-00001';

        return view('expenses::create', [
            'categories' => $categories,
            'nextRefNumber' => $nextRefNumber,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'reference_number' => 'nullable|string',
            'category' => 'nullable|string',
            'payee' => 'required|string',
            'payment_method' => 'required|string',
            'status' => 'required|integer',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total from expense items
            $total = 0;
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    $quantity = $request->quantity[$index] ?? 0;
                    $unitPrice = $request->unit_price[$index] ?? 0;
                    $total += $quantity * $unitPrice;
                }
            }

            // Create expense
            $expense = Expense::create([
                'date' => $request->date,
                'reference_number' => $request->reference_number,
                'category' => $request->category,
                'payee' => $request->payee,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'total' => $total,
                'description' => $request->description,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            // Create expense line items
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    if ($categoryId && $categoryId !== 'เลือก') {
                        ExpenseItem::create([
                            'expense_id' => $expense->id,
                            'category_id' => $categoryId,
                            'quantity' => $request->quantity[$index] ?? 1,
                            'unit_price' => $request->unit_price[$index] ?? 0,
                            'sub_total' => ($request->quantity[$index] ?? 0) * ($request->unit_price[$index] ?? 0),
                            'description' => $request->item_description[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('success', 'บันทึกข้อมูลสำเร็จ');
            return redirect()->route('expenses.show', $expense->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $expense = Expense::with('items.category')->findOrFail($id);

        return view('expenses::view', [
            'expense' => $expense,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $expense = Expense::with('items')->findOrFail($id);
        $categories = ExpenseCategory::get();

        return view('expenses::edit', [
            'expense' => $expense,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'reference_number' => 'nullable|string',
            'category' => 'nullable|string',
            'payee' => 'required|string',
            'payment_method' => 'required|string',
            'status' => 'required|integer',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::findOrFail($id);

            // Calculate total from expense items
            $total = 0;
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    $quantity = $request->quantity[$index] ?? 0;
                    $unitPrice = $request->unit_price[$index] ?? 0;
                    $total += $quantity * $unitPrice;
                }
            }

            // Update expense
            $expense->update([
                'date' => $request->date,
                'reference_number' => $request->reference_number,
                'category' => $request->category,
                'payee' => $request->payee,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'total' => $total,
                'description' => $request->description,
                'notes' => $request->notes,
                'updated_by' => Auth::id(),
            ]);

            // Delete existing items
            ExpenseItem::where('expense_id', $expense->id)->delete();

            // Create new expense line items
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    if ($categoryId && $categoryId !== 'เลือก') {
                        ExpenseItem::create([
                            'expense_id' => $expense->id,
                            'category_id' => $categoryId,
                            'quantity' => $request->quantity[$index] ?? 1,
                            'unit_price' => $request->unit_price[$index] ?? 0,
                            'sub_total' => ($request->quantity[$index] ?? 0) * ($request->unit_price[$index] ?? 0),
                            'description' => $request->item_description[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('success', 'อัพเดทข้อมูลสำเร็จ');
            return redirect()->route('expenses');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->input('id');
            $expense = Expense::findOrFail($id);

            // Delete associated items first
            ExpenseItem::where('expense_id', $expense->id)->delete();

            // Delete the expense
            $expense->delete();

            return response()->json([
                'type' => 'success',
                'message' => 'ลบข้อมูลสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}
