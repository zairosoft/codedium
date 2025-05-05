@extends('layouts.layout')
@section('title', 'CRM Settings')
@section('style')
<style scoped>
    .settings-tab.active {
        border-left: 3px solid #4361ee;
        background-color: rgba(67, 97, 238, 0.1);
    }
    
    .dark .settings-tab.active {
        background-color: rgba(67, 97, 238, 0.2);
    }
</style>
@endsection

@section('content')
<div x-data="settingsPage">
    <div class="panel">
        <div class="flex flex-wrap justify-between items-center p-4 sm:p-6 border-b border-[#ebedf2] dark:border-[#191e3a]">
            <div>
                <h5 class="text-lg font-semibold dark:text-white-light">CRM Settings</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure your CRM system settings</p>
            </div>
        </div>
        
        <div class="grid grid-cols-12 gap-5 p-4 sm:p-6">
            <!-- Settings Tabs -->
            <div class="col-span-12 md:col-span-3">
                <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md overflow-hidden">
                    <div 
                        class="settings-tab p-4 cursor-pointer flex items-center" 
                        :class="{'active': activeTab === 'general'}" 
                        @click="activeTab = 'general'"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2">
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M13.7654 2.15224C13.3978 2 12.9319 2 12 2C11.0681 2 10.6022 2 10.2346 2.15224C9.74457 2.35523 9.35522 2.74458 9.15223 3.23463C9.05957 3.45834 9.0233 3.7185 9.00911 4.09799C8.98826 4.65568 8.70226 5.17189 8.21894 5.45093C7.73564 5.72996 7.14559 5.71954 6.65219 5.45876C6.31645 5.2813 6.07301 5.18262 5.83294 5.15102C5.30704 5.08178 4.77518 5.22429 4.35436 5.5472C4.03874 5.78938 3.80577 6.1929 3.33983 6.99993C2.87389 7.80697 2.64092 8.21048 2.58899 8.60491C2.51976 9.1308 2.66227 9.66266 2.98518 10.0835C3.13256 10.2756 3.3397 10.437 3.66119 10.639C4.1338 10.936 4.43789 11.4419 4.43786 12C4.43783 12.5581 4.13375 13.0639 3.66118 13.3608C3.33965 13.5629 3.13248 13.7244 2.98508 13.9165C2.66217 14.3373 2.51966 14.8691 2.5889 15.395C2.64082 15.7894 2.87379 16.193 3.33973 17C3.80568 17.807 4.03865 18.2106 4.35426 18.4527C4.77508 18.7756 5.30694 18.9181 5.83284 18.8489C6.07289 18.8173 6.31632 18.7186 6.65204 18.5412C7.14547 18.2804 7.73556 18.27 8.2189 18.549C8.70224 18.8281 8.98826 19.3443 9.00911 19.9021C9.02331 20.2815 9.05957 20.5417 9.15223 20.7654C9.35522 21.2554 9.74457 21.6448 10.2346 21.8478C10.6022 22 11.0681 22 12 22C12.9319 22 13.3978 22 13.7654 21.8478C14.2554 21.6448 14.6448 21.2554 14.8477 20.7654C14.9404 20.5417 14.9767 20.2815 14.9909 19.902C15.0117 19.3443 15.2977 18.8281 15.781 18.549C16.2643 18.2699 16.8544 18.2804 17.3479 18.5412C17.6836 18.7186 17.927 18.8172 18.167 18.8488C18.6929 18.9181 19.2248 18.7756 19.6456 18.4527C19.9612 18.2105 20.1942 17.807 20.6601 16.9999C21.1261 16.1929 21.3591 15.7894 21.411 15.395C21.4802 14.8691 21.3377 14.3372 21.0148 13.9164C20.8674 13.7243 20.6602 13.5628 20.3387 13.3608C19.8662 13.0639 19.5621 12.558 19.5621 11.9999C19.5621 11.4418 19.8662 10.9361 20.3387 10.6392C20.6603 10.4371 20.8675 10.2757 21.0149 10.0835C21.3378 9.66273 21.4803 9.13087 21.4111 8.60497C21.3592 8.21055 21.1262 7.80703 20.6602 7C20.1943 6.19297 19.9613 5.78945 19.6457 5.54727C19.2249 5.22436 18.693 5.08185 18.1671 5.15109C17.9271 5.18269 17.6837 5.28136 17.3479 5.4588C16.8545 5.71959 16.2644 5.73002 15.7811 5.45096C15.2977 5.17191 15.0117 4.65566 14.9909 4.09794C14.9767 3.71848 14.9404 3.45833 14.8477 3.23463C14.6448 2.74458 14.2554 2.35523 13.7654 2.15224Z" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        General
                    </div>
                    <div 
                        class="settings-tab p-4 cursor-pointer flex items-center" 
                        :class="{'active': activeTab === 'users'}"
                        @click="activeTab = 'users'"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2">
                            <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        Users & Teams
                    </div>
                    <div 
                        class="settings-tab p-4 cursor-pointer flex items-center" 
                        :class="{'active': activeTab === 'custom-fields'}"
                        @click="activeTab = 'custom-fields'"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2">
                            <path d="M4.02693 18.329C4.18385 19.277 5.0075 20 6 20H18C19.1046 20 20 19.1046 20 18V14M4.02693 18.329C4.00922 18.2191 4 18.1103 4 18V6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V14M4.02693 18.329L7.96983 14.1542C8.30233 13.7983 8.81366 13.6396 9.30138 13.7485L11.9381 14.3721C12.0608 14.3974 12.1867 14.4106 12.3128 14.4106H15.5C16.3284 14.4106 17 13.739 17 12.9106V12.5C17 11.6716 16.3284 11 15.5 11H12.5C12.2239 11 12 10.7761 12 10.5V10.0894C12 9.26103 12.6716 8.58942 13.5 8.58942H16.5C17.3284 8.58942 18 7.91782 18 7.08942V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Custom Fields
                    </div>
                    <div 
                        class="settings-tab p-4 cursor-pointer flex items-center" 
                        :class="{'active': activeTab === 'workflow'}"
                        @click="activeTab = 'workflow'"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2">
                            <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="currentColor" stroke-width="1.5" />
                            <path d="M9 10C9 8.89543 9.89543 8 11 8H13C14.1046 8 15 8.89543 15 10V10C15 11.1046 14.1046 12 13 12H11C9.89543 12 9 12.8954 9 14V14C9 15.1046 9.89543 16 11 16H13C14.1046 16 15 15.1046 15 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Workflow Automation
                    </div>
                    <div 
                        class="settings-tab p-4 cursor-pointer flex items-center" 
                        :class="{'active': activeTab === 'integrations'}"
                        @click="activeTab = 'integrations'"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2">
                            <path d="M13.4286 10.0001L20 10.0001M15.8571 15.0001L20 15.0001M2 19.0001H20M2 5.00006H20M4 14.0001L7.42857 14.0001" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M8.42857 10.0001H9.14286M10.5714 10.0001H11.2857M4 10.0001H4.71429M6.14286 10.0001H6.85714" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Integrations
                    </div>
                    <div 
                        class="settings-tab p-4 cursor-pointer flex items-center" 
                        :class="{'active': activeTab === 'email'}"
                        @click="activeTab = 'email'"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2">
                            <path d="M12 18C8.68629 18 6 15.3137 6 12C6 8.68629 8.68629 6 12 6C15.3137 6 18 8.68629 18 12C18 12.7215 17.8726 13.4133 17.6392 14.054C17.5551 14.285 17.4075 14.4861 17.2268 14.6527L17.1463 14.727C16.591 15.2392 15.7573 15.3049 15.1288 14.8866C14.6735 14.5708 14.4 14.0588 14.4 13.5V12C14.4 11.1163 13.6837 10.4 12.8 10.4H12C11.1163 10.4 10.4 11.1163 10.4 12C10.4 12.8837 11.1163 13.6 12 13.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        Email Templates
                    </div>
                </div>
            </div>
            
            <!-- Settings Content -->
            <div class="col-span-12 md:col-span-9">
                <!-- General Settings -->
                <div x-show="activeTab === 'general'">
                    <form @submit.prevent="saveSettings">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12">
                                <h6 class="text-lg font-medium">Company Information</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">These details will be used in invoices and other documents.</p>
                            </div>
                            
                            <div class="col-span-12 lg:col-span-6">
                                <div class="mb-5">
                                    <label for="company_name">Company Name</label>
                                    <input id="company_name" type="text" class="form-input" x-model="settings.company_name" />
                                </div>
                            </div>
                            
                            <div class="col-span-12 lg:col-span-6">
                                <div class="mb-5">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-input" x-model="settings.email" />
                                </div>
                            </div>
                            
                            <div class="col-span-12 lg:col-span-6">
                                <div class="mb-5">
                                    <label for="phone">Phone</label>
                                    <input id="phone" type="text" class="form-input" x-model="settings.phone" />
                                </div>
                            </div>
                            
                            <div class="col-span-12 lg:col-span-6">
                                <div class="mb-5">
                                    <label for="website">Website</label>
                                    <input id="website" type="text" class="form-input" x-model="settings.website" />
                                </div>
                            </div>
                            
                            <div class="col-span-12">
                                <div class="mb-5">
                                    <label for="address">Address</label>
                                    <textarea id="address" class="form-textarea" rows="3" x-model="settings.address"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-span-12">
                                <div class="mb-5">
                                    <label for="logo">Company Logo</label>
                                    <div class="flex items-center gap-5">
                                        <div class="w-24 h-24 border border-gray-200 dark:border-gray-700 rounded flex items-center justify-center overflow-hidden bg-gray-100 dark:bg-gray-800">
                                            <img src="https://placehold.co/200x200?text=Logo" alt="Company Logo" class="max-w-full max-h-full" />
                                        </div>
                                        <div>
                                            <input id="logo" type="file" class="form-input" accept="image/*" />
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recommended size: 200x200px. Max file size: 2MB.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-span-12 mt-4">
                                <h6 class="text-lg font-medium">CRM Settings</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Configure your CRM preferences.</p>
                            </div>
                            
                            <div class="col-span-12 lg:col-span-6">
                                <div class="mb-5">
                                    <label>Lead Sources</label>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <template x-for="(source, index) in settings.lead_sources" :key="index">
                                            <div class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">
                                                <span x-text="source"></span>
                                                <button type="button" @click="removeLeadSource(index)" class="text-gray-500 hover:text-danger">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4">
                                                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                        <div class="inline-flex items-center">
                                            <input type="text" placeholder="Add new source" class="form-input py-1 px-2 h-auto" x-model="newLeadSource" @keydown.enter.prevent="addLeadSource" />
                                            <button type="button" @click="addLeadSource" class="btn btn-primary btn-sm ml-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4">
                                                    <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-span-12 lg:col-span-6">
                                <div class="mb-5">
                                    <label>Deal Stages</label>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <template x-for="(stage, index) in settings.deal_stages" :key="index">
                                            <div class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">
                                                <span x-text="stage"></span>
                                                <button type="button" @click="removeDealStage(index)" class="text-gray-500 hover:text-danger">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4">
                                                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                        <div class="inline-flex items-center">
                                            <input type="text" placeholder="Add new stage" class="form-input py-1 px-2 h-auto" x-model="newDealStage" @keydown.enter.prevent="addDealStage" />
                                            <button type="button" @click="addDealStage" class="btn btn-primary btn-sm ml-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4">
                                                    <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-span-12">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Other tab contents would go here -->
                <div x-show="activeTab !== 'general'" class="flex flex-col items-center justify-center py-10">
                    <svg width="160" height="160" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-gray-300 dark:text-gray-600 mb-5">
                        <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M13.25 8C13.25 8.69036 12.6904 9.25 12 9.25C11.3096 9.25 10.75 8.69036 10.75 8C10.75 7.30964 11.3096 6.75 12 6.75C12.6904 6.75 13.25 7.30964 13.25 8Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M13 12H11V16H13V12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h5 class="text-lg font-semibold dark:text-white-light">This section is under development</h5>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">We're working on adding more settings options. Check back soon!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("settingsPage", () => ({
            activeTab: 'general',
            newLeadSource: '',
            newDealStage: '',
            settings: {
                company_name: 'Your Company',
                email: 'contact@yourcompany.com',
                phone: '+1 (123) 456-7890',
                website: 'www.yourcompany.com',
                address: '123 Business Street, Suite 100, City, State, 12345',
                lead_sources: ['Website', 'Referral', 'Social Media', 'Trade Show', 'Email Campaign'],
                deal_stages: ['Qualified', 'Proposal', 'Negotiation', 'Closed Won', 'Closed Lost'],
                activity_types: ['Task', 'Call', 'Meeting', 'Email']
            },
            
            addLeadSource() {
                if (this.newLeadSource.trim()) {
                    this.settings.lead_sources.push(this.newLeadSource.trim());
                    this.newLeadSource = '';
                }
            },
            
            removeLeadSource(index) {
                this.settings.lead_sources.splice(index, 1);
            },
            
            addDealStage() {
                if (this.newDealStage.trim()) {
                    this.settings.deal_stages.push(this.newDealStage.trim());
                    this.newDealStage = '';
                }
            },
            
            removeDealStage(index) {
                this.settings.deal_stages.splice(index, 1);
            },
            
            saveSettings() {
                // Here you would typically send the settings to the server
                // This is a placeholder for the actual implementation
                console.log('Saving settings:', this.settings);
                
                // Show success notification
                // This would be replaced with your actual notification system
                alert('Settings saved successfully!');
            }
        }));
    });
</script>
@endsection
