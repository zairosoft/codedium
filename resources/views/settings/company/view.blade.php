@extends('layouts.layout')
@section('title', 'ดูข้อมูลบริษัท')
@section('content')
    <div>
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">ข้อมูลบริษัท</div>
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
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/settings/company') }}">บริษัท</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">ดู</a></li>
                </ul>
            </div>
        </div>
        <div>
            <div x-data="{ tab: 'home' }">
                <ul class="sm:flex font-semibold border-b border-[#ebedf2] dark:border-[#191e3a] mb-5 whitespace-nowrap overflow-y-auto">
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
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                            :class="{ '!border-primary text-primary': tab == 'preferences' }" @click="tab='preferences'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M9.50004 2H14.5L15.1518 8.51737C15.3382 10.382 13.874 12 12 12C10.1261 12 8.66184 10.382 8.8483 8.51737L9.50004 2Z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M3.32975 5.35133C3.50783 4.46093 3.59687 4.01573 3.77791 3.65484C4.15938 2.89439 4.84579 2.33168 5.66628 2.10675C6.05567 2 6.50969 2 7.41771 2H9.50002L8.77549 9.24527C8.61911 10.8091 7.30318 12 5.73155 12C3.8011 12 2.35324 10.2339 2.73183 8.34093L3.32975 5.35133Z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M20.6703 5.35133C20.4922 4.46093 20.4031 4.01573 20.2221 3.65484C19.8406 2.89439 19.1542 2.33168 18.3337 2.10675C17.9443 2 17.4903 2 16.5823 2H14.5L15.2245 9.24527C15.3809 10.8091 16.6968 12 18.2685 12C20.1989 12 21.6468 10.2339 21.2682 8.34093L20.6703 5.35133Z" stroke="currentColor" stroke-width="1.5"/>
                                <path opacity="0.5" d="M8.75 21.5C8.75 21.9142 9.08579 22.25 9.5 22.25C9.91421 22.25 10.25 21.9142 10.25 21.5H8.75ZM13.75 21.5C13.75 21.9142 14.0858 22.25 14.5 22.25C14.9142 22.25 15.25 21.9142 15.25 21.5H13.75ZM13.75 16.201L14.125 15.5514L13.75 16.201ZM14.299 16.75L13.6495 17.125V17.125L14.299 16.75ZM9.70096 16.75L10.3505 17.125L9.70096 16.75ZM10.25 16.201L10.625 16.8505L10.25 16.201ZM12.5 21.25H11.5V22.75H12.5V21.25ZM4.25 14V11H2.75V14H4.25ZM19.75 11V14H21.25V11H19.75ZM11.5 21.25C9.59318 21.25 8.23851 21.2484 7.21085 21.1102C6.20476 20.975 5.62511 20.7213 5.2019 20.2981L4.14124 21.3588C4.88961 22.1071 5.83855 22.4392 7.01098 22.5969C8.16182 22.7516 9.63558 22.75 11.5 22.75V21.25ZM2.75 14C2.75 15.8644 2.74841 17.3382 2.90313 18.489C3.06076 19.6614 3.39288 20.6104 4.14124 21.3588L5.2019 20.2981C4.77869 19.8749 4.52502 19.2952 4.38976 18.2892C4.25159 17.2615 4.25 15.9068 4.25 14H2.75ZM12.5 22.75C14.3644 22.75 15.8382 22.7516 16.989 22.5969C18.1614 22.4392 19.1104 22.1071 19.8588 21.3588L18.7981 20.2981C18.3749 20.7213 17.7952 20.975 16.7892 21.1102C15.7615 21.2484 14.4068 21.25 12.5 21.25V22.75ZM19.75 14C19.75 15.9068 19.7484 17.2615 19.6102 18.2892C19.475 19.2952 19.2213 19.8749 18.7981 20.2981L19.8588 21.3588C20.6071 20.6104 20.9392 19.6614 21.0969 18.489C21.2516 17.3382 21.25 15.8644 21.25 14H19.75ZM10.25 21.5V18.5H8.75V21.5H10.25ZM13.75 18.5V21.5H15.25V18.5H13.75ZM12 16.75C12.4811 16.75 12.7918 16.7507 13.0273 16.7721C13.2524 16.7925 13.3341 16.8269 13.375 16.8505L14.125 15.5514C13.8178 15.3741 13.4918 15.308 13.1627 15.2782C12.8438 15.2493 12.4535 15.25 12 15.25V16.75ZM15.25 18.5C15.25 18.0465 15.2507 17.6562 15.2218 17.3373C15.192 17.0082 15.1259 16.6822 14.9486 16.375L13.6495 17.125C13.6731 17.1659 13.7075 17.2476 13.7279 17.4727C13.7493 17.7082 13.75 18.0189 13.75 18.5H15.25ZM13.375 16.8505C13.489 16.9163 13.5837 17.011 13.6495 17.125L14.9486 16.375C14.7511 16.033 14.467 15.7489 14.125 15.5514L13.375 16.8505ZM10.25 18.5C10.25 18.0189 10.2507 17.7082 10.2721 17.4727C10.2925 17.2476 10.3269 17.1659 10.3505 17.125L9.05144 16.375C8.87407 16.6822 8.80802 17.0082 8.77818 17.3373C8.74928 17.6562 8.75 18.0465 8.75 18.5H10.25ZM12 15.25C11.5465 15.25 11.1562 15.2493 10.8373 15.2782C10.5082 15.308 10.1822 15.3741 9.875 15.5514L10.625 16.8505C10.6659 16.8269 10.7476 16.7925 10.9727 16.7721C11.2082 16.7507 11.5189 16.75 12 16.75V15.25ZM10.3505 17.125C10.4163 17.011 10.511 16.9163 10.625 16.8505L9.875 15.5514C9.53296 15.7489 9.24892 16.033 9.05144 16.375L10.3505 17.125Z" fill="currentColor"/>
                            </svg>
                            สาขา
                        </a>
                    </li>
                </ul>
                <template x-if="tab === 'home'">
                    <div>
                        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">

                            <h6 class="text-lg font-bold mb-5">ข้อมูลทั่วไป</h6>
                            <div class="flex flex-col sm:flex-row">
                                <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
                                    <img src="{{ asset($company->img === null ? 'assets/images/image.png' : 'assets/images/companies/' . $company->img) }}" alt="image" class="w-20 h-20 md:w-32 md:h-32 object-cover mx-auto" id="preview" />
                                </div>
                                <div class="space-y-5 flex-1 grid">
                                    <div>
                                        <label for="name">ชื่อ</label>
                                        <div>{{ $lang->name }}</div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="tel">โทรศัพท์</label>
                                            <div>{{ $company->tel }}</div>
                                        </div>
                                        <div>
                                            <label for="phone">มือถือ</label>
                                            <div>{{ $company->mobile }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="email">อีเมล</label>
                                            <div>{{ $company->email }}</div>
                                        </div>
                                        <div>
                                            <label for="website">เว็บไซต์</label>
                                            <div>{{ $company->website }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="tel">เลขประจำตัวผู้เสียภาษี</label>
                                            <div>{{ $company->tax_id }}</div>
                                        </div>
                                        <div>
                                            <label for="phone">ID บริษัท</label>
                                            <div>{{ $company->company_id }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="gridAddress2">ที่อยู่</label>
                                        <div>{{ $lang->address }}</div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4">
                                        <div class="md:col-span-2">
                                            <label for="district">อำเภอ / เขต</label>
                                            <div>{{ $lang->district }}</div>
                                        </div>
                                        <div>
                                            <label for="province">จังหวัด</label>
                                            <div>{{ $lang->province }}</div>
                                        </div>
                                        <div>
                                            <label for="gridZip">รหัสไปรษณีย์</label>
                                            <div>{{ $lang->zip }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="tab === 'preferences'">
                    <div class="switch">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Choose Theme</h5>
                                <div class="flex justify-around">
                                    <label class="inline-flex cursor-pointer">
                                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio"
                                            name="theme" value="1" checked="" />
                                        <span>
                                            <img class="ms-3" width="100" height="68" alt="settings-dark"
                                                src="{{ asset('assets/images/settings-light.svg') }}" />
                                        </span>
                                    </label>
                                    <label class="inline-flex cursor-pointer">
                                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio"
                                            name="theme" value="1" />
                                        <span>
                                            <img class="ms-3" width="100" height="68" alt="settings-light"
                                                src="{{ asset('assets/images/settings-dark.svg') }}" />
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Activity data</h5>
                                <p>Download your Summary, Task and Payment History Data</p>
                                <button type="button" class="btn btn-primary">Download Data</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">บทบาทน</h5>
                                <p>บทบาทนการใช้งานระบบ</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox1" />
                                    <span for="custom_switch_checkbox1"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Show my email</h5>
                                <p>Your <span class="text-primary">Email</span> will be visible to anyone on the network.
                                </p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox2" />
                                    <span for="custom_switch_checkbox2"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Enable keyboard shortcuts</h5>
                                <p>When enabled, press <span class="text-primary">ctrl</span> for help</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox3" />
                                    <span for="custom_switch_checkbox3"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Hide left navigation</h5>
                                <p>Sidebar will be <span class="text-primary">hidden</span> by default</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox4" />
                                    <span for="custom_switch_checkbox4"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Advertisements</h5>
                                <p>Display <span class="text-primary">Ads</span> on your dashboard</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox5" />
                                    <span for="custom_switch_checkbox5"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Social Profile</h5>
                                <p>Enable your <span class="text-primary">social</span> profiles on this network</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox6" />
                                    <span for="custom_switch_checkbox6"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endsection
