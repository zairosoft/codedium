@extends('layouts.layout')
@section('title', 'แก้ไขหมวดหมู่ค่าใช้จ่าย')
@section('content')
    <div class="panel">
        <div class="mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">แก้ไขหมวดหมู่ค่าใช้จ่าย</h5>
        </div>
        <form action="{{ route('expenses.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label for="name">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" class="form-input" required
                    value="{{ old('name', $category->name) }}" />
            </div>
            <div class="mb-5">
                <label for="description">คำอธิบาย</label>
                <textarea id="description" name="description" class="form-textarea"
                    rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="mb-5">
                <label for="status">สถานะ</label>
                <select id="status" name="status" class="form-select">
                    <option value="1" {{ old('status', $category->status) == '1' ? 'selected' : '' }}>ใช้งาน</option>
                    <option value="0" {{ old('status', $category->status) == '0' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="{{ route('expenses.categories.index') }}" class="btn btn-outline-danger">ยกเลิก</a>
            </div>
        </form>
    </div>
@endsection