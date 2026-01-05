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
            'defaultCurrency' => 'THB',
            'defaultVatPercentage' => 7,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'reference_number' => 'nullable|string',
            'payee' => 'required|string',
            'vendor_name' => 'nullable|string',
            'payment_method' => 'required|string',
            'currency' => 'required|string',
            'vat_exempt' => 'nullable|boolean',
            'discount_percentage' => 'nullable|numeric',
            'withholding_tax_percentage' => 'nullable|numeric',
            'status' => 'required|integer',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate subtotal from expense items
            $subtotal = 0;
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    $quantity = $request->quantity[$index] ?? 0;
                    $unitPrice = $request->unit_price[$index] ?? 0;
                    $itemDiscountPercentage = $request->item_discount_percentage[$index] ?? 0;

                    $amount = $quantity * $unitPrice;
                    $itemDiscountAmount = $amount * ($itemDiscountPercentage / 100);
                    $itemTotal = $amount - $itemDiscountAmount;

                    $subtotal += $itemTotal;
                }
            }

            // Calculate discount
            $discountPercentage = $request->discount_percentage ?? 0;
            $discountAmount = $subtotal * ($discountPercentage / 100);
            $afterDiscount = $subtotal - $discountAmount;

            // Calculate VAT
            $vatExempt = $request->has('vat_exempt');
            $vatPercentage = $vatExempt ? 0 : ($request->vat_percentage ?? 7);
            $vatAmount = $afterDiscount * ($vatPercentage / 100);

            // Calculate withholding tax
            $whtPercentage = $request->withholding_tax_percentage ?? 0;
            $whtAmount = $afterDiscount * ($whtPercentage / 100);

            // Calculate grand total
            $grandTotal = $afterDiscount + $vatAmount - $whtAmount;

            // Create expense
            $expense = Expense::create([
                'date' => $request->date,
                'due_date' => $request->due_date,
                'reference_number' => $request->reference_number,
                'payee' => $request->payee,
                'vendor_name' => $request->vendor_name,
                'payment_method' => $request->payment_method,
                'currency' => $request->currency ?? 'THB',
                'vat_exempt' => $vatExempt,
                'subtotal' => $subtotal,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'vat_percentage' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'withholding_tax_percentage' => $whtPercentage,
                'withholding_tax_amount' => $whtAmount,
                'total' => $subtotal,
                'grand_total' => $grandTotal,
                'description' => $request->description,
                'notes' => $request->notes,
                'status' => $request->status,
                'created_by' => Auth::id(),
            ]);

            // Create expense line items
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    if ($categoryId && $categoryId !== 'เลือก') {
                        $quantity = $request->quantity[$index] ?? 0;
                        $unitPrice = $request->unit_price[$index] ?? 0;
                        $itemDiscountPercentage = $request->item_discount_percentage[$index] ?? 0;

                        $amount = $quantity * $unitPrice;
                        $itemDiscountAmount = $amount * ($itemDiscountPercentage / 100);
                        $itemTotal = $amount - $itemDiscountAmount;

                        ExpenseItem::create([
                            'expense_id' => $expense->id,
                            'category_id' => $categoryId,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'discount_percentage' => $itemDiscountPercentage,
                            'discount_amount' => $itemDiscountAmount,
                            'amount' => $amount,
                            'total' => $itemTotal,
                            'description' => $request->item_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('expenses/attachments', $filename, 'public');

                    $expense->attachments()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_by' => Auth::id(),
                    ]);
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
        $expense = Expense::with(['items.category', 'attachments'])->findOrFail($id);

        return view('expenses::view', [
            'expense' => $expense,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $expense = Expense::with(['items', 'attachments'])->findOrFail($id);
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
            'due_date' => 'nullable|date',
            'reference_number' => 'nullable|string',
            'payee' => 'required|string',
            'vendor_name' => 'nullable|string',
            'payment_method' => 'required|string',
            'currency' => 'required|string',
            'vat_exempt' => 'nullable|boolean',
            'discount_percentage' => 'nullable|numeric',
            'withholding_tax_percentage' => 'nullable|numeric',
            'status' => 'required|integer',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $expense = Expense::findOrFail($id);

        DB::beginTransaction();
        try {
            // Calculate subtotal from expense items
            $subtotal = 0;
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    $quantity = $request->quantity[$index] ?? 0;
                    $unitPrice = $request->unit_price[$index] ?? 0;
                    $itemDiscountPercentage = $request->item_discount_percentage[$index] ?? 0;

                    $amount = $quantity * $unitPrice;
                    $itemDiscountAmount = $amount * ($itemDiscountPercentage / 100);
                    $itemTotal = $amount - $itemDiscountAmount;

                    $subtotal += $itemTotal;
                }
            }

            // Calculate discount
            $discountPercentage = $request->discount_percentage ?? 0;
            $discountAmount = $subtotal * ($discountPercentage / 100);
            $afterDiscount = $subtotal - $discountAmount;

            // Calculate VAT
            $vatExempt = $request->has('vat_exempt');
            $vatPercentage = $vatExempt ? 0 : ($request->vat_percentage ?? 7);
            $vatAmount = $afterDiscount * ($vatPercentage / 100);

            // Calculate withholding tax
            $whtPercentage = $request->withholding_tax_percentage ?? 0;
            $whtAmount = $afterDiscount * ($whtPercentage / 100);

            // Calculate grand total
            $grandTotal = $afterDiscount + $vatAmount - $whtAmount;

            // Update expense
            $expense->update([
                'date' => $request->date,
                'due_date' => $request->due_date,
                'reference_number' => $request->reference_number,
                'payee' => $request->payee,
                'vendor_name' => $request->vendor_name,
                'payment_method' => $request->payment_method,
                'currency' => $request->currency ?? 'THB',
                'vat_exempt' => $vatExempt,
                'subtotal' => $subtotal,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'vat_percentage' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'withholding_tax_percentage' => $whtPercentage,
                'withholding_tax_amount' => $whtAmount,
                'total' => $subtotal,
                'grand_total' => $grandTotal,
                'description' => $request->description,
                'notes' => $request->notes,
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);

            // Delete existing items
            ExpenseItem::where('expense_id', $expense->id)->delete();

            // Create new expense line items
            if ($request->has('category_id') && is_array($request->category_id)) {
                foreach ($request->category_id as $index => $categoryId) {
                    if ($categoryId && $categoryId !== 'เลือก') {
                        $quantity = $request->quantity[$index] ?? 0;
                        $unitPrice = $request->unit_price[$index] ?? 0;
                        $itemDiscountPercentage = $request->item_discount_percentage[$index] ?? 0;

                        $amount = $quantity * $unitPrice;
                        $itemDiscountAmount = $amount * ($itemDiscountPercentage / 100);
                        $itemTotal = $amount - $itemDiscountAmount;

                        ExpenseItem::create([
                            'expense_id' => $expense->id,
                            'category_id' => $categoryId,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'discount_percentage' => $itemDiscountPercentage,
                            'discount_amount' => $itemDiscountAmount,
                            'amount' => $amount,
                            'total' => $itemTotal,
                            'description' => $request->item_description[$index] ?? null,
                            'chart_of_account_id' => null, // Future use
                        ]);
                    }
                }
            }

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('expenses/attachments', 'public');

                    $expense->attachments()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getClientMimeType(),
                        'uploaded_by' => Auth::id(),
                    ]);
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

    /**
     * Display report page with filters
     */
    public function report(Request $request)
    {
        $query = Expense::with('items.category')->orderBy('date', 'desc');

        // Apply filters
        if ($request->has('filter_type') && $request->has('filter_value')) {
            $filterType = $request->filter_type;
            $filterValue = $request->filter_value;

            switch ($filterType) {
                case 'daily':
                    $query->whereDate('date', $filterValue);
                    break;
                case 'weekly':
                    $startDate = Carbon::parse($filterValue)->startOfWeek();
                    $endDate = Carbon::parse($filterValue)->endOfWeek();
                    $query->whereBetween('date', [$startDate, $endDate]);
                    break;
                case 'monthly':
                    $date = Carbon::parse($filterValue . '-01');
                    $query->whereMonth('date', $date->month)
                        ->whereYear('date', $date->year);
                    break;
                case 'yearly':
                    $query->whereYear('date', $filterValue);
                    break;
            }
        }

        $expenses = $query->get();
        $totalAmount = $expenses->sum('total');

        return view('expenses::report', [
            'expenses' => $expenses,
            'totalAmount' => $totalAmount,
            'filterType' => $request->filter_type ?? 'monthly',
            'filterValue' => $request->filter_value ?? Carbon::now()->format('Y-m'),
        ]);
    }

    /**
     * Export report to Excel
     */
    public function exportReport(Request $request)
    {
        $query = Expense::with('items.category')->orderBy('date', 'asc');
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Apply same filters as report page
        if ($request->has('filter_type') && $request->has('filter_value')) {
            $filterType = $request->filter_type;
            $filterValue = $request->filter_value;

            switch ($filterType) {
                case 'daily':
                    $query->whereDate('date', $filterValue);
                    $date = Carbon::parse($filterValue);
                    $month = $date->month;
                    $year = $date->year;
                    $filename = 'expense_report_' . $date->format('d-m-Y');
                    break;
                case 'weekly':
                    $startDate = Carbon::parse($filterValue)->startOfWeek();
                    $endDate = Carbon::parse($filterValue)->endOfWeek();
                    $query->whereBetween('date', [$startDate, $endDate]);
                    $month = $startDate->month;
                    $year = $startDate->year;
                    $filename = 'expense_report_week_' . $startDate->format('d-m-Y');
                    break;
                case 'monthly':
                    $date = Carbon::parse($filterValue . '-01');
                    $query->whereMonth('date', $date->month)
                        ->whereYear('date', $date->year);
                    $month = $date->month;
                    $year = $date->year;
                    // Use Thai Buddhist year
                    $thaiYear = $date->year + 543;
                    $filename = 'expense_report_' . $date->month . ':' . $thaiYear;
                    break;
                case 'yearly':
                    $query->whereYear('date', $filterValue);
                    $year = $filterValue;
                    $month = 1; // Default to January for title
                    $thaiYear = $filterValue + 543;
                    $filename = 'expense_report_' . $thaiYear;
                    break;
                default:
                    $filename = 'expense_report_' . Carbon::now()->format('m-Y');
            }
        } else {
            $filename = 'expense_report_' . Carbon::now()->format('m-Y');
        }

        $expenses = $query->get();

        return Excel::download(
            new ExpenseReportExport($expenses, $month, $year),
            $filename . '.xlsx'
        );
    }

    /**
     * Display print view for expense
     */
    public function print($id)
    {
        $expense = Expense::with('items.category')->findOrFail($id);
        $company = \App\Models\CompanyLang::first();

        return view('expenses::print', [
            'expense' => $expense,
            'company' => $company,
        ]);
    }
}
