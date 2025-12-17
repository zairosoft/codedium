<?php

namespace Modules\IntentForms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\IntentForms\App\Models\Type;
use Modules\IntentForms\App\Models\Donation;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = Type::orderBy('id', 'desc')->get();

        return view('intentforms::types.index', [
            'types' => $types,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('intentforms::types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            Type::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            Session::flash('success', 'เพิ่มประเภทการบริจาคสำเร็จ');
            return redirect()->route('intentform.types.index');
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
        $type = Type::findOrFail($id);

        return view('intentforms::types.edit', [
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $type = Type::findOrFail($id);

            $type->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            Session::flash('success', 'อัพเดทประเภทการบริจาคสำเร็จ');
            return redirect()->route('intentform.types.index');
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
            $type = Type::findOrFail($id);

            // Check if type is being used in any donations
            $donationCount = Donation::where('type_id', $type->id)->count();

            if ($donationCount > 0) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'ไม่สามารถลบได้ เนื่องจากประเภทนี้ถูกใช้งานอยู่ใน ' . $donationCount . ' รายการ'
                ], 400);
            }

            // Delete the type
            $type->delete();

            return response()->json([
                'type' => 'success',
                'message' => 'ลบประเภทการบริจาคสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}
