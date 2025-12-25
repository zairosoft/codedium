<?php

namespace Modules\IntentForms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\IntentForms\App\Models\Intentform;
use Modules\IntentForms\App\Models\Type;
use Modules\IntentForms\App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyLang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\IntentForms\App\Exports\IntentFormReportExport;
use Carbon\Carbon;


class IntentFormsController extends Controller
{


    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:intentform view', ['only' => ['index', 'report', 'show']]);
        $this->middleware('permission:intentform create', ['only' => ['create', 'store']]);
        $this->middleware('permission:intentform update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:intentform delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $intentforms = Intentform::orderByDesc('id')->get();


        return view('intentforms::index', [
            'intentforms' => $intentforms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = CompanyLang::first();
        $type = Type::where('status', 1)->get();

        // Get next volume and number
        $lastIntentform = Intentform::orderBy('id', 'desc')->first();

        if ($lastIntentform) {
            if ($lastIntentform->number >= 99) {
                // Reset number to 1 and increment volume
                $nextVolume = $lastIntentform->volume + 1;
                $nextNumber = 1;
            } else {
                // Increment number, keep same volume
                $nextVolume = $lastIntentform->volume;
                $nextNumber = $lastIntentform->number + 1;
            }
        } else {
            // First record
            $nextVolume = 1;
            $nextNumber = 1;
        }

        return view('intentforms::create', [
            'company' => $company,
            'type' => $type,
            'nextVolume' => $nextVolume,
            'nextNumber' => $nextNumber,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_bank' => 'nullable|string',
            'name' => 'required|string',
            'payee' => 'nullable|string',
            'refer' => 'nullable|string',
            'payment_methods' => 'required|string',
            'status' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total from donation items
            $total = 0;
            if ($request->has('type_id') && is_array($request->type_id)) {
                foreach ($request->type_id as $index => $typeId) {
                    $quantity = $request->quantity[$index] ?? 0;
                    $price = $request->price[$index] ?? 0;
                    $total += $quantity * $price;
                }
            }

            // Get next volume and number
            $lastIntentform = Intentform::orderBy('id', 'desc')->first();

            if ($lastIntentform) {
                if ($lastIntentform->number >= 99) {
                    // Reset number to 1 and increment volume
                    $volume = $lastIntentform->volume + 1;
                    $number = 1;
                } else {
                    // Increment number, keep same volume
                    $volume = $lastIntentform->volume;
                    $number = $lastIntentform->number + 1;
                }
            } else {
                // First record
                $volume = 1;
                $number = 1;
            }

            // Create intentform
            $intentform = Intentform::create([
                'volume' => $volume,
                'number' => $number,
                'date' => $request->date,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'account_bank' => $request->account_bank,
                'name' => $request->name,
                'payee' => $request->payee,
                'refer' => $request->refer,
                'payment_methods' => $request->payment_methods,
                'status' => $request->status,
                'total' => $total,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            // Create donation line items
            if ($request->has('type_id') && is_array($request->type_id)) {
                foreach ($request->type_id as $index => $typeId) {
                    if ($typeId && $typeId !== 'เลือก') {
                        Donation::create([
                            'intentform_id' => $intentform->id,
                            'type_id' => $typeId,
                            'quantity' => $request->quantity[$index] ?? 1,
                            'sub_total' => ($request->quantity[$index] ?? 0) * ($request->price[$index] ?? 0),
                            'description' => $request->description[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('success', 'บันทึกข้อมูลสำเร็จ');
            return redirect()->route('intentform.show', $intentform->id);
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
        $intentform = Intentform::with('donations.type')->findOrFail($id);
        $company = CompanyLang::first();

        return view('intentforms::view', [
            'intentform' => $intentform,
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $intentform = Intentform::with('donations')->findOrFail($id);
        $company = CompanyLang::first();
        $type = Type::get();

        return view('intentforms::edit', [
            'intentform' => $intentform,
            'company' => $company,
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_bank' => 'nullable|string',
            'name' => 'required|string',
            'payee' => 'nullable|string',
            'refer' => 'nullable|string',
            'payment_methods' => 'required|string',
            'status' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $intentform = Intentform::findOrFail($id);

            // Calculate total from donation items
            $total = 0;
            if ($request->has('type_id') && is_array($request->type_id)) {
                foreach ($request->type_id as $index => $typeId) {
                    $quantity = $request->quantity[$index] ?? 0;
                    $price = $request->price[$index] ?? 0;
                    $total += $quantity * $price;
                }
            }

            // Update intentform
            $intentform->update([
                'date' => $request->date,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'account_bank' => $request->account_bank,
                'name' => $request->name,
                'payee' => $request->payee,
                'refer' => $request->refer,
                'payment_methods' => $request->payment_methods,
                'status' => $request->status,
                'total' => $total,
                'notes' => $request->notes,
                'updated_by' => Auth::id(),
            ]);

            // Delete existing donations
            Donation::where('intentform_id', $intentform->id)->delete();

            // Create new donation line items
            if ($request->has('type_id') && is_array($request->type_id)) {
                foreach ($request->type_id as $index => $typeId) {
                    if ($typeId && $typeId !== 'เลือก') {
                        Donation::create([
                            'intentform_id' => $intentform->id,
                            'type_id' => $typeId,
                            'quantity' => $request->quantity[$index] ?? 1,
                            'sub_total' => ($request->quantity[$index] ?? 0) * ($request->price[$index] ?? 0),
                            'description' => $request->description[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('success', 'อัพเดทข้อมูลสำเร็จ');
            return redirect()->route('intentform');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function print($id)
    {
        $intentform = Intentform::with('donations.type')->findOrFail($id);
        $company = CompanyLang::first();

        return view('intentforms::print', [
            'intentform' => $intentform,
            'company' => $company,
        ]);
    }

    /**
     * Display report page with filters
     */
    public function report(Request $request)
    {
        $query = Intentform::with('donations.type')->orderBy('date', 'desc');
        $company = CompanyLang::first();

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

        $intentforms = $query->get();
        $totalAmount = $intentforms->sum('total');

        return view('intentforms::report', [
            'intentforms' => $intentforms,
            'totalAmount' => $totalAmount,
            'filterType' => $request->filter_type ?? 'monthly',
            'filterValue' => $request->filter_value ?? Carbon::now()->format('Y-m'),
            'company' => $company,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->input('id');
            $intentform = Intentform::findOrFail($id);

            // Delete associated donations first
            Donation::where('intentform_id', $intentform->id)->delete();

            // Delete the intentform
            $intentform->delete();

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
     * Export report to Excel
     */
    public function exportReport(Request $request)
    {
        $query = Intentform::with('donations.type')->orderBy('date', 'asc');
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
                    $filename = 'report_' . $date->format('d-m-Y');
                    break;
                case 'weekly':
                    $startDate = Carbon::parse($filterValue)->startOfWeek();
                    $endDate = Carbon::parse($filterValue)->endOfWeek();
                    $query->whereBetween('date', [$startDate, $endDate]);
                    $month = $startDate->month;
                    $year = $startDate->year;
                    $filename = 'report_week_' . $startDate->format('d-m-Y');
                    break;
                case 'monthly':
                    $date = Carbon::parse($filterValue . '-01');
                    $query->whereMonth('date', $date->month)
                        ->whereYear('date', $date->year);
                    $month = $date->month;
                    $year = $date->year;
                    // Use Thai Buddhist year
                    $thaiYear = $date->year + 543;
                    $filename = 'report_' . $date->month . ':' . $thaiYear;
                    break;
                case 'yearly':
                    $query->whereYear('date', $filterValue);
                    $year = $filterValue;
                    $month = 1; // Default to January for title
                    $thaiYear = $filterValue + 543;
                    $filename = 'report_' . $thaiYear;
                    break;
                default:
                    $filename = 'report_' . Carbon::now()->format('m-Y');
            }
        } else {
            $filename = 'report_' . Carbon::now()->format('m-Y');
        }

        $intentforms = $query->get();

        return Excel::download(
            new IntentFormReportExport($intentforms, $month, $year),
            $filename . '.xlsx'
        );
    }
}

