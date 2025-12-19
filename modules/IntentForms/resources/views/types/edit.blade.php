@extends('layouts.layout')
@section('title', 'แก้ไขประเภทการบริจาค')
@section('content')
    <div>
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">แก้ไขประเภทการบริจาค
            </div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <ul class="flex text-gray-500 dark:text-white-dark">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="before:content-['/'] before:px-1.5"><a
                            href="{{ url('/intentform/types') }}">ประเภทการบริจาค</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;"
                            class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">แก้ไข</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="panel">
            <form action="{{ route('intentform.types.update', $type->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name">ชื่อประเภท <span class="text-danger">*</span></label>
                        <input id="name" type="text" name="name" class="form-input @error('name') border-danger @enderror"
                            placeholder="กรอกชื่อประเภทการบริจาค" value="{{ old('name', $type->name) }}" required />
                        @error('name')
                            <span class="text-danger text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="price">ราคา (บาท) <span class="text-danger">*</span></label>
                        <input id="price" type="number" step="0.01" min="0" name="price"
                            class="form-input @error('price') border-danger @enderror" placeholder="กรอกราคา"
                            value="{{ old('price', $type->price) }}" required />
                        @error('price')
                            <span class="text-danger text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description">คำอธิบาย</label>
                        <textarea id="description" name="description" rows="3"
                            class="form-textarea @error('description') border-danger @enderror"
                            placeholder="กรอกคำอธิบาย (ถ้ามี)">{{ old('description', $type->description) }}</textarea>
                        @error('description')
                            <span class="text-danger text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="status">สถานะ <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select @error('status') border-danger @enderror"
                            required>
                            <option value="1" {{ old('status', $type->status) == '1' ? 'selected' : '' }}>ใช้งาน</option>
                            <option value="0" {{ old('status', $type->status) == '0' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                        </select>
                        @error('status')
                            <span class="text-danger text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        บันทึก
                    </button>
                    <a href="{{ route('intentform.types.index') }}" class="btn btn-outline-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(\Session::has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: '{{ \Session::get("error") }}',
                    padding: '2em',
                });
            });
        </script>
    @endif
@endsection