@extends('layouts.layout')
@section('title', 'แดชบอร์ด')
@section('style')
    <style>
        .table-customer tr { display: none; }
        .table-customer tr.active { display: table-row; }
    </style>
@endsection
@section('content')
    <script defer src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <div x-data="sales">
        <div class="flex mb-5">
            <ul class="flex text-gray-500 dark:text-white-dark">
                <li>
                    <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 shrink-0">
                            <path opacity="0.5"
                                d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                            <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </a>
                </li>
                <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/sales') }}">แดชบอร์ด</a></li>
                <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">ฝ่ายขาย</a>
                </li>
            </ul>

            <div x-data="dropdown" @click.outside="open = false" class="dropdown ltr:ml-auto rtl:mr-auto float-right">
                <button class="rounded-full bg-white-light/40 p-2 pl-3 pr-3 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 shadow-none dropdown-toggle" @click="toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ltr:mr-2 rtl:ml-2 h-4 w-4"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg> เลือกปี
                </button>
                <ul x-show="open" x-transition="" x-transition.duration.300ms="" class="ltr:right-0 rtl:left-0" style="display: none;">
                    @foreach ($years as $year)
                    <li><a href="{{ url('sales') }}/dashboard/?d={{ $year->year }}" @click="toggle">{{ $year->year }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div>
            <div class="grid xl:grid-cols-3 gap-6 mb-6">
                <div class="panel h-full xl:col-span-2">
                    <div class="flex items-center dark:text-white-light mb-5">
                        <h5 class="font-semibold text-lg">รายได้</h5>
                    </div>
                    <p class="text-lg dark:text-white-light/90">ยอดขายรวม <span class="text-primary ml-2">฿{{ number_format($total[0]->total, 2) }}</span></p>
                    <div class="relative overflow-hidden">
                        <div x-ref="revenueChart" class="bg-white dark:bg-black rounded-lg">
                            <div class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                                <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full">
                    <div class="flex items-center mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">ยอดขายตามประเภท</h5>
                    </div>
                    <div class="overflow-hidden">
                        <div x-ref="salesByCategory" class="bg-white dark:bg-black rounded-lg">
                            <div class="min-h-[353px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] ">
                                <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent  rounded-full w-5 h-5 inline-flex"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
                <div class="panel h-full p-0 lg:col-span-2">
                    <div class="flex items-start justify-between p-5 dark:text-white-light">
                        <h5 class="text-lg font-semibold">เป้าหมายการขาย</h5>
                    </div>
                    <div x-ref="columnChart" class="bg-white dark:bg-black rounded-lg"></div>
                </div>
                <div class="panel h-full p-0">
                    <div class="flex items-center justify-between w-full p-5 absolute">
                        <div class="relative">
                            <div class="text-success dark:text-success-light bg-success-light dark:bg-success w-11 h-11 rounded-lg flex items-center justify-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2 3L2.26491 3.0883C3.58495 3.52832 4.24497 3.74832 4.62248 4.2721C5 4.79587 5 5.49159 5 6.88304V9.5C5 12.3284 5 13.7426 5.87868 14.6213C6.75736 15.5 8.17157 15.5 11 15.5H19"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path opacity="0.5"
                                        d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M16.5 18.0001C17.3284 18.0001 18 18.6716 18 19.5001C18 20.3285 17.3284 21.0001 16.5 21.0001C15.6716 21.0001 15 20.3285 15 19.5001C15 18.6716 15.6716 18.0001 16.5 18.0001Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5" d="M11 9H8" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M5 6H16.4504C18.5054 6 19.5328 6 19.9775 6.67426C20.4221 7.34853 20.0173 8.29294 19.2078 10.1818L18.7792 11.1818C18.4013 12.0636 18.2123 12.5045 17.8366 12.7523C17.4609 13 16.9812 13 16.0218 13H5"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <h5 class="font-semibold text-2xl ltr:text-right rtl:text-left dark:text-white-light">
                            {{ $orders[0]->orders }}
                            <span class="block text-sm font-normal">ยอดสั่งซื้อรวม</span>
                        </h5>
                    </div>
                    <div x-ref="totalOrders" class="bg-transparent rounded-lg overflow-hidden">
                        <!-- loader -->
                        <div class="min-h-[290px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] ">
                            <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent  rounded-full w-5 h-5 inline-flex"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($targetbysales as $key => $targetbysale)
                <div class="panel h-full">
                    <div class="flex items-start justify-between p-5 dark:text-white-light">
                        <h5 class="font-semibold">{{ $targetbysale->Sale }}</h5>
                    </div>
                    <div x-ref="columnChart{{ $key }}" class="bg-white dark:bg-black rounded-lg"></div>
                </div>
                @endforeach
            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($targetbysales as $key => $targetbysale)
                <div class="panel h-full">
                    <div class="flex items-center dark:text-white-light mb-5">
                        <h5 class="font-semibold text-lg">{{ $targetbysale->Sale }}</h5>
                    </div>
                    <div class="relative overflow-hidden">
                        <div x-ref="saleChart{{ $key }}" class="bg-white dark:bg-black rounded-lg">
                            <div class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                                <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="grid lg:grid-cols-2 grid-cols-1 gap-6">
                <div class="panel h-full w-full">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Top Customers</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table-customer">
                            <thead>
                                <tr>
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md">ลูกค้า</th>
                                    <th>ราคา</th>
                                    <th class="ltr:rounded-r-md rtl:rounded-l-md">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr class="text-white-dark hover:text-black dark:hover:text-white-light/90 group">
                                        <td class="min-w-[150px] text-black dark:text-white">
                                            {{ $customer->customer_name }}
                                        </td>
                                        <td>฿{{ number_format($customer->total, 2) }}</td>
                                        <td><span
                                                class="badge bg-success shadow-md dark:group-hover:bg-transparent">{{ $customer->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="#" class="load_more text-center block mt-5">ดูเพิ่มเติม</a>
                    </div>
                </div>
                <div class="panel h-full w-full">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Top Sellers</h5>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr class="border-b-0">
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md">ผู้ขาย</th>
                                    <th>ราคา</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sellings as $selling)
                                    <tr class="text-white-dark hover:text-black dark:hover:text-white-light/90 group">
                                        <td class="min-w-[150px] text-black dark:text-white">
                                            @if ($selling->sale_name == '')
                                                เอด้าซอฟท์
                                            @else
                                                {{ $selling->sale_name }}
                                            @endif
                                        </td>
                                        <td>฿{{ number_format($selling->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        async function showAlert() {
            @if (\Session::has('success'))
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 4000,
                    padding: '2em',
                });
                toast.fire({
                    icon: 'success',
                    title: '{!! \Session::get('success') !!}',
                    padding: '2em',
                });
            @elseif (\Session::has('errors'))
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 4000,
                    padding: '2em',
                    customClass: {
                        popup: `color-danger`
                    },
                });
                toast.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาดบางประการ กรุณาตรวจสอบ Log Error',
                    padding: '2em',
                });
            @endif
        }
        @if (\Session::has('success') || \Session::has('errors'))
            setTimeout(() => {
                window.onload = showAlert();
            }, "400");
        @endif
        document.addEventListener("alpine:init", () => {
            Alpine.data("sales", () => ({
                init() {
                    isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;
                    isRtl = this.$store.app.rtlClass === "rtl" ? true : false;
                    const revenueChart = null;


                    const salesByCategory = null;
                    const dailySales = null;
                    const totalOrders = null;



                    let columnChart = new ApexCharts(this.$refs.columnChart, this.columnChartOptions);
                    columnChart.render();
                    @foreach ($targetbysales as $key => $targetbysale)

                    const saleChart{{ $key }} = null;

                    let columnChart{{ $key }} = new ApexCharts(this.$refs.columnChart{{ $key }}, this.columnChartOptions{{ $key }});
                    columnChart{{ $key }}.render();
                    @endforeach



                    setTimeout(() => {
                        this.revenueChart = new ApexCharts(this.$refs.revenueChart, this.revenueChartOptions);
                        this.$refs.revenueChart.innerHTML = "";
                        this.revenueChart.render();


                        @foreach ($targetbysales as $key => $targetbysale)
                        this.saleChart{{ $key }} = new ApexCharts(this.$refs.saleChart{{ $key }}, this.saleChartOptions{{ $key }});
                        this.$refs.saleChart{{ $key }}.innerHTML = "";
                        this.saleChart{{ $key }}.render();
                        @endforeach






                        this.salesByCategory = new ApexCharts(this.$refs.salesByCategory, this.salesByCategoryOptions);
                        this.$refs.salesByCategory.innerHTML = "";
                        this.salesByCategory.render();
                        this.totalOrders = new ApexCharts(this.$refs.totalOrders, this.totalOrdersOptions);
                        this.$refs.totalOrders.innerHTML = "";
                        this.totalOrders.render();
                    }, 300);
                    this.$watch('$store.app.theme', () => {
                        isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;

                        this.revenueChart.updateOptions(this.revenueChartOptions);


                        @foreach ($targetbysales as $key => $targetbysale)
                        this.saleChart{{ $key }}.updateOptions(this.saleChartOptions{{ $key }});
                        @endforeach


                        this.salesByCategory.updateOptions(this.salesByCategoryOptions);
                        this.dailySales.updateOptions(this.dailySalesOptions);
                        this.totalOrders.updateOptions(this.totalOrdersOptions);
                        this.targetChart.updateOptions(this.targetChartOptions);
                        columnChart.updateOptions(this.columnChartOptions);

                        @foreach ($targetbysales as $key => $targetbysale)
                        columnChart.updateOptions(this.columnChartOptions{{ $key }});
                        @endforeach
                    });
                    this.$watch('$store.app.rtlClass', () => {
                        isRtl = this.$store.app.rtlClass === "rtl" ? true : false;



                        this.revenueChart.updateOptions(this.revenueChartOptions);

                        @foreach ($targetbysales as $key => $targetbysale)
                        this.saleChart{{ $key }}.updateOptions(this.saleChartOptions{{ $key }});
                        @endforeach

                    });
                },



                get revenueChartOptions() {
                    return {
                        series: [{
                            name: 'รายได้',
                            data: [
                                @foreach ($incomes as $income)
                                    {{ $income->total }},
                                @endforeach
                            ]
                        }, ],
                        chart: {
                            height: 325,
                            type: "area",
                            fontFamily: 'Nunito, sans-serif',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            curve: 'smooth',
                            width: 2,
                            lineCap: 'square'
                        },
                        dropShadow: {
                            enabled: true,
                            opacity: 0.2,
                            blur: 10,
                            left: -7,
                            top: 22
                        },
                        colors: isDark ? ['#2196f3', '#e7515a'] : ['#1b55e2', '#e7515a'],
                        labels: [
                            @foreach ($incomes as $income)
                                '{!! Modules\Sales\App\Helpers\Helper::Month('2024-' . $income->month . '-20') !!}',
                            @endforeach
                        ],
                        xaxis: {
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            },
                            crosshairs: {
                                show: true
                            },
                            labels: {
                                offsetX: isRtl ? 2 : 0,
                                offsetY: 5,
                                style: {
                                    fontSize: '12px',
                                    cssClass: 'apexcharts-xaxis-title'
                                }
                            },
                        },
                        yaxis: {
                            tickAmount: 7,
                            labels: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('th-TH', {
                                        style: 'currency',
                                        currency: 'THB'
                                    }).format(value);
                                },
                                offsetX: isRtl ? -30 : -10,
                                offsetY: 0,
                                style: {
                                    fontSize: '12px',
                                    cssClass: 'apexcharts-yaxis-title'
                                },
                            },
                            opposite: isRtl ? true : false,
                        },
                        grid: {
                            borderColor: isDark ? '#191e3a' : '#e0e6ed',
                            strokeDashArray: 5,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            },
                            yaxis: {
                                lines: {
                                    show: false
                                }
                            },
                            padding: {
                                top: 0,
                                right: 0,
                                bottom: 0,
                                left: 0
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            fontSize: '16px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -2,
                            },
                            itemMargin: {
                                horizontal: 10,
                                vertical: 5
                            },
                        },
                        tooltip: {
                            marker: {
                                show: true
                            },
                            x: {
                                show: false
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                inverseColors: !1,
                                opacityFrom: isDark ? 0.19 : 0.28,
                                opacityTo: 0.05,
                                stops: isDark ? [100, 100] : [45, 100],
                            },
                        },
                    }
                },


                @foreach ($targetbysales as $key => $targetbysale)
                get saleChartOptions{{ $key }}() {
                    return {
                        series: [
                            {
                                name: 'Taget',
                                data: [{{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['01'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['02'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['03'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['04'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['05'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['06'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['07'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['08'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['09'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['10'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['11'] }}, {{ @$targetMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['12'] }}],
                            },
                            {
                                name: 'Actual',
                                data: [{{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['01']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['02']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['03']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['04']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['05']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['06']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['07']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['08']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['09']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['10']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['11']['sums'] }}, {{ @$saleByMonths[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['12']['sums'] }}],
                            },
                        ],
                        chart: {
                            height: 325,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            zoom: {
                                enabled: false,
                            },
                            toolbar: {
                                show: false,
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            show: true,
                            curve: 'smooth',
                            width: 2,
                            lineCap: 'square',
                        },
                        dropShadow: {
                            enabled: true,
                            opacity: 0.2,
                            blur: 10,
                            left: -7,
                            top: 22,
                        },
                        colors: isDark ? ['#2196f3', '#e7515a'] : ['#1b55e2', '#e7515a'],
                        labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
                        xaxis: {
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                            crosshairs: {
                                show: true,
                            },
                            labels: {
                                offsetX: isRtl ? 2 : 0,
                                offsetY: 5,
                                style: {
                                    fontSize: '12px',
                                    cssClass: 'apexcharts-xaxis-title',
                                },
                            },
                        },
                        yaxis: {
                            tickAmount: 7,
                            labels: {
                                formatter: (value) => {
                                    return value;
                                },
                                offsetX: isRtl ? -30 : -10,
                                offsetY: 0,
                                style: {
                                    fontSize: '12px',
                                    cssClass: 'apexcharts-yaxis-title',
                                },
                            },
                            opposite: isRtl ? true : false,
                        },
                        grid: {
                            borderColor: isDark ? '#191e3a' : '#e0e6ed',
                            strokeDashArray: 5,
                            xaxis: {
                                lines: {
                                    show: true,
                                },
                            },
                            yaxis: {
                                lines: {
                                    show: false,
                                },
                            },
                            padding: {
                                top: 0,
                                right: 0,
                                bottom: 0,
                                left: 0,
                            },
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            fontSize: '16px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -2,
                            },
                            itemMargin: {
                                horizontal: 10,
                                vertical: 5,
                            },
                        },
                        tooltip: {
                            marker: {
                                show: true,
                            },
                            x: {
                                show: false,
                            },
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                inverseColors: !1,
                                opacityFrom: isDark ? 0.19 : 0.28,
                                opacityTo: 0.05,
                                stops: isDark ? [100, 100] : [45, 100],
                            },
                        },
                    };
                },
                @endforeach


                get salesByCategoryOptions() {
                    return {
                        series: [
                            @foreach ($products as $product)
                                @if ($product->total > 0)
                                    {{ $product->total }},
                                @endif
                            @endforeach
                        ],
                        chart: {
                            type: 'donut',
                            height: 460,
                            fontFamily: 'Nunito, sans-serif',
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 25,
                            colors: isDark ? '#0e1726' : '#fff'
                        },
                        colors: isDark ? ['#5c1ac3', '#e2a03f', '#e7515a', '#e2a03f'] : ['#e2a03f',
                            '#5c1ac3', '#e7515a'
                        ],
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -2,
                            },
                            height: 50,
                            offsetY: 20,
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    background: 'transparent',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '29px',
                                            offsetY: -10
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '26px',
                                            color: isDark ? '#bfc9d4' : undefined,
                                            offsetY: 16,
                                            formatter: (val) => {
                                                return val;
                                            },
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            color: '#888ea8',
                                            fontSize: '29px',
                                            formatter: (w) => {
                                                return w.globals.seriesTotals.reduce(function(a,
                                                    b) {
                                                    let number = a + b;
                                                    return Math.round(number * 100) /
                                                        100;
                                                }, 0);
                                            },
                                        },
                                    },
                                },
                            },
                        },
                        labels: [
                            @foreach ($products as $product)
                                @if ($product->total > 0)
                                    '{{ $product->type }}',
                                @endif
                            @endforeach
                        ],
                        states: {
                            hover: {
                                filter: {
                                    type: 'none',
                                    value: 0.15,
                                }
                            },
                            active: {
                                filter: {
                                    type: 'none',
                                    value: 0.15,
                                }
                            },
                        }
                    }
                },
                get totalOrdersOptions() {
                    return {
                        series: [{
                            name: 'Sales',
                            data: [
                                @foreach ($ordermonth as $ordermonth)
                                    {{ $ordermonth->orders }},
                                @endforeach
                            ]
                        }],
                        chart: {
                            height: 290,
                            type: "area",
                            fontFamily: 'Nunito, sans-serif',
                            sparkline: {
                                enabled: true
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        colors: isDark ? ['#00ab55'] : ['#00ab55'],
                        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                        yaxis: {
                            min: 0,
                            show: false
                        },
                        grid: {
                            padding: {
                                top: 125,
                                right: 0,
                                bottom: 0,
                                left: 0
                            }
                        },
                        fill: {
                            opacity: 1,
                            type: 'gradient',
                            gradient: {
                                type: 'vertical',
                                shadeIntensity: 1,
                                inverseColors: !1,
                                opacityFrom: 0.3,
                                opacityTo: 0.05,
                                stops: [100, 100],
                            },
                        },
                        tooltip: {
                            x: {
                                show: false
                            },
                        },
                    }
                },
                get columnChartOptions() {
                    return {
                        series: [{
                            name: 'Taget',
                            data: [
                                @foreach ($targettotals as $targettotal)
                                    {{ $targettotal->Q1 }}, {{ $targettotal->Q2 }}, {{ $targettotal->Q3 }}, {{ $targettotal->Q4 }}
                                @endforeach
                            ]
                        }, {
                            name: 'Pipeline',
                            data: [
                                @foreach ($pipelines as $pipeline)
                                    {{ $pipeline->Q2 }}, {{ $pipeline->Q2 }}, {{ $pipeline->Q2 }}, {{ $pipeline->Q2 }}
                                @endforeach
                            ]
                        }, {
                            name: 'Actual',
                            data: [
                                @foreach ($targetprices as $targetprice)
                                    {{ $targetprice->T1 }}, {{ $targetprice->T2 }}, {{ $targetprice->T3 }}, {{ $targetprice->T4 }}
                                @endforeach
                            ]
                        }],
                        chart: {
                            height: 200,
                            type: 'bar',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false
                            }
                        },
                        colors: ['#805dca', '#e7515a', '#00ab55'],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        grid: {
                            borderColor: isDark ? '#191e3a' : '#e0e6ed'
                        },
                        xaxis: {
                            categories: ['Q1', 'Q2', 'Q3', 'Q4'],
                            axisBorder: {
                                color: isDark ? '#191e3a' : '#e0e6ed'
                            },
                        },
                        yaxis: {
                            opposite: isRtl ? true : false,
                            labels: {
                                offsetX: isRtl ? -10 : 0,
                            }
                        },
                        tooltip: {
                            theme: isDark ? 'dark' : 'light',
                            y: {
                                formatter: function(val) {
                                    return val;
                                },
                            },
                        },
                    }
                },
                @foreach ($targetbysales as $key => $targetbysale)
                get columnChartOptions{{ $key }}() {
                    return {
                        series: [{
                            name: 'Taget',
                            data: [{{ $targetbysale->Q1}}, {{ $targetbysale->Q2 }}, {{ $targetbysale->Q3 }}, {{ $targetbysale->Q4 }}]
                        }, {
                            name: 'Pipeline',
                            data: [{{ @$pipelinenames[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['Q1'] }}, {{ @$pipelinenames[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['Q2'] }}, {{ @$pipelinenames[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['Q3'] }}, {{ @$pipelinenames[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['Q4'] }}]
                        },
                        {
                            name: 'Actual',
                            data: [
                            {{ @$targets[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['T1'] }}, {{ @$targets[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['T2'] }}, {{ @$targets[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['T3'] }}, {{ @$targets[preg_replace('/[[:space:]]+/', '', trim($targetbysale->Sale))]['T4'] }}
                            ]
                        }],
                        chart: {
                            height: 200,
                            type: 'bar',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false
                            }
                        },
                        colors: ['#805dca', '#e7515a', '#00ab55'],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        grid: {
                            borderColor: isDark ? '#191e3a' : '#e0e6ed'
                        },
                        xaxis: {
                            categories: ['Q1', 'Q2', 'Q3', 'Q4'],
                            axisBorder: {
                                color: isDark ? '#191e3a' : '#e0e6ed'
                            },
                        },
                        yaxis: {
                            opposite: isRtl ? true : false,
                            labels: {
                                offsetX: isRtl ? -10 : 0,
                            }
                        },
                        tooltip: {
                            theme: isDark ? 'dark' : 'light',
                            y: {
                                formatter: function(val) {
                                    return val;
                                },
                            },
                        },
                    }
                },
                @endforeach
            }));
        });
    </script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script>
        $(function() {
            var originalLength = 8;
            $('.table-customer tr:lt(' + originalLength + ')').addClass('active');
            var rowCount = $('.table-customer tr').length;
            var hidden = true;
            var $table = $('.table-customer').find('tbody');
            $('a.load_more').on('click', function(e) {
                e.preventDefault();
                if (hidden) {
                    $table.find('tr:lt(' + rowCount + ')').hide();
                    $table.find('tr:lt(' + rowCount + ')').show();
                    $(this).html('ดูน้อยลง');
                } else {
                    $table.find('tr:lt(' + rowCount + ')').hide();
                    $table.find('tr:lt(' + (originalLength - 1) + ')').show();
                    $(this).html('ดูเพิ่มเติม');
                }
                hidden = !hidden;
            });
        });
    </script>
@endsection
