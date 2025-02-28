<?php

namespace Modules\Sales\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sales\Models\Sale;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

use Modules\Sales\App\Imports\SalesImport;
use Modules\Sales\App\Imports\ProductsImport;
use Modules\Sales\App\Imports\TargetsImport;


class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:sale view', ['only' => ['index', 'view']]);
        $this->middleware('permission:sale create', ['only' => ['create', 'store']]);
        $this->middleware('permission:sale update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:sale delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $sellings = Cache::remember('sellings', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return DB::table('sales')
                ->select('*')
                ->get();
        });
        return view('sales::index', ['sellings' => $sellings]);
    }

    public function dashboard()
    {
        $year = request()->get(key: 'd') ? request()->get('d') : date('Y');
        $customers = Cache::remember('customers' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sales')
                ->select('customer_name', 'status', DB::raw('sum(total) AS total'))
                ->whereYear('date', '=', $year)
                ->where('status', '=', 'ออกแล้ว')
                ->groupBy(['customer_name', 'status'])
                ->orderBy('total', 'DESC')
                ->get();
        });

        $years = Cache::remember('years', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return DB::table('sales')->select(DB::raw('YEAR(date) year'))->where('status', '=', 'ออกแล้ว')->groupBy('year')->orderBy('year', 'DESC')->get();
        });

        $sellings = Cache::remember('sales' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sales')
                ->select('sale_name', DB::raw('sum(total) AS total'))
                ->whereYear('date', '=', $year)
                ->where('status', '=', 'ออกแล้ว')
                ->whereNotNull('total')
                //->where('sale_name', 'IS NOT', null)
                ->groupBy(['sale_name'])
                ->orderBy('total', 'DESC')
                ->get();
        });
        $total = Cache::remember('total' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sales')
                ->select(DB::raw('sum(total) AS total'))
                ->whereYear('date', '=', $year)
                ->where('status', '=', 'ออกแล้ว')
                ->whereNotNull('total')
                ->get();
        });
        $products = Cache::remember('products' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sales')
                ->select('sale_products.type', DB::raw('sum(sales.total) AS total'))
                ->leftJoin('sale_products', 'sale_products.product_code', '=', 'sales.product_code')
                ->whereYear('sales.date', '=', $year)
                ->where('sales.status', '=', 'ออกแล้ว')
                ->where('sales.total', '!=', '')
                //->groupBy(['sale_products.type'])
                ->groupBy(['sale_products.type', 'sales.total'])
                ->orderBy('sales.total', 'DESC')
                ->get();
        });
        $orders = Cache::remember('orders' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sales')
                ->select(DB::raw('count(id) AS orders'))
                ->whereYear('date', '=', $year)
                ->where('status', '=', 'ออกแล้ว')
                ->where('total', '!=', '')
                ->where('product_name', '!=', '')
                // ->groupBy(['refer'])
                ->get();
        });
        $ordermonth = Cache::remember('ordermonth' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sales')
                ->select(DB::raw('count(id) AS orders'), DB::raw('MONTH(date) month'))
                ->whereYear('date', '=', $year)
                ->where('status', '=', 'ออกแล้ว')
                ->where('total', '!=', '')
                ->where('product_name', '!=', '')
                ->groupBy(['month'])
                ->orderBy('month', 'ASC')
                ->get();
        });
        $incomes = Cache::remember('incomes' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sales')
                ->select(DB::raw('sum(total) AS total'), DB::raw('MONTH(date) month'))
                ->whereYear('date', '=', $year)
                ->where('status', '=', 'ออกแล้ว')
                ->whereNotNull('total')
                //->where('sale_name', 'IS NOT', null)
                ->groupBy(['month'])
                ->orderBy('month', 'ASC')
                ->get();
        });

        // targets รวมทั้งหมดประจำปี
        $targettotals = Cache::remember('targettotals' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sale_product_targets')
                ->select(DB::raw('sum(q1) as Q1'), DB::raw('sum(q2) as Q2'), DB::raw('sum(q3) as Q3'), DB::raw('sum(q4) as Q4'))
                ->whereYear('date', '=', $year)
                ->get();
        });
        $pipelines = Cache::remember('pipelines' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sale_product_pipelines')
                ->select(DB::raw('sum(q1) as Q1'), DB::raw('sum(q2) as Q2'), DB::raw('sum(q3) as Q3'), DB::raw('sum(q4) as Q4'))
                ->whereYear('date', '=', $year)
                ->get();
        });
        $targetprices = Cache::remember('targetprices' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::select("SELECT SUM(CASE WHEN quarter = 'T1' THEN a.total ELSE 0 END) AS T1, SUM(CASE WHEN quarter = 'T2' THEN a.total ELSE 0 END) AS T2, SUM(CASE WHEN quarter = 'T3' THEN a.total ELSE 0 END) AS T3, SUM(CASE WHEN quarter = 'T4' THEN a.total ELSE 0 END) AS T4 FROM sales a INNER JOIN ( SELECT 'T1' AS quarter, '" . $year . "-01-01' AS QuarterStart, '" . $year . "-03-31' AS QuarterEnd UNION ALL SELECT 'T2', '" . $year . "-04-01', '" . $year . "-06-30' UNION ALL SELECT 'T3', '" . $year . "-07-01', '" . $year . "-09-30' UNION ALL SELECT 'T4', '" . $year . "-10-01', '" . $year . "-12-31' ) q ON a.date < q.QuarterEnd AND a.date >= q.QuarterStart WHERE a.status = 'ออกแล้ว'");
        });

        // targets by sale ประจำปี
        $targetbysales = Cache::remember('targetbysales' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sale_product_targets')
                ->select(DB::raw('sale_name as Sale'), DB::raw('sum(q1) as Q1'), DB::raw('sum(q2) as Q2'), DB::raw('sum(q3) as Q3'), DB::raw('sum(q4) as Q4'))
                ->whereYear('date', '=', $year)
                ->groupBy(['sale_name'])
                ->get();
        });
        $pipelinebysales = Cache::remember('saleproductpipelines' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::table('sale_product_pipelines')
                ->select(DB::raw('sale_name as Sale'), DB::raw('sum(q1) as Q1'), DB::raw('sum(q2) as Q2'), DB::raw('sum(q3) as Q3'), DB::raw('sum(q4) as Q4'))
                ->whereYear('date', '=', $year)
                ->groupBy(['sale_name'])
                ->get();
        });

        $pipelinenames = [];
        foreach ($pipelinebysales as $pipeline) {
            if ($pipeline->Sale != "") {
                $pipelinenames[preg_replace('/[[:space:]]+/', '', trim($pipeline->Sale))] = [
                    'Q1' => $pipeline->Q1,
                    'Q2' => $pipeline->Q2,
                    'Q3' => $pipeline->Q3,
                    'Q4' => $pipeline->Q4,
                ];
            }
        }

        $targetbyname = Cache::remember('targetbyname' . $year, now()->addMinutes((int)env('CACHE_EXPIRE')), function () use ($year) {
            return DB::select("SELECT a.sale_name AS Name, SUM(CASE WHEN quarter = 'T1' THEN a.total ELSE 0 END) AS T1, SUM(CASE WHEN quarter = 'T2' THEN a.total ELSE 0 END) AS T2, SUM(CASE WHEN quarter = 'T3' THEN a.total ELSE 0 END) AS T3, SUM(CASE WHEN quarter = 'T4' THEN a.total ELSE 0 END) AS T4 FROM sales a INNER JOIN ( SELECT 'T1' AS quarter, '" . $year . "-01-01' AS QuarterStart, '" . $year . "-03-31' AS QuarterEnd UNION ALL SELECT 'T2', '" . $year . "-04-01', '" . $year . "-06-30' UNION ALL SELECT 'T3', '" . $year . "-07-01', '" . $year . "-09-30' UNION ALL SELECT 'T4', '" . $year . "-10-01', '" . $year . "-12-31' ) q ON a.date < q.QuarterEnd AND a.date >= q.QuarterStart WHERE a.status = 'ออกแล้ว' GROUP BY a.sale_name;");
        });
        $targets = [];
        foreach ($targetbyname as $target) {
            if ($target->Name != "") {
                $targets[preg_replace('/[[:space:]]+/', '', trim($target->Name))] = [
                    'T1' => $target->T1,
                    'T2' => $target->T2,
                    'T3' => $target->T3,
                    'T4' => $target->T4,
                ];
            }
        }

        // ยอกขายประจำเดือน by sale_name
        $saleByMonths = [];
        $saleMonths = DB::table('sales')
            ->select(
                DB::raw('TRIM(sale_name) as name'),
                DB::raw('sum(total) as sums'),
                DB::raw("DATE_FORMAT(date,'%m') as months")
            )
            ->where('status', '=', 'ออกแล้ว')
            ->whereYear('date', '=', $year)
            ->groupBy('months')
            ->groupBy('name')
            ->get();

        $saleMonths->each(function($item) use (&$saleByMonths) {
            if($item->name != ""){
                $saleByMonths[preg_replace('/[[:space:]]+/', '', trim($item->name))][$item->months] = [
                    'sums' => $item->sums
                ];
            }
        });

        $targetMonths = [];
        foreach ($targetbysales as $target) {
            if ($pipeline->Sale != "") {
                $Q1 = round((float)$target->Q1 / 3, 2);
                $Q2 = round((float)$target->Q2 / 3, 2);
                $Q3 = round((float)$target->Q3 / 3, 2);
                $Q4 = round((float)$target->Q4 / 3, 2);

                $targetMonths[preg_replace('/[[:space:]]+/', '', trim($target->Sale))] = [
                    '01' => $Q1,
                    '02' => $Q1,
                    '03' => $Q1,
                    '04' => $Q2,
                    '05' => $Q2,
                    '06' => $Q2,
                    '07' => $Q3,
                    '08' => $Q3,
                    '09' => $Q3,
                    '10' => $Q4,
                    '11' => $Q4,
                    '12' => $Q4
                ];
            }
        }

        return view('sales::dashboard', [
            'customers' => $customers,
            'sellings' => $sellings,
            'products' => $products,
            'orders' => $orders,
            'ordermonth' => $ordermonth,
            'total' => $total,
            'incomes' => $incomes,
            'targettotals' => $targettotals,
            'targetprices' => $targetprices,
            'targetbysales' => $targetbysales,
            'targets' => $targets,
            'pipelines' => $pipelines,
            'pipelinenames' => $pipelinenames,
            'saleByMonths' => $saleByMonths,
            'targetMonths' => $targetMonths,
            'years' => $years
        ]);
    }

    public function create()
    {
        return view('sales::create');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new SalesImport, $request->file('file'));
            Cache::forget('sellings');
            return redirect('sales')->withSuccess('บันทึกสำเร็จ');
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()->withErrors('เกิดข้อผิดพลาดบางประการ กรุณาตรวจสอบ Log Error');
        }
    }

    public function product()
    {
        return view('sales::product.product');
    }

    public function productAdd(Request $request)
    {
        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect('sales')->withSuccess('บันทึกสำเร็จ');
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()->withErrors('เกิดข้อผิดพลาดบางประการ กรุณาตรวจสอบ Log Error');
        }
    }

    public function target()
    {
        return view('sales::product.target');
    }

    public function targetAdd(Request $request)
    {
        //Excel::import(new SalesImport, 'public/PEAK.xlsx');
        Excel::import(new TargetsImport, 'public/peak-new.xlsx');

        // try {
        //     Excel::import(new SalesImport, $request->file('file'));
        //     Cache::forget('sellings');
        //     return redirect('sales')->withSuccess('บันทึกสำเร็จ');
        // } catch (\Exception $ex) {
        //     Log::info($ex);
        //     return redirect()->back()->withErrors('เกิดข้อผิดพลาดบางประการ กรุณาตรวจสอบ Log Error');
        // }
    }
}
