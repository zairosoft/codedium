@extends('layouts.layout')
@section('title', 'อนุโมทนาบัตร')
@section('style')
    <style>
        .btn-add {
            right: 230px;
        }

        table thead th:nth-child(1) {
            width: 5%;
        }

        table thead th:nth-child(2) {
            width: 10%;
        }

        table thead th:last-child {
            width: 8%;
        }
    </style>
@endsection
@section('content')
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
    <div x-data="sorting">
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
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">อนุโมทนาบัตร</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel min-h-screen">
            @can('intentform create')
                <div class="px-5">
                    <div class="md:absolute btn-add">
                        <div class="flex items-center">
                            <a href="{{ url('intentform/create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 ltr:mr-3 rtl:ml-3">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                {{ __('others.add') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
            <table id="myTable" class="whitespace-nowrap table-hover"></table>
        </div>
        @include('layouts.confirm')
    </div>
@endsection
@section('script')
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
            @endif
        }
        @if (\Session::has('success'))
            setTimeout(() => {
                window.onload = showAlert();
            }, "400");
        @endif
        document.addEventListener('alpine:init', () => {
            Alpine.data('sorting', () => ({
                selectedRows: [],
                items: [
                    @foreach ($intentforms as $key => $value)
                        {
                            no: {{ $key + 1 }},
                            name: '{{ $value->name }}',
                            number_runding: '{{ $value->volume }}/{{ $value->number }}',
                            payment_methods: '{{ $value->payment_methods }}',
                            total: '{{ $value->total }}',
                            status: '{{ $value->status == 1 ? "ใช้งาน" : "ไม่ใช้งาน" }}',
                            date: '{{ $value->date }}',
                            actions: {{ $value->id }}
                        },
                    @endforeach
                ],
                searchText: '',
                datatable: null,
                dataArr: [],
                itemID: null,
                isDeleteModal: false,
                init() {
                    this.setTableData();
                    this.initializeTable();
                    this.$watch('items', (value) => {
                        this.datatable.destroy();
                        this.setTableData();
                        this.initializeTable();
                    });
                    this.$watch('selectedRows', (value) => {
                        this.datatable.destroy();
                        this.setTableData();
                        this.initializeTable();
                    });
                },
                initializeTable() {
                    this.datatable = new simpleDatatables.DataTable('#myTable', {
                        data: {
                            headings: ["ลำดับ", "ชื่อ", "เล่มที่/เลขที่", "การชำระเงิน", "จำนวนเงินทั้งหมด", "สถานะ", "วันที่", "{{ __('others.view') }}@can('intentform update') / {{ __('others.edit') }}@endcan @can('intentform delete') / {{ __('others.delete') }}@endcan"],
                            data: this.dataArr,
                        },
                        perPage: 20,
                        perPageSelect: [20, 30, 50, 100],
                        columns: [{
                            select: 7,
                            sortable: false,
                            render: function(data, cell, row) {
                                return `<ul class="flex gap-2">
                                        <li><a href="{{ url('intentform') }}/${data}/show" x-tooltip="{{ __('others.view') }}"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary"> <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path> <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path> </svg></a></li>
                                        @can('intentform update')<li><a href="{{ url('intentform') }}/${data}/edit" x-tooltip="{{ __('others.edit') }}"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-success"> <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5"></path> <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5"></path> </svg> </a></li>@endcan
                                        @can('intentform delete')<li><button type="button" class="text-danger" @click="deleteConfirm(${data})" x-tooltip="{{ __('others.delete') }}"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-danger"> <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> <path opacity="0.5" d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="currentColor" stroke-width="1.5"></path></svg></button></li>@endcan
                                        </ul>`;
                            },
                        }, ],
                        firstLast: true,
                        firstText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        lastText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        prevText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        nextText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        labels: {
                            perPage: "<span class='ml-2'>{select}</span>",
                            noRows: '{{ __('header.notifications.no data available') }}',
                        },
                        layout: {
                            top: '{search}',
                            bottom: '{info}{select}{pager}',
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
                searchItems() {
                    return this.items.filter((d) => (d.name && d.name.toLowerCase().includes(this.searchText)));
                },
                deleteConfirm(item) {
                    setTimeout(() => {
                        this.itemID = item;
                        this.isDeleteModal = true;
                    });
                },
                deleteItem() {
                    const dataID = {
                        'id': this.itemID
                    };
                    fetch("{{ route('intentform.delete') }}", {
                        method: "DELETE",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': document.querySelector('input[name=_token]').value
                        },
                        body: JSON.stringify(dataID)
                    }).then((res) => res.json()).then((response) => {
                        this.showMessage(response);
                    });
                    if (this.itemID) {
                        if (this.itemID != 1) {
                            this.items = this.items.filter((d) => d.id != this.itemID);
                            this.selectedRows = [];
                        }
                    } else {
                        this.items = this.items.filter((d) => !this.selectedRows.includes(d.id));
                        this.selectedRows = [];
                    }
                    this.isDeleteModal = false;
                    this.searchItems();
                },
                showMessage(response) {
                    if (response['type'] == 'success') {
                        const toast = window.Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 4000,
                            padding: '2em',
                        });
                        toast.fire({
                            icon: 'success',
                            title: response['message'],
                            padding: '2em',
                        });
                    } else {
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
                            title: response['message'],
                            padding: '2em',
                        });
                    }
                },
            }));
        });
    </script>
@endsection
