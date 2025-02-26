@extends('layouts.layout')
@section('title', 'แสดงรายการบทบาท')
@section('style')
<style>
</style>
@endsection
@section('content')
<div>
    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">แสดงรายการบทบาท</div>
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
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">ดู</a></li>
            </ul>
        </div>
    </div>
    <div class="panel">
        <div class="mb-5">
            <form action="{{ url('roles', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <label for="name">บทบาท</label>
                    <div>
                        {{ $role->name }}
                    </div>
                </div>
                <div class="table-responsive mt-6">
                    <table>
                        <thead>
                            <tr>
                                <th>สิทธิ์</th>
                                <th>สิทธิ์ที่ใช้ได้</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $key => $group)
                                <tr>
                                    <td>
                                        <b>{{ ucfirst($key) }}</b>
                                    </td>
                                    <td>
                                        @forelse($group as $permission)
                                        <div class="@error('name')has-error @enderror">
                                            <label style="width: 30%">
                                                <input name="permission[]" class="form-checkbox" type="checkbox" disabled value="{{ $permission->name }}"{{ in_array($permission->id, $rolePermissions) ? ' checked':'' }}>{{ $permission->name }}
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
            </form>
        </div>
    </div>
</div>
@endsection
