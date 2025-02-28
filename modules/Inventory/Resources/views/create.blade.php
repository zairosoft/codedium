@extends('layouts.layout')
@section('title', 'แสดงรายการบทบาท')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/quill.snow.css') }}" />
@endsection
@section('content')
    <div x-data="form">
        <form class="space-y-5" @submit.prevent="SubmitForm()" action="#">
            <div class="lg:flex gap-5">
                <div class="grow space-y-5">
                    <div class="panel">
                        <div class="grid gap-6">
                            <div :class="[isSubmitForm ? (form.name ? '' : 'has-error') : '']">
                                <label for="name">ชื่อสินค้า</label>
                                <input id="name" type="text" placeholder="ป้อนชื่อสินค้า" class="form-input" value="{{ old('name') }}" x-model="form.name" />
                                <template x-if="isSubmitForm && !form.name">
                                    <p class="text-danger mt-1">กรุณาป้อนชื่อสินค้า</p>
                                </template>
                            </div>
                        </div>
                        <div class="mt-5">
                            <label>รายละเอียดสินค้า</label>
                            <div id="editor"></div>
                        </div>
                    </div>
                    <div class="panel p-0">
                        <div class="p-4 border-b dark:border-[#191e3a] font-semibold text-base dark:text-white">Product
                            Gallery
                        </div>
                        <div action="" class="space-y-5 p-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <label
                                    class="border-2 border-dashed border-primary/50 bg-primary/5 flex items-center justify-center flex-col gap-4 relative p-5">
                                    <span
                                        class="flex items-center justify-center w-14 h-14 bg-primary/10 rounded-full text-primary">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3 14.25C3.41421 14.25 3.75 14.5858 3.75 15C3.75 16.4354 3.75159 17.4365 3.85315 18.1919C3.9518 18.9257 4.13225 19.3142 4.40901 19.591C4.68577 19.8678 5.07435 20.0482 5.80812 20.1469C6.56347 20.2484 7.56459 20.25 9 20.25H15C16.4354 20.25 17.4365 20.2484 18.1919 20.1469C18.9257 20.0482 19.3142 19.8678 19.591 19.591C19.8678 19.3142 20.0482 18.9257 20.1469 18.1919C20.2484 17.4365 20.25 16.4354 20.25 15C20.25 14.5858 20.5858 14.25 21 14.25C21.4142 14.25 21.75 14.5858 21.75 15V15.0549C21.75 16.4225 21.75 17.5248 21.6335 18.3918C21.5125 19.2919 21.2536 20.0497 20.6517 20.6516C20.0497 21.2536 19.2919 21.5125 18.3918 21.6335C17.5248 21.75 16.4225 21.75 15.0549 21.75H8.94513C7.57754 21.75 6.47522 21.75 5.60825 21.6335C4.70814 21.5125 3.95027 21.2536 3.34835 20.6517C2.74643 20.0497 2.48754 19.2919 2.36652 18.3918C2.24996 17.5248 2.24998 16.4225 2.25 15.0549C2.25 15.0366 2.25 15.0183 2.25 15C2.25 14.5858 2.58579 14.25 3 14.25Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 2.25C12.2106 2.25 12.4114 2.33852 12.5535 2.49392L16.5535 6.86892C16.833 7.17462 16.8118 7.64902 16.5061 7.92852C16.2004 8.20802 15.726 8.18678 15.4465 7.88108L12.75 4.9318V16C12.75 16.4142 12.4142 16.75 12 16.75C11.5858 16.75 11.25 16.4142 11.25 16V4.9318L8.55353 7.88108C8.27403 8.18678 7.79963 8.20802 7.49393 7.92852C7.18823 7.64902 7.16698 7.17462 7.44648 6.86892L11.4465 2.49392C11.5886 2.33852 11.7894 2.25 12 2.25Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <h5 class="text-lg font-bold text-center">Drag & drop any image here</h5>
                                    <input type="file" class="absolute opacity-0 z-[1] cursor-pointer">
                                </label>
                                <label
                                    class="border-2 border-dashed border-primary/50 bg-primary/5 flex items-center justify-center flex-col gap-4 relative p-5">
                                    <span
                                        class="flex items-center justify-center w-14 h-14 bg-primary/10 rounded-full text-primary">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3 14.25C3.41421 14.25 3.75 14.5858 3.75 15C3.75 16.4354 3.75159 17.4365 3.85315 18.1919C3.9518 18.9257 4.13225 19.3142 4.40901 19.591C4.68577 19.8678 5.07435 20.0482 5.80812 20.1469C6.56347 20.2484 7.56459 20.25 9 20.25H15C16.4354 20.25 17.4365 20.2484 18.1919 20.1469C18.9257 20.0482 19.3142 19.8678 19.591 19.591C19.8678 19.3142 20.0482 18.9257 20.1469 18.1919C20.2484 17.4365 20.25 16.4354 20.25 15C20.25 14.5858 20.5858 14.25 21 14.25C21.4142 14.25 21.75 14.5858 21.75 15V15.0549C21.75 16.4225 21.75 17.5248 21.6335 18.3918C21.5125 19.2919 21.2536 20.0497 20.6517 20.6516C20.0497 21.2536 19.2919 21.5125 18.3918 21.6335C17.5248 21.75 16.4225 21.75 15.0549 21.75H8.94513C7.57754 21.75 6.47522 21.75 5.60825 21.6335C4.70814 21.5125 3.95027 21.2536 3.34835 20.6517C2.74643 20.0497 2.48754 19.2919 2.36652 18.3918C2.24996 17.5248 2.24998 16.4225 2.25 15.0549C2.25 15.0366 2.25 15.0183 2.25 15C2.25 14.5858 2.58579 14.25 3 14.25Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 2.25C12.2106 2.25 12.4114 2.33852 12.5535 2.49392L16.5535 6.86892C16.833 7.17462 16.8118 7.64902 16.5061 7.92852C16.2004 8.20802 15.726 8.18678 15.4465 7.88108L12.75 4.9318V16C12.75 16.4142 12.4142 16.75 12 16.75C11.5858 16.75 11.25 16.4142 11.25 16V4.9318L8.55353 7.88108C8.27403 8.18678 7.79963 8.20802 7.49393 7.92852C7.18823 7.64902 7.16698 7.17462 7.44648 6.86892L11.4465 2.49392C11.5886 2.33852 11.7894 2.25 12 2.25Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <h5 class="text-lg font-bold text-center">Drag & drop any gallery here</h5>
                                    <input type="file" class="absolute opacity-0 z-[1] cursor-pointer">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="panel p-0">
                        <div action="" class="space-y-8 p-4">
                            <div class="mb-5" x-data="{ tab: 'general' }">
                                <div>
                                    <ul class="flex flex-wrap mt-3 mb-5 border-b border-white-light dark:border-[#191e3a]">
                                        <li>
                                            <a href="javascript:"
                                                class="p-5 py-3 -mb-[1px] flex items-center hover:border-b border-transparent hover:!border-secondary hover:text-secondary"
                                                :class="{ 'border-b !border-secondary text-secondary': tab === 'general' }"
                                                @click="tab = 'general'">
                                                ข้อมูลทั่วไป
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:"
                                                class="p-5 py-3 -mb-[1px] flex items-center hover:border-b border-transparent hover:!border-secondary hover:text-secondary"
                                                :class="{ 'border-b !border-secondary text-secondary': tab === 'attributes' }"
                                                @click="tab = 'attributes'">
                                                คุณสมบัติและตัวแปร
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:"
                                                class="p-5 py-3 -mb-[1px] flex items-center hover:border-b border-transparent hover:!border-secondary hover:text-secondary"
                                                :class="{ 'border-b !border-secondary text-secondary': tab === 'contact' }"
                                                @click="tab='contact'">
                                                Contact
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex-1 text-sm ">
                                    <template x-if="tab === 'general'">
                                        <div>
                                            <div class="grid gap-6">
                                                <div>
                                                    <label for="name">Barcode</label>
                                                    <input id="barcode" name="barcode" type="text" placeholder="ป้อน Barcode" value="{{ old('barcode') }}" class="form-input" />
                                                </div>
                                            </div>
                                            <div class="grid sm:grid-cols-2 gap-6 mt-5">
                                                <div>
                                                    <label>ผู้ผลิต</label>
                                                    <input type="text" placeholder="ป้อนผู้ผลิต" name="manufacturer_name" value="{{ old('manufacturer_name') }}" id="manufacturer_name" class="form-input" />
                                                </div>
                                                <div>
                                                    <label>ผู้จัดจำหน่าย</label>
                                                    <input type="text" placeholder="ป้อนผู้จัดจำหน่าย" value="{{ old('manufacturer_brand') }}" id="manufacturer_brand" name="manufacturer_brand" class="form-input" />
                                                </div>
                                            </div>
                                            <div class="grid sm:grid-cols-2 gap-6 mt-5">
                                                <div>
                                                    <label>ยี่ห้อ</label>
                                                    <input type="text" placeholder="ป้อนยี่ห้อ" id="brand" value="{{ old('brand') }}" name="brand" class="form-input" />
                                                </div>
                                                <div>
                                                    <label>รุ่น</label>
                                                    <input type="text" placeholder="ป้อนรุ่น" name="model" value="{{ old('model') }}" id="model" class="form-input" />
                                                </div>
                                            </div>
                                            <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6 mt-5">
                                                <div :class="[isSubmitForm ? (form.stock ? '' : 'has-error') : '']">
                                                    <label for="stock">คลัง</label>
                                                    <input id="stock" type="number" name="stock" placeholder="ป้อนชื่อสินค้า" class="form-input" x-model="form.stock" />
                                                    <template x-if="isSubmitForm && !form.stock">
                                                        <p class="text-danger mt-1">กรุณาป้อนจำนวน</p>
                                                    </template>
                                                </div>
                                                <div :class="[isSubmitForm ? (form.price ? '' : 'has-error') : '']">
                                                    <label for="price">ราคา</label>
                                                    <input id="price" type="number" name="price" placeholder="ป้อนชื่อราคา" class="form-input" value="{{ old('price') }}" x-model="form.price" />
                                                    <template x-if="isSubmitForm && !form.price">
                                                        <p class="text-danger mt-1">กรุณาป้อนราคา</p>
                                                    </template>
                                                </div>
                                                <div>
                                                    <label>ต้นทุน</label>
                                                    <input type="text" placeholder="ป้อนต้นทุน" name="cost" id="cost" value="{{ old('cost') }}" class="form-input" />
                                                </div>
                                                <div>
                                                    <label>เลข SKU</label>
                                                    <input type="text" placeholder="ป้อนเลข SKU" name="sku" id="sku" value="{{ old('sku') }}" class="form-input" />
                                                </div>
                                            </div>
                                            <div class="mt-5">
                                                <label>หมายเหตุ</label>
                                                <textarea placeholder="ป้อนหมายเหตุ" rows="4" name="note" id="note" class="form-textarea">{{ old('note') }}</textarea>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="tab === 'attributes'">
                                        <div>
                                            <div class="flex items-start">
                                                <div class="w-20 h-20 ltr:mr-4 rtl:ml-4 flex-none">
                                                    <img src="/assets/images/profile-34.jpeg" alt="image"
                                                        class="w-20 h-20 m-0 rounded-full ring-2 ring-[#ebedf2] dark:ring-white-dark object-cover" />
                                                </div>
                                                <div class="flex-auto">
                                                    <h5 class="text-xl font-medium mb-4">Media heading</h5>
                                                    <p class="text-white-dark">Cras sit amet nibh libero, in gravida nulla.
                                                        Nulla vel metus scelerisque ante sollicitudin. Cras purus odio,
                                                        vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum
                                                        nunc ac nisi vulputate fringilla. Donec lacinia congue felis in
                                                        faucibus.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="tab === 'contact'">
                                        <div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shrink-0 lg:max-w-xs w-full mt-5 lg:mt-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-5">
                        <div class="panel p-0">
                            <div class="p-4 border-b dark:border-[#191e3a] font-semibold text-base dark:text-white">เผยแพร่
                            </div>
                            <div action="" class="space-y-5 p-4">
                                <div>
                                    <label class="">Status</label>
                                    <select class="form-select" name="status" id="status">
                                        <option value="0">ซ่อน</option>
                                        <option value="1" selected>เผยแพร่</option>
                                        <option value="2">ร่าง</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="">กำหนดเผยแพร่</label>
                                    <input type="date" id="publish_schedule" name="publish_schedule"
                                        class="form-input" />
                                </div>
                            </div>
                        </div>
                        <div class="panel p-0">
                            <div class="p-4 border-b dark:border-[#191e3a] font-semibold text-base dark:text-white">
                                หมวดหมู่สินค้า  <a href="#" x-tooltip="{{ __('others.add') }}" class="inline-flex float-right p-1 bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg></a>
                            </div>
                            <div action="" class="space-y-5 p-4">
                                <div>
                                    <label class="">เลือกหมวดหมู่สินค้า</label>
                                    <select class="form-select" id="category" name="category">
                                        @foreach ($categories as $key => $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel p-0">
                            <div class="p-4 border-b dark:border-[#191e3a] font-semibold text-base dark:text-white">
                                คำอธิบายสั้น ๆ ของสินค้า
                            </div>
                            <div action="" class="space-y-5 p-4">
                                <div>
                                    <label>เพิ่มคำอธิบายสั้นๆ สำหรับสินค้า</label>
                                    <textarea placeholder="Enter product description" id="short_description" name="short_description" rows="4"
                                        class="form-input"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-4">
                            @csrf
                            <a href="javascript:history.back()" class="btn btn-outline-danger">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('script')
    <script src="{{ asset('assets/js/quill.js') }}"></script>
    <script>
        new Quill('#editor', {
            theme: 'snow'
        });
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('form', () => ({
                form: {
                    name: '',
                    stock: '',
                    price: '',
                },
                isSubmitForm: false,
                SubmitForm() {
                    this.isSubmitForm = true;
                    if (this.form.name && this.form.stock && this.form.price) {
                        this.submit();
                    }
                },
                submit() {
                    try {
                        fetch("{{ route('product-store') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                name: document.querySelector('#name').value,
                                barcode: document.querySelector('#barcode').value,
                                price: document.querySelector('#price').value,
                                editor: document.getElementById('editor').innerHTML,
                                "_token": "{{ csrf_token() }}",
                            })
                        }).then((res) => res.json()).then((response) => {
                            console.log(response);
                        });
                    } catch (error) {
                        console.error(error);
                    } finally {
                        console.log('API call completed');
                    }
                }
            }));
        });
    </script>
@endsection
