@extends('layouts.layout')
@section('title', 'เพิ่มหมวดหมู่ค่าใช้จ่าย')
@section('content')
    <div class="panel">
        <div class="mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">เพิ่มหมวดหมู่ค่าใช้จ่าย</h5>
        </div>
        <form action="{{ route('expenses.categories.store') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="name">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" class="form-input" required value="{{ old('name') }}" />
            </div>
            <div class="mb-5">
                <label for="description">คำอธิบาย</label>
                <textarea id="description" name="description" class="form-textarea"
                    rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="mb-5">
                <label for="status">สถานะ</label>
                <select id="status" name="status" class="form-select">
                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>ใช้งาน</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="{{ route('expenses.categories.index') }}" class="btn btn-outline-danger">ยกเลิก</a>
            </div>
        </form>
    </div>
@endsection