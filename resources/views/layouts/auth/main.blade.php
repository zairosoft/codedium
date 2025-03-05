<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>@yield('title') | {{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ url('public/favicon.ico')}}" />
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css') }}" />
        @yield('style')
    </head>
    <body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">
        <div class="screen_loader animate__animated dvanimation fixed inset-0 z-[60] grid place-content-center bg-[#fafafa] dark:bg-[#060818]">
            <span class="animate-spin border-8 border-[#f1f2f3] border-l-primary rounded-full w-14 h-14 inline-block align-middle m-auto mb-10"></span>
        </div>
        <div class="fixed bottom-6 right-6 z-50" x-data="scrollToTop">
            <template x-if="showTopButton">
                <button type="button" class="btn btn-outline-primary animate-pulse rounded-full p-2" @click="goToTop">
                    <svg width="24" height="24" class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M12 20.75C12.4142 20.75 12.75 20.4142 12.75 20L12.75 10.75L11.25 10.75L11.25 20C11.25 20.4142 11.5858 20.75 12 20.75Z" fill="currentColor" /> <path d="M6.00002 10.75C5.69667 10.75 5.4232 10.5673 5.30711 10.287C5.19103 10.0068 5.25519 9.68417 5.46969 9.46967L11.4697 3.46967C11.6103 3.32902 11.8011 3.25 12 3.25C12.1989 3.25 12.3897 3.32902 12.5304 3.46967L18.5304 9.46967C18.7449 9.68417 18.809 10.0068 18.6929 10.287C18.5768 10.5673 18.3034 10.75 18 10.75L6.00002 10.75Z" fill="currentColor" /> </svg>
                </button>
            </template>
        </div>
        <div class="main-container min-h-screen text-black dark:text-white-dark">
            <div>
                <div class="absolute inset-0">
                    <img src="{{ asset('assets/images/auth/bg-gradient.png') }}" alt="image" class="h-full w-full object-cover" />
                </div>
                <div class="relative flex min-h-screen items-center justify-center bg-[url({{ asset('assets/images/auth/map.png') }})] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
                    <img src="{{ asset('assets/images/auth/coming-soon-object1.png') }}" alt="image" class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
                    <img src="{{ asset('assets/images/auth/coming-soon-object2.png') }}" alt="image" class="absolute left-24 top-0 h-40 md:left-[30%]" />
                    <img src="{{ asset('assets/images/auth/coming-soon-object3.png') }}" alt="image" class="absolute right-0 top-0 h-[300px]" />
                    <img src="{{ asset('assets/images/auth/polygon-object.svg') }}" alt="image" class="absolute bottom-0 end-[28%]" />
                    <div class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
                        <div class="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20">
                            <div class="absolute top-6 end-6">
                                <div class="dropdown" x-data="dropdown" @click.outside="open = false">
                                    <a href="javascript:;" class="flex items-center gap-2.5 rounded-lg border border-white-dark/30 bg-white px-2 py-1.5 text-white-dark hover:border-primary hover:text-primary dark:bg-black" :class="{'!border-primary !text-primary' : open}" @click="toggle">
                                        <div>
                                            <img :src="`{{ asset('assets/images/flags') }}/{{ strtoupper(Lang::locale()) }}.svg`" alt="image" class="h-5 w-5 rounded-full object-cover" />
                                        </div>
                                        <div class="text-base font-bold uppercase">{{ Lang::locale() }}</div>
                                        <span class="shrink-0" :class="{'rotate-180' : open}">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M6.99989 9.79988C6.59156 9.79988 6.18322 9.64238 5.87406 9.33321L2.07072 5.52988C1.90156 5.36071 1.90156 5.08071 2.07072 4.91154C2.23989 4.74238 2.51989 4.74238 2.68906 4.91154L6.49239 8.71488C6.77239 8.99488 7.22739 8.99488 7.50739 8.71488L11.3107 4.91154C11.4799 4.74238 11.7599 4.74238 11.9291 4.91154C12.0982 5.08071 12.0982 5.36071 11.9291 5.52988L8.12572 9.33321C7.81656 9.64238 7.40822 9.79988 6.99989 9.79988Z" fill="currentColor" /> </svg>
                                        </span>
                                    </a>
                                    @include('layouts.language')
                                </div>
                            </div>
                            <div class="mx-auto w-full max-w-[440px]">
                                @yield('content')
                            </div>
                        </div>
                        <div class="dark:text-white-dark text-center pt-5 pb-4 mt-auto">
                            <div class="footer-copyright">Copyright <span id="footer-year">{{ date('Y') }}</span> © {{ config('app.name') }} | <a href="{{ __('footer.link') }}" target="_blank">{{ __('footer.powered') }}</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/js/alpine-collaspe.min.js') }}"></script>
        <script src="{{ asset('assets/js/alpine-persist.min.js') }}"></script>
        <script defer src="{{ asset('assets/js/alpine-ui.min.js') }}"></script>
        <script defer src="{{ asset('assets/js/alpine-focus.min.js') }}"></script>
        <script defer src="{{ asset('assets/js/alpine.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('scrollToTop', () => ({
                    showTopButton: false,
                    init() {
                        window.onscroll = () => {
                            this.scrollFunction();
                        };
                    },
                    scrollFunction() {
                        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                            this.showTopButton = true;
                        } else {
                            this.showTopButton = false;
                        }
                    },
                    goToTop() {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    },
                }));
            });
        </script>
        @yield('script')
    </body>
</html>
