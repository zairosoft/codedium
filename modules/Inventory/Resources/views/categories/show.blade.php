@extends('layouts.layout')
@section('title', 'Category Details')
@section('style')
<style scoped>
    .category-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endsection
@section('content')
<div>
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">Category Details</h5>
            <div class="flex gap-2">
                <a href="{{ route('categories') }}" class="btn btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back to Categories
                </a>
                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit Category
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="flex justify-center mb-4">
                    @if($category->img)
                    <img src="{{ asset('storage/' . $category->img) }}" alt="{{ $category->translations[0]->name ?? 'Category' }}" class="category-img">
                    @else
                    <div class="category-img bg-gray-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-gray-400">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500">Category ID: {{ $category->id }}</p>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="space-y-6">
                    <div>
                        <h6 class="font-bold text-lg mb-2">{{ $category->translations[0]->name ?? 'Untitled' }}</h6>
                        <p class="text-gray-500 text-sm">{{ $category->translations[0]->description ?? 'No description available' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h6 class="font-bold mb-1">Slug</h6>
                            <p>{{ $category->translations[0]->slug ?? 'No slug' }}</p>
                        </div>
                        <div>
                            <h6 class="font-bold mb-1">Created At</h6>
                            <p>{{ $category->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <h6 class="font-bold mb-1">Last Updated</h6>
                            <p>{{ $category->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <h6 class="font-bold mb-1">Products Count</h6>
                            <p>{{ DB::table('products')->where('category_id', $category->id)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
        $products = \Modules\Inventory\Models\Product::where('category_id', $category->id)
            ->join('product_langs', 'product_langs.product_id', '=', 'products.id')
            ->where('product_langs.code', '=', app()->getLocale())
            ->select('products.*', 'product_langs.name')
            ->limit(10)
            ->get();
        @endphp

        @if(count($products) > 0)
        <div class="mt-8">
            <h6 class="font-bold mb-3">Products in this Category</h6>
            <div class="table-responsive">
                <table class="whitespace-nowrap">
                    <thead>
                        <tr>
                            <th class="dark:text-white-light">ID</th>
                            <th class="dark:text-white-light">Name</th>
                            <th class="dark:text-white-light">Model</th>
                            <th class="dark:text-white-light">Price</th>
                            <th class="dark:text-white-light">Stock</th>
                            <th class="dark:text-white-light">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->model }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('product.show', $product->id) }}" class="hover:text-primary">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                            <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(DB::table('products')->where('category_id', $category->id)->count() > 10)
            <div class="mt-4 text-center">
                <a href="{{ route('inventory') }}?category={{ $category->id }}" class="btn btn-outline-primary btn-sm">View All Products</a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
