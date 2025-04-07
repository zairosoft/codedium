@extends('layouts.layout')
@section('title', 'Edit Product')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">
<style scoped>
    .product-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
    }
    .preview-img-container {
        display: inline-block;
        position: relative;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .preview-img-delete {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>
@endsection
@section('content')
<div x-data="editProduct">
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">Edit Product</h5>
        </div>

        <form @submit.prevent="submitForm" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name">Product Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" x-model="formData.name" class="form-input" placeholder="Enter product name" required>
                    <template x-if="errors.name">
                        <div class="text-danger mt-1" x-text="errors.name"></div>
                    </template>
                </div>

                <div>
                    <label for="category_id">Category <span class="text-danger">*</span></label>
                    <select id="category_id" x-model="formData.category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <template x-if="errors.category_id">
                        <div class="text-danger mt-1" x-text="errors.category_id"></div>
                    </template>
                </div>

                <div>
                    <label for="model">Model <span class="text-danger">*</span></label>
                    <input id="model" type="text" x-model="formData.model" class="form-input" placeholder="Enter product model" required>
                    <template x-if="errors.model">
                        <div class="text-danger mt-1" x-text="errors.model"></div>
                    </template>
                </div>

                <div>
                    <label for="price">Price <span class="text-danger">*</span></label>
                    <input id="price" type="number" x-model="formData.price" class="form-input" placeholder="Enter product price" min="0" step="0.01" required>
                    <template x-if="errors.price">
                        <div class="text-danger mt-1" x-text="errors.price"></div>
                    </template>
                </div>

                <div>
                    <label for="cost">Cost</label>
                    <input id="cost" type="number" x-model="formData.cost" class="form-input" placeholder="Enter product cost" min="0" step="0.01">
                    <template x-if="errors.cost">
                        <div class="text-danger mt-1" x-text="errors.cost"></div>
                    </template>
                </div>

                <div>
                    <label for="barcode">Barcode</label>
                    <input id="barcode" type="text" x-model="formData.barcode" class="form-input" placeholder="Enter product barcode">
                    <template x-if="errors.barcode">
                        <div class="text-danger mt-1" x-text="errors.barcode"></div>
                    </template>
                </div>

                <div>
                    <label for="sku">SKU</label>
                    <input id="sku" type="text" x-model="formData.sku" class="form-input" placeholder="Enter product SKU">
                    <template x-if="errors.sku">
                        <div class="text-danger mt-1" x-text="errors.sku"></div>
                    </template>
                </div>

                <div>
                    <label for="stock">Stock <span class="text-danger">*</span></label>
                    <input id="stock" type="number" x-model="formData.stock" class="form-input" placeholder="Enter product stock" min="0" required>
                    <template x-if="errors.stock">
                        <div class="text-danger mt-1" x-text="errors.stock"></div>
                    </template>
                </div>

                <div>
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select id="status" x-model="formData.status" class="form-select" required>
                        <option value="">Select Status</option>
                        <option value="1">Published</option>
                        <option value="0">Hidden</option>
                        <option value="2">Draft</option>
                    </select>
                    <template x-if="errors.status">
                        <div class="text-danger mt-1" x-text="errors.status"></div>
                    </template>
                </div>

                <div>
                    <label for="publish_schedule">Publish Schedule</label>
                    <input id="publish_schedule" type="date" x-model="formData.publish_schedule" class="form-input">
                    <template x-if="errors.publish_schedule">
                        <div class="text-danger mt-1" x-text="errors.publish_schedule"></div>
                    </template>
                </div>
            </div>

            <div class="mb-5">
                <label for="manufacturer_name">Manufacturer Name</label>
                <input id="manufacturer_name" type="text" x-model="formData.manufacturer_name" class="form-input" placeholder="Enter manufacturer name">
                <template x-if="errors.manufacturer_name">
                    <div class="text-danger mt-1" x-text="errors.manufacturer_name"></div>
                </template>
            </div>

            <div class="mb-5">
                <label for="manufacturer_brand">Manufacturer Brand</label>
                <input id="manufacturer_brand" type="text" x-model="formData.manufacturer_brand" class="form-input" placeholder="Enter manufacturer brand">
                <template x-if="errors.manufacturer_brand">
                    <div class="text-danger mt-1" x-text="errors.manufacturer_brand"></div>
                </template>
            </div>

            <div class="mb-5">
                <label for="brand">Brand</label>
                <input id="brand" type="text" x-model="formData.brand" class="form-input" placeholder="Enter product brand">
                <template x-if="errors.brand">
                    <div class="text-danger mt-1" x-text="errors.brand"></div>
                </template>
            </div>

            <div class="mb-5">
                <label for="short_description">Short Description</label>
                <input id="short_description" type="text" x-model="formData.short_description" class="form-input" placeholder="Enter short description">
                <template x-if="errors.short_description">
                    <div class="text-danger mt-1" x-text="errors.short_description"></div>
                </template>
            </div>

            <div class="mb-5">
                <label for="description">Description</label>
                <textarea id="description" x-model="formData.description" class="form-input min-h-[100px]" placeholder="Enter product description"></textarea>
                <template x-if="errors.description">
                    <div class="text-danger mt-1" x-text="errors.description"></div>
                </template>
            </div>

            <div class="mb-5">
                <label for="product_image">Main Product Image</label>
                <input id="product_image" type="file" @change="previewMainImage" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold file:bg-primary/10 dark:file:bg-primary/20 dark:file:text-white-light file:text-primary" accept="image/*">
                <template x-if="mainImagePreview">
                    <div class="mt-3">
                        <img :src="mainImagePreview" class="product-img">
                    </div>
                </template>
                <template x-if="!mainImagePreview && product.img">
                    <div class="mt-3">
                        <img src="{{ asset('storage') }}/{{ $product->img }}" class="product-img">
                    </div>
                </template>
                <template x-if="errors.product_image">
                    <div class="text-danger mt-1" x-text="errors.product_image"></div>
                </template>
            </div>

            <div class="mb-5">
                <label for="additional_images">Additional Product Images</label>
                <input id="additional_images" type="file" @change="previewAdditionalImages" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold file:bg-primary/10 dark:file:bg-primary/20 dark:file:text-white-light file:text-primary" accept="image/*" multiple>

                <div class="mt-3 flex flex-wrap">
                    <template x-if="additionalImagePreviews.length > 0">
                        <template x-for="(preview, index) in additionalImagePreviews" :key="index">
                            <div class="preview-img-container">
                                <img :src="preview" class="product-img">
                                <span class="preview-img-delete" @click="removeAdditionalImage(index)">×</span>
                            </div>
                        </template>
                    </template>

                    @if($product->images && count($product->images) > 0)
                        @foreach($product->images as $image)
                        <div class="preview-img-container existing-image">
                            <img src="{{ asset('storage') }}/{{ $image->img }}" class="product-img">
                            <span class="preview-img-delete" onclick="deleteExistingImage({{ $image->id }})">×</span>
                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                        </div>
                        @endforeach
                    @endif
                </div>

                <template x-if="errors.additional_images">
                    <div class="text-danger mt-1" x-text="errors.additional_images"></div>
                </template>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('inventory') }}" class="btn btn-outline-danger mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary" :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }" :disabled="isSubmitting">
                    <template x-if="isSubmitting">
                        <span class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 animate-spin mr-2">
                                <line x1="12" y1="2" x2="12" y2="6"></line>
                                <line x1="12" y1="18" x2="12" y2="22"></line>
                                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                <line x1="2" y1="12" x2="6" y2="12"></line>
                                <line x1="18" y1="12" x2="22" y2="12"></line>
                                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                            </svg>
                            Saving...
                        </span>
                    </template>
                    <template x-if="!isSubmitting">
                        Update Product
                    </template>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let deletedImages = [];

    function deleteExistingImage(imageId) {
        deletedImages.push(imageId);
        document.querySelector(`.existing-image input[value="${imageId}"]`).parentElement.remove();
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('editProduct', () => ({
            product: @json($product),
            formData: {
                name: '',
                category_id: '',
                model: '',
                price: '',
                cost: '',
                barcode: '',
                sku: '',
                stock: '',
                status: '',
                publish_schedule: '',
                manufacturer_name: '',
                manufacturer_brand: '',
                brand: '',
                short_description: '',
                description: '',
                product_image: null,
                additional_images: []
            },
            mainImagePreview: null,
            additionalImagePreviews: [],
            errors: {},
            isSubmitting: false,

            init() {
                // Get translation in the current locale
                const translation = this.product.translations && this.product.translations.length > 0
                    ? this.product.translations[0]
                    : null;

                this.formData.name = translation?.name || '';
                this.formData.category_id = this.product.category_id || '';
                this.formData.model = this.product.model || '';
                this.formData.price = this.product.price || '';
                this.formData.cost = this.product.cost || '';
                this.formData.barcode = this.product.barcode || '';
                this.formData.sku = this.product.sku || '';
                this.formData.stock = this.product.stock || '';
                this.formData.status = this.product.status?.toString() || '';
                this.formData.publish_schedule = this.product.publish_schedule || '';
                this.formData.manufacturer_name = translation?.manufacturer_name || '';
                this.formData.manufacturer_brand = translation?.manufacturer_brand || '';
                this.formData.brand = translation?.brand || '';
                this.formData.short_description = translation?.short_description || '';
                this.formData.description = translation?.description || '';
            },

            previewMainImage(event) {
                const file = event.target.files[0];
                this.formData.product_image = file;

                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.mainImagePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.mainImagePreview = null;
                }
            },

            previewAdditionalImages(event) {
                const files = event.target.files;
                if (files) {
                    Array.from(files).forEach(file => {
                        this.formData.additional_images.push(file);

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.additionalImagePreviews.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            },

            removeAdditionalImage(index) {
                this.additionalImagePreviews.splice(index, 1);
                this.formData.additional_images.splice(index, 1);
            },

            submitForm() {
                this.isSubmitting = true;
                this.errors = {};

                const formData = new FormData();
                formData.append('name', this.formData.name);
                formData.append('category_id', this.formData.category_id);
                formData.append('model', this.formData.model);
                formData.append('price', this.formData.price);
                formData.append('cost', this.formData.cost);
                formData.append('barcode', this.formData.barcode);
                formData.append('sku', this.formData.sku);
                formData.append('stock', this.formData.stock);
                formData.append('status', this.formData.status);
                formData.append('publish_schedule', this.formData.publish_schedule);
                formData.append('manufacturer_name', this.formData.manufacturer_name);
                formData.append('manufacturer_brand', this.formData.manufacturer_brand);
                formData.append('brand', this.formData.brand);
                formData.append('short_description', this.formData.short_description);
                formData.append('description', this.formData.description);

                if (this.formData.product_image) {
                    formData.append('product_image', this.formData.product_image);
                }

                if (this.formData.additional_images.length > 0) {
                    this.formData.additional_images.forEach((image, index) => {
                        formData.append(`additional_images[${index}]`, image);
                    });
                }

                if (deletedImages.length > 0) {
                    deletedImages.forEach(id => {
                        formData.append('delete_images[]', id);
                    });
                }

                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PUT');

                fetch('{{ route("product.update", $product->id) }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        this.errors = data.errors;
                    } else if (data.error) {
                        alert('Error: ' + data.error);
                    } else if (data.success) {
                        window.location.href = '{{ route("inventory") }}';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the product.');
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
            }
        }));
    });
</script>
@endsection
