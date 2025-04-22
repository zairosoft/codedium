@extends('pagebuilder::layouts.master')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">หน้าเว็บ</h4>
                    <div class="card-tools">
                        <a href="{{ route('pagebuilder.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> สร้างหน้าใหม่
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ชื่อหน้า</th>
                                    <th>Slug</th>
                                    <th>สถานะ</th>
                                    <th>วันที่สร้าง</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr>
                                        <td>{{ $page->id }}</td>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->slug }}</td>
                                        <td>
                                            @if($page->is_published)
                                                <span class="badge bg-success">เผยแพร่</span>
                                            @else
                                                <span class="badge bg-secondary">ฉบับร่าง</span>
                                            @endif
                                        </td>
                                        <td>{{ $page->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('pagebuilder.builder', $page->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-pencil-alt"></i> Page Builder
                                                </a>
                                                <a href="{{ route('pagebuilder.edit', $page->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i> แก้ไข
                                                </a>
                                                <a href="{{ route('pagebuilder.preview', $page->slug) }}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fas fa-eye"></i> ดูตัวอย่าง
                                                </a>
                                                <form action="{{ route('pagebuilder.destroy', $page->id) }}" method="POST" onsubmit="return confirm('คุณต้องการลบหน้านี้ใช่หรือไม่?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">ไม่พบข้อมูล</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
