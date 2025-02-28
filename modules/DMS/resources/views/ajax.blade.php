<div class="mb-6 grid gap-6 lg:grid-cols-3">
    <div class="h-full p-0">
        @foreach ($alarms as $key => $value)
        <div class="mb-3">
            <div class="mb-3">
                <div class="flex">
                    <div class="mt-0.5 text-primary ltr:mr-2 rtl:ml-2.5">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                            <path d="M4 10C4 6.22876 4 4.34315 5.17157 3.17157C6.34315 2 8.22876 2 12 2C15.7712 2 17.6569 2 18.8284 3.17157C20 4.34315 20 6.22876 20 10V12C20 15.7712 20 17.6569 18.8284 18.8284C17.6569 20 15.7712 20 12 20C8.22876 20 6.34315 20 5.17157 18.8284C4 17.6569 4 15.7712 4 12V10Z" stroke="currentColor" stroke-width="1.5"></path>
                            <path opacity="0.5" d="M4 13H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.5 16H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M7 16H8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path opacity="0.5" d="M6 19.5V21C6 21.5523 6.44772 22 7 22H8.5C9.05228 22 9.5 21.5523 9.5 21V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path opacity="0.5" d="M18 19.5V21C18 21.5523 17.5523 22 17 22H15.5C14.9477 22 14.5 21.5523 14.5 21V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path opacity="0.5" d="M20 9H21C21.5523 9 22 9.44772 22 10V11C22 11.3148 21.8518 11.6111 21.6 11.8L20 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path opacity="0.5" d="M4 9H3C2.44772 9 2 9.44772 2 10V11C2 11.3148 2.14819 11.6111 2.4 11.8L4 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path opacity="0.5" d="M19.5 5H4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </div>
                    <div class="flex-1 font-semibold">
                    <h6 class="mb-1 mt-1">{{ $value->device_id }} : {{ $value->type }}</h6>
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="mt-0.5 text-primary ltr:mr-2 rtl:ml-2.5">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4">
                            <circle cx="12" cy="6" r="4" stroke="#e7515a" stroke-width="1.5"/>
                            <path opacity="0.5" d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" stroke="#f7737b" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-xs mb-1 mt-1">{{ $dms[0]->name }}</div>
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="mt-0.5 text-primary ltr:mr-2 rtl:ml-2.5">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4">
                            <circle opacity="0.5" cx="12" cy="12" r="10" stroke="#00ab55" stroke-width="1.5"/>
                            <path d="M12 8V12L14.5 14.5" stroke="#08d76f" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-xs mb-1 mt-1">{{ date('d-m-Y H:i:s', strtotime($value->created_at)) }}</div>
                    </div>
                </div>
            </div>
            <img src="{{ asset('modules/dms/images/alarms/' . $value->img) }}" width="100%" alt="{{ $value->img }}">
        </div>
        @endforeach
    </div>
    <div class="h-full p-0 lg:col-span-2">
        <div class="map_canvas relative">
            <iframe class="map no_scroll" width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ $alarms[0]->latitude }},{{ $alarms[0]->longitude }}&z=15&output=embed"></iframe>
        </div>
    </div>
</div>
<style>
    iframe {
        display: block;
        border: none;
        height: 80vh;
    }
</style>
