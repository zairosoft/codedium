@extends('layouts.layout')
@section('title', 'รายการขาย')
@section('content')
<div x-data="invoiceList">
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
    <ul class="flex text-gray-500 dark:text-white-dark mb-5">
        <li>
            <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0">
                    <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5"></path>
                    <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </a>
        </li>
        <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/sales') }}">ฝ่ายขาย</a></li>
        <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">รายการขาย</a></li>
    </ul>
    <div class="panel min-h-screen">
        <div class="px-5">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex items-center gap-2 mb-5">
                    {{-- <button type="button" class="btn btn-danger gap-2" @click="deleteRow()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path opacity="0.5" d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                        Delete </button> --}}
                    <a href="{{ route('sales.create') }}" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        {{ __('others.add') }}</a>
                </div>
            </div>
        </div>
        <div class="invoice-table">
            <table id="myTable" class="whitespace-nowrap"></table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data('invoiceList', () => ({
            selectedRows: [],
            items: [
                @foreach ( $sellings as $sale)
                    {
                        id: {{ $sale->id }},
                        invoice: '{{ $sale->invoice }}',
                        customer_name: '{{ $sale->customer_name }}',
                        product_name: '{{ $sale->product_name }}',
                        date: '{{ $sale->date }}',
                        price: '{{ number_format((float)$sale->price, 2) }}',
                        status: '{{ $sale->status }}',
                    },
                @endforeach
            ],
            searchText: '',
            datatable: null,
            dataArr: [],
            init() {
                this.setTableData();
                this.initializeTable();
                this.$watch('items', value => {
                    this.datatable.destroy();
                    this.setTableData();
                    this.initializeTable();
                });
                this.$watch('selectedRows', value => {
                    this.datatable.destroy();
                    this.setTableData();
                    this.initializeTable();
                });
            },

            initializeTable() {
                this.datatable = new simpleDatatables.DataTable('#myTable', {
                    data: {
                        headings: [
                            'ลำดับ',
                            "เลขที่ใบแจ้งหนี้",
                            "ลูกค้า",
                            "สินค้า",
                            "วันที่",
                            "ราคา",
                            "สถานะ",
                        ],
                        data: this.dataArr
                    },
                    perPage: 20,
                    perPageSelect: [20, 30, 50, 100],
                    columns: [{
                            select: 0,
                            sortable: false,
                            render: function(data, cell, row) {
                                return data;
                            }
                        },
                        {
                            select: 1,
                            render: function(data, cell, row) {
                                return '<a href="javascript:;" class="text-primary underline font-semibold hover:no-underline">#' + data + '</a>';
                            }
                        },
                        {
                            select: 2,
                            render: function(data, cell, row) {
                                return `<div class="flex items-center font-semibold">${data}</div>`;
                            }
                        },
                        {
                            select: 5,
                            render: function(data, cell, row) {
                                return '<div class="font-semibold">฿' + data + '</div>';
                            }
                        },
                        {
                            select: 6,
                            render: function(data, cell, row) {
                                let styleClass = data == 'ออกแล้ว' ? 'badge-outline-success' : 'badge-outline-danger';
                                return '<span class="badge ' + styleClass + '">' + data + '</span>';
                            },
                        }
                    ],
                    firstLast: true,
                    firstText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    lastText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    prevText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    nextText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    labels: {
                        perPage: "<span class='ml-2'>{select}</span>",
                        noRows: "No data available",
                    },
                    layout: {
                        top: "{search}",
                        bottom: "{info}{select}{pager}",
                    },
                });
            },

            setTableData() {
                this.dataArr = [];
                for (let i = 0; i < this.items.length; i++) {
                    this.dataArr[i] = [];
                    for (let p in this.items[i]) {
                        if (this.items[i].hasOwnProperty(p)) {
                            this.dataArr[i].push(this.items[i][p]);
                        }
                    }
                }
            },

            searchInvoice() {
                return this.items.filter((d) =>
                    (d.invoice && d.invoice.toLowerCase().includes(this.searchText)) ||
                    (d.name && d.name.toLowerCase().includes(this.searchText)) ||
                    (d.email && d.email.toLowerCase().includes(this.searchText)) ||
                    (d.date && d.date.toLowerCase().includes(this.searchText)) ||
                    (d.amount && d.amount.toLowerCase().includes(this.searchText)) ||
                    (d.status && d.status.toLowerCase().includes(this.searchText))
                );
            },
        }));
    });
</script>
<script>
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
                title: 'เกิดข้อผิดพลาดบางประการ กรุณาตรวจสอบ Log Error',
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
