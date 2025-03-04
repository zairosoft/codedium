@extends('layouts.layout')
@section('title', 'แก้ไขสิทธิ์การใช้งาน')
@section('content')
<div>
    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">สิทธิ์การใช้งาน</div>
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
                <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/permissions') }}">สิทธิ์การใช้งาน</a></li>
                <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">แก้ไข</a></li>
            </ul>
        </div>
    </div>
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">แก้ไขสิทธิ์การใช้งาน</h5>
        </div>
        <div class="mb-5" x-data="form">
            <form class="space-y-5" method="POST" @submit.prevent="submitForm()">
                @csrf
                @method('PUT')
                <div :class="[isSubmitForm ? (form.name ? '' : 'has-error') : '']">
                    <label for="fullName">สิทธิ์การใช้งาน</label>
                    <input id="fullName" type="text" name="name" placeholder="ป้อนสิทธิ์การใช้งาน" class="form-input" x-model="form.name" />
                    <template x-if="isSubmitForm && !form.name">
                        <p class="text-danger mt-1">กรุณาป้อนสิทธิ์การใช้งาน</p>
                    </template>
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
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                form: {
                    name: '{{ $permission->name }}'
                },
                isSubmitForm: false,
                submitForm() {
                    this.isSubmitForm = true;
                    const formData = new FormData();
                    if (this.form.name) {
                        fetch('{{ url('permissions', $permission->id) }}', {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ name: this.form.name })
                        })
                        .then(() => {
                            location.href = '{{ url('permissions') }}';
                        })
                        .catch(() => {
                            console.log('Ooops! Something went wrong!');
                        })
                    }
                }
            }));
        });
    </script>
@endsection
