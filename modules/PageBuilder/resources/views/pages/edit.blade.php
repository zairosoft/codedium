@extends('pagebuilder::layouts.master')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">แก้ไขหน้า: {{ $page->title }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('pagebuilder.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> กลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('pagebuilder.update', $page->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">ชื่อหน้า</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="html_content" class="form-label">HTML Content</label>
                            <textarea class="form-control @error('html_content') is-invalid @enderror" id="html_content" name="html_content" rows="10">{{ old('html_content', $page->html_content) }}</textarea>
                            @error('html_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="css_content" class="form-label">CSS Content</label>
                            <textarea class="form-control @error('css_content') is-invalid @enderror" id="css_content" name="css_content" rows="5">{{ old('css_content', $page->css_content) }}</textarea>
                            @error('css_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="js_content" class="form-label">JS Content</label>
                            <textarea class="form-control @error('js_content') is-invalid @enderror" id="js_content" name="js_content" rows="5">{{ old('js_content', $page->js_content) }}</textarea>
                            @error('js_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">เผยแพร่</label>
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <a href="{{ route('pagebuilder.index') }}" class="btn btn-secondary">ยกเลิก</a>
                            <a href="{{ route('pagebuilder.builder', $page->id) }}" class="btn btn-info">
                                <i class="fas fa-pencil-alt"></i> ใช้ Page Builder
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
