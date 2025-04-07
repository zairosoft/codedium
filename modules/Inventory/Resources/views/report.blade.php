@extends('layouts.layout')
@section('title', 'Inventory Reports')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flatpickr.min.css') }}">
<style scoped>
    .stock-critical {
        color: #e7515a;
    }
    .stock-warning {
        color: #e2a03f;
    }
    .stock-normal {
        color: #009688;
    }
</style>
@endsection
@section('content')
<div x-data="inventoryReports">
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">Inventory Reports</h5>
            <div class="flex items-center">
                <button type="button" class="btn btn-outline-success mr-3" @click="exportToCSV()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    Export to CSV
                </button>
                <button type="button" class="btn btn-outline-info" onclick="window.print()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Print
                </button>
            </div>
        </div>

        <div class="mb-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div>
                    <label for="category-filter">Category</label>
                    <select id="category-filter" class="form-select" x-model="filters.category">
                        <option value="">All Categories</option>
                        @php
                        $categories = \Modules\Inventory\Models\Category::join('product_category_langs', 'product_category_langs.category_id', '=', 'product_categories.id')
                            ->where('product_category_langs.code', app()->getLocale())
                            ->select('product_categories.id', 'product_category_langs.name')
                            ->get();
                        @endphp
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stock-filter">Stock Status</label>
                    <select id="stock-filter" class="form-select" x-model="filters.stockStatus">
                        <option value="">All</option>
                        <option value="critical">Critical (0-5)</option>
                        <option value="low">Low (6-20)</option>
                        <option value="normal">Normal (20+)</option>
                        <option value="out">Out of stock</option>
                    </select>
                </div>
                <div>
                    <label for="search-filter">Search</label>
                    <input id="search-filter" type="text" placeholder="Search products..." class="form-input" x-model="filters.search">
                </div>
                <div>
                    <label for="date-filter">Date Range</label>
                    <input id="date-filter" x-ref="dateFilter" class="form-input flatpickr flatpickr-input" type="text" placeholder="Select date range...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="inventory-table" class="table-hover whitespace-nowrap">
                <thead>
                    <tr>
                        <th @click="sortBy('id')" class="cursor-pointer">
                            ID
                            <span x-show="sortField === 'id'" class="text-primary">
                                <span x-show="sortDirection === 'asc'">&uarr;</span>
                                <span x-show="sortDirection === 'desc'">&darr;</span>
                            </span>
                        </th>
                        <th @click="sortBy('name')" class="cursor-pointer">
                            Product Name
                            <span x-show="sortField === 'name'" class="text-primary">
                                <span x-show="sortDirection === 'asc'">&uarr;</span>
                                <span x-show="sortDirection === 'desc'">&darr;</span>
                            </span>
                        </th>
                        <th>Category</th>
                        <th @click="sortBy('price')" class="cursor-pointer">
                            Price
                            <span x-show="sortField === 'price'" class="text-primary">
                                <span x-show="sortDirection === 'asc'">&uarr;</span>
                                <span x-show="sortDirection === 'desc'">&darr;</span>
                            </span>
                        </th>
                        <th @click="sortBy('stock')" class="cursor-pointer">
                            Stock
                            <span x-show="sortField === 'stock'" class="text-primary">
                                <span x-show="sortDirection === 'asc'">&uarr;</span>
                                <span x-show="sortDirection === 'desc'">&darr;</span>
                            </span>
                        </th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="product in filteredProducts" :key="product.id">
                        <tr>
                            <td x-text="product.id"></td>
                            <td>
                                <div class="flex items-center">
                                    <template x-if="product.img">
                                        <img :src="`{{ asset('storage') }}/${product.img}`" class="w-8 h-8 rounded-md object-cover mr-2" :alt="product.name">
                                    </template>
                                    <template x-if="!product.img">
                                        <div class="bg-gray-100 w-8 h-8 rounded-md flex items-center justify-center mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-500">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                        </div>
                                    </template>
                                    <span class="whitespace-nowrap" x-text="product.name"></span>
                                </div>
                            </td>
                            <td x-text="getCategoryName(product.category_id)"></td>
                            <td x-text="formatPrice(product.price)"></td>
                            <td>
                                <span :class="{
                                    'stock-critical': product.stock <= 5 && product.stock > 0,
                                    'stock-warning': product.stock > 5 && product.stock <= 20,
                                    'stock-normal': product.stock > 20,
                                    'badge badge-outline-danger': product.stock === 0
                                }" x-text="product.stock"></span>
                            </td>
                            <td>
                                <template x-if="product.status == 1">
                                    <span class="badge badge-outline-success">Published</span>
                                </template>
                                <template x-if="product.status == 0">
                                    <span class="badge badge-outline-danger">Hidden</span>
                                </template>
                                <template x-if="product.status == 2">
                                    <span class="badge badge-outline-warning">Draft</span>
                                </template>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a :href="'{{ route('product.show', '') }}/' + product.id" class="hover:text-primary">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                            <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                                        </svg>
                                    </a>
                                    <a :href="'{{ route('product.edit', '') }}/' + product.id" class="hover:text-info">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredProducts.length === 0">
                        <tr>
                            <td colspan="7" class="text-center py-4">No products found matching your filters.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-5">
            <div>
                <span>Showing <span x-text="filteredProducts.length"></span> of <span x-text="products.length"></span> products</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('inventoryReports', () => ({
            products: @json($products),
            sortField: 'stock',
            sortDirection: 'asc',
            filters: {
                category: '',
                stockStatus: '',
                search: '',
                dateFrom: '',
                dateTo: ''
            },

            init() {
                // Initialize flatpickr for date range
                const that = this;
                flatpickr(this.$refs.dateFilter, {
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    onChange: function(selectedDates, dateStr) {
                        if (selectedDates.length === 2) {
                            that.filters.dateFrom = selectedDates[0].toISOString().split('T')[0];
                            that.filters.dateTo = selectedDates[1].toISOString().split('T')[0];
                        } else {
                            that.filters.dateFrom = '';
                            that.filters.dateTo = '';
                        }
                    }
                });
            },

            get filteredProducts() {
                return this.products
                    .filter(product => {
                        let matchesCategory = true;
                        let matchesStockStatus = true;
                        let matchesSearch = true;
                        let matchesDate = true;

                        // Filter by category
                        if (this.filters.category) {
                            matchesCategory = product.category_id.toString() === this.filters.category;
                        }

                        // Filter by stock status
                        if (this.filters.stockStatus) {
                            switch(this.filters.stockStatus) {
                                case 'out':
                                    matchesStockStatus = product.stock === 0;
                                    break;
                                case 'critical':
                                    matchesStockStatus = product.stock > 0 && product.stock <= 5;
                                    break;
                                case 'low':
                                    matchesStockStatus = product.stock > 5 && product.stock <= 20;
                                    break;
                                case 'normal':
                                    matchesStockStatus = product.stock > 20;
                                    break;
                            }
                        }

                        // Search filter - check if product name or brand contains search text
                        if (this.filters.search) {
                            const searchTerm = this.filters.search.toLowerCase();
                            matchesSearch = product.name?.toLowerCase().includes(searchTerm) ||
                                          product.brand?.toLowerCase().includes(searchTerm) ||
                                          product.manufacturer_name?.toLowerCase().includes(searchTerm) ||
                                          product.model?.toLowerCase().includes(searchTerm);
                        }

                        // Date filter
                        if (this.filters.dateFrom && this.filters.dateTo) {
                            const productDate = new Date(product.created_at).toISOString().split('T')[0];
                            matchesDate = productDate >= this.filters.dateFrom && productDate <= this.filters.dateTo;
                        }

                        return matchesCategory && matchesStockStatus && matchesSearch && matchesDate;
                    })
                    .sort((a, b) => {
                        const aValue = a[this.sortField];
                        const bValue = b[this.sortField];

                        if (typeof aValue === 'string' && typeof bValue === 'string') {
                            if (this.sortDirection === 'asc') {
                                return aValue.localeCompare(bValue);
                            } else {
                                return bValue.localeCompare(aValue);
                            }
                        } else {
                            if (this.sortDirection === 'asc') {
                                return aValue - bValue;
                            } else {
                                return bValue - aValue;
                            }
                        }
                    });
            },

            sortBy(field) {
                if (this.sortField === field) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortDirection = 'asc';
                }
            },

            getCategoryName(categoryId) {
                @php
                $categoriesJson = $categories->keyBy('id')->map(function($category) {
                    return $category->name;
                });
                @endphp

                const categories = @json($categoriesJson);
                return categories[categoryId] || 'Uncategorized';
            },

            formatPrice(price) {
                return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(price);
            },

            exportToExcel() {
                // Create URL with all active filters
                let url = "{{ route('inventory.reports.csv') }}?";
                if (this.filters.category) {
                    url += `category=${this.filters.category}&`;
                }
                if (this.filters.stockStatus) {
                    url += `stockStatus=${this.filters.stockStatus}&`;
                }
                if (this.filters.dateFrom && this.filters.dateTo) {
                    url += `dateFrom=${this.filters.dateFrom}&dateTo=${this.filters.dateTo}&`;
                }

                // Redirect to the CSV download route
                window.location.href = url;
            },

            exportToCSV() {
                this.exportToExcel();
            }
        }));
    });
</script>
@endsection
