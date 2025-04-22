@extends('pagebuilder::layouts.master')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page->title }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('pagebuilder.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> กลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Slug:</strong> {{ $page->slug }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>สถานะ:</strong>
                        @if($page->is_published)
                            <span class="badge bg-success">เผยแพร่</span>
                        @else
                            <span class="badge bg-secondary">ฉบับร่าง</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>วันที่สร้าง:</strong> {{ $page->created_at->format('d M Y H:i') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>วันที่ปรับปรุงล่าสุด:</strong> {{ $page->updated_at->format('d M Y H:i') }}
                    </div>
                    
                    @if($page->meta_description)
                    <div class="mb-3">
                        <strong>Meta Description:</strong> {{ $page->meta_description }}
                    </div>
                    @endif
                    
                    @if($page->meta_keywords)
                    <div class="mb-3">
                        <strong>Meta Keywords:</strong> {{ $page->meta_keywords }}
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <a href="{{ route('pagebuilder.edit', $page->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> แก้ไข
                        </a>
                        <a href="{{ route('pagebuilder.builder', $page->id) }}" class="btn btn-info">
                            <i class="fas fa-pencil-alt"></i> ใช้ Page Builder
                        </a>
                        <a href="{{ route('pagebuilder.preview', $page->slug) }}" target="_blank" class="btn btn-success">
                            <i class="fas fa-eye"></i> ดูตัวอย่าง
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
