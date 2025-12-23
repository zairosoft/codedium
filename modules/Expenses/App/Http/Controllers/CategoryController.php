<?php

namespace Modules\Expenses\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Expenses\App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:expenses view', ['only' => ['index']]);
        $this->middleware('permission:expenses create', ['only' => ['create', 'store']]);
        $this->middleware('permission:expenses update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:expenses delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExpenseCategory::get();

        return view('expenses::categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses::categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        try {
            ExpenseCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            Session::flash('success', 'บันทึกข้อมูลสำเร็จ');
            return redirect()->route('expenses.categories.index');
        } catch (\Exception $e) {
            Session::flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);

        return view('expenses::categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        try {
            $category = ExpenseCategory::findOrFail($id);

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            Session::flash('success', 'อัพเดทข้อมูลสำเร็จ');
            return redirect()->route('expenses.categories.index');
        } catch (\Exception $e) {
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
            $category = ExpenseCategory::findOrFail($id);
            $category->delete();

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
