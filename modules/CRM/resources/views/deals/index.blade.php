@extends('layouts.layout')
@section('title', 'Deals Management')
@section('style')
<style scoped>
    /* range picker */
    input[type=range] {
        -webkit-appearance: none;
    }

    input[type=range]::-webkit-slider-runnable-track {
        width: 100%;
        height: 8px;
        background: #dee2e6;
        border: none;
        border-radius: 3px;
    }

    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        border: none;
        height: 16px;
        width: 16px;
        border-radius: 50%;
        background: #4361ee;
        margin-top: -4px;
    }

    .dark input[type=range]::-webkit-slider-runnable-track {
        background: #1b2e4b;
    }

    .dark input[type=range] {
        background-color: transparent;
    }

    input[type=range]:focus {
        outline: none;
    }

    input[type=range]:active::-webkit-slider-thumb {
        background: #4361eec2;
        cursor: pointer;
    }

    @media only screen and (max-width: 639px) {
        .crm-table .dataTable-search,
        .crm-table .dataTable-search .dataTable-input {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div x-data="dealsList">    
    <div class="flex gap-5 relative xl:h-[calc(100vh_-_150px)] h-full sm:min-h-0" :class="{'min-h-[999px]' : isShowSidebar}">
        <!-- Sidebar Filters -->
        <div class="perfect-scrollbar panel p-4 flex-none overflow-x-hidden max-w-xs w-full absolute xl:relative z-10 space-y-4 h-full hidden xl:block" :class="isShowSidebar && '!block'">
            <div class="flex items-center justify-between">
                <h4 class="text-xl font-semibold">Filters</h4>
                <button type="button" class="text-primary font-medium">Clear All</button>
            </div>
            <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
            
            <!-- Deal Stage Filter -->
            <div>
                <h6 class="text-base font-bold pb-5">Deal Stage</h6>
                <ul class="space-y-1.5">
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="qualified" class="form-checkbox" />
                            <label for="qualified" class="mb-0 cursor-pointer">Qualified</label>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="proposal" class="form-checkbox" />
                            <label for="proposal" class="mb-0 cursor-pointer">Proposal</label>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="negotiation" class="form-checkbox" />
                            <label for="negotiation" class="mb-0 cursor-pointer">Negotiation</label>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="closed-won" class="form-checkbox" />
                            <label for="closed-won" class="mb-0 cursor-pointer">Closed Won</label>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="closed-lost" class="form-checkbox" />
                            <label for="closed-lost" class="mb-0 cursor-pointer">Closed Lost</label>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
            
            <!-- Deal Value Filter -->
            <div>
                <h6 class="text-base font-bold pb-5">Deal Value</h6>
                <div>
                    <input type="range" class="w-full py-2.5" min="0" max="100" x-model="valueSlider" />
                    <div class="font-bold"> 
                        <span x-text="valueSlider + 'K'" class="inline-block py-1 px-2 rounded text-primary border border-white-light dark:border-dark"></span> 
                        <span>+</span>
                    </div>
                </div>
            </div>
            
            <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
            
            <!-- Probability Filter -->
            <div>
                <h6 class="text-base font-bold pb-5">Probability</h6>
                <div>
                    <input type="range" class="w-full py-2.5" min="0" max="100" x-model="probSlider" />
                    <div class="font-bold"> 
                        <span x-text="probSlider + '%'" class="inline-block py-1 px-2 rounded text-primary border border-white-light dark:border-dark"></span> 
                        <span>+</span>
                    </div>
                </div>
            </div>
            
            <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
            
            <!-- Expected Close Date -->
            <div>
                <h6 class="text-base font-bold pb-5">Expected Close Date</h6>
                <ul class="space-y-1.5">
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="this-month" class="form-checkbox" />
                            <label for="this-month" class="mb-0 cursor-pointer">This Month</label>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="next-month" class="form-checkbox" />
                            <label for="next-month" class="mb-0 cursor-pointer">Next Month</label>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="this-quarter" class="form-checkbox" />
                            <label for="this-quarter" class="mb-0 cursor-pointer">This Quarter</label>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:" class="inline-flex items-center gap-1">
                            <input type="checkbox" id="next-quarter" class="form-checkbox" />
                            <label for="next-quarter" class="mb-0 cursor-pointer">Next Quarter</label>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-black/60 z-[5] w-full h-full absolute rounded-md hidden" :class="isShowSidebar && '!block xl:!hidden'" @click="isShowSidebar = !isShowSidebar"></div>
        
        <!-- Main Content -->
        <div class="panel flex-1 overflow-auto">
            <div class="sm:absolute sm:top-5 ltr:sm:left-5 rtl:sm:right-5 mb-5">
                <div class="flex items-center justify-between relative gap-4">
                    <div class="flex items-center">
                        <button type="button" class="xl:hidden hover:text-primary block ltr:mr-3 rtl:ml-3" @click="isShowSidebar = !isShowSidebar">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </button>
                        <div class="relative border border-white-dark/30 dark:border-[#1b2e4b] rounded-md p-3">
                            <input type="text" placeholder="Search Deals..." class="form-input peer !border-0 !bg-transparent !shadow-none ps-8 !py-0 !h-5" />
                            <div class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 peer-focus:text-primary">
                                <svg class="peer-focus:text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                                    <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center">
                        <a href="{{ route('crm.deals.create') }}" class="btn btn-primary">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Add Deal
                        </a>
                    </div>
                </div>
            </div>
            <div class="crm-table pt-14 relative">
                <table id="dealsTable" class="whitespace-nowrap">
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/simple-datatables.js')}}"></script>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("dealsList", () => ({
            valueSlider: '10',
            probSlider: '50',
            isShowSidebar: false,
            selectedRows: [],
            deals: [],

            init() {
                this.initializeTable();
                this.loadDeals();
            },

            async loadDeals() {
                try {
                    const response = await fetch('{{ route("crm.api.deals") }}');
                    const result = await response.json();
                    
                    if (result.success) {
                        this.deals = result.data;
                        this.setTableData();
                    }
                } catch (error) {
                    console.error('Error loading deals:', error);
                }
            },

            initializeTable() {
                this.datatable = new simpleDatatables.DataTable('#dealsTable', {
                    data: {
                        headings: [
                            'ID',
                            "Deal Name",
                            "Contact",
                            "Amount",
                            "Stage",
                            "Expected Close Date",
                            "Probability",
                            "Assigned To",
                            "Actions",
                        ],
                        data: [],
                    },
                    perPage: 10,
                    perPageSelect: [10, 20, 30, 50],
                    columns: [
                        {
                            select: 1,
                            render: function(data, cell, row) {
                                return `<div class="flex items-center gap-2">
                                    <div class="p-2 bg-success/10 rounded-full dark:bg-success/20">
                                        <span class="font-bold text-success text-xl">${data.charAt(0)}</span>
                                    </div>
                                    <div>
                                        <a href="/crm/deals/${row.cells[0].data}" class="font-semibold text-primary hover:underline">${data}</a>
                                    </div>
                                </div>`;
                            },
                        },
                        {
                            select: 3,
                            render: function(data, cell, row) {
                                return `<span class="font-semibold text-success">$${Number(data).toLocaleString()}</span>`;
                            },
                        },
                        {
                            select: 4,
                            render: function(data, cell, row) {
                                let stageClass = '';
                                if (data === 'Qualified') stageClass = 'bg-info/10 text-info dark:bg-info/20';
                                else if (data === 'Proposal') stageClass = 'bg-warning/10 text-warning dark:bg-warning/20';
                                else if (data === 'Negotiation') stageClass = 'bg-primary/10 text-primary dark:bg-primary/20';
                                else if (data === 'Closed Won') stageClass = 'bg-success/10 text-success dark:bg-success/20';
                                else if (data === 'Closed Lost') stageClass = 'bg-danger/10 text-danger dark:bg-danger/20';
                                
                                return `<span class="px-2.5 py-0.5 rounded text-xs ${stageClass}">${data}</span>`;
                            },
                        },
                        {
                            select: 6,
                            render: function(data, cell, row) {
                                let probClass = '';
                                if (data >= 80) probClass = 'text-success';
                                else if (data >= 60) probClass = 'text-warning';
                                else if (data >= 40) probClass = 'text-info';
                                else probClass = 'text-danger';
                                
                                return `<span class="font-semibold ${probClass}">${data}%</span>`;
                            },
                        },
                        {
                            select: 8,
                            sortable: false,
                            render: function(data, cell, row) {
                                return `<ul class="flex gap-2">
                                    <li>
                                        <a href="/crm/deals/${row.cells[0].data}" class="hover:text-info" title="View">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                                                <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/crm/deals/${row.cells[0].data}/edit" class="hover:text-primary" title="Edit">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                <path opacity="0.5" d="M22 14.5V11.5C22 9.9 21.1 9 19.5 9H14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M18 2L18 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M18 5L20 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M18 5L16 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="currentColor" stroke-width="1.5"></path>
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="hover:text-danger" title="Delete" @click="deleteDeal(${row.cells[0].data})">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path opacity="0.5" d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="currentColor" stroke-width="1.5"></path>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>`;
                            },
                        },
                    ],
                    firstLast: true,
                    firstText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    lastText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    prevText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    nextText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    labels: {
                        perPage: '{select}',
                    },
                    layout: {
                        top: '{search}',
                        bottom: '{info}{select}{pager}',
                    },
                });
            },

            setTableData() {
                let dataArr = [];
                for (let i = 0; i < this.deals.length; i++) {
                    dataArr.push([
                        this.deals[i].id,
                        this.deals[i].name,
                        this.deals[i].contact,
                        this.deals[i].amount,
                        this.deals[i].stage,
                        this.deals[i].expected_close_date,
                        this.deals[i].probability,
                        this.deals[i].assigned_to,
                        ''
                    ]);
                }
                this.datatable.insert({ data: dataArr });
            },

            deleteDeal(id) {
                if (confirm('Are you sure you want to delete this deal?')) {
                    // Send delete request to server
                    fetch(`/crm/deals/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove from table
                            this.deals = this.deals.filter(deal => deal.id !== id);
                            this.datatable.refresh();
                            this.setTableData();
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting deal:', error);
                    });
                }
            }
        }));
    });
</script>
@endsection
