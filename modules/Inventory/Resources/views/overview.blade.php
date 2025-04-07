@extends('layouts.layout')
@section('title', 'Inventory Overview')
@section('style')
<style scoped>
    .chart-container {
        height: 300px;
    }
</style>
@endsection
@section('content')
<div x-data="inventoryOverview">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
        <div class="panel">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Total Products</h5>
                <div class="dropdown">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-70">
                        <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                        <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                        <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <div class="text-primary w-full">
                    <div class="flex justify-center mb-5">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-primary">
                                <path d="M12.0005 9.27881L5.00037 12.0288L12.0005 14.7788L19.0005 12.0288L12.0005 9.27881Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.5" d="M4.99976 12.0288V16.5588C4.99976 16.5588 5.13976 18.8688 12.0008 21.9688C18.8618 18.8688 19.0008 16.5588 19.0008 16.5588V12.0288" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.5" d="M12.0008 2.03723V9.27881" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M19.1115 5.3131L12.0005 9.2788L7.16553 6.9088" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="text-[40px] mb-0 font-bold">{{ $totalProducts }}</h5>
                        <p class="font-semibold">Total Products</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Low Stock Products</h5>
                <div class="dropdown">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-70">
                        <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                        <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                        <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <div class="text-danger w-full">
                    <div class="flex justify-center mb-5">
                        <div class="w-20 h-20 bg-danger/10 rounded-full flex items-center justify-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-danger">
                                <path d="M2 9C2 7.89543 2.89543 7 4 7H20C21.1046 7 22 7.89543 22 9V20C22 21.1046 21.1046 22 20 22H4C2.89543 22 2 21.1046 2 20V9Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M2 9H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M12 7V2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M8 2H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <circle opacity="0.5" cx="12" cy="15" r="2" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M12 17V19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="text-[40px] mb-0 font-bold">{{ $lowStockProducts }}</h5>
                        <p class="font-semibold">Low Stock Products</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Categories</h5>
                <div class="dropdown">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-70">
                        <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                        <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                        <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <div class="text-success w-full">
                    <div class="flex justify-center mb-5">
                        <div class="w-20 h-20 bg-success/10 rounded-full flex items-center justify-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-success">
                                <path d="M4 7H20C21.1046 7 22 7.89543 22 9V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V9C2 7.89543 2.89543 7 4 7Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M14 5C14 5 14.6349 3 17 3C19.3651 3 20 5 20 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M4 5C4 5 4.63493 3 7 3C9.36507 3 10 5 10 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M3 8L3 16" stroke="currentColor" stroke-width="1.5" />
                                <path d="M21 8L21 16" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 21V14" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 10L12 7" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="text-[40px] mb-0 font-bold">{{ $categoriesCount }}</h5>
                        <p class="font-semibold">Categories</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="panel h-full">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Recent Products</h5>
                <a href="{{ route('inventory') }}" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $recentProducts = \Modules\Inventory\Models\Product::join('product_langs', 'product_langs.product_id', '=', 'products.id')
                            ->where('product_langs.code', '=', app()->getLocale())
                            ->orderBy('products.created_at', 'desc')
                            ->limit(5)
                            ->get([
                                'products.*',
                                'product_langs.name'
                            ]);
                        @endphp

                        @foreach($recentProducts as $product)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    @if($product->img)
                                    <img src="{{ asset('storage/' . $product->img) }}" class="w-8 h-8 rounded-md object-cover mr-2" alt="{{ $product->name }}">
                                    @else
                                    <div class="bg-gray-100 w-8 h-8 rounded-md flex items-center justify-center mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-500">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                    @endif
                                    <span class="whitespace-nowrap">{{ Str::limit($product->name, 25) }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                $category = \Modules\Inventory\Models\Category::join('product_category_langs', 'product_category_langs.category_id', '=', 'product_categories.id')
                                    ->where('product_categories.id', $product->category_id)
                                    ->where('product_category_langs.code', app()->getLocale())
                                    ->first(['product_category_langs.name']);
                                @endphp
                                {{ $category ? $category->name : 'Uncategorized' }}
                            </td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->stock <= 5)
                                <span class="badge badge-outline-danger">{{ $product->stock }}</span>
                                @elseif($product->stock <= 20)
                                <span class="badge badge-outline-warning">{{ $product->stock }}</span>
                                @else
                                <span class="badge badge-outline-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->status == 1)
                                <span class="badge badge-outline-success">Published</span>
                                @elseif($product->status == 0)
                                <span class="badge badge-outline-danger">Hidden</span>
                                @else
                                <span class="badge badge-outline-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel h-full">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Stock Status</h5>
            </div>
            <div class="chart-container" id="stockChart">
                <div class="flex items-center justify-center h-full">
                    <div class="animate-spin border-2 border-primary border-l-transparent rounded-full w-10 h-10"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('inventoryOverview', () => ({
            init() {
                setTimeout(() => {
                    this.initStockChart();
                }, 300);
            },

            initStockChart() {
                @php
                $stockData = \Modules\Inventory\Models\Product::selectRaw('
                    CASE
                        WHEN stock = 0 THEN "Out of Stock"
                        WHEN stock <= 5 THEN "Critical"
                        WHEN stock <= 20 THEN "Low"
                        ELSE "Good"
                    END as stock_status, COUNT(*) as count')
                    ->groupBy('stock_status')
                    ->get();

                $stockChartData = [
                    'outOfStock' => $stockData->where('stock_status', 'Out of Stock')->first()->count ?? 0,
                    'critical' => $stockData->where('stock_status', 'Critical')->first()->count ?? 0,
                    'low' => $stockData->where('stock_status', 'Low')->first()->count ?? 0,
                    'good' => $stockData->where('stock_status', 'Good')->first()->count ?? 0,
                ];
                @endphp

                const stockChartOptions = {
                    series: [
                        {{ $stockChartData['outOfStock'] }},
                        {{ $stockChartData['critical'] }},
                        {{ $stockChartData['low'] }},
                        {{ $stockChartData['good'] }}
                    ],
                    chart: {
                        height: 300,
                        type: 'pie',
                    },
                    labels: ['Out of Stock', 'Critical (≤5)', 'Low (≤20)', 'Good'],
                    colors: ['#e7515a', '#ff9800', '#ffbb00', '#00ab55'],
                    legend: {
                        position: 'bottom',
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                document.getElementById('stockChart').innerHTML = '';
                const stockChart = new ApexCharts(document.getElementById('stockChart'), stockChartOptions);
                stockChart.render();
            }
        }));
    });
</script>
@endsection
