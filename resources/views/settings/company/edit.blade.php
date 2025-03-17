@extends('layouts.layout')
@section('title', 'บริษัท')
@section('script')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(e) {
            var options = {
                searchable: true
            };
            NiceSelect.bind(document.getElementById("seachable-select"), options);
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
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">ข้อมูลบริษัท</div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <ul class="flex text-gray-500 dark:text-white-dark">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ route('setting.company') }}">บริษัท</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;"
                            class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">แก้ไข</a>
                    </li>
                </ul>
            </div>
        </div>
        <div>
            <div x-data="{activeTab: parseInt(localStorage.getItem('activeTab')) || 0, setActiveTab(index){this.activeTab = index; localStorage.setItem('activeTab', index.toString());}}">
                <ul role="tablist" class="sm:flex font-semibold border-b border-[#ebedf2] dark:border-[#191e3a] mb-5 whitespace-nowrap overflow-y-auto">
                    <li role="presentation" class="inline-block">
                        <a href="javascript:;" class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary" @click="setActiveTab(0)" :aria-selected="activeTab === 0" :tabindex="activeTab === 0 ? 0 : -1" :class="{ '!border-primary text-primary': activeTab === 0 }" role="tab" id="tab-1" aria-controls="panel-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            ข้อมูลทั่วไป
                        </a>
                    </li>
                    <li role="presentation" class="inline-block">
                        <a href="javascript:;" class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary" @click="setActiveTab(1)" :aria-selected="activeTab === 1" :tabindex="activeTab === 1 ? 0 : -1" :class="{ '!border-primary text-primary': activeTab === 1 }" role="tab" id="tab-2" aria-controls="panel-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M9.50004 2H14.5L15.1518 8.51737C15.3382 10.382 13.874 12 12 12C10.1261 12 8.66184 10.382 8.8483 8.51737L9.50004 2Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M3.32975 5.35133C3.50783 4.46093 3.59687 4.01573 3.77791 3.65484C4.15938 2.89439 4.84579 2.33168 5.66628 2.10675C6.05567 2 6.50969 2 7.41771 2H9.50002L8.77549 9.24527C8.61911 10.8091 7.30318 12 5.73155 12C3.8011 12 2.35324 10.2339 2.73183 8.34093L3.32975 5.35133Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M20.6703 5.35133C20.4922 4.46093 20.4031 4.01573 20.2221 3.65484C19.8406 2.89439 19.1542 2.33168 18.3337 2.10675C17.9443 2 17.4903 2 16.5823 2H14.5L15.2245 9.24527C15.3809 10.8091 16.6968 12 18.2685 12C20.1989 12 21.6468 10.2339 21.2682 8.34093L20.6703 5.35133Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M8.75 21.5C8.75 21.9142 9.08579 22.25 9.5 22.25C9.91421 22.25 10.25 21.9142 10.25 21.5H8.75ZM13.75 21.5C13.75 21.9142 14.0858 22.25 14.5 22.25C14.9142 22.25 15.25 21.9142 15.25 21.5H13.75ZM13.75 16.201L14.125 15.5514L13.75 16.201ZM14.299 16.75L13.6495 17.125V17.125L14.299 16.75ZM9.70096 16.75L10.3505 17.125L9.70096 16.75ZM10.25 16.201L10.625 16.8505L10.25 16.201ZM12.5 21.25H11.5V22.75H12.5V21.25ZM4.25 14V11H2.75V14H4.25ZM19.75 11V14H21.25V11H19.75ZM11.5 21.25C9.59318 21.25 8.23851 21.2484 7.21085 21.1102C6.20476 20.975 5.62511 20.7213 5.2019 20.2981L4.14124 21.3588C4.88961 22.1071 5.83855 22.4392 7.01098 22.5969C8.16182 22.7516 9.63558 22.75 11.5 22.75V21.25ZM2.75 14C2.75 15.8644 2.74841 17.3382 2.90313 18.489C3.06076 19.6614 3.39288 20.6104 4.14124 21.3588L5.2019 20.2981C4.77869 19.8749 4.52502 19.2952 4.38976 18.2892C4.25159 17.2615 4.25 15.9068 4.25 14H2.75ZM12.5 22.75C14.3644 22.75 15.8382 22.7516 16.989 22.5969C18.1614 22.4392 19.1104 22.1071 19.8588 21.3588L18.7981 20.2981C18.3749 20.7213 17.7952 20.975 16.7892 21.1102C15.7615 21.2484 14.4068 21.25 12.5 21.25V22.75ZM19.75 14C19.75 15.9068 19.7484 17.2615 19.6102 18.2892C19.475 19.2952 19.2213 19.8749 18.7981 20.2981L19.8588 21.3588C20.6071 20.6104 20.9392 19.6614 21.0969 18.489C21.2516 17.3382 21.25 15.8644 21.25 14H19.75ZM10.25 21.5V18.5H8.75V21.5H10.25ZM13.75 18.5V21.5H15.25V18.5H13.75ZM12 16.75C12.4811 16.75 12.7918 16.7507 13.0273 16.7721C13.2524 16.7925 13.3341 16.8269 13.375 16.8505L14.125 15.5514C13.8178 15.3741 13.4918 15.308 13.1627 15.2782C12.8438 15.2493 12.4535 15.25 12 15.25V16.75ZM15.25 18.5C15.25 18.0465 15.2507 17.6562 15.2218 17.3373C15.192 17.0082 15.1259 16.6822 14.9486 16.375L13.6495 17.125C13.6731 17.1659 13.7075 17.2476 13.7279 17.4727C13.7493 17.7082 13.75 18.0189 13.75 18.5H15.25ZM13.375 16.8505C13.489 16.9163 13.5837 17.011 13.6495 17.125L14.9486 16.375C14.7511 16.033 14.467 15.7489 14.125 15.5514L13.375 16.8505ZM10.25 18.5C10.25 18.0189 10.2507 17.7082 10.2721 17.4727C10.2925 17.2476 10.3269 17.1659 10.3505 17.125L9.05144 16.375C8.87407 16.6822 8.80802 17.0082 8.77818 17.3373C8.74928 17.6562 8.75 18.0465 8.75 18.5H10.25ZM12 15.25C11.5465 15.25 11.1562 15.2493 10.8373 15.2782C10.5082 15.308 10.1822 15.3741 9.875 15.5514L10.625 16.8505C10.6659 16.8269 10.7476 16.7925 10.9727 16.7721C11.2082 16.7507 11.5189 16.75 12 16.75V15.25ZM10.3505 17.125C10.4163 17.011 10.511 16.9163 10.625 16.8505L9.875 15.5514C9.53296 15.7489 9.24892 16.033 9.05144 16.375L10.3505 17.125Z" fill="currentColor" />
                            </svg>
                            สาขา
                        </a>
                    </li>
                </ul>
                <div x-show="activeTab === 0" role="tabpanel" id="panel-1" aria-labelledby="tab-1">
                    <div>
                        <form action="{{ route('setting.company.update') }}" method="post"
                            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="comid" value="{{ $company->id }}">
                            <input type="hidden" name="img" value="{{ $company->img }}">
                            <h6 class="text-lg font-bold mb-5">ข้อมูลทั่วไป</h6>
                            <div class="flex flex-col sm:flex-row">
                                <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
                                    <img src="{{ asset($company->img === null ? 'assets/images/image.png' : 'assets/images/companies/' . $company->img) }}"
                                        alt="image" class="w-20 h-20 md:w-32 md:h-32 object-cover mx-auto"
                                        id="preview" />
                                    <div
                                        class="upload-field-customized block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                                        <input type="file" name="image" accept="image/*" id="file_upload"
                                            onchange="previewImage(event)">
                                        <span class="btn-upload">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                                </path>
                                                <path opacity="0.5"
                                                    d="M2 12.5001L3.75159 10.9675C4.66286 10.1702 6.03628 10.2159 6.89249 11.0721L11.1822 15.3618C11.8694 16.0491 12.9512 16.1428 13.7464 15.5839L14.0446 15.3744C15.1888 14.5702 16.7369 14.6634 17.7765 15.599L21 18.5001"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                                </path>
                                                <path
                                                    d="M18.562 2.9354L18.9791 2.5183C19.6702 1.82723 20.7906 1.82723 21.4817 2.5183C22.1728 3.20937 22.1728 4.32981 21.4817 5.02087L21.0646 5.43797M18.562 2.9354C18.562 2.9354 18.6142 3.82172 19.3962 4.60378C20.1783 5.38583 21.0646 5.43797 21.0646 5.43797M18.562 2.9354L14.7275 6.76995C14.4677 7.02968 14.3379 7.15954 14.2262 7.30273C14.0945 7.47163 13.9815 7.65439 13.8894 7.84776C13.8112 8.01169 13.7532 8.18591 13.637 8.53437L13.2651 9.65M21.0646 5.43797L17.23 9.27253C16.9703 9.53225 16.8405 9.66211 16.6973 9.7738C16.5284 9.90554 16.3456 10.0185 16.1522 10.1106C15.9883 10.1888 15.8141 10.2468 15.4656 10.363L14.35 10.7349M14.35 10.7349L13.6281 10.9755C13.4567 11.0327 13.2676 10.988 13.1398 10.8602C13.012 10.7324 12.9673 10.5433 13.0245 10.3719L13.2651 9.65M14.35 10.7349L13.2651 9.65"
                                                    stroke="currentColor" stroke-width="1.5"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-5 flex-1 grid">
                                    <div>
                                        <label for="name">ชื่อ</label>
                                        <div class="relative text-white-dark @error('name')has-error @enderror">
                                            <input id="name" type="text" placeholder="ป้อนชื่อบริษัท"
                                                name="name" value="{{ old('name', $lang->name) }}" class="form-input" />
                                        </div>
                                        @error('name')
                                            <div>
                                                <p class="text-danger mt-1">{!! $errors->first('name') !!}</p>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="tel">โทรศัพท์</label>
                                            <input id="tel" type="text" name="tel" value="{{ old('tel', $company->tel) }}" placeholder="ป้อนเบอร์โทรศัพท์"
                                                class="form-input">
                                        </div>
                                        <div>
                                            <label for="phone">มือถือ</label>
                                            <input id="phone" type="text" name="mobile" value="{{ old('mobile', $company->mobile) }}" placeholder="ป้อนมือถือ"
                                                class="form-input">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="email">อีเมล</label>
                                            <div class="relative text-white-dark @error('email')has-error @enderror">
                                                <input id="email" type="text" placeholder="ป้อนอีเมล" value="{{ old('email', $company->email) }}" name="email" class="form-input" />
                                            </div>
                                            @error('email')
                                                <div>
                                                    <p class="text-danger mt-1">{!! $errors->first('email') !!}</p>
                                                </div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="website">เว็บไซต์</label>
                                            <input id="website" type="url" name="website" value="{{ old('website', $company->website) }}" placeholder="ป้อนเว็บไซต์" class="form-input">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="tel">เลขประจำตัวผู้เสียภาษี</label>
                                            <input id="tel" type="text" name="tax_id" value="{{ old('tax_id', $company->tax_id) }}" placeholder="ป้อนเลขประจำตัวผู้เสียภาษี"
                                                class="form-input">
                                        </div>
                                        <div>
                                            <label for="phone">ID บริษัท</label>
                                            <input id="phone" type="text" name="company_id" value="{{ old('company_id', $company->company_id) }}" placeholder="ป้อน ID บริษัท" class="form-input">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="gridAddress2">ที่อยู่</label>
                                        <input id="gridAddress2" type="text" name="address" value="{{ old('address', $lang->address) }}" placeholder="ป้อนที่อยู่" class="form-input">
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4">
                                        <div class="md:col-span-2">
                                            <label for="district">อำเภอ / เขต</label>
                                            <input id="district" type="text" name="district" value="{{ old('district', $lang->district) }}" placeholder="ป้อนอำเภอ / เขต" class="form-input">
                                        </div>
                                        <div>
                                            <label for="province">จังหวัด</label>
                                            <select name="province" id="seachable-select">
                                                <option value="">กรุณาเลือก</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->name }}"{{ $lang->province == $province->name ? ' selected' : '' }}>
                                                        {{ $province->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="gridZip">รหัสไปรษณีย์</label>
                                            <input id="gridZip" type="text" name="zip" value="{{ old('zip', $lang->zip) }}" placeholder="ป้อนรหัสไปรษณีย์"
                                                class="form-input">
                                        </div>
                                    </div>
                                    <div class="hidden lg:flex mt-1 gap-4">
                                        <a href="javascript:history.back()" class="btn btn-outline-danger">ยกเลิก</a>
                                        <button class="btn btn-primary" type="submit" x-data="{loading:false}" x-on:click="loading = true; setTimeout(() => loading = false, 4000)" x-html="loading ? `<span class='animate-spin border-2 border-white border-l-transparent rounded-full w-5 h-5 ltr:mr-4 rtl:ml-4 inline-block align-middle'></span>Loading` : 'บันทึก'">
                                            บันทึก
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div x-show="activeTab === 1" role="tabpanel" id="panel-2" aria-labelledby="tab-2">
                    csacascascasc
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date1: '{{ date('d-m-Y') }}',
                init() {
                    flatpickr(document.getElementById('basic'), {
                        dateFormat: 'd-m-Y',
                        defaultDate: this.date1,
                    })
                }
            }));
        });
    </script>
@endsection
