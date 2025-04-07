<div>
    <ul>
        <li class="menu nav-item">
            <a href="{{ route('inventory.overview') }}" class="nav-link group {{ request()->routeIs('inventory.overview') ? 'active' : '' }}">
                <div class="flex items-center">
                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{ __('Overview') }}</span>
                </div>
            </a>
        </li>

        <li class="menu nav-item">
            <a href="{{ route('inventory.index') }}" class="nav-link group {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                <div class="flex items-center">
                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.42229 20.6181C10.1779 21.5395 11.0557 22.0001 12 22.0001V12.0001L2.63802 7.07275C2.62423 7.09491 2.6107 7.11727 2.5974 7.13986C2 8.15436 2 9.41678 2 11.9416V12.0586C2 14.5834 2 15.8459 2.5974 16.8604C3.19479 17.8749 4.27063 18.4395 6.42229 19.5686L8.42229 20.6181Z" fill="currentColor"></path>
                        <path opacity="0.7" d="M17.5774 4.43152L15.5774 3.38197C13.8218 2.46066 12.944 2 11.9997 2C11.0554 2 10.1776 2.46066 8.42197 3.38197L6.42197 4.43152C4.31821 5.53552 3.24291 6.09982 2.6377 7.07264L11.9997 12L21.3617 7.07264C20.7564 6.09982 19.6811 5.53552 17.5774 4.43152Z" fill="currentColor"></path>
                        <path opacity="0.5" d="M21.4026 7.13986C21.3893 7.11727 21.3758 7.09491 21.362 7.07275L12 12.0001V22.0001C12.9443 22.0001 13.8221 21.5395 15.5777 20.6181L17.5777 19.5686C19.7294 18.4395 20.8052 17.8749 21.4026 16.8604C22 15.8459 22 14.5834 22 12.0586V11.9416C22 9.41678 22 8.15436 21.4026 7.13986Z" fill="currentColor"></path>
                    </svg>
                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{ __('Products') }}</span>
                </div>
            </a>
        </li>

        <li class="menu nav-item">
            <a href="{{ route('category.index') }}" class="nav-link group {{ request()->routeIs('category.index') ? 'active' : '' }}">
                <div class="flex items-center">
                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5" d="M13 15.5C13 14.3954 13.8954 13.5 15 13.5H20V19.5H15C13.8954 19.5 13 18.6046 13 17.5V15.5Z" stroke="currentColor" stroke-width="1.5" />
                        <path opacity="0.5" d="M4 11.5C4 10.3954 4.89543 9.5 6 9.5H11V15.5H6C4.89543 15.5 4 14.6046 4 13.5V11.5Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M4 5.5C4 4.39543 4.89543 3.5 6 3.5H9.5H11C12.1046 3.5 13 4.39543 13 5.5V9.5H6C4.89543 9.5 4 8.60457 4 7.5V5.5Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M13 5.5C13 4.39543 13.8954 3.5 15 3.5H18C19.1046 3.5 20 4.39543 20 5.5V7.5C20 8.60457 19.1046 9.5 18 9.5H13V5.5Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M13 17.5C13 18.6046 13.8954 19.5 15 19.5H18C19.1046 19.5 20 18.6046 20 17.5V15.5H15C13.8954 15.5 13 16.3954 13 17.5Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M11 15.5H6C4.89543 15.5 4 16.3954 4 17.5V19.5C4 20.6046 4.89543 21.5 6 21.5H11C12.1046 21.5 13 20.6046 13 19.5V15.5H11Z" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{ __('Categories') }}</span>
                </div>
            </a>
        </li>

        <li class="menu nav-item">
            <a href="{{ route('inventory.reports') }}" class="nav-link group {{ request()->routeIs('inventory.reports') ? 'active' : '' }}">
                <div class="flex items-center">
                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5" d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path d="M16.5 14.5C16.5 15.3284 15.8284 16 15 16C14.1716 16 13.5 15.3284 13.5 14.5C13.5 13.6716 14.1716 13 15 13C15.8284 13 16.5 13.6716 16.5 14.5Z" fill="currentColor"></path>
                        <path d="M6.5 9.5H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path d="M6.5 14.5H10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{ __('Reports') }}</span>
                </div>
            </a>
        </li>
    </ul>
</div>
