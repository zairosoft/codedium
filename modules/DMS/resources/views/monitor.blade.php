@extends('layouts.layout')
@section('title', 'ติดตามอุปกรณ์')
@section('style')
<style>
    @media (min-width: 640px) {
        .sm\:h-\[calc\(100vh_-_357px\)\] {
            height: calc(100vh - 300px);
        }
    }
    .mr-auto.ml-auto {
        margin-right: auto;
        margin-left: auto;
    }
</style>
@endsection
@section('content')
<div>
    <ul class="mb-5 flex text-gray-500 dark:text-white-dark">
        <li>
            <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 shrink-0">
                    <path opacity="0.5"
                        d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </a>
        </li>
        <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/dms') }}">DMS</a></li>
        <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">ติดตาม</a></li>
    </ul>
    <div>
        <div class="relative flex h-full gap-5 sm:h-[calc(100vh_-_150px)] sm:min-h-0">
            <div class="panel absolute z-10 hidden w-full max-w-xs flex-none space-y-4 overflow-hidden p-4 xl:relative xl:block xl:h-full">
                <div class="relative">
                    <input type="text" class="peer form-input ltr:pr-9 rtl:pl-9" placeholder="ค้นหา..." />
                    <div class="absolute top-1/2 -translate-y-1/2 peer-focus:text-primary ltr:right-2 rtl:left-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <button class="group">
                        <div class="h-5 w-5 rounded-full bg-success mb-2 mr-auto ml-auto"></div>
                        On-line
                    </button>
                    <button class="group">
                        <div class="h-5 w-5 rounded-full bg-dark mb-2 mr-auto ml-auto"></div>
                        Off-line
                    </button>
                    <button class="group">
                        <div class="h-5 w-5 rounded-full bg-danger mb-2 mr-auto ml-auto"></div>
                        Alarm
                    </button>
                </div>
                <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                <div class="!mt-0">
                    <div class="chat-users perfect-scrollbar relative -mr-3.5 h-full min-h-[100px] space-y-0.5 pr-3.5 sm:h-[calc(100vh_-_357px)]">
                        <div class="flex flex-col rounded-md">
                            @foreach ($dms as $key => $value)
                            <div class="cursor-pointer flex border-b border-[#e0e6ed] px-4 py-2.5 hover:bg-[#eee] dark:border-[#1b2e4b] dark:hover:bg-[#eee]/10" onclick="openMonitor('{{$value->device_id}}')">
                                <div class="mt-0.5 text-primary ltr:mr-2 rtl:ml-2.5">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                        <path d="M4 10C4 6.22876 4 4.34315 5.17157 3.17157C6.34315 2 8.22876 2 12 2C15.7712 2 17.6569 2 18.8284 3.17157C20 4.34315 20 6.22876 20 10V12C20 15.7712 20 17.6569 18.8284 18.8284C17.6569 20 15.7712 20 12 20C8.22876 20 6.34315 20 5.17157 18.8284C4 17.6569 4 15.7712 4 12V10Z" stroke="currentColor" stroke-width="1.5"/>
                                        <path opacity="0.5" d="M4 13H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15.5 16H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 16H8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path opacity="0.5" d="M6 19.5V21C6 21.5523 6.44772 22 7 22H8.5C9.05228 22 9.5 21.5523 9.5 21V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path opacity="0.5" d="M18 19.5V21C18 21.5523 17.5523 22 17 22H15.5C14.9477 22 14.5 21.5523 14.5 21V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path opacity="0.5" d="M20 9H21C21.5523 9 22 9.44772 22 10V11C22 11.3148 21.8518 11.6111 21.6 11.8L20 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path opacity="0.5" d="M4 9H3C2.44772 9 2 9.44772 2 10V11C2 11.3148 2.14819 11.6111 2.4 11.8L4 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path opacity="0.5" d="M19.5 5H4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="flex-1 font-semibold">
                                    @if ($value->alarm != null)
                                    <h6 class="mb-1 mt-1 text-danger">{{ $value->device_name }} ({{ $value->device_id }})</h6>
                                    @else
                                    <h6 class="mb-1 mt-1" id="alarm_{{ $value->device_id }}">{{ $value->device_name }} ({{ $value->device_id }})</h6>
                                    @endif
                                    <p class="text-xs">{{ $value->alarm }}&nbsp;</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute z-[5] hidden h-full w-full rounded-md bg-black/60"></div>
            <div class="panel flex-1 p-4" id="moniting-system">
                <div class="flex flex-col items-center justify-center py-8">
                    <img src="{{ asset('assets/images/coming-soon.svg') }}" alt="image" class="w-[280px] md:w-[430px] mx-auto h-[calc(100vh_-_320px)] min-h-[120px]" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ config("dms.config.url") }}/socket.io/socket.io.js"></script>
<script>
    const socket = io('{{ config("dms.config.url") }}', { transports: ['websocket', 'polling', 'flashsocket'] });
    socket.on('connect', () => {
        socket.emit('register', { type: 'operator' });
    });
    socket.on('Devices', (devices) => {
        devices.forEach(device => {
            document.querySelector('#alarm_'+device.device_id+'').style.color = '#00ab55';
        });
    });
</script>
<script>
    function openMonitor(device_id) {
        $.get("{{ url('dms') }}/"+device_id+"/ajax", function(data, status){
            $("#moniting-system").html(data);
            $("#alarm-"+device_id).removeClass('text-danger');
        });
    }
</script>
@endsection
