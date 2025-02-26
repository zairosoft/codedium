@extends('layouts.layout')
@section('title', 'แก้ไขบทบาท')
@section('style')
<style>
    .is-invalid {
        --tw-text-opacity: 1;
        color: rgb(231 81 90 / var(--tw-text-opacity));
        border: 1px solid rgb(231 81 90 / var(--tw-text-opacity));
    }
</style>
@endsection
@section('content')
<div>
    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">แก้ไขบทบาท</div>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <ul class="flex text-gray-500 dark:text-white-dark">
                <li>
                    <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0">
                            <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5"></path>
                            <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </a>
                </li>
                <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/roles') }}">บทบาท</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">แก้ไข</a></li>
            </ul>
        </div>
    </div>

    <div class="panel">
        <div class="mb-5">
            <form action="{{ url('roles', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <div class="@error('name')has-error @enderror">
                        <label for="fullName">บทบาท</label>
                        <input id="fullName" type="text" name="name" value="{{ $role->name }}" placeholder="ป้อนบทบาท" class="form-input" />
                        @if ($errors->has('name'))
                            <p class="text-danger mt-1">กรุณาป้อนบทบาท</p>
                        @endif
                    </div>
                </div>
                <div class="table-responsive mt-6">
                    <table>
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="form-checkbox" id="checkboxAll" /></th>
                                <th>สิทธิ์</th>
                                <th>สิทธิ์ที่ใช้ได้</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $key => $group)
                                <tr>
                                    <td width="5%">
                                        <label>
                                            <input class="form-checkbox" id="checkboxSub" type="checkbox" value="{{ $key }}">
                                        </label>
                                    </td>
                                    <td>
                                        <b>{{ ucfirst($key) }}</b>
                                    </td>
                                    <td>
                                        @forelse($group as $permission)

                                        <div class="@error('permission')has-error @enderror">
                                            <label style="width: 30%">
                                                <input name="permission[]" class="form-checkbox {{ $permission->name }}" id="checkboxItem" type="checkbox" value="{{ $permission->name }}"{{ in_array($permission->id, $rolePermissions) ? ' checked':'' }}>{{ $permission->name }}
                                            </label>
                                        </div>


                                        @empty
                                        No permission in this group !
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="hidden lg:flex mt-5 gap-4">
                    <a href="javascript:history.back()" class="btn btn-outline-danger">ยกเลิก</a>
                    <button class="btn btn-primary" type="submit" x-data="{loading:false}" x-on:click="loading = true; setTimeout(() => loading = false, 4000)" x-html="loading ? `<span class='animate-spin border-2 border-white border-l-transparent rounded-full w-5 h-5 ltr:mr-4 rtl:ml-4 inline-block align-middle'></span>Loading` : 'บันทึก'">
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script>
$(function(){
    $("input[id*='checkboxAll']:checkbox").click(function () {
        $("input[id*='checkboxSub']:checkbox").attr('checked', $(this).is(':checked'));
        $("input[id*='checkboxItem']:checkbox").attr('checked', $(this).is(':checked'));
    });


    $("input[id*='checkboxSub']:checkbox").click(function () {
        $("input[class*='"+$(this).val()+"']:checkbox").attr('checked', $(this).is(':checked'));
    });
});
</script>
@endsection
