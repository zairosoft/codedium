@extends('layouts.layout')
@section('title', 'Edit Category')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">
<style scoped>
    .preview-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>
@endsection
@section('content')
<div x-data="editCategory">
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">Edit Category</h5>
        </div>

        <form @submit.prevent="submitForm" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name">Category Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" x-model="formData.name" class="form-input" placeholder="Enter category name" required>
                    <template x-if="errors.name">
                        <div class="text-danger mt-1" x-text="errors.name"></div>
                    </template>
                </div>

                <div>
                    <label for="slug">Slug</label>
                    <input id="slug" type="text" x-model="formData.slug" class="form-input" placeholder="Enter category slug">
                    <div class="text-info mt-1">Leave empty to auto-generate from name.</div>
                    <template x-if="errors.slug">
                        <div class="text-danger mt-1" x-text="errors.slug"></div>
                    </template>
                </div>
            </div>

            <div class="mb-5">
                <label for="description">Description</label>
                <textarea id="description" x-model="formData.description" class="form-input min-h-[100px]" placeholder="Enter category description"></textarea>
                <template x-if="errors.description">
                    <div class="text-danger mt-1" x-text="errors.description"></div>
                </template>
            </div>

            <div class="mb-5">
                <label for="category_image">Category Image</label>
                <input id="category_image" type="file" @change="previewImage" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold file:bg-primary/10 dark:file:bg-primary/20 dark:file:text-white-light file:text-primary" accept="image/*">
                <template x-if="imagePreview">
                    <div class="mt-3">
                        <img :src="imagePreview" class="preview-img">
                    </div>
                </template>
                <template x-if="!imagePreview && category.img">
                    <div class="mt-3">
                        <img src="{{ asset('storage') }}/{{ $category->img }}" class="preview-img">
                    </div>
                </template>
                <template x-if="errors.category_image">
                    <div class="text-danger mt-1" x-text="errors.category_image"></div>
                </template>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('categories') }}" class="btn btn-outline-danger mr-2">Cancel</a>
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
                        Update Category
                    </template>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('editCategory', () => ({
            category: @json($category),
            formData: {
                name: '',
                slug: '',
                description: '',
                category_image: null
            },
            imagePreview: null,
            errors: {},
            isSubmitting: false,

            init() {
                // Get translation in the current locale
                const translation = this.category.translations && this.category.translations.length > 0
                    ? this.category.translations[0]
                    : null;

                this.formData.name = translation?.name || '';
                this.formData.slug = translation?.slug || '';
                this.formData.description = translation?.description || '';
            },

            previewImage(event) {
                const file = event.target.files[0];
                this.formData.category_image = file;

                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.imagePreview = null;
                }
            },

            submitForm() {
                this.isSubmitting = true;
                this.errors = {};

                const formData = new FormData();
                formData.append('name', this.formData.name);
                formData.append('slug', this.formData.slug);
                formData.append('description', this.formData.description);
                if (this.formData.category_image) {
                    formData.append('category_image', this.formData.category_image);
                }
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PUT');

                fetch('{{ route("category.update", $category->id) }}', {
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
                        window.location.href = '{{ route("categories") }}';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the category.');
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
            }
        }));
    });
</script>
@endsection
