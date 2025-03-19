@extends('layouts.layout')
@section('title', 'รายการอุปกรณ์')
@section('style')
<style>
    .before\:bg-dark:before {
        content: var(--tw-content);
        background-color: rgb(107 114 128 / var(--tw-text-opacity));
    }
    .before\:bg-success:before {
        content: var(--tw-content);
        background-color: rgb(0 171 85/var(--tw-text-opacity));
    }
    button[disabled=disabled], button:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>
@endsection
@section('content')
<div x-data="devices">
    <ul class="mb-5 flex text-gray-500 dark:text-white-dark">
        <li>
            <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0">
                    <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5"></path>
                    <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </a>
        </li>
        <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">DMS</a>
        </li>
    </ul>
    <div class="panel min-h-screen">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <h5 class="md:mb-0 mb-5 font-semibold text-lg dark:text-white-light">รายการอุปกรณ์</h5>
            <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
                <div class="flex gap-3">
                    @can('dms create')
                    <div>
                        <a href="{{ route('dms.create') }}" class="btn btn-primary">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            {{ __('others.add') }}
                        </a>
                    </div>
                    @endcan
                    <div>
                        <button type="button" class="btn btn-outline-primary p-2" :class="{ 'bg-primary text-white': displayType === 'list' }" @click="setDisplayType('list')">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M2 5.5L3.21429 7L7.5 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.5" d="M2 12.5L3.21429 14L7.5 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2 19.5L3.21429 21L7.5 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 19L12 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M22 12L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M22 5L12 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary p-2" :class="{ 'bg-primary text-white': displayType === 'grid' }" @click="setDisplayType('grid')">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path opacity="0.5" d="M2.5 6.5C2.5 4.61438 2.5 3.67157 3.08579 3.08579C3.67157 2.5 4.61438 2.5 6.5 2.5C8.38562 2.5 9.32843 2.5 9.91421 3.08579C10.5 3.67157 10.5 4.61438 10.5 6.5C10.5 8.38562 10.5 9.32843 9.91421 9.91421C9.32843 10.5 8.38562 10.5 6.5 10.5C4.61438 10.5 3.67157 10.5 3.08579 9.91421C2.5 9.32843 2.5 8.38562 2.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M13.5 17.5C13.5 15.6144 13.5 14.6716 14.0858 14.0858C14.6716 13.5 15.6144 13.5 17.5 13.5C19.3856 13.5 20.3284 13.5 20.9142 14.0858C21.5 14.6716 21.5 15.6144 21.5 17.5C21.5 19.3856 21.5 20.3284 20.9142 20.9142C20.3284 21.5 19.3856 21.5 17.5 21.5C15.6144 21.5 14.6716 21.5 14.0858 20.9142C13.5 20.3284 13.5 19.3856 13.5 17.5Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M2.5 17.5C2.5 15.6144 2.5 14.6716 3.08579 14.0858C3.67157 13.5 4.61438 13.5 6.5 13.5C8.38562 13.5 9.32843 13.5 9.91421 14.0858C10.5 14.6716 10.5 15.6144 10.5 17.5C10.5 19.3856 10.5 20.3284 9.91421 20.9142C9.32843 21.5 8.38562 21.5 6.5 21.5C4.61438 21.5 3.67157 21.5 3.08579 20.9142C2.5 20.3284 2.5 19.3856 2.5 17.5Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M13.5 6.5C13.5 4.61438 13.5 3.67157 14.0858 3.08579C14.6716 2.5 15.6144 2.5 17.5 2.5C19.3856 2.5 20.3284 2.5 20.9142 3.08579C21.5 3.67157 21.5 4.61438 21.5 6.5C21.5 8.38562 21.5 9.32843 20.9142 9.91421C20.3284 10.5 19.3856 10.5 17.5 10.5C15.6144 10.5 14.6716 10.5 14.0858 9.91421C13.5 9.32843 13.5 8.38562 13.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="relative ">
                    <input type="text" placeholder="ค้นหา" class="form-input py-2 ltr:pr-11 rtl:pl-11 peer" x-model="searchUser" @keyup="searchDevices" />
                    <div class="absolute ltr:right-[11px] rtl:left-[11px] top-1/2 -translate-y-1/2 peer-focus:text-primary">
                        <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 panel p-0 border-0 overflow-hidden">
            <template x-if="displayType === 'list'">
                <div class="table-responsive">
                    <table class="table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ชื่อ</th>
                                <th>ชื่ออุปกรณ์</th>
                                <th>รหัสอุปกรณ์</th>
                                <th>เบอร์โทรศัพท์</th>
                                <th>ป้ายทะเบียน</th>
                                <th>สถานะ</th>
                                <th>โทร</th>
                                <th>ดู@can('dms update') / แก้ไข@endcan @can('dms delete') / ลบ@endcan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="device in filterdDevicesList" :key="device.id">
                                <tr>
                                    <td>
                                        <div class="flex items-center w-max">
                                            <div x-show="device.path" class="w-max">
                                                <img :src="`{{ Module::asset('dms:images') }}/${device.path}`" class="h-8 w-8 rounded-full object-cover ltr:mr-2 rtl:ml-2" alt="avatar" />
                                            </div>
                                            <div x-text="device.name"></div>
                                        </div>
                                    </td>
                                    <td x-text="device.device_name"></td>
                                    <td x-text="device.device_id"></td>
                                    <td x-text="device.tel"></td>
                                    <td x-text="device.car_plate_number"></td>
                                    <td>
                                        <span :id="`${device.device_id}-online`" style="display: none;" class="call-status-online rounded-full bg-[#1b2e4b] px-4 py-1.5 text-xs text-success before:inline-block before:h-2 before:w-2 before:rounded-full before:bg-success ltr:before:mr-2 rtl:before:ml-2">ออนไลน์</span>
                                        <span :id="`${device.device_id}-offline`" class="call-status-offline rounded-full bg-[#1b2e4b] px-4 py-1.5 text-xs text-white before:inline-block before:h-2 before:w-2 before:rounded-full before:bg-dark ltr:before:mr-2 rtl:before:ml-2">ออฟไลน์</span>
                                        <span :id="`${device.device_id}-busy`" style="display: none;" class="call-status-offline rounded-full bg-[#1b2e4b] px-4 py-1.5 text-xs text-white before:inline-block before:h-2 before:w-2 before:rounded-full before:bg-dark ltr:before:mr-2 rtl:before:ml-2">สายไม่ว่าง</span>
                                    </td>
                                    <td>
                                        <button type="button" class="text-success" x-tooltip="โทร" @click="calling(device)" :id="`${device.device_id}-call`" disabled>
                                            <input type="hidden" name="device_id" :id="device.device_id">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-success">
                                                <path d="M13.5 2C13.5 2 15.8335 2.21213 18.8033 5.18198C21.7731 8.15183 21.9853 10.4853 21.9853 10.4853" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M14.207 5.53564C14.207 5.53564 15.197 5.81849 16.6819 7.30341C18.1668 8.78834 18.4497 9.77829 18.4497 9.77829" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                <path opacity="0.5" d="M15.1007 15.0272L14.5569 14.5107L15.1007 15.0272ZM15.5562 14.5477L16.1 15.0642H16.1L15.5562 14.5477ZM17.9728 14.2123L17.5987 14.8623H17.5987L17.9728 14.2123ZM19.8833 15.312L19.5092 15.962L19.8833 15.312ZM20.4217 18.7584L20.9655 19.2749L20.4217 18.7584ZM19.0011 20.254L18.4573 19.7375L19.0011 20.254ZM17.6763 20.9631L17.7499 21.7095L17.6763 20.9631ZM7.81536 16.4752L8.35915 15.9587L7.81536 16.4752ZM3.00289 6.96594L2.25397 7.00613L2.25397 7.00613L3.00289 6.96594ZM9.47752 8.50311L10.0213 9.01963H10.0213L9.47752 8.50311ZM9.63424 5.6931L10.2466 5.26012L9.63424 5.6931ZM8.37326 3.90961L7.76086 4.3426V4.3426L8.37326 3.90961ZM5.26145 3.60864L5.80524 4.12516L5.26145 3.60864ZM3.69185 5.26114L3.14806 4.74462L3.14806 4.74462L3.69185 5.26114ZM11.0631 13.0559L11.6069 12.5394L11.0631 13.0559ZM15.6445 15.5437L16.1 15.0642L15.0124 14.0312L14.5569 14.5107L15.6445 15.5437ZM17.5987 14.8623L19.5092 15.962L20.2575 14.662L18.347 13.5623L17.5987 14.8623ZM19.8779 18.2419L18.4573 19.7375L19.5449 20.7705L20.9655 19.2749L19.8779 18.2419ZM17.6026 20.2167C16.1676 20.3584 12.4233 20.2375 8.35915 15.9587L7.27157 16.9917C11.7009 21.655 15.9261 21.8895 17.7499 21.7095L17.6026 20.2167ZM8.35915 15.9587C4.48303 11.8778 3.83285 8.43556 3.75181 6.92574L2.25397 7.00613C2.35322 8.85536 3.1384 12.6403 7.27157 16.9917L8.35915 15.9587ZM9.7345 9.32159L10.0213 9.01963L8.93372 7.9866L8.64691 8.28856L9.7345 9.32159ZM10.2466 5.26012L8.98565 3.47663L7.76086 4.3426L9.02185 6.12608L10.2466 5.26012ZM4.71766 3.09213L3.14806 4.74462L4.23564 5.77765L5.80524 4.12516L4.71766 3.09213ZM9.1907 8.80507C8.64691 8.28856 8.64622 8.28929 8.64552 8.29002C8.64528 8.29028 8.64458 8.29102 8.64411 8.29152C8.64316 8.29254 8.64219 8.29357 8.64121 8.29463C8.63924 8.29675 8.6372 8.29896 8.6351 8.30127C8.63091 8.30588 8.62646 8.31087 8.62178 8.31625C8.61243 8.32701 8.60215 8.33931 8.59116 8.3532C8.56918 8.38098 8.54431 8.41512 8.51822 8.45588C8.46591 8.53764 8.40917 8.64531 8.36112 8.78033C8.26342 9.0549 8.21018 9.4185 8.27671 9.87257C8.40742 10.7647 8.99198 11.9644 10.5193 13.5724L11.6069 12.5394C10.1793 11.0363 9.82761 10.1106 9.76086 9.65511C9.72866 9.43536 9.76138 9.31957 9.77432 9.28321C9.78159 9.26277 9.78635 9.25709 9.78169 9.26437C9.77944 9.26789 9.77494 9.27451 9.76738 9.28407C9.76359 9.28885 9.75904 9.29437 9.7536 9.30063C9.75088 9.30375 9.74793 9.30706 9.74476 9.31056C9.74317 9.31231 9.74152 9.3141 9.73981 9.31594C9.73896 9.31686 9.73809 9.31779 9.7372 9.31873C9.73676 9.3192 9.73608 9.31992 9.73586 9.32015C9.73518 9.32087 9.7345 9.32159 9.1907 8.80507ZM10.5193 13.5724C12.0422 15.1757 13.1923 15.806 14.0698 15.9485C14.5201 16.0216 14.8846 15.9632 15.1606 15.8544C15.2955 15.8012 15.4022 15.7387 15.4823 15.6819C15.5223 15.6535 15.5556 15.6266 15.5824 15.6031C15.5959 15.5913 15.6077 15.5803 15.618 15.5703C15.6232 15.5654 15.628 15.5606 15.6324 15.5562C15.6346 15.554 15.6367 15.5518 15.6387 15.5497C15.6397 15.5487 15.6407 15.5477 15.6417 15.5467C15.6422 15.5462 15.6429 15.5454 15.6431 15.5452C15.6438 15.5444 15.6445 15.5437 15.1007 15.0272C14.5569 14.5107 14.5576 14.51 14.5583 14.5093C14.5585 14.509 14.5592 14.5083 14.5596 14.5078C14.5605 14.5069 14.5614 14.506 14.5623 14.5051C14.5641 14.5033 14.5658 14.5015 14.5674 14.4998C14.5708 14.4965 14.574 14.4933 14.577 14.4904C14.583 14.4846 14.5885 14.4796 14.5933 14.4754C14.6028 14.467 14.6099 14.4616 14.6145 14.4584C14.6239 14.4517 14.6229 14.454 14.6102 14.459C14.5909 14.4666 14.5 14.4987 14.3103 14.4679C13.9077 14.4025 13.0391 14.0472 11.6069 12.5394L10.5193 13.5724ZM8.98565 3.47663C7.97206 2.04305 5.94384 1.80119 4.71766 3.09213L5.80524 4.12516C6.32808 3.57471 7.24851 3.61795 7.76086 4.3426L8.98565 3.47663ZM3.75181 6.92574C3.73038 6.52644 3.90425 6.12654 4.23564 5.77765L3.14806 4.74462C2.61221 5.30877 2.20493 6.09246 2.25397 7.00613L3.75181 6.92574ZM18.4573 19.7375C18.1783 20.0313 17.8864 20.1887 17.6026 20.2167L17.7499 21.7095C18.497 21.6357 19.1016 21.2373 19.5449 20.7705L18.4573 19.7375ZM10.0213 9.01963C10.9889 8.00095 11.0574 6.40678 10.2466 5.26012L9.02185 6.12608C9.44399 6.72315 9.37926 7.51753 8.93372 7.9866L10.0213 9.01963ZM19.5092 15.962C20.33 16.4345 20.4907 17.5968 19.8779 18.2419L20.9655 19.2749C22.2704 17.901 21.8904 15.6019 20.2575 14.662L19.5092 15.962ZM16.1 15.0642C16.4854 14.6584 17.086 14.5672 17.5987 14.8623L18.347 13.5623C17.2485 12.93 15.8861 13.1113 15.0124 14.0312L16.1 15.0642Z" fill="currentColor"/>
                                            </svg>
                                        </button>
                                        <div class="fixed inset-0 z-[999] hidden overflow-y-auto bg-[black]/60 px-4" :class="isCallingModal && '!block'" x-on:click.stop>
                                            <div class="flex min-h-screen items-center justify-center">
                                                <div x-show="isCallingModal" x-transition x-transition.duration.300 @click.outside="isCallingModal = false" class="panel my-8 w-[90%] max-w-lg overflow-hidden rounded-lg border-0 p-0 md:w-full">
                                                    <div class="p-5 text-center">
                                                        <div class="mx-auto w-fit rounded-full">
                                                            <div class="text-center"><img :src="`{{ Module::asset('dms:images') }}/${device.path}`" class="h-24 w-24 rounded-full object-cover" alt="avatar"><div class="mt-2"><b x-text="device.name"></b></div><p class="mt-1 text-sm text-base-content/60"><span id="minutes">00</span>:<span id="seconds">00</span></p></div>
                                                        </div>
                                                        <div class="mt-8 flex items-center justify-center">
                                                            <button type="button" class="btn btn-danger h-14 w-14 rounded-full p-0 text-white" @click="endCall()">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white mx-auto h-7 w-7">
                                                                    <path d="M8 12.8617L8 13.4782C8 14.3304 7.42329 15.0823 6.57997 15.3294L4.57997 15.9155C3.29561 16.2919 2 15.3623 2 14.0643L2 12.1414C2 11.6525 2.1247 11.1704 2.44083 10.7889M21.3703 9.85596C21.8162 10.2541 22 10.8313 22 11.4182V13.5429C22 14.7266 20.9105 15.6329 19.7004 15.4555L17.7004 15.1624C16.7227 15.0192 16 14.2063 16 13.2499V12.8617" stroke="currentColor" stroke-width="1.5"/>
                                                                    <path opacity="0.5" d="M7.25009 12.8617C7.25009 13.2759 7.58588 13.6117 8.00009 13.6117C8.4143 13.6117 8.75009 13.2759 8.75009 12.8617H7.25009ZM12.0001 7V7.75V7ZM15.2501 12.8617C15.2501 13.2759 15.5859 13.6117 16.0001 13.6117C16.4143 13.6117 16.7501 13.2759 16.7501 12.8617L15.2501 12.8617ZM12.0001 11.3963V10.6463V11.3963ZM3.01844 11.2674C3.90188 10.2012 6.51327 7.75 12.0001 7.75V6.25C5.98973 6.25 2.97883 8.96419 1.8634 10.3104L3.01844 11.2674ZM12.0001 7.75C17.2331 7.75 19.8649 9.51708 20.8709 10.4154L21.87 9.29661C20.5945 8.15756 17.6088 6.25 12.0001 6.25V7.75ZM16.0001 12.8617C16.7501 12.8617 16.7501 12.8607 16.7501 12.8597C16.7501 12.8593 16.7501 12.8583 16.7501 12.8576C16.7501 12.8562 16.7501 12.8548 16.75 12.8534C16.75 12.8505 16.75 12.8475 16.7499 12.8444C16.7498 12.8382 16.7496 12.8316 16.7492 12.8245C16.7486 12.8104 16.7476 12.7946 16.746 12.7773C16.7429 12.7426 16.7375 12.7016 16.7284 12.6552C16.7103 12.5622 16.6777 12.4484 16.6203 12.3222C16.5031 12.0645 16.2942 11.7811 15.9434 11.524C15.2574 11.0214 14.0695 10.6463 12.0001 10.6463V12.1463C13.9307 12.1463 14.7428 12.5039 15.0568 12.734C15.206 12.8433 15.2471 12.9262 15.2549 12.9433C15.26 12.9544 15.2586 12.9551 15.2561 12.9423C15.2549 12.936 15.2534 12.9264 15.2522 12.9132C15.2516 12.9066 15.2511 12.8991 15.2507 12.8905C15.2505 12.8862 15.2504 12.8817 15.2503 12.8769C15.2502 12.8745 15.2502 12.872 15.2501 12.8695C15.2501 12.8682 15.2501 12.867 15.2501 12.8657C15.2501 12.865 15.2501 12.864 15.2501 12.8637C15.2501 12.8627 15.2501 12.8617 16.0001 12.8617ZM12.0001 10.6463C9.93065 10.6463 8.74276 11.0214 8.05681 11.524C7.70599 11.7811 7.49712 12.0645 7.37991 12.3222C7.32248 12.4484 7.28989 12.5622 7.27176 12.6552C7.26272 12.7016 7.25731 12.7426 7.25415 12.7773C7.25257 12.7946 7.25156 12.8104 7.25094 12.8245C7.25063 12.8316 7.25042 12.8382 7.25028 12.8444C7.25021 12.8475 7.25017 12.8505 7.25014 12.8534C7.25012 12.8548 7.25011 12.8562 7.2501 12.8576C7.2501 12.8583 7.25009 12.8594 7.25009 12.8597C7.25009 12.8607 7.25009 12.8617 8.00009 12.8617C8.75009 12.8617 8.75009 12.8627 8.75009 12.8637C8.75009 12.864 8.75008 12.865 8.75008 12.8657C8.75007 12.867 8.75006 12.8682 8.75005 12.8695C8.75002 12.872 8.74998 12.8745 8.74993 12.8769C8.74983 12.8817 8.74968 12.8862 8.74949 12.8905C8.74911 12.8991 8.74858 12.9066 8.74798 12.9132C8.74678 12.9264 8.74527 12.936 8.74404 12.9423C8.74154 12.9551 8.7402 12.9544 8.74527 12.9433C8.75306 12.9262 8.79419 12.8433 8.94337 12.734C9.25742 12.5039 10.0695 12.1463 12.0001 12.1463V10.6463Z" fill="currentColor"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex gap-4">
                                            <ul class="flex gap-2">
                                                <li><a :href="`{{ url('dms') }}/${device.id}/show`" x-tooltip="ดู" class="hover:bg-default"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary"> <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path> <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path> </svg></a></li>
                                                @can('dms update')<li><a :href="`{{ url('dms') }}/${device.id}/edit`" x-tooltip="แก้ไข"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-success"> <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5"></path> <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5"></path> </svg> </a></li>@endcan
                                                @can('dms delete')<li><button type="button" class="text-danger" @click="deleteConfirm(device)" x-tooltip="ลบ"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-danger"> <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="currentColor" stroke-width="1.5"></path></svg></button></li>@endcan
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
        <template x-if="displayType === 'grid'">
            <div class="grid 2xl:grid-cols-4 xl:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6 w-full">
                <template x-for="device in filterdDevicesList" :key="device.id">
                    <div class="bg-white dark:bg-[#1c232f] rounded-md overflow-hidden text-center shadow relative">
                        <div class="rounded-t-md bg-white/40 bg-[url('../images/notification-bg.png')] bg-cover bg-center p-6 pb-0">
                            <template x-if="device.path">
                                <img class="object-contain w-4/5 max-h-40 mx-auto" :src="`{{ Module::asset('dms:images') }}/${device.path}`" />
                            </template>
                        </div>
                        <div class="px-6 pb-24 -mt-10 relative">
                            <div class="shadow-md bg-white dark:bg-gray-900 rounded-md px-2 py-4">
                                <div class="text-xl" x-text="device.name"></div>
                                <div class="text-white-dark" x-text="device.role"></div>
                            </div>
                            <div class="mt-6 grid grid-cols-1 gap-4 ltr:text-left rtl:text-right">
                                <div class="flex items-center">
                                    <div class="flex-none ltr:mr-2 rtl:ml-2">ชื่ออุปกรณ์ :</div>
                                    <div class="truncate text-white-dark" x-text="device.device_name"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex-none ltr:mr-2 rtl:ml-2">รหัสอุปกรณ์ :</div>
                                    <div class="text-white-dark" x-text="device.device_id"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex-none ltr:mr-2 rtl:ml-2">สถานะ :</div>
                                    <div class="text-white-dark">
                                        <span :id="`${device.device_id}-online`" style="display: none;" class="call-status-online rounded-full bg-[#1b2e4b] px-4 py-1.5 text-xs text-success before:inline-block before:h-2 before:w-2 before:rounded-full before:bg-success ltr:before:mr-2 rtl:before:ml-2">ออนไลน์</span>
                                        <span :id="`${device.device_id}-offline`" class="call-status-offline rounded-full bg-[#1b2e4b] px-4 py-1.5 text-xs text-white before:inline-block before:h-2 before:w-2 before:rounded-full before:bg-dark ltr:before:mr-2 rtl:before:ml-2">ออฟไลน์</span>
                                        <span :id="`${device.device_id}-busy`" style="display: none;" class="call-status-offline rounded-full bg-[#1b2e4b] px-4 py-1.5 text-xs text-white before:inline-block before:h-2 before:w-2 before:rounded-full before:bg-dark ltr:before:mr-2 rtl:before:ml-2">สายไม่ว่าง</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4 absolute bottom-0 w-full ltr:left-0 rtl:right-0 p-6">
                            <a href="javascript:;" class="btn btn-outline-success w-1/3" @click="calling(device)" :id="`${device.device_id}-call`" disabled>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                    <path d="M13.5 2C13.5 2 15.8335 2.21213 18.8033 5.18198C21.7731 8.15183 21.9853 10.4853 21.9853 10.4853" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    <path d="M14.207 5.53564C14.207 5.53564 15.197 5.81849 16.6819 7.30341C18.1668 8.78834 18.4497 9.77829 18.4497 9.77829" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    <path opacity="0.5" d="M15.1007 15.0272L14.5569 14.5107L15.1007 15.0272ZM15.5562 14.5477L16.1 15.0642H16.1L15.5562 14.5477ZM17.9728 14.2123L17.5987 14.8623H17.5987L17.9728 14.2123ZM19.8833 15.312L19.5092 15.962L19.8833 15.312ZM20.4217 18.7584L20.9655 19.2749L20.4217 18.7584ZM19.0011 20.254L18.4573 19.7375L19.0011 20.254ZM17.6763 20.9631L17.7499 21.7095L17.6763 20.9631ZM7.81536 16.4752L8.35915 15.9587L7.81536 16.4752ZM3.00289 6.96594L2.25397 7.00613L2.25397 7.00613L3.00289 6.96594ZM9.47752 8.50311L10.0213 9.01963H10.0213L9.47752 8.50311ZM9.63424 5.6931L10.2466 5.26012L9.63424 5.6931ZM8.37326 3.90961L7.76086 4.3426V4.3426L8.37326 3.90961ZM5.26145 3.60864L5.80524 4.12516L5.26145 3.60864ZM3.69185 5.26114L3.14806 4.74462L3.14806 4.74462L3.69185 5.26114ZM11.0631 13.0559L11.6069 12.5394L11.0631 13.0559ZM15.6445 15.5437L16.1 15.0642L15.0124 14.0312L14.5569 14.5107L15.6445 15.5437ZM17.5987 14.8623L19.5092 15.962L20.2575 14.662L18.347 13.5623L17.5987 14.8623ZM19.8779 18.2419L18.4573 19.7375L19.5449 20.7705L20.9655 19.2749L19.8779 18.2419ZM17.6026 20.2167C16.1676 20.3584 12.4233 20.2375 8.35915 15.9587L7.27157 16.9917C11.7009 21.655 15.9261 21.8895 17.7499 21.7095L17.6026 20.2167ZM8.35915 15.9587C4.48303 11.8778 3.83285 8.43556 3.75181 6.92574L2.25397 7.00613C2.35322 8.85536 3.1384 12.6403 7.27157 16.9917L8.35915 15.9587ZM9.7345 9.32159L10.0213 9.01963L8.93372 7.9866L8.64691 8.28856L9.7345 9.32159ZM10.2466 5.26012L8.98565 3.47663L7.76086 4.3426L9.02185 6.12608L10.2466 5.26012ZM4.71766 3.09213L3.14806 4.74462L4.23564 5.77765L5.80524 4.12516L4.71766 3.09213ZM9.1907 8.80507C8.64691 8.28856 8.64622 8.28929 8.64552 8.29002C8.64528 8.29028 8.64458 8.29102 8.64411 8.29152C8.64316 8.29254 8.64219 8.29357 8.64121 8.29463C8.63924 8.29675 8.6372 8.29896 8.6351 8.30127C8.63091 8.30588 8.62646 8.31087 8.62178 8.31625C8.61243 8.32701 8.60215 8.33931 8.59116 8.3532C8.56918 8.38098 8.54431 8.41512 8.51822 8.45588C8.46591 8.53764 8.40917 8.64531 8.36112 8.78033C8.26342 9.0549 8.21018 9.4185 8.27671 9.87257C8.40742 10.7647 8.99198 11.9644 10.5193 13.5724L11.6069 12.5394C10.1793 11.0363 9.82761 10.1106 9.76086 9.65511C9.72866 9.43536 9.76138 9.31957 9.77432 9.28321C9.78159 9.26277 9.78635 9.25709 9.78169 9.26437C9.77944 9.26789 9.77494 9.27451 9.76738 9.28407C9.76359 9.28885 9.75904 9.29437 9.7536 9.30063C9.75088 9.30375 9.74793 9.30706 9.74476 9.31056C9.74317 9.31231 9.74152 9.3141 9.73981 9.31594C9.73896 9.31686 9.73809 9.31779 9.7372 9.31873C9.73676 9.3192 9.73608 9.31992 9.73586 9.32015C9.73518 9.32087 9.7345 9.32159 9.1907 8.80507ZM10.5193 13.5724C12.0422 15.1757 13.1923 15.806 14.0698 15.9485C14.5201 16.0216 14.8846 15.9632 15.1606 15.8544C15.2955 15.8012 15.4022 15.7387 15.4823 15.6819C15.5223 15.6535 15.5556 15.6266 15.5824 15.6031C15.5959 15.5913 15.6077 15.5803 15.618 15.5703C15.6232 15.5654 15.628 15.5606 15.6324 15.5562C15.6346 15.554 15.6367 15.5518 15.6387 15.5497C15.6397 15.5487 15.6407 15.5477 15.6417 15.5467C15.6422 15.5462 15.6429 15.5454 15.6431 15.5452C15.6438 15.5444 15.6445 15.5437 15.1007 15.0272C14.5569 14.5107 14.5576 14.51 14.5583 14.5093C14.5585 14.509 14.5592 14.5083 14.5596 14.5078C14.5605 14.5069 14.5614 14.506 14.5623 14.5051C14.5641 14.5033 14.5658 14.5015 14.5674 14.4998C14.5708 14.4965 14.574 14.4933 14.577 14.4904C14.583 14.4846 14.5885 14.4796 14.5933 14.4754C14.6028 14.467 14.6099 14.4616 14.6145 14.4584C14.6239 14.4517 14.6229 14.454 14.6102 14.459C14.5909 14.4666 14.5 14.4987 14.3103 14.4679C13.9077 14.4025 13.0391 14.0472 11.6069 12.5394L10.5193 13.5724ZM8.98565 3.47663C7.97206 2.04305 5.94384 1.80119 4.71766 3.09213L5.80524 4.12516C6.32808 3.57471 7.24851 3.61795 7.76086 4.3426L8.98565 3.47663ZM3.75181 6.92574C3.73038 6.52644 3.90425 6.12654 4.23564 5.77765L3.14806 4.74462C2.61221 5.30877 2.20493 6.09246 2.25397 7.00613L3.75181 6.92574ZM18.4573 19.7375C18.1783 20.0313 17.8864 20.1887 17.6026 20.2167L17.7499 21.7095C18.497 21.6357 19.1016 21.2373 19.5449 20.7705L18.4573 19.7375ZM10.0213 9.01963C10.9889 8.00095 11.0574 6.40678 10.2466 5.26012L9.02185 6.12608C9.44399 6.72315 9.37926 7.51753 8.93372 7.9866L10.0213 9.01963ZM19.5092 15.962C20.33 16.4345 20.4907 17.5968 19.8779 18.2419L20.9655 19.2749C22.2704 17.901 21.8904 15.6019 20.2575 14.662L19.5092 15.962ZM16.1 15.0642C16.4854 14.6584 17.086 14.5672 17.5987 14.8623L18.347 13.5623C17.2485 12.93 15.8861 13.1113 15.0124 14.0312L16.1 15.0642Z" fill="currentColor"></path>
                                </svg>&nbsp;โทร
                            </a>
                            @can('dms update')<a :href="`{{ url('dms') }}/${device.id}/edit`" class="btn btn-outline-primary w-1/3">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5"> <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5"></path> <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5"></path> </svg>&nbsp;แก้ไข
                            </a>@endcan
                            @can('dms delete')<a href="javascript:;" class="btn btn-outline-danger w-1/3" @click="deleteConfirm(device)">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"> <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="currentColor" stroke-width="1.5"></path></svg>&nbsp;ลบ
                            </a>@endcan
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
    @include('layouts.confirm')
</div>
@endsection
@section('script')
<script src="{{ config("dms.config.url") }}/socket.io/socket.io.js"></script>
<script>
    const socket = io('{{ config("dms.config.url") }}', { transports: ['websocket', 'polling', 'flashsocket'] });
    socket.on('connect', () => {
        console.log('Connected to server');
        socket.emit('register', { type: 'operator' });
    });
</script>
<script src="{{ Module::asset('dms:js') }}/app.js"></script>
<script>
    let count = 0;
    let timer;
    document.addEventListener("alpine:init", () => {
        Alpine.data("devices", () => ({
            defaultParams: {
                id: null,
                name: '',
                device_name: '',
                device_id: '',
                key: '',
                tel: '',
                type: '',
                car_plate_number: '',
                car_plate_number_sub: ''
            },
            displayType: 'list',
            addDeviceModal: false,
            params: {
                id: null,
                name: '',
                device_name: '',
                device_id: '',
                key: '',
                tel: '',
                type: '',
                car_plate_number: '',
                car_plate_number_sub: ''
            },
            filterdDevicesList: [],
            searchUser: '',
            itemID: null,
            itemCallID: null,
            isDeleteModal: false,
            isCallingModal: false,
            setDeviceCall: null,
            deviceList: [
            @foreach ($dms as $value)
                {
                    id: {{ $value->id }},
                    path: '{{ ($value->img === null ? 'user.png' : $value->img) }}',
                    name: '{{ $value->name }}',
                    device_name: '{{ $value->device_name }}',
                    device_id: '{{ $value->device_id }}',
                    key: '{{ $value->key }}',
                    tel: '{{ $value->tel }}',
                    type: '{{ $value->car_type }}',
                    car_plate_number: '{{ $value->car_plate_number }}',
                    car_plate_number_sub: '{{ $value->car_plate_number_sub }}',
                },
            @endforeach
            ],
            init() {
                this.searchDevices();
                socket.on('Devices', (devices) => {
                    devices.forEach(device => {
                        document.getElementById(device.device_id).value = device.socket_id.call;
                        document.querySelector('#' + device.device_id + '-call').disabled = false;

                        document.querySelector('#' + device.device_id + '-online').style.display = 'inline';
                        document.querySelector('#' + device.device_id + '-offline').style.display = 'none';
                    });
                });
            },
            searchDevices() {
                return this.filterdDevicesList = this.deviceList.filter((d) => d.name.toLowerCase().includes(this.searchUser.toLowerCase()));
            },
            deleteConfirm(item) {
                setTimeout(() => {
                    this.itemID = item.id;
                    this.isDeleteModal = true;
                });
            },
            deleteItem() {
                const dataID = {
                    'id': this.itemID
                };
                fetch("{{ route('dms.delete') }}", {
                    method: "DELETE",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('input[name=_token]').value
                    },
                    body: JSON.stringify(dataID)
                }).then((res) => res.json()).then((response) => {
                    this.showMessage('ลบสำเร็จ');
                });
                if (this.itemID) {
                    this.deviceList = this.deviceList.filter((d) => d.id != this.itemID);
                    this.selectedRows = [];
                } else {
                    this.deviceList = this.deviceList.filter((d) => !this.selectedRows.includes(d.id));
                    this.selectedRows = [];
                }
                this.isDeleteModal = false;
                this.searchDevices();
            },
            renderTimer() {
                const minutes = document.querySelector("#minutes")
                const seconds = document.querySelector("#seconds")
                count += 1;
                minutes.innerHTML = Math.floor(count / 60).toString().padStart(2, "0");
                seconds.innerHTML = (count % 60).toString().padStart(2, "0");
            },
            async calling(item) {
                setDeviceCall = document.getElementById(item.device_id).value;
                const peerConnection = new RTCPeerConnection(configuration);
                peerConnections[setDeviceCall] = peerConnection;
                peerConnection.onicecandidate = event => {
                    if (event.candidate) {
                        console.log('Sending ICE candidate:', event.candidate);
                        socket.emit('signal', {
                            target: setDeviceCall,
                            data: { candidate: event.candidate }
                        });
                    }
                };
                peerConnection.ontrack = event => {
                    if (event.track.kind === 'audio') {
                        const audio = new Audio();
                        console.log(event)
                        audio.srcObject = event.streams[0];
                        audio.play();
                    }
                };
                try {
                    if (!localStream) {
                        localStream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
                    }
                    localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
                } catch (err) {
                    console.error('Error accessing media devices.', err);
                    alert('Could not access your microphone.');
                    return;
                }
                try {
                    const offer = await peerConnection.createOffer();
                    await peerConnection.setLocalDescription(offer);
                    socket.emit('signal', {
                        target: setDeviceCall,
                        data: peerConnection.localDescription
                    });
                } catch (err) {
                    console.error('Error creating offer:', err);
                }
                setTimeout(() => {
                    this.itemCallID = item.id;
                    this.isCallingModal = true;
                    timer = setInterval(this.renderTimer, 1000);
                });
            },
            endCall() {
                count = 0;
                document.querySelector("#minutes").innerHTML = "00";
                document.querySelector("#seconds").innerHTML = "00";
                this.isCallingModal = false;
                clearInterval(timer);
                if (!setDeviceCall) return;
                const peerConnection = peerConnections[setDeviceCall];
                if (peerConnection) {
                    peerConnection.close();
                    delete peerConnections[setDeviceCall];
                }
                if (localStream) {
                    localStream.getTracks().forEach(track => track.stop());
                    localStream = null;
                }
                socket.emit('signal', {
                    target: setDeviceCall,
                    data: { type: 'end' }
                });
            },
            setDisplayType(type) {
                this.displayType = type;
            },
            showMessage(msg = '', type = 'success') {
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                });
                toast.fire({
                    icon: type,
                    title: msg,
                    padding: '10px 20px',
                });
            },
        }));
    });
    async function showAlert() {
        @if (\Session::has('success'))
            const toast = window.Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 4000,
                padding: '2em',
            });
            toast.fire({
                icon: 'success',
                title: '{!! \Session::get('success') !!}',
                padding: '2em',
            });
        @elseif (\Session::has('errors'))
            const toast = window.Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 4000,
                padding: '2em',
                customClass: {
                    popup: `color-danger`
                },
            });
            toast.fire({
                icon: 'error',
                title: 'ไม่สามารถลบรายการนี้ได้',
                padding: '2em',
            });
        @endif
    }
    @if (\Session::has('success') || \Session::has('errors'))
        setTimeout(() => {
            window.onload = showAlert();
        }, "400");
    @endif
</script>
@endsection
