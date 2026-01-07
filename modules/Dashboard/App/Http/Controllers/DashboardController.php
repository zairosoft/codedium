<?php

namespace Modules\Dashboard\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\IntentForms\App\Models\Intentform;
use Modules\Expenses\App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Default to monthly view
        $filterType = $request->get('filter_type', 'monthly');
        $paymentMethod = $request->get('payment_method', 'all');

        // Set default filter value based on filter type
        $defaultValue = match ($filterType) {
            'daily', 'weekly' => Carbon::now()->format('Y-m-d'),
            'monthly' => Carbon::now()->format('Y-m'),
            'yearly' => Carbon::now()->format('Y'),
            default => Carbon::now()->format('Y-m')
        };

        $filterValue = $request->get('filter_value', $defaultValue);

        // Initialize queries
        $incomeQuery = Intentform::query()->where('status', '!=', 0);
        $expenseQuery = Expense::query();

        // Apply Payment Method Filters
        if ($paymentMethod !== 'all') {
            if ($paymentMethod === 'cash') {
                $incomeQuery->where('payment_methods', 'เงินสด');
                $expenseQuery->where('payment_method', 'เงินสด');
            } elseif ($paymentMethod === 'transfer') {
                $incomeQuery->where('payment_methods', 'เงินโอน');
                $expenseQuery->where('payment_method', 'เงินโอน');
            }
        }

        // Apply date filters
        switch ($filterType) {
            case 'daily':
                $incomeQuery->whereDate('date', $filterValue);
                $expenseQuery->whereDate('date', $filterValue);
                break;
            case 'weekly':
                $startDate = Carbon::parse($filterValue)->startOfWeek();
                $endDate = Carbon::parse($filterValue)->endOfWeek();
                $incomeQuery->whereBetween('date', [$startDate, $endDate]);
                $expenseQuery->whereBetween('date', [$startDate, $endDate]);
                break;
            case 'monthly':
                $date = Carbon::parse($filterValue . '-01');
                $incomeQuery->whereMonth('date', $date->month)
                    ->whereYear('date', $date->year);
                $expenseQuery->whereMonth('date', $date->month)
                    ->whereYear('date', $date->year);
                break;
            case 'yearly':
                $incomeQuery->whereYear('date', $filterValue);
                $expenseQuery->whereYear('date', $filterValue);
                break;
        }

        // Get totals
        $totalIncome = $incomeQuery->sum('total');
        $totalExpense = $expenseQuery->sum('grand_total');
        $netProfit = $totalIncome - $totalExpense;

        // Income by payment method
        $incomeByPaymentMethod = Intentform::query()
            ->where('status', '!=', 0)
            ->when($filterType == 'daily', function ($q) use ($filterValue) {
                $q->whereDate('date', $filterValue);
            })
            ->when($filterType == 'weekly', function ($q) use ($filterValue) {
                $startDate = Carbon::parse($filterValue)->startOfWeek();
                $endDate = Carbon::parse($filterValue)->endOfWeek();
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->when($filterType == 'monthly', function ($q) use ($filterValue) {
                $date = Carbon::parse($filterValue . '-01');
                $q->whereMonth('date', $date->month)->whereYear('date', $date->year);
            })
            ->when($filterType == 'yearly', function ($q) use ($filterValue) {
                $q->whereYear('date', $filterValue);
            })
            ->select('payment_methods', DB::raw('SUM(total) as total'))
            ->groupBy('payment_methods')
            ->get();

        // Expense by category
        $expenseByCategory = DB::table('expenses')
            ->join('expense_items', 'expenses.id', '=', 'expense_items.expense_id')
            ->join('expense_categories', 'expense_items.category_id', '=', 'expense_categories.id')
            ->when($filterType == 'daily', function ($q) use ($filterValue) {
                $q->whereDate('expenses.date', $filterValue);
            })
            ->when($filterType == 'weekly', function ($q) use ($filterValue) {
                $startDate = Carbon::parse($filterValue)->startOfWeek();
                $endDate = Carbon::parse($filterValue)->endOfWeek();
                $q->whereBetween('expenses.date', [$startDate, $endDate]);
            })
            ->when($filterType == 'monthly', function ($q) use ($filterValue) {
                $date = Carbon::parse($filterValue . '-01');
                $q->whereMonth('expenses.date', $date->month)->whereYear('expenses.date', $date->year);
            })
            ->when($filterType == 'yearly', function ($q) use ($filterValue) {
                $q->whereYear('expenses.date', $filterValue);
            })
            ->select('expense_categories.name as category', DB::raw('SUM(expense_items.total) as total'))
            ->groupBy('expense_categories.name')
            ->get();

        // Recent transactions
        $recentIncome = Intentform::where('status', '!=', 0)->orderBy('date', 'desc')->limit(5)->get();
        $recentExpenses = Expense::with('items.category')->orderBy('date', 'desc')->limit(5)->get();

        return view('dashboard::index', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netProfit' => $netProfit,
            'incomeByPaymentMethod' => $incomeByPaymentMethod,
            'expenseByCategory' => $expenseByCategory,
            'recentIncome' => $recentIncome,
            'recentExpenses' => $recentExpenses,
            'filterType' => $filterType,
            'filterValue' => $filterValue,
            'paymentMethod' => $paymentMethod,
        ]);
    }
}
