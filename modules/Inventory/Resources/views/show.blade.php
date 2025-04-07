@extends('layouts.layout')
@section('title', 'Product Details')
@section('style')
<style scoped>
    .product-img {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
    }
    .product-thumb {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        opacity: 0.7;
        transition: all 0.3s;
    }
    .product-thumb:hover, .product-thumb.active {
        opacity: 1;
        border: 2px solid #4361ee;
    }
</style>
@endsection
@section('content')
<div x-data="productDetails">
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">Product Details</h5>
            <div class="flex gap-2">
                <a href="{{ route('inventory') }}" class="btn btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back to Products
                </a>
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit Product
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="flex justify-center mb-4">
                    @if($product->img)
                    <img :src="mainImage" alt="{{ $product->translations[0]->name ?? 'Product' }}" class="product-img">
                    @else
                    <div class="product-img bg-gray-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-gray-400">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    @endif
                </div>

                @if(($product->images && count($product->images) > 0) || $product->img)
                <div class="flex flex-wrap justify-center gap-2 mt-4">
                    @if($product->img)
                    <img src="{{ asset('storage/' . $product->img) }}"
                         @click="setMainImage('{{ asset('storage/' . $product->img) }}')"
                         :class="{'active': mainImage === '{{ asset('storage/' . $product->img) }}'}"
                         class="product-thumb">
                    @endif

                    @if($product->images && count($product->images) > 0)
                        @foreach($product->images as $image)
                        <img src="{{ asset('storage/' . $image->img) }}"
                             @click="setMainImage('{{ asset('storage/' . $image->img) }}')"
                             :class="{'active': mainImage === '{{ asset('storage/' . $image->img) }}'}"
                             class="product-thumb">
                        @endforeach
                    @endif
                </div>
                @endif

                <div class="mt-6">
                    <div class="border p-4 rounded-lg">
                        <h6 class="font-bold mb-3">Stock Information</h6>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-sm text-gray-500">In Stock</p>
                                <p class="font-semibold">{{ $product->stock }} units</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">SKU</p>
                                <p class="font-semibold">{{ $product->sku ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Barcode</p>
                                <p class="font-semibold">{{ $product->barcode ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="font-semibold">
                                    @if($product->status == 1)
                                    <span class="badge badge-outline-success">Published</span>
                                    @elseif($product->status == 0)
                                    <span class="badge badge-outline-danger">Hidden</span>
                                    @else
                                    <span class="badge badge-outline-warning">Draft</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="space-y-6">
                    <div>
                        <h6 class="font-bold text-2xl mb-2">{{ $product->translations[0]->name ?? 'Untitled Product' }}</h6>
                        <p class="text-gray-500 text-sm">{{ $product->translations[0]->short_description ?? '' }}</p>

                        <div class="flex items-center gap-4 mt-4">
                            <div>
                                <span class="text-xl font-bold">{{ number_format($product->price, 2) }}</span>
                            </div>
                            @if($product->cost)
                            <div>
                                <span class="text-gray-500 line-through">{{ number_format($product->cost, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-b py-4">
                        <div>
                            <h6 class="font-bold mb-1">Model</h6>
                            <p>{{ $product->model }}</p>
                        </div>
                        <div>
                            <h6 class="font-bold mb-1">Category</h6>
                            <p>{{ $product->category->translations[0]->name ?? 'Uncategorized' }}</p>
                        </div>
                        <div>
                            <h6 class="font-bold mb-1">Brand</h6>
                            <p>{{ $product->translations[0]->brand ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h6 class="font-bold mb-1">Manufacturer</h6>
                            <p>{{ $product->translations[0]->manufacturer_name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div>
                        <h6 class="font-bold mb-3">Description</h6>
                        <div class="prose max-w-none">
                            {!! $product->translations[0]->description ?? 'No description available' !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-500">
                        <div>
                            <p>Created: {{ $product->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p>Last Updated: {{ $product->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($product->serials && count($product->serials) > 0)
        <div class="mt-8">
            <h6 class="font-bold mb-3">Serial Numbers</h6>
            <div class="table-responsive">
                <table class="whitespace-nowrap">
                    <thead>
                        <tr>
                            <th class="dark:text-white-light">Serial Number</th>
                            <th class="dark:text-white-light">Status</th>
                            <th class="dark:text-white-light">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->serials as $serial)
                        <tr>
                            <td>{{ $serial->serial_number }}</td>
                            <td>
                                @if($serial->status == 'Available')
                                <span class="badge badge-outline-success">Available</span>
                                @elseif($serial->status == 'Sold')
                                <span class="badge badge-outline-danger">Sold</span>
                                @elseif($serial->status == 'Reserved')
                                <span class="badge badge-outline-warning">Reserved</span>
                                @else
                                <span class="badge badge-outline-primary">{{ $serial->status }}</span>
                                @endif
                            </td>
                            <td>{{ $serial->notes }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productDetails', () => ({
            mainImage: '{{ $product->img ? asset("storage/" . $product->img) : "" }}',

            setMainImage(image) {
                this.mainImage = image;
            }
        }));
    });
</script>
@endsection
