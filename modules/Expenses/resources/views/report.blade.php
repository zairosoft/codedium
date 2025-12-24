@extends('layouts.layout')
@section('title', 'รายงานค่าใช้จ่าย')
@section('content')
    <div x-data="reportFilter">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">รายงานค่าใช้จ่าย
            </div>
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
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/expenses') }}">ค่าใช้จ่าย</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;"
                            class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">รายงาน</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="panel mb-5">
            <h5 class="text-lg font-semibold mb-4">กรองข้อมูล</h5>
            <form method="GET" action="{{ route('expenses.report') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="filter_type">ประเภทการกรอง</label>
                    <select id="filter_type" name="filter_type" class="form-select" x-model="filterType"
                        @change="updateFilterValue()">
                        <option value="daily">รายวัน</option>
                        <option value="weekly">รายสัปดาห์</option>
                        <option value="monthly" selected>รายเดือน</option>
                        <option value="yearly">รายปี</option>
                    </select>
                </div>

                <div>
                    <label for="filter_value">
                        <span x-show="filterType === 'daily'">เลือกวันที่</span>
                        <span x-show="filterType === 'weekly'">เลือกสัปดาห์</span>
                        <span x-show="filterType === 'monthly'">เลือกเดือน</span>
                        <span x-show="filterType === 'yearly'">เลือกปี</span>
                    </label>

                    <!-- Daily -->
                    <input x-show="filterType === 'daily'" type="date" name="filter_value" class="form-input"
                        :value="filterType === 'daily' ? filterValue : ''" />

                    <!-- Weekly -->
                    <input x-show="filterType === 'weekly'" type="date" name="filter_value" class="form-input"
                        :value="filterType === 'weekly' ? filterValue : ''" />

                    <!-- Monthly -->
                    <input x-show="filterType === 'monthly'" type="month" name="filter_value" class="form-input"
                        :value="filterType === 'monthly' ? filterValue : ''" />

                    <!-- Yearly -->
                    <input x-show="filterType === 'yearly'" type="number" name="filter_value" class="form-input"
                        placeholder="ปี ค.ศ." min="2000" max="2100" :value="filterType === 'yearly' ? filterValue : ''" />
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="mr-2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        ค้นหา
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Panel -->
        <div class="panel mb-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="text-primary">
                                <path d="M9 11L12 14L22 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M21 12V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H16"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">จำนวนรายการ</div>
                        <div class="text-2xl font-bold">{{ count($expenses) }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success/10">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="text-success">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M9 11H15M12 8V14" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">ยอดรวมทั้งหมด</div>
                        <div class="text-2xl font-bold">{{ number_format($totalAmount, 2) }} บาท</div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('expenses.report.export', ['filter_type' => $filterType, 'filter_value' => $filterValue]) }}"
                        class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="panel">
            <div class="mb-5">
                <h5 class="text-lg font-semibold">รายการค่าใช้จ่าย</h5>
            </div>
            <div class="table-responsive">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่อ้างอิง</th>
                            <th>วันที่</th>
                            <th>หมวดหมู่</th>
                            <th>ผู้รับเงิน</th>
                            <th>รายการ</th>
                            <th class="text-right">จำนวนเงิน</th>
                            <th>ช่องทางชำระ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $index => $expense)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $expense->reference_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                <td>{{ $expense->category ?? '-' }}</td>
                                <td>{{ $expense->payee }}</td>
                                <td>
                                    @if($expense->items->count() > 0)
                                        <ul class="list-disc list-inside text-sm">
                                            @foreach($expense->items as $item)
                                                <li>{{ $item->category->name ?? '-' }} ({{ $item->quantity }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ $expense->description ?? '-' }}
                                    @endif
                                </td>
                                <td class="text-right font-semibold">{{ number_format($expense->total, 2) }}</td>
                                <td>{{ $expense->payment_method }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('expenses.show', $expense->id) }}" class="text-primary"
                                            title="ดูรายละเอียด">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                <path
                                                    d="M12 5C8.24261 5 5.43602 7.4404 3.76737 9.43934C2.51521 10.9394 2.51521 13.0606 3.76737 14.5607C5.43602 16.5596 8.24261 19 12 19C15.7574 19 18.564 16.5596 20.2326 14.5607C21.4848 13.0606 21.4848 10.9394 20.2326 9.43934C18.564 7.4404 15.7574 5 12 5Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path
                                                    d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8 text-gray-500">
                                    ไม่พบข้อมูลในช่วงเวลาที่เลือก
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($expenses) > 0)
                        <tfoot>
                            <tr class="bg-gray-100 dark:bg-gray-800 font-bold">
                                <td colspan="6" class="text-right">รวมทั้งหมด:</td>
                                <td class="text-right">{{ number_format($totalAmount, 2) }} บาท</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reportFilter', () => ({
                filterType: '{{ $filterType }}',
                filterValue: '{{ $filterValue }}',

                updateFilterValue() {
                    // Reset filter value when type changes
                    const today = new Date();

                    switch (this.filterType) {
                        case 'daily':
                            this.filterValue = today.toISOString().split('T')[0];
                            break;
                        case 'weekly':
                            this.filterValue = today.toISOString().split('T')[0];
                            break;
                        case 'monthly':
                            this.filterValue = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
                            break;
                        case 'yearly':
                            this.filterValue = today.getFullYear().toString();
                            break;
                    }
                }
            }));
        });
    </script>
@endsection