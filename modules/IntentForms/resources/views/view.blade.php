@extends('layouts.layout')
@section('title', 'ดูอนุโมทนาบัตร')
@section('content')
    <div>
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">อนุโมทนาบัตร</div>
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
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/intentform') }}">อนุโมทนาบัตร</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">ดู</a></li>
                </ul>
            </div>
        </div>
        <div>
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">รายละเอียดอนุโมทนาบัตร</h5>
                    <div class="flex gap-2">
                        @can('intentform update')
                            <a href="{{ route('intentform.edit', $intentform->id) }}" class="btn btn-primary">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                    <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5"></path>
                                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5"></path>
                                </svg>
                                แก้ไข
                            </a>
                        @endcan
                        <a href="{{ route('intentform.print', $intentform->id) }}" target="_blank" class="btn btn-info">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path d="M6 17.9827C4.44655 17.9359 3.51998 17.7626 2.87868 17.1213C2 16.2426 2 14.8284 2 12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12C22 14.8284 22 16.2426 21.1213 17.1213C20.48 17.7626 19.5535 17.9359 18 17.9827" stroke="currentColor" stroke-width="1.5"></path>
                                <path opacity="0.5" d="M9 10H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M19 14L5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M18 14V19C18 20.1046 17.1046 21 16 21H8C6.89543 21 6 20.1046 6 19V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                            พิมพ์
                        </a>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="flex flex-wrap justify-between px-4">
                        <div class="mb-6 w-full lg:w-1/2">
                            <div class="text-lg font-semibold mb-3">{{ $company->name }}</div>
                            <div class="space-y-1 text-gray-500 dark:text-gray-400">
                                @if($company->address)
                                    <div>{{ $company->address }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="w-full lg:w-1/2 lg:max-w-fit">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-semibold">เล่มที่ / เลขที่:</div>
                                <div class="ml-4">{{ $intentform->volume }} / {{ $intentform->number }}</div>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-semibold">วันที่:</div>
                                <div class="ml-4">{{ \Carbon\Carbon::parse($intentform->date)->format('d/m/Y') }}</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="font-semibold">สถานะ:</div>
                                <div class="ml-4">
                                    @if($intentform->status == 1)
                                        <span class="badge bg-success">ใช้งาน</span>
                                    @else
                                        <span class="badge bg-danger">ไม่ใช้งาน</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-[#e0e6ed] dark:border-[#1b2e4b] my-6" />

                <div class="mt-8">
                    <div class="flex flex-col justify-between lg:flex-row px-4">
                        <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6">
                            <div class="text-lg font-semibold mb-3">ข้อมูล</div>
                            <div class="space-y-2">
                                <div class="flex">
                                    <div class="font-semibold w-1/3">ชื่อบัญชี:</div>
                                    <div class="flex-1">{{ $intentform->account_name ?? '-' }}</div>
                                </div>
                                <div class="flex">
                                    <div class="font-semibold w-1/3">เลขบัญชี:</div>
                                    <div class="flex-1">{{ $intentform->account_number ?? '-' }}</div>
                                </div>
                                <div class="flex">
                                    <div class="font-semibold w-1/3">บัตรนี้แสดงว่า:</div>
                                    <div class="flex-1">{{ $intentform->name }}</div>
                                </div>
                                <div class="flex">
                                    <div class="font-semibold w-1/3">ผู้รับเงิน:</div>
                                    <div class="flex-1">{{ $intentform->payee ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/2">
                            <div class="text-lg font-semibold mb-3">&nbsp;</div>
                            <div class="space-y-2">
                                <div class="flex">
                                    <div class="font-semibold w-1/3">ธนาคาร:</div>
                                    <div class="flex-1">{{ $intentform->account_bank ?? '-' }}</div>
                                </div>
                                <div class="flex">
                                    <div class="font-semibold w-1/3">อ้างอิง:</div>
                                    <div class="flex-1">{{ $intentform->refer ?? '-' }}</div>
                                </div>
                                <div class="flex">
                                    <div class="font-semibold w-1/3">มูลนิธิ:</div>
                                    <div class="flex-1">{{ $intentform->foundation ?? '-' }}</div>
                                </div>
                                <div class="flex">
                                    <div class="font-semibold w-1/3">ช่องทางการชำระ:</div>
                                    <div class="flex-1">{{ $intentform->payment_methods }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="text-lg font-semibold mb-3 px-4">รายการบริจาค</div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รายการ</th>
                                    <th>จำนวน</th>
                                    <th>ราคา</th>
                                    <th class="text-right">รวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($intentform->donations as $index => $donation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="font-semibold">{{ $donation->type->name ?? '-' }}</div>
                                            @if($donation->description)
                                                <div class="text-sm text-gray-500">{{ $donation->description }}</div>
                                            @endif
                                        </td>
                                        <td>{{ number_format($donation->quantity) }}</td>
                                        <td>{{ number_format($donation->type->price ?? 0, 2) }} บาท</td>
                                        <td class="text-right">{{ number_format($donation->sub_total, 2) }} บาท</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">ไม่มีรายการบริจาค</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <td colspan="4" class="text-right font-semibold">รวมทั้งสิ้น:</td>
                                    <td class="text-right font-semibold">{{ number_format($intentform->total, 2) }} บาท</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if($intentform->notes)
                    <div class="mt-8 px-4">
                        <div class="text-lg font-semibold mb-3">หมายเหตุ</div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded">
                            {{ $intentform->notes }}
                        </div>
                    </div>
                @endif

                <div class="mt-8 px-4">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('intentform') }}" class="btn btn-outline-primary">
                            กลับไปหน้ารายการ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
