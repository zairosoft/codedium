@extends('layouts.layout')
@section('title', 'เพิ่มผู้ใช้ระบบ')
@section('script')
<script src="{{ asset('assets/js/nice-select2.js') }}"></script>
<!-- script -->
<script>
    document.addEventListener("DOMContentLoaded", function(e) {
        // default
        var els = document.querySelectorAll(".selectize");
        els.forEach(function(select) {
            NiceSelect.bind(select);
        });
    });
</script>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css') }}" />
    <style>
        .upload-field-customized {
            position: relative;
            cursor: pointer;
            top: -45px;
            left: -80px;
            float: right;
            background: #eeeeee;

            input[type="file"] {
                position: absolute;
                width: 50px;
                height: 50px;
                opacity: 0;
                cursor: pointer;
                left: -5px;
                top: -10px;
                z-index: 10;
            }
        }

        .btn-upload {
            cursor: pointer;
        }

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
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">ผู้ใช้ระบบ</div>
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
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/users') }}">ผู้ใช้ระบบ</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">เพิ่ม</a></li>
                </ul>
            </div>
        </div>

        <div>
            <div x-data="{ tab: 'home' }">
                <ul
                    class="sm:flex font-semibold border-b border-[#ebedf2] dark:border-[#191e3a] mb-5 whitespace-nowrap overflow-y-auto">
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                            :class="{ '!border-primary text-primary': tab == 'home' }" @click="tab='home'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            ข้อมูลทั่วไป
                        </a>
                    </li>
                </ul>
                <template x-if="tab === 'home'">
                    <div>
                        <form action="{{ url('users') }}" method="post"
                            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]"
                            enctype="multipart/form-data">
                            @csrf
                            <h6 class="text-lg font-bold mb-5">ข้อมูลทั่วไป</h6>
                            <div class="flex flex-col sm:flex-row">
                                <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
                                    <img src="{{ asset('assets/images/user.png') }}" alt="image" class="w-20 h-20 md:w-32 md:h-32 rounded-full object-cover mx-auto" id="preview" />
                                    <div
                                        class="upload-field-customized block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                                        <input type="file" name="image" accept="image/*" id="file_upload" onchange="previewImage(event)">
                                        <span class="btn-upload">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path opacity="0.5"
                                                    d="M2 12.5001L3.75159 10.9675C4.66286 10.1702 6.03628 10.2159 6.89249 11.0721L11.1822 15.3618C11.8694 16.0491 12.9512 16.1428 13.7464 15.5839L14.0446 15.3744C15.1888 14.5702 16.7369 14.6634 17.7765 15.599L21 18.5001"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path
                                                    d="M18.562 2.9354L18.9791 2.5183C19.6702 1.82723 20.7906 1.82723 21.4817 2.5183C22.1728 3.20937 22.1728 4.32981 21.4817 5.02087L21.0646 5.43797M18.562 2.9354C18.562 2.9354 18.6142 3.82172 19.3962 4.60378C20.1783 5.38583 21.0646 5.43797 21.0646 5.43797M18.562 2.9354L14.7275 6.76995C14.4677 7.02968 14.3379 7.15954 14.2262 7.30273C14.0945 7.47163 13.9815 7.65439 13.8894 7.84776C13.8112 8.01169 13.7532 8.18591 13.637 8.53437L13.2651 9.65M21.0646 5.43797L17.23 9.27253C16.9703 9.53225 16.8405 9.66211 16.6973 9.7738C16.5284 9.90554 16.3456 10.0185 16.1522 10.1106C15.9883 10.1888 15.8141 10.2468 15.4656 10.363L14.35 10.7349M14.35 10.7349L13.6281 10.9755C13.4567 11.0327 13.2676 10.988 13.1398 10.8602C13.012 10.7324 12.9673 10.5433 13.0245 10.3719L13.2651 9.65M14.35 10.7349L13.2651 9.65"
                                                    stroke="currentColor" stroke-width="1.5"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="name">ชื่อ-นามสกุล</label>
                                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="ป้อนชื่อ-นามสกุล" class="form-input @error('name')is-invalid @enderror" />
                                        @if ($errors->has('name'))
                                            <div>
                                                <p class="text-danger mt-1">{!! $errors->first('name') !!}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="gender">เพศ</label>
                                        <select id="gender" name="gender" placeholder="เลือก..." class="selectize">
                                            <option value="male"{{ old('gender') == 'male' ? ' selected' : '' }}>ชาย</option>
                                            <option value="female"{{ old('gender') == 'female' ? ' selected' : '' }}>หญิง</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="profession">วันเกิด</label>
                                        <div x-data="form">
                                            <input id="basic" type="text" name="birthday" value="{{ old('birthday') }}" x-model="date1" placeholder="ป้อนวันเกิด" class="form-input" />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="profession">ตําแหน่งงาน</label>
                                        <input id="profession" name="profession" type="text" value="{{ old('profession') }}" placeholder="ป้อนตําแหน่งงาน" class="form-input" />
                                    </div>
                                    <div>
                                        <label for="email">อีเมล</label>
                                        <input id="email" type="email"  placeholder="ป้อนอีเมล" value="{{ old('email') }}" name="email" class="form-input @error('email')is-invalid @enderror" />
                                        @if ($errors->has('email'))
                                            <div>
                                                <p class="text-danger mt-1">{!! $errors->first('email') !!}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="web">เว็บไซต์</label>
                                        <input id="web" type="url" name="website" value="{{ old('website') }}" placeholder="ป้อน URL" class="form-input" />
                                    </div>
                                    <div>
                                        <label for="address">ที่อยู่</label>
                                        <input id="address" type="text" name="address" value="{{ old('address') }}" placeholder="ป้อนที่อยู่ปัจจุบัน" class="form-input" />
                                    </div>
                                    <div>
                                        <label for="phone">เบอร์โทรศัพท์</label>
                                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="099 999 9999" x-mask="099 999 9999" class="form-input" id="phoneMask" />
                                    </div>
                                    <div>
                                        <label for="phone">บทบาท</label>
                                        <select name="roles[]" class="selectize" placeholder="เลือก..." multiple="multiple" @error('roles')is-invalid @enderror>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}">{{ $role }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('roles'))
                                            <div>
                                                <p class="text-danger mt-1">{!! $errors->first('roles') !!}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="Password">รหัสผ่าน</label>
                                        <input id="Password" type="password" placeholder="ป้อนรหัสผ่าน" name="password" class="form-input @error('password')is-invalid @enderror" />
                                        @if ($errors->has('password'))
                                            <div>
                                                <p class="text-danger mt-1">{!! $errors->first('password') !!}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 flex flex-col sm:flex-row">
                                <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5"></div>
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-1 gap-5">
                                    <div>
                                        <label for="about">เกี่ยวกับ</label>
                                        <textarea class="form-textarea" name="about" placeholder="ป้อนคำอธิบาย" style="height: 125px;">{{ old('about') }}</textarea>
                                    </div>
                                    <div class="hidden lg:flex mt-1 gap-4">
                                        <a href="javascript:history.back()" class="btn btn-outline-danger">ยกเลิก</a>
                                        <button class="btn btn-primary" type="submit" x-data="{loading:false}" x-on:click="loading = true; setTimeout(() => loading = false, 4000)" x-html="loading ? `<span class='animate-spin border-2 border-[#fafafa] border-l-transparent rounded-full w-5 h-5 ltr:mr-4 rtl:ml-4 inline-block align-middle'></span>Loading` : 'บันทึก'">
                                            บันทึก
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script src="{{ asset('assets/js/alpine-mask.min.js') }}"></script>
    <script>
        flatpickr.localize(flatpickr.l10ns.th);
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date1: '{{ date('d-m-Y') }}',
                init() {
                    flatpickr(document.getElementById('basic'), {
                        "locale": "th",
                        dateFormat: 'd-m-Y',
                        defaultDate: this.date1,
                    })
                }
            }));
        });
        async function showAlert() {
            const toast = window.Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3500,
                padding: '2em',
            });
            toast.fire({
                icon: 'success',
                title: '{!! \Session::get('success') !!}',
                padding: '2em',
            });
        }
        @if (\Session::has('success'))
            setTimeout(() => {
                window.onload = showAlert();
            }, "500");
            // {!! \Session::get('success') !!}
        @endif
    </script>
@endsection
