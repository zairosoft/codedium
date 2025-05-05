@extends('layouts.layout')
@section('title', 'CRM Reports')
@section('style')
<style scoped>
    .report-card {
        transition: all 0.3s ease-in-out;
    }
    
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div x-data="reportsPage">
    <div class="panel p-0">
        <div class="flex flex-wrap justify-between items-center p-4 sm:p-6">
            <div>
                <h5 class="text-lg font-semibold dark:text-white-light">CRM Reports Dashboard</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View analytics and generate reports for your CRM data</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" class="form-input" x-model="dateRange" placeholder="Select Date Range" readonly @click="showDatepicker = !showDatepicker">
                    <div class="absolute top-full mt-1 z-10 w-auto right-0" x-show="showDatepicker" @click.away="showDatepicker = false">
                        <!-- Date picker would render here -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-md">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm">Start Date</label>
                                    <input type="date" class="form-input" x-model="startDate">
                                </div>
                                <div>
                                    <label class="text-sm">End Date</label>
                                    <input type="date" class="form-input" x-model="endDate">
                                </div>
                            </div>
                            <button class="btn btn-primary w-full mt-4" @click="applyDateRange">Apply</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path d="M19 7H5M19 7V18C19 19.1046 18.1046 20 17 20H7C5.89543 20 5 19.1046 5 18V7M19 7V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M9 11L15 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M12 15L12 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    Export Reports
                </button>
            </div>
        </div>
        
        <!-- Report Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 p-4 sm:p-6">
            <div class="panel report-card bg-primary/10 dark:bg-primary/20">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-semibold">Total Leads</h5>
                        <p class="text-3xl font-bold text-primary mt-2">127</p>
                    </div>
                    <div class="p-3 rounded-full bg-primary/20 dark:bg-primary/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary">
                            <path opacity="0.5" d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 10C8 8.89543 8.89543 8 10 8H14C15.1046 8 16 8.89543 16 10V10C16 11.1046 15.1046 12 14 12H10C8.89543 12 8 11.1046 8 10V10Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 16C8 14.8954 8.89543 14 10 14H14C15.1046 14 16 14.8954 16 16V16C16 17.1046 15.1046 18 14 18H10C8.89543 18 8 17.1046 8 16V16Z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="badge bg-primary/20 text-primary px-2.5 py-1 rounded">+25% from last month</span>
                </div>
            </div>
            
            <div class="panel report-card bg-success/10 dark:bg-success/20">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-semibold">Conversion Rate</h5>
                        <p class="text-3xl font-bold text-success mt-2">42%</p>
                    </div>
                    <div class="p-3 rounded-full bg-success/20 dark:bg-success/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-success">
                            <path opacity="0.5" d="M19.4768 5.56397C17.4626 3.55019 15.1222 2.45858 12.4896 2.48419C9.85716 2.48419 7.51673 3.55019 5.52924 5.53835C3.51604 7.55213 2.42468 9.91695 2.45022 12.5499C2.45022 15.1826 3.51604 17.5229 5.50371 19.5111C7.51673 21.525 9.85716 22.6165 12.4896 22.5909C15.1478 22.5909 17.4882 21.525 19.5014 19.5111C21.4891 17.4973 22.5805 15.1571 22.5549 12.5243C22.5549 9.89133 21.489 7.55213 19.4768 5.56397Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M7.5 12H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M16.5 12L13.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M16.5 12L13.5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="badge bg-success/20 text-success px-2.5 py-1 rounded">+5% from last month</span>
                </div>
            </div>
            
            <div class="panel report-card bg-warning/10 dark:bg-warning/20">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-semibold">Open Deals</h5>
                        <p class="text-3xl font-bold text-warning mt-2">36</p>
                    </div>
                    <div class="p-3 rounded-full bg-warning/20 dark:bg-warning/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-warning">
                            <path opacity="0.5" d="M5 8.51464C5 4.9167 8.13401 2 12 2C15.866 2 19 4.9167 19 8.51464C19 12.0844 16.7658 16.2499 13.2801 17.7396C12.4675 18.0868 11.5325 18.0868 10.7199 17.7396C7.23416 16.2499 5 12.0844 5 8.51464Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M14 9C14 10.1046 13.1046 11 12 11C10.8954 11 10 10.1046 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M12 22V17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="badge bg-warning/20 text-warning px-2.5 py-1 rounded">$563,290 Total Value</span>
                </div>
            </div>
            
            <div class="panel report-card bg-info/10 dark:bg-info/20">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-semibold">Upcoming Activities</h5>
                        <p class="text-3xl font-bold text-info mt-2">18</p>
                    </div>
                    <div class="p-3 rounded-full bg-info/20 dark:bg-info/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-info">
                            <path opacity="0.5" d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M7 5.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M7 9.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M7 13.5H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="badge bg-info/20 text-info px-2.5 py-1 rounded">8 Due Today</span>
                </div>
            </div>
        </div>
        
        <!-- Report Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4 sm:p-6">
            <!-- Sales Pipeline -->
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Sales Pipeline</h5>
                    <div class="dropdown">
                        <a href="javascript:;" class="text-gray-600 dark:text-gray-400 hover:text-primary">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="h-80">
                    <!-- Chart would render here -->
                    <div class="h-full flex items-center justify-center">
                        <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 dark:text-gray-600">
                            <path opacity="0.5" d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M7 14.5L9.5 12M9.5 12L7 9.5M9.5 12L16.5 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-primary inline-block mr-2"></span>
                        <span class="text-gray-600 dark:text-gray-400">Qualified</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-warning inline-block mr-2"></span>
                        <span class="text-gray-600 dark:text-gray-400">Proposal</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-info inline-block mr-2"></span>
                        <span class="text-gray-600 dark:text-gray-400">Negotiation</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-success inline-block mr-2"></span>
                        <span class="text-gray-600 dark:text-gray-400">Closed Won</span>
                    </div>
                </div>
            </div>
            
            <!-- Lead Sources -->
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Lead Sources</h5>
                    <div class="dropdown">
                        <a href="javascript:;" class="text-gray-600 dark:text-gray-400 hover:text-primary">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="h-80">
                    <!-- Chart would render here -->
                    <div class="h-full flex items-center justify-center">
                        <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 dark:text-gray-600">
                            <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-5">
                    <div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-primary inline-block mr-2"></span>
                            <span class="text-gray-600 dark:text-gray-400">Website (32%)</span>
                        </div>
                        <div class="flex items-center mt-2">
                            <span class="w-3 h-3 rounded-full bg-info inline-block mr-2"></span>
                            <span class="text-gray-600 dark:text-gray-400">Social Media (24%)</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-success inline-block mr-2"></span>
                            <span class="text-gray-600 dark:text-gray-400">Referral (28%)</span>
                        </div>
                        <div class="flex items-center mt-2">
                            <span class="w-3 h-3 rounded-full bg-warning inline-block mr-2"></span>
                            <span class="text-gray-600 dark:text-gray-400">Other (16%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-4 sm:p-6">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Recent Deals</h5>
                    <a href="{{ route('crm.reports.deals') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Deal Name</th>
                                <th>Contact</th>
                                <th>Amount</th>
                                <th>Stage</th>
                                <th>Expected Close</th>
                                <th>Probability</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Software Implementation</td>
                                <td>John Doe</td>
                                <td class="text-success font-semibold">$15,000</td>
                                <td><span class="badge bg-warning text-white">Proposal</span></td>
                                <td>Jun 15, 2025</td>
                                <td class="text-warning">70%</td>
                            </tr>
                            <tr>
                                <td>Consulting Services</td>
                                <td>Jane Smith</td>
                                <td class="text-success font-semibold">$8,500</td>
                                <td><span class="badge bg-info text-white">Negotiation</span></td>
                                <td>May 30, 2025</td>
                                <td class="text-success">85%</td>
                            </tr>
                            <tr>
                                <td>Hardware Upgrade</td>
                                <td>Michael Brown</td>
                                <td class="text-success font-semibold">$22,000</td>
                                <td><span class="badge bg-primary text-white">Qualified</span></td>
                                <td>Jul 10, 2025</td>
                                <td class="text-primary">50%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("reportsPage", () => ({
            showDatepicker: false,
            dateRange: 'Last 30 Days',
            startDate: '',
            endDate: '',
            
            init() {
                // Set default dates (last 30 days)
                const today = new Date();
                const thirtyDaysAgo = new Date(today);
                thirtyDaysAgo.setDate(today.getDate() - 30);
                
                this.startDate = this.formatDate(thirtyDaysAgo);
                this.endDate = this.formatDate(today);
            },
            
            formatDate(date) {
                return date.toISOString().split('T')[0]; // YYYY-MM-DD format
            },
            
            applyDateRange() {
                this.dateRange = `${this.startDate} - ${this.endDate}`;
                this.showDatepicker = false;
                
                // Here you would typically trigger a reload of the report data
                // based on the new date range
            }
        }));
    });
</script>
@endsection
