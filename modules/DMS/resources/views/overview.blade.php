@extends('layouts.layout')
@section('title', 'ภาพรวม')
@section('style')
<style>
    .before\:bg-dark:before {
        content: var(--tw-content);
        background-color: rgb(107 114 128 / var(--tw-text-opacity));
    }
    .before\:bg-success:before {
        content: var(--tw-content);
        background-color: rgb(0 171 85/var(--tw-text-opacity));
    }
    button[disabled=disabled], button:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>
@endsection
@section('content')
<div x-data="sorting">
    <ul class="mb-5 flex text-gray-500 dark:text-white-dark">
        <li>
            <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0">
                    <path opacity="0.5"
                        d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </a>
        </li>
        <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/dms') }}">DMS</a></li>
        <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">ภาพรวม</a></li>
    </ul>
    <div>
        <div class="mb-6 grid grid-cols-1 gap-6 text-white sm:grid-cols-2 xl:grid-cols-4">
            <!-- Users Visit -->
            <div class="panel bg-gradient-to-r from-cyan-500 to-cyan-400">
                <div class="flex justify-between">
                    <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">วันนี้</div>
                </div>
                <div class="mt-5 flex items-center">
                    <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{ number_format($today) }}</div>
                </div>
                <div class="mt-5 flex items-center font-semibold">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                        <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                    </svg>
                    เมื่อวาน {{ number_format($yesterday) }}
                </div>
            </div>
            <!-- Sessions -->
            <div class="panel bg-gradient-to-r from-violet-500 to-violet-400">
                <div class="flex justify-between">
                    <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">สัปดาห์นี้</div>
                </div>
                <div class="mt-5 flex items-center">
                    <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{ number_format($thisweek) }}</div>
                </div>
                <div class="mt-5 flex items-center font-semibold">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                        <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                    </svg>
                    สัปดาห์ที่แล้ว {{ number_format($lastweek) }}
                </div>
            </div>
            <!-- Time On-Site -->
            <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
                <div class="flex justify-between">
                    <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">เดือนนี้</div>
                </div>
                <div class="mt-5 flex items-center">
                    <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{ number_format($thismonth) }}</div>
                </div>
                <div class="mt-5 flex items-center font-semibold">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                        <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                    </svg>
                    เดือนที่แล้ว {{ number_format($lastmonth) }}
                </div>
            </div>
            <!-- Bounce Rate -->
            <div class="panel bg-gradient-to-r from-fuchsia-500 to-fuchsia-400">
                <div class="flex justify-between">
                    <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">ปีนี้</div>
                </div>
                <div class="mt-5 flex items-center">
                    <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{ number_format($thisyear) }}</div>
                </div>
                <div class="mt-5 flex items-center font-semibold">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                        <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                    </svg>
                    ปีที่แล้ว {{ number_format($lastyear) }}
                </div>
            </div>
        </div>
        <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-1">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-5">
                <!-- actionEye -->
                <div class="panel">
                    <div class="mb-5 flex items-center font-semibold">
                        <div class="grid h-10 w-10">
                            <img src="{{ asset('modules/dms/images/eyelash.png') }}" alt="actionEye">
                        </div>
                        <div class="ltr:ml-2 rtl:mr-2">
                            <h6 class="text-dark dark:text-white-light">ผู้ขับขี่หลับตา</h6>
                            <p class="text-xs text-white-dark">actionEye</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-base font-bold">
                        {{ number_format($actionEye) }}
                    </div>
                </div>
                <!-- actionLooking -->
                <div class="panel">
                    <div class="mb-5 flex items-center font-semibold">
                        <div class="grid h-10 w-10">
                            <img src="{{ asset('modules/dms/images/road.png') }}" alt="actionLooking">
                        </div>
                        <div class="ltr:ml-2 rtl:mr-2">
                            <h6 class="text-dark dark:text-white-light">ผู้ขับขี่ละสายตาจากด้านหน้า</h6>
                            <p class="text-xs text-white-dark">actionLooking</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-base font-bold">
                        {{ number_format($actionLooking) }}
                    </div>
                </div>
                <!-- actionYawning -->
                <div class="panel">
                    <div class="mb-5 flex items-center font-semibold">
                        <div class="grid h-10 w-10">
                            <img src="{{ asset('modules/dms/images/yawn.png') }}" alt="actionYawning">
                        </div>
                        <div class="ltr:ml-2 rtl:mr-2">
                            <h6 class="text-dark dark:text-white-light">ผู้ขับขี่หาว</h6>
                            <p class="text-xs text-white-dark">actionYawning</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-base font-bold">
                        {{ number_format($actionYawning) }}
                    </div>
                </div>
                <!-- actionPhone -->
                <div class="panel">
                    <div class="mb-5 flex items-center font-semibold">
                        <div class="grid h-10 w-10">
                            <img src="{{ asset('modules/dms/images/phone.png') }}" alt="actionPhone">
                        </div>
                        <div class="ltr:ml-2 rtl:mr-2">
                            <h6 class="text-dark dark:text-white-light">ผู้ขับขี่ใช้โทรศัพท์มือถือ</h6>
                            <p class="text-xs text-white-dark">actionPhone</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-base font-bold">
                        {{ number_format($actionPhone) }}
                    </div>
                </div>
                <!-- actionCigarette -->
                <div class="panel">
                    <div class="mb-5 flex items-center font-semibold">
                        <div class="grid h-10 w-10">
                            <img src="{{ asset('modules/dms/images/cigarette.png') }}" alt="actionCigarette">
                        </div>
                        <div class="ltr:ml-2 rtl:mr-2">
                            <h6 class="text-dark dark:text-white-light">ผู้ขับขี่สูบบุหรี่</h6>
                            <p class="text-xs text-white-dark">actionCigarette</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-base font-bold">
                        {{ number_format($actionCigarette) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="panel h-full sm:col-span-2 lg:col-span-1">
                <!-- statistics -->
                <div class="mb-5 flex items-center justify-between dark:text-white-light">
                    <h5 class="text-lg font-semibold">สถานะอุปกรณ์</h5>
                </div>
                <div class="grid gap-8 text-sm font-bold text-[#515365] sm:grid-cols-2">
                    <div>
                        <div>
                            <div class="text-success">ออนไลน์</div>
                            <div class="text-lg text-success" id="device-online">0</div>
                        </div>
                        <div x-ref="totalVisit" class="overflow-hidden" style="min-height: 58px;"><div id="apexcharts1cfto1if" class="apexcharts-canvas apexcharts1cfto1if apexcharts-theme-light" style="width: 222px; height: 58px;"><svg id="SvgjsSvg1511" width="222" height="58" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1513" class="apexcharts-inner apexcharts-graphical" transform="translate(5, 5)"><defs id="SvgjsDefs1512"><clipPath id="gridRectMask1cfto1if"><rect id="SvgjsRect1518" width="218" height="50" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask1cfto1if"></clipPath><clipPath id="nonForecastMask1cfto1if"></clipPath><clipPath id="gridRectMarkerMask1cfto1if"><rect id="SvgjsRect1519" width="216" height="52" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><filter id="SvgjsFilter1525" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood1526" flood-color="#009688" flood-opacity="0.4" result="SvgjsFeFlood1526Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite1527" in="SvgjsFeFlood1526Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite1527Out"></feComposite><feOffset id="SvgjsFeOffset1528" dx="2" dy="2" result="SvgjsFeOffset1528Out" in="SvgjsFeComposite1527Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur1529" stdDeviation="3 " result="SvgjsFeGaussianBlur1529Out" in="SvgjsFeOffset1528Out"></feGaussianBlur><feMerge id="SvgjsFeMerge1530" result="SvgjsFeMerge1530Out" in="SourceGraphic"><feMergeNode id="SvgjsFeMergeNode1531" in="SvgjsFeGaussianBlur1529Out"></feMergeNode><feMergeNode id="SvgjsFeMergeNode1532" in="[object Arguments]"></feMergeNode></feMerge><feBlend id="SvgjsFeBlend1533" in="SourceGraphic" in2="SvgjsFeMerge1530Out" mode="normal" result="SvgjsFeBlend1533Out"></feBlend></filter></defs><line id="SvgjsLine1517" x1="0" y1="0" x2="0" y2="48" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="48" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1534" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1535" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1547" class="apexcharts-grid"><g id="SvgjsG1548" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1550" x1="0" y1="0" x2="212" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1551" x1="0" y1="6.857142857142857" x2="212" y2="6.857142857142857" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1552" x1="0" y1="13.714285714285714" x2="212" y2="13.714285714285714" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1553" x1="0" y1="20.57142857142857" x2="212" y2="20.57142857142857" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1554" x1="0" y1="27.428571428571427" x2="212" y2="27.428571428571427" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1555" x1="0" y1="34.285714285714285" x2="212" y2="34.285714285714285" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1556" x1="0" y1="41.14285714285714" x2="212" y2="41.14285714285714" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1557" x1="0" y1="47.99999999999999" x2="212" y2="47.99999999999999" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1549" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1559" x1="0" y1="48" x2="212" y2="48" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1558" x1="0" y1="1" x2="0" y2="48" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1520" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG1521" class="apexcharts-series" seriesName="seriesx1" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1524" d="M 0 33.6C 8.244444444444445 33.6 15.311111111111112 41.82857142857143 23.555555555555557 41.82857142857143C 31.800000000000004 41.82857142857143 38.86666666666667 23.314285714285713 47.111111111111114 23.314285714285713C 55.35555555555556 23.314285714285713 62.422222222222224 39.77142857142857 70.66666666666667 39.77142857142857C 78.91111111111111 39.77142857142857 85.97777777777779 17.828571428571426 94.22222222222223 17.828571428571426C 102.46666666666667 17.828571428571426 109.53333333333335 30.857142857142858 117.77777777777779 30.857142857142858C 126.02222222222223 30.857142857142858 133.0888888888889 7.542857142857137 141.33333333333334 7.542857142857137C 149.57777777777778 7.542857142857137 156.64444444444445 19.885714285714283 164.88888888888889 19.885714285714283C 173.13333333333333 19.885714285714283 180.20000000000002 2.74285714285714 188.44444444444446 2.74285714285714C 196.6888888888889 2.74285714285714 203.75555555555556 30.857142857142858 212 30.857142857142858" fill="none" fill-opacity="1" stroke="rgba(0,150,136,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMask1cfto1if)" filter="url(#SvgjsFilter1525)" pathTo="M 0 33.6C 8.244444444444445 33.6 15.311111111111112 41.82857142857143 23.555555555555557 41.82857142857143C 31.800000000000004 41.82857142857143 38.86666666666667 23.314285714285713 47.111111111111114 23.314285714285713C 55.35555555555556 23.314285714285713 62.422222222222224 39.77142857142857 70.66666666666667 39.77142857142857C 78.91111111111111 39.77142857142857 85.97777777777779 17.828571428571426 94.22222222222223 17.828571428571426C 102.46666666666667 17.828571428571426 109.53333333333335 30.857142857142858 117.77777777777779 30.857142857142858C 126.02222222222223 30.857142857142858 133.0888888888889 7.542857142857137 141.33333333333334 7.542857142857137C 149.57777777777778 7.542857142857137 156.64444444444445 19.885714285714283 164.88888888888889 19.885714285714283C 173.13333333333333 19.885714285714283 180.20000000000002 2.74285714285714 188.44444444444446 2.74285714285714C 196.6888888888889 2.74285714285714 203.75555555555556 30.857142857142858 212 30.857142857142858" pathFrom="M -1 48L -1 48L 23.555555555555557 48L 47.111111111111114 48L 70.66666666666667 48L 94.22222222222223 48L 117.77777777777779 48L 141.33333333333334 48L 164.88888888888889 48L 188.44444444444446 48L 212 48"></path><g id="SvgjsG1522" class="apexcharts-series-markers-wrap" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle1565" r="0" cx="0" cy="0" class="apexcharts-marker wop79jwao no-pointer-events" stroke="#ffffff" fill="#009688" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG1523" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1560" x1="0" y1="0" x2="212" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1561" x1="0" y1="0" x2="212" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1562" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1563" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1564" class="apexcharts-point-annotations"></g></g><rect id="SvgjsRect1516" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1546" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1514" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 29px;"></div><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(0, 150, 136);"></span><div class="apexcharts-tooltip-text" style="font-family: Nunito, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
                    </div>
                    <div>
                        <div>
                            <div>ออฟไลน์</div>
                            <div class="text-lg text-dark" id="device-offline">0</div>
                        </div>
                        <div x-ref="paidVisit" class="overflow-hidden" style="min-height: 58px;"><div id="apexchartsi06r3q6" class="apexcharts-canvas apexchartsi06r3q6 apexcharts-theme-light" style="width: 222px; height: 58px;"><svg id="SvgjsSvg1567" width="222" height="58" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1569" class="apexcharts-inner apexcharts-graphical" transform="translate(5, 5)"><defs id="SvgjsDefs1568"><clipPath id="gridRectMaski06r3q6"><rect id="SvgjsRect1574" width="218" height="50" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaski06r3q6"></clipPath><clipPath id="nonForecastMaski06r3q6"></clipPath><clipPath id="gridRectMarkerMaski06r3q6"><rect id="SvgjsRect1575" width="216" height="52" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><filter id="SvgjsFilter1581" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood1582" flood-color="#e2a03f" flood-opacity="0.4" result="SvgjsFeFlood1582Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite1583" in="SvgjsFeFlood1582Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite1583Out"></feComposite><feOffset id="SvgjsFeOffset1584" dx="2" dy="2" result="SvgjsFeOffset1584Out" in="SvgjsFeComposite1583Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur1585" stdDeviation="3 " result="SvgjsFeGaussianBlur1585Out" in="SvgjsFeOffset1584Out"></feGaussianBlur><feMerge id="SvgjsFeMerge1586" result="SvgjsFeMerge1586Out" in="SourceGraphic"><feMergeNode id="SvgjsFeMergeNode1587" in="SvgjsFeGaussianBlur1585Out"></feMergeNode><feMergeNode id="SvgjsFeMergeNode1588" in="[object Arguments]"></feMergeNode></feMerge><feBlend id="SvgjsFeBlend1589" in="SourceGraphic" in2="SvgjsFeMerge1586Out" mode="normal" result="SvgjsFeBlend1589Out"></feBlend></filter></defs><line id="SvgjsLine1573" x1="0" y1="0" x2="0" y2="48" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="48" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1590" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1591" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1603" class="apexcharts-grid"><g id="SvgjsG1604" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1606" x1="0" y1="0" x2="212" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1607" x1="0" y1="6.857142857142857" x2="212" y2="6.857142857142857" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1608" x1="0" y1="13.714285714285714" x2="212" y2="13.714285714285714" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1609" x1="0" y1="20.57142857142857" x2="212" y2="20.57142857142857" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1610" x1="0" y1="27.428571428571427" x2="212" y2="27.428571428571427" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1611" x1="0" y1="34.285714285714285" x2="212" y2="34.285714285714285" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1612" x1="0" y1="41.14285714285714" x2="212" y2="41.14285714285714" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1613" x1="0" y1="47.99999999999999" x2="212" y2="47.99999999999999" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1605" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1615" x1="0" y1="48" x2="212" y2="48" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1614" x1="0" y1="1" x2="0" y2="48" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1576" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG1577" class="apexcharts-series" seriesName="seriesx1" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1580" d="M 0 39.77142857142857C 8.244444444444445 39.77142857142857 15.311111111111112 41.828571428571436 23.555555555555557 41.828571428571436C 31.800000000000004 41.828571428571436 38.86666666666667 34.28571428571429 47.111111111111114 34.28571428571429C 55.35555555555556 34.28571428571429 62.422222222222224 22.628571428571433 70.66666666666667 22.628571428571433C 78.91111111111111 22.628571428571433 85.97777777777779 32.91428571428572 94.22222222222223 32.91428571428572C 102.46666666666667 32.91428571428572 109.53333333333335 24.685714285714287 117.77777777777779 24.685714285714287C 126.02222222222223 24.685714285714287 133.0888888888889 31.542857142857144 141.33333333333334 31.542857142857144C 149.57777777777778 31.542857142857144 156.64444444444445 17.142857142857146 164.88888888888889 17.142857142857146C 173.13333333333333 17.142857142857146 180.20000000000002 26.742857142857144 188.44444444444446 26.742857142857144C 196.6888888888889 26.742857142857144 203.75555555555556 7.5428571428571445 212 7.5428571428571445" fill="none" fill-opacity="1" stroke="rgba(226,160,63,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMaski06r3q6)" filter="url(#SvgjsFilter1581)" pathTo="M 0 39.77142857142857C 8.244444444444445 39.77142857142857 15.311111111111112 41.828571428571436 23.555555555555557 41.828571428571436C 31.800000000000004 41.828571428571436 38.86666666666667 34.28571428571429 47.111111111111114 34.28571428571429C 55.35555555555556 34.28571428571429 62.422222222222224 22.628571428571433 70.66666666666667 22.628571428571433C 78.91111111111111 22.628571428571433 85.97777777777779 32.91428571428572 94.22222222222223 32.91428571428572C 102.46666666666667 32.91428571428572 109.53333333333335 24.685714285714287 117.77777777777779 24.685714285714287C 126.02222222222223 24.685714285714287 133.0888888888889 31.542857142857144 141.33333333333334 31.542857142857144C 149.57777777777778 31.542857142857144 156.64444444444445 17.142857142857146 164.88888888888889 17.142857142857146C 173.13333333333333 17.142857142857146 180.20000000000002 26.742857142857144 188.44444444444446 26.742857142857144C 196.6888888888889 26.742857142857144 203.75555555555556 7.5428571428571445 212 7.5428571428571445" pathFrom="M -1 54.85714285714286L -1 54.85714285714286L 23.555555555555557 54.85714285714286L 47.111111111111114 54.85714285714286L 70.66666666666667 54.85714285714286L 94.22222222222223 54.85714285714286L 117.77777777777779 54.85714285714286L 141.33333333333334 54.85714285714286L 164.88888888888889 54.85714285714286L 188.44444444444446 54.85714285714286L 212 54.85714285714286"></path><g id="SvgjsG1578" class="apexcharts-series-markers-wrap" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle1621" r="0" cx="0" cy="0" class="apexcharts-marker w97ek4l9q no-pointer-events" stroke="#ffffff" fill="#e2a03f" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG1579" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1616" x1="0" y1="0" x2="212" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1617" x1="0" y1="0" x2="212" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1618" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1619" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1620" class="apexcharts-point-annotations"></g></g><rect id="SvgjsRect1572" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1602" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1570" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 29px;"></div><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(226, 160, 63);"></span><div class="apexcharts-tooltip-text" style="font-family: Nunito, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
                    </div>
                </div>
            </div>
            <div class="panel h-full">
                <div class="mb-5 flex items-center justify-between dark:text-white-light">
                    <h5 class="text-lg font-semibold">จำนวนอุปกรณ์</h5>
                </div>
                <div class="my-10 text-3xl font-bold text-[#e95f2b]">
                    <span>{{ number_format($dmsCount) }}</span>
                </div>
            </div>
            <div class="panel h-full">
                <div class="mb-5 flex items-center justify-between dark:text-white-light">
                    <h5 class="text-lg font-semibold">จำนวนพนักงาน Control Room</h5>
                </div>
                <div class="my-10 text-3xl font-bold text-[#e95f2b]">
                    <span>{{ number_format($userDms) }}</span>
                </div>
            </div>
        </div>
        <div class="panel">
            <h5 class="md:absolute md:top-[25px] md:mb-0 mb-5 font-semibold text-lg dark:text-white-light">รายการอุปกรณ์</h5>
            <table id="myTable" class="whitespace-nowrap table-hover"></table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
<script src="{{ config("dms.config.url") }}/socket.io/socket.io.js"></script>
<script>
    const socket = io('{{ config("dms.config.url") }}', { transports: ['websocket', 'polling', 'flashsocket'] });
    socket.on('connect', () => {
        socket.emit('register', { type: 'operator' });
    });
    socket.on('Devices', (devices) => {
        document.querySelector('#device-online').textContent = devices.length;
        const offlineDevices = {{ $dms }} - devices.length;
        document.querySelector('#device-offline').textContent = offlineDevices;
    });
    document.addEventListener('alpine:init', () => {
            Alpine.data('sorting', () => ({
                selectedRows: [],
                items: [
                    @foreach ($lists as $key => $list)
                    {
                            no: {{ $key + 1 }},
                            name: '{{ $list->name }}',
                            name_device: '{{ $list->device_name }}',
                            device_id: '{{ $list->device_id }}',
                            tel: '{{ $list->tel }}',
                            car_plate_number: '{{ $list->car_plate_number }}',
                            alarm: '{{ $list->alarm }}',
                        },
                    @endforeach
                ],
                searchText: '',
                datatable: null,
                dataArr: [],
                itemID: null,
                init() {
                    this.setTableData();
                    this.initializeTable();
                    this.$watch('items', (value) => {
                        this.datatable.destroy();
                        this.setTableData();
                        this.initializeTable();
                    });
                    this.$watch('selectedRows', (value) => {
                        this.datatable.destroy();
                        this.setTableData();
                        this.initializeTable();
                    });
                },
                initializeTable() {
                    this.datatable = new simpleDatatables.DataTable('#myTable', {
                        data: {
                            headings: ["ลำดับ", "ชื่อ", "ชื่ออุปกรณ์", "รหัสอุปกรณ์", "เบอร์โทรศัพท์", "ป้ายทะเบียน", "แจ้งเตือน"],
                            data: this.dataArr,
                        },
                        perPage: 20,
                        perPageSelect: [20, 30, 50, 100],
                        firstLast: true,
                        firstText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        lastText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        prevText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        nextText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        labels: {
                            perPage: "<span class='ml-2'>{select}</span>",
                            noRows: 'No data available',
                        },
                        layout: {
                            top: '{search}',
                            bottom: '{info}{select}{pager}',
                        },
                    });
                },
                setTableData() {
                    this.dataArr = [];
                    for (let i = 0; i < this.items.length; i++) {
                        this.dataArr[i] = [];
                        for (let p in this.items[i]) {
                            if (this.items[i].hasOwnProperty(p)) {
                                this.dataArr[i].push(this.items[i][p]);
                            }
                        }
                    }
                },
                searchItems() {
                    return this.items.filter((d) => (d.name && d.name.toLowerCase().includes(this.searchText)));
                }
            }));
        });
</script>
@endsection
