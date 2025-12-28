@extends('layouts.layout')
@section('title', 'แดชบอร์ด')
@section('style')
    <style>
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .stat-card.income {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.expense {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        }

        .stat-card.profit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.loss {
            background: linear-gradient(135deg, #ff512f 0%, #dd2476 100%);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .filter-btn {
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .recent-transaction-item {
            transition: all 0.3s ease;
        }

        .recent-transaction-item:hover {
            background: #f3f4f6;
            transform: translateX(4px);
        }

        .dark .recent-transaction-item:hover {
            background: #1f2937;
        }
    </style>
@endsection

@section('content')
    <div>
        <!-- Header -->
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div class="text-lg font-semibold dark:text-white-light">แดชบอร์ด</div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <ul class="flex text-gray-500 dark:text-white-dark">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;"
                            class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">แดชบอร์ด</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="panel mb-5" x-data="{
                        filterType: '{{ $filterType }}',
                        filterValue: '{{ $filterValue }}',
                        setFilter(type) {
                            this.filterType = type;
                            const now = new Date();
                            if (type === 'daily' || type === 'weekly') {
                                this.filterValue = now.toISOString().split('T')[0];
                            } else if (type === 'monthly') {
                                const year = now.getFullYear();
                                const month = String(now.getMonth() + 1).padStart(2, '0');
                                this.filterValue = `${year}-${month}`;
                            } else if (type === 'yearly') {
                                this.filterValue = now.getFullYear().toString();
                            }
                            this.$nextTick(() => {
                                document.getElementById('filterForm').submit();
                            });
                        },
                        submitForm() {
                            document.getElementById('filterForm').submit();
                        }
                    }">
            <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">ประเภทการดู</label>
                        <div class="flex gap-2 flex-wrap">
                            <button type="button" @click="setFilter('daily')" class="filter-btn px-4 py-2 rounded-lg border"
                                :class="filterType === 'daily' ? 'active' : 'border-gray-300'">รายวัน</button>
                            <button type="button" @click="setFilter('weekly')"
                                class="filter-btn px-4 py-2 rounded-lg border"
                                :class="filterType === 'weekly' ? 'active' : 'border-gray-300'">รายสัปดาห์</button>
                            <button type="button" @click="setFilter('monthly')"
                                class="filter-btn px-4 py-2 rounded-lg border"
                                :class="filterType === 'monthly' ? 'active' : 'border-gray-300'">รายเดือน</button>
                            <button type="button" @click="setFilter('yearly')"
                                class="filter-btn px-4 py-2 rounded-lg border"
                                :class="filterType === 'yearly' ? 'active' : 'border-gray-300'">รายปี</button>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">เลือกวันที่</label>
                        <input type="hidden" name="filter_type" x-model="filterType">
                        <input type="date" name="filter_value" x-model="filterValue" @change="submitForm()"
                            x-show="filterType === 'daily' || filterType === 'weekly'" class="form-input">
                        <input type="month" name="filter_value" x-model="filterValue" @change="submitForm()"
                            x-show="filterType === 'monthly'" class="form-input">
                        <input type="number" name="filter_value" x-model="filterValue" @change="submitForm()"
                            x-show="filterType === 'yearly'" class="form-input" min="2000" max="2100" placeholder="ปี ค.ศ.">
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
            <!-- Total Income -->
            <div class="stat-card income">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm opacity-90">รายรับทั้งหมด</div>
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 6V18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path
                            d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="text-3xl font-bold mb-1">฿{{ number_format($totalIncome, 2) }}</div>
                <div class="text-sm opacity-75">Income</div>
            </div>

            <!-- Total Expense -->
            <div class="stat-card expense">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm opacity-90">รายจ่ายทั้งหมด</div>
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 9C4 6.17157 4 4.75736 4.87868 3.87868C5.75736 3 7.17157 3 10 3H14C16.8284 3 18.2426 3 19.1213 3.87868C20 4.75736 20 6.17157 20 9V15C20 17.8284 20 19.2426 19.1213 20.1213C18.2426 21 16.8284 21 14 21H10C7.17157 21 5.75736 21 4.87868 20.1213C4 19.2426 4 17.8284 4 15V9Z"
                            stroke="currentColor" stroke-width="2" />
                        <path d="M8 12L16 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="text-3xl font-bold mb-1">฿{{ number_format($totalExpense, 2) }}</div>
                <div class="text-sm opacity-75">Expenses</div>
            </div>

            <!-- Net Profit/Loss -->
            <div class="stat-card {{ $netProfit >= 0 ? 'profit' : 'loss' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm opacity-90">{{ $netProfit >= 0 ? 'กำไรสุทธิ' : 'ขาดทุน' }}</div>
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        @if ($netProfit >= 0)
                            <path d="M12 5V19M12 5L6 11M12 5L18 11" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        @else
                            <path d="M12 19V5M12 19L18 13M12 19L6 13" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        @endif
                    </svg>
                </div>
                <div class="text-3xl font-bold mb-1">฿{{ number_format(abs($netProfit), 2) }}</div>
                <div class="text-sm opacity-75">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
            <!-- Income by Payment Method -->
            <div class="panel">
                <div class="mb-4 flex items-center justify-between">
                    <h5 class="text-lg font-semibold dark:text-white-light">รายรับตามวิธีการชำระเงิน</h5>
                </div>
                <div class="chart-container">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>

            <!-- Expense by Category -->
            <div class="panel">
                <div class="mb-4 flex items-center justify-between">
                    <h5 class="text-lg font-semibold dark:text-white-light">รายจ่ายตามหมวดหมู่</h5>
                </div>
                <div class="chart-container">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Recent Income -->
            <div class="panel">
                <div class="mb-4 flex items-center justify-between">
                    <h5 class="text-lg font-semibold dark:text-white-light">รายรับล่าสุด</h5>
                    <a href="{{ route('intentform') }}" class="text-primary hover:underline text-sm">ดูทั้งหมด</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentIncome as $income)
                        <div class="recent-transaction-item p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $income->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $income->payment_methods }} |
                                        {{ \Carbon\Carbon::parse($income->date)->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-green-600">฿{{ number_format($income->total, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">ไม่มีข้อมูลรายรับ</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="panel">
                <div class="mb-4 flex items-center justify-between">
                    <h5 class="text-lg font-semibold dark:text-white-light">รายจ่ายล่าสุด</h5>
                    <a href="{{ route('expenses') }}" class="text-primary hover:underline text-sm">ดูทั้งหมด</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentExpenses as $expense)
                        <div class="recent-transaction-item p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $expense->payee }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $expense->items->first()?->category?->name ?? 'ไม่ระบุ' }} |
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-red-600">฿{{ number_format($expense->grand_total, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">ไม่มีข้อมูลรายจ่าย</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Income Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        const incomeData = {
            labels: [
                @foreach ($incomeByPaymentMethod as $item)
                    '{{ $item->payment_methods }}',
                @endforeach
                                ],
            datasets: [{
                label: 'รายรับ (บาท)',
                data: [
                    @foreach ($incomeByPaymentMethod as $item)
                        {{ $item->total }},
                    @endforeach
                                    ],
                backgroundColor: [
                    'rgba(17, 153, 142, 0.8)',
                    'rgba(56, 239, 125, 0.8)',
                    'rgba(102, 126, 234, 0.8)',
                ],
                borderColor: [
                    'rgba(17, 153, 142, 1)',
                    'rgba(56, 239, 125, 1)',
                    'rgba(102, 126, 234, 1)',
                ],
                borderWidth: 2
            }]
        };

        new Chart(incomeCtx, {
            type: 'doughnut',
            data: incomeData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Expense Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseData = {
            labels: [
                @foreach ($expenseByCategory as $item)
                    '{{ $item->category }}',
                @endforeach
                                ],
            datasets: [{
                label: 'รายจ่าย (บาท)',
                data: [
                    @foreach ($expenseByCategory as $item)
                        {{ $item->total }},
                    @endforeach
                                    ],
                backgroundColor: [
                    'rgba(238, 9, 121, 0.8)',
                    'rgba(255, 106, 0, 0.8)',
                    'rgba(255, 81, 47, 0.8)',
                    'rgba(221, 36, 118, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                ],
                borderColor: [
                    'rgba(238, 9, 121, 1)',
                    'rgba(255, 106, 0, 1)',
                    'rgba(255, 81, 47, 1)',
                    'rgba(221, 36, 118, 1)',
                    'rgba(255, 159, 64, 1)',
                ],
                borderWidth: 2
            }]
        };

        new Chart(expenseCtx, {
            type: 'doughnut',
            data: expenseData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endsection