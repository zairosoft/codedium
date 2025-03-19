@extends('layouts.layout')
@section('title', 'เพิ่มหน้าเว็บไซต์')
@section('script')

@endsection
@section('style')

@endsection
@section('content')



<div>
<div class="mb-5 flex flex-wrap items-center justify-between gap-4">
    <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">เพิ่มหน้าเว็บไซต์</div>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <ul class="flex text-gray-500 dark:text-white-dark">
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
            <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/roles') }}">เว็บไซต์</a></li>
            <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">เพิ่มหน้าเว็บไซต์</a></li>
        </ul>
    </div>
</div>
<div>
    <form action="" method="post">
        <div class="flex gap-5 relative">
            <div class="flex-1">
                <div class="panel">
                    <div>
                        <label for="notes">Page name</label>
                        <input type="text" class="form-input" placeholder="Enter Page name" name="name" />
                    </div>
                    <div class="mt-5">
                        <label for="notes">Page title</label>
                        <input type="text" class="form-input" placeholder="Enter page title" name="title" />
                    </div>
                </div>
                <div class="panel mt-5">
                    <div class="border-b p-4 px-0 dark:border-[#191e3a] font-semibold text-base dark:text-white"> SEO</div>
                    <div class="mt-5">
                        <label for="notes">Focus keyphras</label>
                        <input type="text" class="form-input" placeholder="Enter focus keyphras" name="notes" />
                    </div>
                    <div class="mt-5">
                        <label for="notes">Meta description</label>
                        <textarea id="notes" name="notes" class="form-textarea min-h-[130px]" placeholder="Notes...." x-model="params.notes"></textarea>
                    </div>
                </div>
            </div>
            <div class="mt-6 w-full xl:mt-0 xl:w-96">
                <div class="panel mb-5">
                    <div>
                        <label for="payment-method">Page slug</label>
                        <input type="text" class="form-input" placeholder="Enter slug" name="slug" />
                    </div>
                    <div class="mt-5">
                        <label for="payment-method">สถานะ</label>
                        <select name="payment-method" class="form-select">
                            <option value="">Select Payment</option>
                            <option value="bank">Bank Account</option>
                            <option value="paypal">Paypal</option>
                        </select>
                    </div>
                </div>
                <div class="panel">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-1">
                        <button type="button" class="btn btn-primary w-full gap-2">
                            {{ __('others.save') }}
                        </button>
                        <a href="javascript:history.back()" class="btn btn-outline-danger w-full gap-2">{{ __('others.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>







</div>










@endsection
