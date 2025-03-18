@extends('layouts.layout')
@section('title', __('companies.add_company'))
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
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">{{ __('companies.general_info') }}</div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <ul class="flex text-gray-500 dark:text-white-dark">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0">
                                <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ route('setting.company') }}">{{ __('settings.companies') }}</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">{{ __('companies.add_company') }}</a>
                    </li>
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
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            {{ __('companies.general_info') }}
                        </a>
                    </li>
                    {{-- <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                            :class="{ '!border-primary text-primary': tab == 'preferences' }" @click="tab='preferences'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path
                                    d="M9.50004 2H14.5L15.1518 8.51737C15.3382 10.382 13.874 12 12 12C10.1261 12 8.66184 10.382 8.8483 8.51737L9.50004 2Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path
                                    d="M3.32975 5.35133C3.50783 4.46093 3.59687 4.01573 3.77791 3.65484C4.15938 2.89439 4.84579 2.33168 5.66628 2.10675C6.05567 2 6.50969 2 7.41771 2H9.50002L8.77549 9.24527C8.61911 10.8091 7.30318 12 5.73155 12C3.8011 12 2.35324 10.2339 2.73183 8.34093L3.32975 5.35133Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path
                                    d="M20.6703 5.35133C20.4922 4.46093 20.4031 4.01573 20.2221 3.65484C19.8406 2.89439 19.1542 2.33168 18.3337 2.10675C17.9443 2 17.4903 2 16.5823 2H14.5L15.2245 9.24527C15.3809 10.8091 16.6968 12 18.2685 12C20.1989 12 21.6468 10.2339 21.2682 8.34093L20.6703 5.35133Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M8.75 21.5C8.75 21.9142 9.08579 22.25 9.5 22.25C9.91421 22.25 10.25 21.9142 10.25 21.5H8.75ZM13.75 21.5C13.75 21.9142 14.0858 22.25 14.5 22.25C14.9142 22.25 15.25 21.9142 15.25 21.5H13.75ZM13.75 16.201L14.125 15.5514L13.75 16.201ZM14.299 16.75L13.6495 17.125V17.125L14.299 16.75ZM9.70096 16.75L10.3505 17.125L9.70096 16.75ZM10.25 16.201L10.625 16.8505L10.25 16.201ZM12.5 21.25H11.5V22.75H12.5V21.25ZM4.25 14V11H2.75V14H4.25ZM19.75 11V14H21.25V11H19.75ZM11.5 21.25C9.59318 21.25 8.23851 21.2484 7.21085 21.1102C6.20476 20.975 5.62511 20.7213 5.2019 20.2981L4.14124 21.3588C4.88961 22.1071 5.83855 22.4392 7.01098 22.5969C8.16182 22.7516 9.63558 22.75 11.5 22.75V21.25ZM2.75 14C2.75 15.8644 2.74841 17.3382 2.90313 18.489C3.06076 19.6614 3.39288 20.6104 4.14124 21.3588L5.2019 20.2981C4.77869 19.8749 4.52502 19.2952 4.38976 18.2892C4.25159 17.2615 4.25 15.9068 4.25 14H2.75ZM12.5 22.75C14.3644 22.75 15.8382 22.7516 16.989 22.5969C18.1614 22.4392 19.1104 22.1071 19.8588 21.3588L18.7981 20.2981C18.3749 20.7213 17.7952 20.975 16.7892 21.1102C15.7615 21.2484 14.4068 21.25 12.5 21.25V22.75ZM19.75 14C19.75 15.9068 19.7484 17.2615 19.6102 18.2892C19.475 19.2952 19.2213 19.8749 18.7981 20.2981L19.8588 21.3588C20.6071 20.6104 20.9392 19.6614 21.0969 18.489C21.2516 17.3382 21.25 15.8644 21.25 14H19.75ZM10.25 21.5V18.5H8.75V21.5H10.25ZM13.75 18.5V21.5H15.25V18.5H13.75ZM12 16.75C12.4811 16.75 12.7918 16.7507 13.0273 16.7721C13.2524 16.7925 13.3341 16.8269 13.375 16.8505L14.125 15.5514C13.8178 15.3741 13.4918 15.308 13.1627 15.2782C12.8438 15.2493 12.4535 15.25 12 15.25V16.75ZM15.25 18.5C15.25 18.0465 15.2507 17.6562 15.2218 17.3373C15.192 17.0082 15.1259 16.6822 14.9486 16.375L13.6495 17.125C13.6731 17.1659 13.7075 17.2476 13.7279 17.4727C13.7493 17.7082 13.75 18.0189 13.75 18.5H15.25ZM13.375 16.8505C13.489 16.9163 13.5837 17.011 13.6495 17.125L14.9486 16.375C14.7511 16.033 14.467 15.7489 14.125 15.5514L13.375 16.8505ZM10.25 18.5C10.25 18.0189 10.2507 17.7082 10.2721 17.4727C10.2925 17.2476 10.3269 17.1659 10.3505 17.125L9.05144 16.375C8.87407 16.6822 8.80802 17.0082 8.77818 17.3373C8.74928 17.6562 8.75 18.0465 8.75 18.5H10.25ZM12 15.25C11.5465 15.25 11.1562 15.2493 10.8373 15.2782C10.5082 15.308 10.1822 15.3741 9.875 15.5514L10.625 16.8505C10.6659 16.8269 10.7476 16.7925 10.9727 16.7721C11.2082 16.7507 11.5189 16.75 12 16.75V15.25ZM10.3505 17.125C10.4163 17.011 10.511 16.9163 10.625 16.8505L9.875 15.5514C9.53296 15.7489 9.24892 16.033 9.05144 16.375L10.3505 17.125Z"
                                    fill="currentColor" />
                            </svg>
                            สาขา
                        </a>
                    </li> --}}
                </ul>
                <template x-if="tab === 'home'">
                    <div>
                        <form action="{{ route('setting.company.store') }}" method="post" class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]" enctype="multipart/form-data">
                            @csrf
                            <h6 class="text-lg font-bold mb-5">{{ __('companies.general_info') }}</h6>
                            <div class="flex flex-col sm:flex-row">
                                <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
                                    <img src="{{ asset('assets/images/image.png') }}" alt="image" class="w-20 h-20 md:w-32 md:h-32 object-cover mx-auto" id="preview" />
                                    <div class="upload-field-customized block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
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
                                <div class="space-y-5 flex-1 grid">
                                    <div>
                                        <label for="name">{{ __('companies.name') }}</label>
                                        <div class="relative text-white-dark @error('name')has-error @enderror">
                                            <input id="name" type="text" placeholder="{{ __('companies.input.enter_company_name') }}" name="name" value="{{ old('name') }}" class="form-input" />
                                        </div>
                                        @error('name')
                                            <div>
                                                <p class="text-danger mt-1">{!! $errors->first('name') !!}</p>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="tel">{{ __('companies.phone') }}</label>
                                            <input id="tel" value="{{ old('tel') }}" type="text" name="tel" placeholder="{{ __('companies.input.enter_phone_number') }}" class="form-input">
                                        </div>
                                        <div>
                                            <label for="phone">{{ __('companies.mobile') }}</label>
                                            <input id="phone" value="{{ old('mobile') }}" type="text" name="mobile" placeholder="{{ __('companies.input.enter_mobile_phone') }}" class="form-input">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="email">{{ __('companies.email') }}</label>
                                            <div class="relative text-white-dark @error('email')has-error @enderror">
                                                <input id="email" type="text" value="{{ old('email') }}" placeholder="{{ __('companies.input.enter_email') }}" name="email" class="form-input" />
                                            </div>
                                            @error('email')
                                                <div>
                                                    <p class="text-danger mt-1">{!! $errors->first('email') !!}</p>
                                                </div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="website">{{ __('companies.website') }}</label>
                                            <input id="website" type="url" value="{{ old('website') }}" name="website" placeholder="{{ __('companies.input.enter_website') }}" class="form-input">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4">
                                        <div class="md:col-span-2">
                                            <label for="tel">{{ __('companies.tax_id_number') }}</label>
                                            <input id="tel" type="text" value="{{ old('tax_id') }}" name="tax_id" placeholder="{{ __('companies.input.enter_tax_number') }}" class="form-input">
                                        </div>
                                        <div>
                                            <label for="phone">{{ __('companies.company_id') }}</label>
                                            <input id="phone" type="text" value="{{ old('company_id') }}" name="company_id" placeholder="{{ __('companies.input.enter_company_id') }}" class="form-input">
                                        </div>
                                        <div>
                                            <label for="currency">{{ __('companies.currency.currency') }}</label>
                                            <select name="currency" class="form-select">
                                                <option value="">{{ __('companies.input.please_select') }}</option>
                                                <option value="USD" {{ old('currency') == 'USD' ? ' selected' : '' }}>{{ __('companies.currency.USD') }}</option>
                                                <option value="EUR" {{ old('currency') == 'EUR' ? ' selected' : '' }}>{{ __('companies.currency.EUR') }}</option>
                                                <option value="JPY" {{ old('currency') == 'JPY' ? ' selected' : '' }}>{{ __('companies.currency.JPY') }}</option>
                                                <option value="GBP" {{ old('currency') == 'GBP' ? ' selected' : '' }}>{{ __('companies.currency.GBP') }}</option>
                                                <option value="AUD" {{ old('currency') == 'AUD' ? ' selected' : '' }}>{{ __('companies.currency.AUD') }}</option>
                                                <option value="CAD" {{ old('currency') == 'CAD' ? ' selected' : '' }}>{{ __('companies.currency.CAD') }}</option>
                                                <option value="CHF" {{ old('currency') == 'CHF' ? ' selected' : '' }}>{{ __('companies.currency.CHF') }}</option>
                                                <option value="CNY" {{ old('currency') == 'CNY' ? ' selected' : '' }}>{{ __('companies.currency.CNY') }}</option>
                                                <option value="SEK" {{ old('currency') == 'SEK' ? ' selected' : '' }}>{{ __('companies.currency.SEK') }}</option>
                                                <option value="NZD" {{ old('currency') == 'NZD' ? ' selected' : '' }}>{{ __('companies.currency.NZD') }}</option>
                                                <option value="MXN" {{ old('currency') == 'MXN' ? ' selected' : '' }}>{{ __('companies.currency.MXN') }}</option>
                                                <option value="SGD" {{ old('currency') == 'SGD' ? ' selected' : '' }}>{{ __('companies.currency.SGD') }}</option>
                                                <option value="HKD" {{ old('currency') == 'HKD' ? ' selected' : '' }}>{{ __('companies.currency.HKD') }}</option>
                                                <option value="NOK" {{ old('currency') == 'NOK' ? ' selected' : '' }}>{{ __('companies.currency.NOK') }}</option>
                                                <option value="KRW" {{ old('currency') == 'KRW' ? ' selected' : '' }}>{{ __('companies.currency.KRW') }}</option>
                                                <option value="TRY" {{ old('currency') == 'TRY' ? ' selected' : '' }}>{{ __('companies.currency.TRY') }}</option>
                                                <option value="RUB" {{ old('currency') == 'RUB' ? ' selected' : '' }}>{{ __('companies.currency.RUB') }}</option>
                                                <option value="INR" {{ old('currency') == 'INR' ? ' selected' : '' }}>{{ __('companies.currency.INR') }}</option>
                                                <option value="BRL" {{ old('currency') == 'BRL' ? ' selected' : '' }}>{{ __('companies.currency.BRL') }}</option>
                                                <option value="ZAR" {{ old('currency') == 'ZAR' ? ' selected' : '' }}>{{ __('companies.currency.ZAR') }}</option>
                                                <option value="PHP" {{ old('currency') == 'PHP' ? ' selected' : '' }}>{{ __('companies.currency.PHP') }}</option>
                                                <option value="IDR" {{ old('currency') == 'IDR' ? ' selected' : '' }}>{{ __('companies.currency.IDR') }}</option>
                                                <option value="THB" {{ old('currency') == 'THB' ? ' selected' : '' }}>{{ __('companies.currency.THB') }}</option>
                                                <option value="MYR" {{ old('currency') == 'MYR' ? ' selected' : '' }}>{{ __('companies.currency.MYR') }}</option>
                                                <option value="VND" {{ old('currency') == 'VND' ? ' selected' : '' }}>{{ __('companies.currency.VND') }}</option>
                                                <option value="AED" {{ old('currency') == 'AED' ? ' selected' : '' }}>{{ __('companies.currency.AED') }}</option>
                                                <option value="SAR" {{ old('currency') == 'SAR' ? ' selected' : '' }}>{{ __('companies.currency.SAR') }}</option>
                                                <option value="PLN" {{ old('currency') == 'PLN' ? ' selected' : '' }}>{{ __('companies.currency.PLN') }}</option>
                                                <option value="CZK" {{ old('currency') == 'CZK' ? ' selected' : '' }}>{{ __('companies.currency.CZK') }}</option>
                                                <option value="HUF" {{ old('currency') == 'HUF' ? ' selected' : '' }}>{{ __('companies.currency.HUF') }}</option>
                                                <option value="DKK" {{ old('currency') == 'DKK' ? ' selected' : '' }}>{{ __('companies.currency.DKK') }}</option>
                                                <option value="ILS" {{ old('currency') == 'ILS' ? ' selected' : '' }}>{{ __('companies.currency.ILS') }}</option>
                                                <option value="CLP" {{ old('currency') == 'CLP' ? ' selected' : '' }}>{{ __('companies.currency.CLP') }}</option>
                                                <option value="COP" {{ old('currency') == 'COP' ? ' selected' : '' }}>{{ __('companies.currency.COP') }}</option>
                                                <option value="ARS" {{ old('currency') == 'ARS' ? ' selected' : '' }}>{{ __('companies.currency.ARS') }}</option>
                                                <option value="EGP" {{ old('currency') == 'EGP' ? ' selected' : '' }}>{{ __('companies.currency.EGP') }}</option>
                                                <option value="PKR" {{ old('currency') == 'PKR' ? ' selected' : '' }}>{{ __('companies.currency.PKR') }}</option>
                                                <option value="BDT" {{ old('currency') == 'BDT' ? ' selected' : '' }}>{{ __('companies.currency.BDT') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="gridAddress">{{ __('companies.address.address') }}</label>
                                        <textarea id="gridAddress" class="form-textarea" name="address" placeholder="{{ __('companies.input.enter_address') }}" style="height: 75px;">{{ old('address') }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4">
                                        <div>
                                            <label for="city">{{ __('companies.address.city') }}</label>
                                            <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="{{ __('companies.input.enter_city') }}" class="form-input">
                                        </div>
                                        <div>
                                            <label for="province">{{ __('companies.address.province') }}</label>
                                            <input id="province" type="text" name="province" value="{{ old('province') }}" placeholder="{{ __('companies.input.enter_province') }}" class="form-input">
                                        </div>
                                        <div>
                                            <label for="seachable-select">{{ __('companies.address.country') }}</label>
                                            <select name="country" id="seachable-select">
                                                <option value="">{{ __('companies.input.please_select') }}</option>
                                                <option value="AF" {{ old('country') == 'AF' ? ' selected' : '' }}>{{ __('countries.AF') }}</option>
                                                <option value="AX" {{ old('country') == 'AX' ? ' selected' : '' }}>{{ __('countries.AX') }}</option>
                                                <option value="AL" {{ old('country') == 'AL' ? ' selected' : '' }}>{{ __('countries.AL') }}</option>
                                                <option value="DZ" {{ old('country') == 'DZ' ? ' selected' : '' }}>{{ __('countries.DZ') }}</option>
                                                <option value="AS" {{ old('country') == 'AS' ? ' selected' : '' }}>{{ __('countries.AS') }}</option>
                                                <option value="AD" {{ old('country') == 'AD' ? ' selected' : '' }}>{{ __('countries.AD') }}</option>
                                                <option value="AO" {{ old('country') == 'AO' ? ' selected' : '' }}>{{ __('countries.AO') }}</option>
                                                <option value="AI" {{ old('country') == 'AI' ? ' selected' : '' }}>{{ __('countries.AI') }}</option>
                                                <option value="AQ" {{ old('country') == 'AQ' ? ' selected' : '' }}>{{ __('countries.AQ') }}</option>
                                                <option value="AG" {{ old('country') == 'AG' ? ' selected' : '' }}>{{ __('countries.AG') }}</option>
                                                <option value="AR" {{ old('country') == 'AR' ? ' selected' : '' }}>{{ __('countries.AR') }}</option>
                                                <option value="AM" {{ old('country') == 'AM' ? ' selected' : '' }}>{{ __('countries.AM') }}</option>
                                                <option value="AW" {{ old('country') == 'AW' ? ' selected' : '' }}>{{ __('countries.AW') }}</option>
                                                <option value="AU" {{ old('country') == 'AU' ? ' selected' : '' }}>{{ __('countries.AU') }}</option>
                                                <option value="AT" {{ old('country') == 'AT' ? ' selected' : '' }}>{{ __('countries.AT') }}</option>
                                                <option value="AZ" {{ old('country') == 'AZ' ? ' selected' : '' }}>{{ __('countries.AZ') }}</option>
                                                <option value="BS" {{ old('country') == 'BS' ? ' selected' : '' }}>{{ __('countries.BS') }}</option>
                                                <option value="BH" {{ old('country') == 'BH' ? ' selected' : '' }}>{{ __('countries.BH') }}</option>
                                                <option value="BD" {{ old('country') == 'BD' ? ' selected' : '' }}>{{ __('countries.BD') }}</option>
                                                <option value="BB" {{ old('country') == 'BB' ? ' selected' : '' }}>{{ __('countries.BB') }}</option>
                                                <option value="BY" {{ old('country') == 'BY' ? ' selected' : '' }}>{{ __('countries.BY') }}</option>
                                                <option value="BE" {{ old('country') == 'BE' ? ' selected' : '' }}>{{ __('countries.BE') }}</option>
                                                <option value="BZ" {{ old('country') == 'BZ' ? ' selected' : '' }}>{{ __('countries.BZ') }}</option>
                                                <option value="BJ" {{ old('country') == 'BJ' ? ' selected' : '' }}>{{ __('countries.BJ') }}</option>
                                                <option value="BM" {{ old('country') == 'BM' ? ' selected' : '' }}>{{ __('countries.BM') }}</option>
                                                <option value="BT" {{ old('country') == 'BT' ? ' selected' : '' }}>{{ __('countries.BT') }}</option>
                                                <option value="BO" {{ old('country') == 'BO' ? ' selected' : '' }}>{{ __('countries.BO') }}</option>
                                                <option value="BQ" {{ old('country') == 'BQ' ? ' selected' : '' }}>{{ __('countries.BQ') }}</option>
                                                <option value="BA" {{ old('country') == 'BA' ? ' selected' : '' }}>{{ __('countries.BA') }}</option>
                                                <option value="BW" {{ old('country') == 'BW' ? ' selected' : '' }}>{{ __('countries.BW') }}</option>
                                                <option value="BV" {{ old('country') == 'BV' ? ' selected' : '' }}>{{ __('countries.BV') }}</option>
                                                <option value="BR" {{ old('country') == 'BR' ? ' selected' : '' }}>{{ __('countries.BR') }}</option>
                                                <option value="IO" {{ old('country') == 'IO' ? ' selected' : '' }}>{{ __('countries.IO') }}</option>
                                                <option value="BN" {{ old('country') == 'BN' ? ' selected' : '' }}>{{ __('countries.BN') }}</option>
                                                <option value="BG" {{ old('country') == 'BG' ? ' selected' : '' }}>{{ __('countries.BG') }}</option>
                                                <option value="BF" {{ old('country') == 'BF' ? ' selected' : '' }}>{{ __('countries.BF') }}</option>
                                                <option value="BI" {{ old('country') == 'BI' ? ' selected' : '' }}>{{ __('countries.BI') }}</option>
                                                <option value="CV" {{ old('country') == 'CV' ? ' selected' : '' }}>{{ __('countries.CV') }}</option>
                                                <option value="KH" {{ old('country') == 'KH' ? ' selected' : '' }}>{{ __('countries.KH') }}</option>
                                                <option value="CM" {{ old('country') == 'CM' ? ' selected' : '' }}>{{ __('countries.CM') }}</option>
                                                <option value="CA" {{ old('country') == 'CA' ? ' selected' : '' }}>{{ __('countries.CA') }}</option>
                                                <option value="KY" {{ old('country') == 'KY' ? ' selected' : '' }}>{{ __('countries.KY') }}</option>
                                                <option value="CF" {{ old('country') == 'CF' ? ' selected' : '' }}>{{ __('countries.CF') }}</option>
                                                <option value="TD" {{ old('country') == 'TD' ? ' selected' : '' }}>{{ __('countries.TD') }}</option>
                                                <option value="CL" {{ old('country') == 'CL' ? ' selected' : '' }}>{{ __('countries.CL') }}</option>
                                                <option value="CN" {{ old('country') == 'CN' ? ' selected' : '' }}>{{ __('countries.CN') }}</option>
                                                <option value="CX" {{ old('country') == 'CX' ? ' selected' : '' }}>{{ __('countries.CX') }}</option>
                                                <option value="CC" {{ old('country') == 'CC' ? ' selected' : '' }}>{{ __('countries.CC') }}</option>
                                                <option value="CO" {{ old('country') == 'CO' ? ' selected' : '' }}>{{ __('countries.CO') }}</option>
                                                <option value="KM" {{ old('country') == 'KM' ? ' selected' : '' }}>{{ __('countries.KM') }}</option>
                                                <option value="CG" {{ old('country') == 'CG' ? ' selected' : '' }}>{{ __('countries.CG') }}</option>
                                                <option value="CD" {{ old('country') == 'CD' ? ' selected' : '' }}>{{ __('countries.CD') }}</option>
                                                <option value="CK" {{ old('country') == 'CK' ? ' selected' : '' }}>{{ __('countries.CK') }}</option>
                                                <option value="CR" {{ old('country') == 'CR' ? ' selected' : '' }}>{{ __('countries.CR') }}</option>
                                                <option value="CI" {{ old('country') == 'CI' ? ' selected' : '' }}>{{ __('countries.CI') }}</option>
                                                <option value="HR" {{ old('country') == 'HR' ? ' selected' : '' }}>{{ __('countries.HR') }}</option>
                                                <option value="CU" {{ old('country') == 'CU' ? ' selected' : '' }}>{{ __('countries.CU') }}</option>
                                                <option value="CY" {{ old('country') == 'CY' ? ' selected' : '' }}>{{ __('countries.CY') }}</option>
                                                <option value="CZ" {{ old('country') == 'CZ' ? ' selected' : '' }}>{{ __('countries.CZ') }}</option>
                                                <option value="DK" {{ old('country') == 'DK' ? ' selected' : '' }}>{{ __('countries.DK') }}</option>
                                                <option value="DJ" {{ old('country') == 'DJ' ? ' selected' : '' }}>{{ __('countries.DJ') }}</option>
                                                <option value="DM" {{ old('country') == 'DM' ? ' selected' : '' }}>{{ __('countries.DM') }}</option>
                                                <option value="DO" {{ old('country') == 'DO' ? ' selected' : '' }}>{{ __('countries.DO') }}</option>
                                                <option value="EC" {{ old('country') == 'EC' ? ' selected' : '' }}>{{ __('countries.EC') }}</option>
                                                <option value="EG" {{ old('country') == 'EG' ? ' selected' : '' }}>{{ __('countries.EG') }}</option>
                                                <option value="SV" {{ old('country') == 'SV' ? ' selected' : '' }}>{{ __('countries.SV') }}</option>
                                                <option value="GQ" {{ old('country') == 'GQ' ? ' selected' : '' }}>{{ __('countries.GQ') }}</option>
                                                <option value="ER" {{ old('country') == 'ER' ? ' selected' : '' }}>{{ __('countries.ER') }}</option>
                                                <option value="EE" {{ old('country') == 'EE' ? ' selected' : '' }}>{{ __('countries.EE') }}</option>
                                                <option value="SZ" {{ old('country') == 'SZ' ? ' selected' : '' }}>{{ __('countries.SZ') }}</option>
                                                <option value="ET" {{ old('country') == 'ET' ? ' selected' : '' }}>{{ __('countries.ET') }}</option>
                                                <option value="FK" {{ old('country') == 'FK' ? ' selected' : '' }}>{{ __('countries.FK') }}</option>
                                                <option value="FO" {{ old('country') == 'FO' ? ' selected' : '' }}>{{ __('countries.FO') }}</option>
                                                <option value="FJ" {{ old('country') == 'FJ' ? ' selected' : '' }}>{{ __('countries.FJ') }}</option>
                                                <option value="FI" {{ old('country') == 'FI' ? ' selected' : '' }}>{{ __('countries.FI') }}</option>
                                                <option value="FR" {{ old('country') == 'FR' ? ' selected' : '' }}>{{ __('countries.FR') }}</option>
                                                <option value="GF" {{ old('country') == 'GF' ? ' selected' : '' }}>{{ __('countries.GF') }}</option>
                                                <option value="PF" {{ old('country') == 'PF' ? ' selected' : '' }}>{{ __('countries.PF') }}</option>
                                                <option value="TF" {{ old('country') == 'TF' ? ' selected' : '' }}>{{ __('countries.TF') }}</option>
                                                <option value="GA" {{ old('country') == 'GA' ? ' selected' : '' }}>{{ __('countries.GA') }}</option>
                                                <option value="GM" {{ old('country') == 'GM' ? ' selected' : '' }}>{{ __('countries.GM') }}</option>
                                                <option value="GE" {{ old('country') == 'GE' ? ' selected' : '' }}>{{ __('countries.GE') }}</option>
                                                <option value="DE" {{ old('country') == 'DE' ? ' selected' : '' }}>{{ __('countries.DE') }}</option>
                                                <option value="GH" {{ old('country') == 'GH' ? ' selected' : '' }}>{{ __('countries.GH') }}</option>
                                                <option value="GI" {{ old('country') == 'GI' ? ' selected' : '' }}>{{ __('countries.GI') }}</option>
                                                <option value="GR" {{ old('country') == 'GR' ? ' selected' : '' }}>{{ __('countries.GR') }}</option>
                                                <option value="GL" {{ old('country') == 'GL' ? ' selected' : '' }}>{{ __('countries.GL') }}</option>
                                                <option value="GD" {{ old('country') == 'GD' ? ' selected' : '' }}>{{ __('countries.GD') }}</option>
                                                <option value="GP" {{ old('country') == 'GP' ? ' selected' : '' }}>{{ __('countries.GP') }}</option>
                                                <option value="GU" {{ old('country') == 'GU' ? ' selected' : '' }}>{{ __('countries.GU') }}</option>
                                                <option value="GT" {{ old('country') == 'GT' ? ' selected' : '' }}>{{ __('countries.GT') }}</option>
                                                <option value="GG" {{ old('country') == 'GG' ? ' selected' : '' }}>{{ __('countries.GG') }}</option>
                                                <option value="GN" {{ old('country') == 'GN' ? ' selected' : '' }}>{{ __('countries.GN') }}</option>
                                                <option value="GW" {{ old('country') == 'GW' ? ' selected' : '' }}>{{ __('countries.GW') }}</option>
                                                <option value="GY" {{ old('country') == 'GY' ? ' selected' : '' }}>{{ __('countries.GY') }}</option>
                                                <option value="HT" {{ old('country') == 'HT' ? ' selected' : '' }}>{{ __('countries.HT') }}</option>
                                                <option value="HM" {{ old('country') == 'HM' ? ' selected' : '' }}>{{ __('countries.HM') }}</option>
                                                <option value="VA" {{ old('country') == 'VA' ? ' selected' : '' }}>{{ __('countries.VA') }}</option>
                                                <option value="HN" {{ old('country') == 'HN' ? ' selected' : '' }}>{{ __('countries.HN') }}</option>
                                                <option value="HK" {{ old('country') == 'HK' ? ' selected' : '' }}>{{ __('countries.HK') }}</option>
                                                <option value="HU" {{ old('country') == 'HU' ? ' selected' : '' }}>{{ __('countries.HU') }}</option>
                                                <option value="IS" {{ old('country') == 'IS' ? ' selected' : '' }}>{{ __('countries.IS') }}</option>
                                                <option value="IN" {{ old('country') == 'IN' ? ' selected' : '' }}>{{ __('countries.IN') }}</option>
                                                <option value="ID" {{ old('country') == 'ID' ? ' selected' : '' }}>{{ __('countries.ID') }}</option>
                                                <option value="IR" {{ old('country') == 'IR' ? ' selected' : '' }}>{{ __('countries.IR') }}</option>
                                                <option value="IQ" {{ old('country') == 'IQ' ? ' selected' : '' }}>{{ __('countries.IQ') }}</option>
                                                <option value="IE" {{ old('country') == 'IE' ? ' selected' : '' }}>{{ __('countries.IE') }}</option>
                                                <option value="IM" {{ old('country') == 'IM' ? ' selected' : '' }}>{{ __('countries.IM') }}</option>
                                                <option value="IL" {{ old('country') == 'IL' ? ' selected' : '' }}>{{ __('countries.IL') }}</option>
                                                <option value="IT" {{ old('country') == 'IT' ? ' selected' : '' }}>{{ __('countries.IT') }}</option>
                                                <option value="CI" {{ old('country') == 'CI' ? ' selected' : '' }}>{{ __('countries.CI') }}</option>
                                                <option value="JM" {{ old('country') == 'JM' ? ' selected' : '' }}>{{ __('countries.JM') }}</option>
                                                <option value="JP" {{ old('country') == 'JP' ? ' selected' : '' }}>{{ __('countries.JP') }}</option>
                                                <option value="JE" {{ old('country') == 'JE' ? ' selected' : '' }}>{{ __('countries.JE') }}</option>
                                                <option value="JO" {{ old('country') == 'JO' ? ' selected' : '' }}>{{ __('countries.JO') }}</option>
                                                <option value="KZ" {{ old('country') == 'KZ' ? ' selected' : '' }}>{{ __('countries.KZ') }}</option>
                                                <option value="KE" {{ old('country') == 'KE' ? ' selected' : '' }}>{{ __('countries.KE') }}</option>
                                                <option value="KI" {{ old('country') == 'KI' ? ' selected' : '' }}>{{ __('countries.KI') }}</option>
                                                <option value="KP" {{ old('country') == 'KP' ? ' selected' : '' }}>{{ __('countries.KP') }}</option>
                                                <option value="KR" {{ old('country') == 'KR' ? ' selected' : '' }}>{{ __('countries.KR') }}</option>
                                                <option value="KW" {{ old('country') == 'KW' ? ' selected' : '' }}>{{ __('countries.KW') }}</option>
                                                <option value="KG" {{ old('country') == 'KG' ? ' selected' : '' }}>{{ __('countries.KG') }}</option>
                                                <option value="LA" {{ old('country') == 'LA' ? ' selected' : '' }}>{{ __('countries.LA') }}</option>
                                                <option value="LV" {{ old('country') == 'LV' ? ' selected' : '' }}>{{ __('countries.LV') }}</option>
                                                <option value="LB" {{ old('country') == 'LB' ? ' selected' : '' }}>{{ __('countries.LB') }}</option>
                                                <option value="LS" {{ old('country') == 'LS' ? ' selected' : '' }}>{{ __('countries.LS') }}</option>
                                                <option value="LR" {{ old('country') == 'LR' ? ' selected' : '' }}>{{ __('countries.LR') }}</option>
                                                <option value="LY" {{ old('country') == 'LY' ? ' selected' : '' }}>{{ __('countries.LY') }}</option>
                                                <option value="LI" {{ old('country') == 'LI' ? ' selected' : '' }}>{{ __('countries.LI') }}</option>
                                                <option value="LT" {{ old('country') == 'LT' ? ' selected' : '' }}>{{ __('countries.LT') }}</option>
                                                <option value="LU" {{ old('country') == 'LU' ? ' selected' : '' }}>{{ __('countries.LU') }}</option>
                                                <option value="MO" {{ old('country') == 'MO' ? ' selected' : '' }}>{{ __('countries.MO') }}</option>
                                                <option value="MK" {{ old('country') == 'MK' ? ' selected' : '' }}>{{ __('countries.MK') }}</option>
                                                <option value="MG" {{ old('country') == 'MG' ? ' selected' : '' }}>{{ __('countries.MG') }}</option>
                                                <option value="MW" {{ old('country') == 'MW' ? ' selected' : '' }}>{{ __('countries.MW') }}</option>
                                                <option value="MY" {{ old('country') == 'MY' ? ' selected' : '' }}>{{ __('countries.MY') }}</option>
                                                <option value="MV" {{ old('country') == 'MV' ? ' selected' : '' }}>{{ __('countries.MV') }}</option>
                                                <option value="ML" {{ old('country') == 'ML' ? ' selected' : '' }}>{{ __('countries.ML') }}</option>
                                                <option value="MT" {{ old('country') == 'MT' ? ' selected' : '' }}>{{ __('countries.MT') }}</option>
                                                <option value="MH" {{ old('country') == 'MH' ? ' selected' : '' }}>{{ __('countries.MH') }}</option>
                                                <option value="MQ" {{ old('country') == 'MQ' ? ' selected' : '' }}>{{ __('countries.MQ') }}</option>
                                                <option value="MR" {{ old('country') == 'MR' ? ' selected' : '' }}>{{ __('countries.MR') }}</option>
                                                <option value="MU" {{ old('country') == 'MU' ? ' selected' : '' }}>{{ __('countries.MU') }}</option>
                                                <option value="YT" {{ old('country') == 'YT' ? ' selected' : '' }}>{{ __('countries.YT') }}</option>
                                                <option value="MX" {{ old('country') == 'MX' ? ' selected' : '' }}>{{ __('countries.MX') }}</option>
                                                <option value="FM" {{ old('country') == 'FM' ? ' selected' : '' }}>{{ __('countries.FM') }}</option>
                                                <option value="MD" {{ old('country') == 'MD' ? ' selected' : '' }}>{{ __('countries.MD') }}</option>
                                                <option value="MC" {{ old('country') == 'MC' ? ' selected' : '' }}>{{ __('countries.MC') }}</option>
                                                <option value="MN" {{ old('country') == 'MN' ? ' selected' : '' }}>{{ __('countries.MN') }}</option>
                                                <option value="ME" {{ old('country') == 'ME' ? ' selected' : '' }}>{{ __('countries.ME') }}</option>
                                                <option value="MS" {{ old('country') == 'MS' ? ' selected' : '' }}>{{ __('countries.MS') }}</option>
                                                <option value="MA" {{ old('country') == 'MA' ? ' selected' : '' }}>{{ __('countries.MA') }}</option>
                                                <option value="MZ" {{ old('country') == 'MZ' ? ' selected' : '' }}>{{ __('countries.MZ') }}</option>
                                                <option value="MM" {{ old('country') == 'MM' ? ' selected' : '' }}>{{ __('countries.MM') }}</option>
                                                <option value="NA" {{ old('country') == 'NA' ? ' selected' : '' }}>{{ __('countries.NA') }}</option>
                                                <option value="NR" {{ old('country') == 'NR' ? ' selected' : '' }}>{{ __('countries.NR') }}</option>
                                                <option value="NP" {{ old('country') == 'NP' ? ' selected' : '' }}>{{ __('countries.NP') }}</option>
                                                <option value="NL" {{ old('country') == 'NL' ? ' selected' : '' }}>{{ __('countries.NL') }}</option>
                                                <option value="AN" {{ old('country') == 'AN' ? ' selected' : '' }}>{{ __('countries.AN') }}</option>
                                                <option value="NC" {{ old('country') == 'NC' ? ' selected' : '' }}>{{ __('countries.NC') }}</option>
                                                <option value="NZ" {{ old('country') == 'NZ' ? ' selected' : '' }}>{{ __('countries.NZ') }}</option>
                                                <option value="NI" {{ old('country') == 'NI' ? ' selected' : '' }}>{{ __('countries.NI') }}</option>
                                                <option value="NE" {{ old('country') == 'NE' ? ' selected' : '' }}>{{ __('countries.NE') }}</option>
                                                <option value="NG" {{ old('country') == 'NG' ? ' selected' : '' }}>{{ __('countries.NG') }}</option>
                                                <option value="NU" {{ old('country') == 'NU' ? ' selected' : '' }}>{{ __('countries.NU') }}</option>
                                                <option value="NF" {{ old('country') == 'NF' ? ' selected' : '' }}>{{ __('countries.NF') }}</option>
                                                <option value="MP" {{ old('country') == 'MP' ? ' selected' : '' }}>{{ __('countries.MP') }}</option>
                                                <option value="NO" {{ old('country') == 'NO' ? ' selected' : '' }}>{{ __('countries.NO') }}</option>
                                                <option value="OM" {{ old('country') == 'OM' ? ' selected' : '' }}>{{ __('countries.OM') }}</option>
                                                <option value="PK" {{ old('country') == 'PK' ? ' selected' : '' }}>{{ __('countries.PK') }}</option>
                                                <option value="PW" {{ old('country') == 'PW' ? ' selected' : '' }}>{{ __('countries.PW') }}</option>
                                                <option value="PS" {{ old('country') == 'PS' ? ' selected' : '' }}>{{ __('countries.PS') }}</option>
                                                <option value="PA" {{ old('country') == 'PA' ? ' selected' : '' }}>{{ __('countries.PA') }}</option>
                                                <option value="PG" {{ old('country') == 'PG' ? ' selected' : '' }}>{{ __('countries.PG') }}</option>
                                                <option value="PY" {{ old('country') == 'PY' ? ' selected' : '' }}>{{ __('countries.PY') }}</option>
                                                <option value="PE" {{ old('country') == 'PE' ? ' selected' : '' }}>{{ __('countries.PE') }}</option>
                                                <option value="PH" {{ old('country') == 'PH' ? ' selected' : '' }}>{{ __('countries.PH') }}</option>
                                                <option value="PN" {{ old('country') == 'PN' ? ' selected' : '' }}>{{ __('countries.PN') }}</option>
                                                <option value="PL" {{ old('country') == 'PL' ? ' selected' : '' }}>{{ __('countries.PL') }}</option>
                                                <option value="PT" {{ old('country') == 'PT' ? ' selected' : '' }}>{{ __('countries.PT') }}</option>
                                                <option value="QA" {{ old('country') == 'QA' ? ' selected' : '' }}>{{ __('countries.QA') }}</option>
                                                <option value="RE" {{ old('country') == 'RE' ? ' selected' : '' }}>{{ __('countries.RE') }}</option>
                                                <option value="RO" {{ old('country') == 'RO' ? ' selected' : '' }}>{{ __('countries.RO') }}</option>
                                                <option value="RU" {{ old('country') == 'RU' ? ' selected' : '' }}>{{ __('countries.RU') }}</option>
                                                <option value="RW" {{ old('country') == 'RW' ? ' selected' : '' }}>{{ __('countries.RW') }}</option>
                                                <option value="BL" {{ old('country') == 'BL' ? ' selected' : '' }}>{{ __('countries.BL') }}</option>
                                                <option value="SH" {{ old('country') == 'SH' ? ' selected' : '' }}>{{ __('countries.SH') }}</option>
                                                <option value="KN" {{ old('country') == 'KN' ? ' selected' : '' }}>{{ __('countries.KN') }}</option>
                                                <option value="LC" {{ old('country') == 'LC' ? ' selected' : '' }}>{{ __('countries.LC') }}</option>
                                                <option value="MF" {{ old('country') == 'MF' ? ' selected' : '' }}>{{ __('countries.MF') }}</option>
                                                <option value="PM" {{ old('country') == 'PM' ? ' selected' : '' }}>{{ __('countries.PM') }}</option>
                                                <option value="VC" {{ old('country') == 'VC' ? ' selected' : '' }}>{{ __('countries.VC') }}</option>
                                                <option value="WS" {{ old('country') == 'WS' ? ' selected' : '' }}>{{ __('countries.WS') }}</option>
                                                <option value="SM" {{ old('country') == 'SM' ? ' selected' : '' }}>{{ __('countries.SM') }}</option>
                                                <option value="ST" {{ old('country') == 'ST' ? ' selected' : '' }}>{{ __('countries.ST') }}</option>
                                                <option value="SA" {{ old('country') == 'SA' ? ' selected' : '' }}>{{ __('countries.SA') }}</option>
                                                <option value="SN" {{ old('country') == 'SN' ? ' selected' : '' }}>{{ __('countries.SN') }}</option>
                                                <option value="RS" {{ old('country') == 'RS' ? ' selected' : '' }}>{{ __('countries.RS') }}</option>
                                                <option value="SC" {{ old('country') == 'SC' ? ' selected' : '' }}>{{ __('countries.SC') }}</option>
                                                <option value="SL" {{ old('country') == 'SL' ? ' selected' : '' }}>{{ __('countries.SL') }}</option>
                                                <option value="SG" {{ old('country') == 'SG' ? ' selected' : '' }}>{{ __('countries.SG') }}</option>
                                                <option value="SX" {{ old('country') == 'SX' ? ' selected' : '' }}>{{ __('countries.SX') }}</option>
                                                <option value="SK" {{ old('country') == 'SK' ? ' selected' : '' }}>{{ __('countries.SK') }}</option>
                                                <option value="SI" {{ old('country') == 'SI' ? ' selected' : '' }}>{{ __('countries.SI') }}</option>
                                                <option value="SB" {{ old('country') == 'SB' ? ' selected' : '' }}>{{ __('countries.SB') }}</option>
                                                <option value="SO" {{ old('country') == 'SO' ? ' selected' : '' }}>{{ __('countries.SO') }}</option>
                                                <option value="ZA" {{ old('country') == 'ZA' ? ' selected' : '' }}>{{ __('countries.ZA') }}</option>
                                                <option value="GS" {{ old('country') == 'GS' ? ' selected' : '' }}>{{ __('countries.GS') }}</option>
                                                <option value="SS" {{ old('country') == 'SS' ? ' selected' : '' }}>{{ __('countries.SS') }}</option>
                                                <option value="ES" {{ old('country') == 'ES' ? ' selected' : '' }}>{{ __('countries.ES') }}</option>
                                                <option value="LK" {{ old('country') == 'LK' ? ' selected' : '' }}>{{ __('countries.LK') }}</option>
                                                <option value="SD" {{ old('country') == 'SD' ? ' selected' : '' }}>{{ __('countries.SD') }}</option>
                                                <option value="SR" {{ old('country') == 'SR' ? ' selected' : '' }}>{{ __('countries.SR') }}</option>
                                                <option value="SJ" {{ old('country') == 'SJ' ? ' selected' : '' }}>{{ __('countries.SJ') }}</option>
                                                <option value="SZ" {{ old('country') == 'SZ' ? ' selected' : '' }}>{{ __('countries.SZ') }}</option>
                                                <option value="SE" {{ old('country') == 'SE' ? ' selected' : '' }}>{{ __('countries.SE') }}</option>
                                                <option value="CH" {{ old('country') == 'CH' ? ' selected' : '' }}>{{ __('countries.CH') }}</option>
                                                <option value="SY" {{ old('country') == 'SY' ? ' selected' : '' }}>{{ __('countries.SY') }}</option>
                                                <option value="TW" {{ old('country') == 'TW' ? ' selected' : '' }}>{{ __('countries.TW') }}</option>
                                                <option value="TJ" {{ old('country') == 'TJ' ? ' selected' : '' }}>{{ __('countries.TJ') }}</option>
                                                <option value="TZ" {{ old('country') == 'TZ' ? ' selected' : '' }}>{{ __('countries.TZ') }}</option>
                                                <option value="TH" {{ old('country') == 'TH' ? ' selected' : '' }}>{{ __('countries.TH') }}</option>
                                                <option value="TL" {{ old('country') == 'TL' ? ' selected' : '' }}>{{ __('countries.TL') }}</option>
                                                <option value="TG" {{ old('country') == 'TG' ? ' selected' : '' }}>{{ __('countries.TG') }}</option>
                                                <option value="TK" {{ old('country') == 'TK' ? ' selected' : '' }}>{{ __('countries.TK') }}</option>
                                                <option value="TO" {{ old('country') == 'TO' ? ' selected' : '' }}>{{ __('countries.TO') }}</option>
                                                <option value="TT" {{ old('country') == 'TT' ? ' selected' : '' }}>{{ __('countries.TT') }}</option>
                                                <option value="TN" {{ old('country') == 'TN' ? ' selected' : '' }}>{{ __('countries.TN') }}</option>
                                                <option value="TR" {{ old('country') == 'TR' ? ' selected' : '' }}>{{ __('countries.TR') }}</option>
                                                <option value="TM" {{ old('country') == 'TM' ? ' selected' : '' }}>{{ __('countries.TM') }}</option>
                                                <option value="TC" {{ old('country') == 'TC' ? ' selected' : '' }}>{{ __('countries.TC') }}</option>
                                                <option value="TV" {{ old('country') == 'TV' ? ' selected' : '' }}>{{ __('countries.TV') }}</option>
                                                <option value="UG" {{ old('country') == 'UG' ? ' selected' : '' }}>{{ __('countries.UG') }}</option>
                                                <option value="UA" {{ old('country') == 'UA' ? ' selected' : '' }}>{{ __('countries.UA') }}</option>
                                                <option value="AE" {{ old('country') == 'AE' ? ' selected' : '' }}>{{ __('countries.AE') }}</option>
                                                <option value="GB" {{ old('country') == 'GB' ? ' selected' : '' }}>{{ __('countries.GB') }}</option>
                                                <option value="US" {{ old('country') == 'US' ? ' selected' : '' }}>{{ __('countries.US') }}</option>
                                                <option value="UY" {{ old('country') == 'UY' ? ' selected' : '' }}>{{ __('countries.UY') }}</option>
                                                <option value="UZ" {{ old('country') == 'UZ' ? ' selected' : '' }}>{{ __('countries.UZ') }}</option>
                                                <option value="VU" {{ old('country') == 'VU' ? ' selected' : '' }}>{{ __('countries.VU') }}</option>
                                                <option value="VE" {{ old('country') == 'VE' ? ' selected' : '' }}>{{ __('countries.VE') }}</option>
                                                <option value="VN" {{ old('country') == 'VN' ? ' selected' : '' }}>{{ __('countries.VN') }}</option>
                                                <option value="VG" {{ old('country') == 'VG' ? ' selected' : '' }}>{{ __('countries.VG') }}</option>
                                                <option value="VI" {{ old('country') == 'VI' ? ' selected' : '' }}>{{ __('countries.VI') }}</option>
                                                <option value="WF" {{ old('country') == 'WF' ? ' selected' : '' }}>{{ __('countries.WF') }}</option>
                                                <option value="EH" {{ old('country') == 'EH' ? ' selected' : '' }}>{{ __('countries.EH') }}</option>
                                                <option value="YE" {{ old('country') == 'YE' ? ' selected' : '' }}>{{ __('countries.YE') }}</option>
                                                <option value="ZM" {{ old('country') == 'ZM' ? ' selected' : '' }}>{{ __('countries.ZM') }}</option>
                                                <option value="ZW" {{ old('country') == 'ZW' ? ' selected' : '' }}>{{ __('countries.ZW') }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="postalCode">{{ __('companies.address.postal') }}</label>
                                            <input id="postalCode" type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="{{ __('companies.input.enter_postal') }}" class="form-input">
                                        </div>
                                    </div>
                                    <div class="hidden lg:flex mt-1 gap-4">
                                        <a href="javascript:history.back()" class="btn btn-outline-danger">{{ __('others.cancel') }}</a>
                                        <button class="btn btn-primary" type="submit" x-data="{loading:false}" x-on:click="loading = true; setTimeout(() => loading = false, 4000)" x-html="loading ? `<span class='animate-spin border-2 border-white border-l-transparent rounded-full w-5 h-5 ltr:mr-4 rtl:ml-4 inline-block align-middle'></span>Loading` : '{{ __('others.save') }}'">
                                            {{ __('others.save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </template>
                {{-- <template x-if="tab === 'preferences'">
                    <div>
                        <div class="panel mt-6">
                            <h5 class="md:absolute md:top-[25px] md:mb-0 mb-5 font-semibold text-lg dark:text-white-light">รายการสาขา</h5>
                            @can('role create')
                                <div class="px-5">
                                    <div class="md:absolute btn-add">
                                        <div class="flex items-center">
                                            <a href="{{ url('roles/create') }}" class="btn btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 ltr:mr-3 rtl:ml-3">
                                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                                เพิ่มสาขา
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            <table id="myTable" class="whitespace-nowrap table-hover"></table>
                        </div>
                    </div>
                </template> --}}
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
