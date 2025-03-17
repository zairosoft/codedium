@extends('layouts.auth.main')
@section('title', __('auth.forgot_password'))
@section('content')
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">{{ __('auth.forgot_password') }}</h1>
        <p class="text-base font-bold leading-normal text-white-dark">{{ __('auth.login_to') }}</p>
    </div>
    <form class="space-y-5 dark:text-white" action="{{ route('forgot.password') }}" method="post">
        @csrf
        <div>
            <label for="Email">{{ __('auth.email') }}</label>
            <div class="relative text-white-dark @error('email')has-error @enderror">
                <input id="Email" type="email" placeholder="{{ __('auth.enter_email') }}" name="email" value="{{ old('email') }}" class="form-input ps-10 placeholder:text-white-dark" />
                <span class="absolute start-4 top-1/2 -translate-y-1/2">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"> <path opacity="0.5" d="M10.65 2.25H7.35C4.23873 2.25 2.6831 2.25 1.71655 3.23851C0.75 4.22703 0.75 5.81802 0.75 9C0.75 12.182 0.75 13.773 1.71655 14.7615C2.6831 15.75 4.23873 15.75 7.35 15.75H10.65C13.7613 15.75 15.3169 15.75 16.2835 14.7615C17.25 13.773 17.25 12.182 17.25 9C17.25 5.81802 17.25 4.22703 16.2835 3.23851C15.3169 2.25 13.7613 2.25 10.65 2.25Z" fill="currentColor" /> <path d="M14.3465 6.02574C14.609 5.80698 14.6445 5.41681 14.4257 5.15429C14.207 4.89177 13.8168 4.8563 13.5543 5.07507L11.7732 6.55931C11.0035 7.20072 10.4691 7.6446 10.018 7.93476C9.58125 8.21564 9.28509 8.30993 9.00041 8.30993C8.71572 8.30993 8.41956 8.21564 7.98284 7.93476C7.53168 7.6446 6.9973 7.20072 6.22761 6.55931L4.44652 5.07507C4.184 4.8563 3.79384 4.89177 3.57507 5.15429C3.3563 5.41681 3.39177 5.80698 3.65429 6.02574L5.4664 7.53583C6.19764 8.14522 6.79033 8.63914 7.31343 8.97558C7.85834 9.32604 8.38902 9.54743 9.00041 9.54743C9.6118 9.54743 10.1425 9.32604 10.6874 8.97558C11.2105 8.63914 11.8032 8.14522 12.5344 7.53582L14.3465 6.02574Z" fill="currentColor" /> </svg>
                </span>
            </div>
            @if ($errors->has('email'))
            <div>
                <p class="text-danger mt-1">{!! $errors->first('email') !!}</p>
            </div>
            @endif
        </div>
        @stack('recaptcha')
        <button type="submit"class="btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)] p-3">
            {{ __('auth.send_email') }}
        </button>
    </form>
    <div class="relative my-7 text-center md:mb-9">
        <span class="absolute inset-x-0 top-1/2 h-px w-full -translate-y-1/2 bg-white-light dark:bg-white-dark"></span>
        <span class="relative bg-white px-2 font-bold uppercase text-white-dark dark:bg-dark dark:text-white-light">{{ __('auth.or') }}</span>
    </div>
    <div class="text-center dark:text-white">
        <a href="{{ url('auth/login') }}" class="uppercase text-primary underline transition hover:text-black dark:hover:text-white">{{ __('auth.login') }}</a>
    </div>
@endsection
