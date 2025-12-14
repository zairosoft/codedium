@extends('layouts.layout')
@section('title', 'เพิ่มผู้ใช้ระบบ')
@section('script')

@endsection
@section('style')

@endsection
@section('content')
    <div>

        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">เพิ่ม</div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <ul class="flex text-gray-500 dark:text-white-dark">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0">
                                <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/users') }}">ผู้ใช้ระบบ</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">เพิ่มผู้ใช้</a></li>
                </ul>
            </div>
        </div>

         <div x-data="invoiceAdd">
            <div class="flex flex-col gap-2.5 xl:flex-row">
                <div class="panel flex-1 px-0 py-6 ltr:lg:mr-6 rtl:lg:ml-6">
                    <div class="flex flex-wrap justify-between px-4">
                        <div class="mb-6 w-full lg:w-1/2">
                            <div class="flex shrink-0 items-center text-black dark:text-white">
                                &nbsp;
                            </div>
                            <div class="mt-6 space-y-1 text-gray-500 dark:text-gray-400">&nbsp;</div>
                        </div>
                        <div class="w-full lg:w-1/2 lg:max-w-fit">
                            <div class="flex items-center">
                                <label for="number" class="mb-0 flex-1 ltr:mr-2 rtl:ml-2">เล่มที่ / เลขที่</label>
                                <div> 1 / 23 </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <label for="date" class="mb-0 flex-1 ltr:mr-2 rtl:ml-2">วันที่</label>
                                <input id="date" type="date" name="date" class="form-input w-2/3 lg:w-[250px]" x-model="params.dueDate" />
                            </div>
                        </div>
                    </div>
                    <hr class="my-6 border-[#e0e6ed] dark:border-[#1b2e4b]" />
                    <div class="mt-8 px-4">
                        <div class="flex flex-col justify-between lg:flex-row">
                            <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6">
                                <div class="text-lg font-semibold">ข้อมูล</div>
                                <div class="mt-4 flex items-center">
                                    <label for="reciever-name" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">ชื่อบัญชี</label>
                                    <input
                                        id="reciever-name"
                                        type="text"
                                        name="reciever-name"
                                        class="form-input flex-1"
                                        x-model="params.to.name"
                                        placeholder="Enter Name"
                                    />
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="reciever-email" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">เลขบัญชี</label>
                                    <input
                                        id="reciever-email"
                                        type="email"
                                        name="reciever-email"
                                        class="form-input flex-1"
                                        x-model="params.to.email"
                                        placeholder="Enter Email"
                                    />
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="reciever-address" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Address</label>
                                    <input
                                        id="reciever-address"
                                        type="text"
                                        name="reciever-address"
                                        class="form-input flex-1"
                                        x-model="params.to.address"
                                        placeholder="Enter Address"
                                    />
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="reciever-number" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Phone Number</label>
                                    <input
                                        id="reciever-number"
                                        type="text"
                                        name="reciever-number"
                                        class="form-input flex-1"
                                        x-model="params.to.phone"
                                        placeholder="Enter Phone Number"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2">
                                <div class="text-lg font-semibold">&nbsp;</div>
                                <div class="mt-4 flex items-center">
                                    <label for="acno" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">ธนาคาร / เวลา</label>
                                    <input
                                        id="acno"
                                        type="text"
                                        name="acno"
                                        class="form-input flex-1"
                                        x-model="params.bankInfo.no"
                                        placeholder="Enter Account Number"
                                    />
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="bank-name" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">อ้างอิง</label>
                                    <input
                                        id="bank-name"
                                        type="text"
                                        name="bank-name"
                                        class="form-input flex-1"
                                        x-model="params.bankInfo.name"
                                        placeholder="Enter Bank Name"
                                    />
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="swift-code" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">SWIFT Number</label>
                                    <input
                                        id="swift-code"
                                        type="text"
                                        name="swift-code"
                                        class="form-input flex-1"
                                        x-model="params.bankInfo.swiftCode"
                                        placeholder="Enter SWIFT Number"
                                    />
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="iban-code" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">IBAN Number</label>
                                    <input
                                        id="iban-code"
                                        type="text"
                                        name="iban-code"
                                        class="form-input flex-1"
                                        x-model="params.bankInfo.ibanNo"
                                        placeholder="Enter IBAN Number"
                                    />
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>รายการ</th>
                                        <th class="w-1">จำนวน</th>
                                        <th class="w-1">ราคา</th>
                                        <th>รวม</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="items.length <= 0">
                                        <tr>
                                            <td colspan="5" class="!text-center font-semibold">No Item Available</td>
                                        </tr>
                                    </template>
                                    <template x-for="(item, i) in items" :key="i">
                                        <tr class="border-b border-[#e0e6ed] align-top dark:border-[#1b2e4b]">
                                            <td>
                                                <input
                                                    type="text"
                                                    class="form-input min-w-[200px]"
                                                    placeholder="Enter Item Name"
                                                    x-model="item.title"
                                                />
                                                <textarea
                                                    class="form-textarea mt-4"
                                                    placeholder="Enter Description"
                                                    x-model="item.description"
                                                ></textarea>
                                            </td>
                                            <td><input type="number" class="form-input w-32" placeholder="Quantity" x-model="item.quantity" /></td>
                                            <td><input type="text" class="form-input w-32" placeholder="Price" x-model="item.amount" /></td>
                                            <td x-text="`$${item.amount * item.quantity}`"></td>
                                            <td>
                                                <button type="button" @click="removeItem(item)">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24px"
                                                        height="24px"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="h-5 w-5"
                                                    >
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 flex flex-col justify-between px-4 sm:flex-row">
                            <div class="mb-6 sm:mb-0">
                                <button type="button" class="btn btn-primary" @click="addItem()">Add Item</button>
                            </div>
                            <div class="sm:w-2/5">
                                <div class="flex items-center justify-between">
                                    <div>Subtotal</div>
                                    <div>$0.00</div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div>Tax(%)</div>
                                    <div>0%</div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div>Shipping Rate($)</div>
                                    <div>$0.00</div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div>Discount(%)</div>
                                    <div>0%</div>
                                </div>
                                <div class="mt-4 flex items-center justify-between font-semibold">
                                    <div>Total</div>
                                    <div>$0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 px-4">
                        <div>
                            <label for="notes">หมายเหตุ</label>
                            <textarea
                                id="notes"
                                name="notes"
                                class="form-textarea min-h-[130px]"
                                placeholder="หมายเหตุ...."
                                x-model="params.notes"
                            ></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-6 w-full xl:mt-0 xl:w-96">
                    <div class="panel mb-5">
                        <div>
                            <label for="status">สถานะ</label>
                            <select id="status" name="status" class="form-select">
                                <option value=""></option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="tax">Tax(%) </label>
                                    <input id="tax" type="number" name="tax" class="form-input" placeholder="Tax" x-model="tax" />
                                </div>
                                <div>
                                    <label for="discount">Discount(%) </label>
                                    <input
                                        id="discount"
                                        type="number"
                                        name="discount"
                                        class="form-input"
                                        placeholder="Discount"
                                        x-model="discount"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div>
                                <label for="shipping-charge">Shipping Charge($) </label>
                                <input
                                    id="shipping-charge"
                                    type="number"
                                    name="shipping-charge"
                                    class="form-input"
                                    placeholder="Shipping Charge"
                                    x-model="shippingCharge"
                                />
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="payment-method">ช่องทางการชำระ</label>
                            <select id="payment-method" name="" class="form-select" x-model="paymentMethod">
                                <option value="เงินสด">เงินสด</option>
                                <option value="เงินโอน">เงินโอน</option>
                            </select>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-1">
                            <button type="button" class="btn btn-success w-full gap-2">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2"
                                >
                                    <path
                                        d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    />
                                    <path
                                        d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    />
                                    <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                                Save
                            </button>

                            <a href="apps-invoice-preview.html" class="btn btn-primary w-full gap-2">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2"
                                >
                                    <path
                                        opacity="0.5"
                                        d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    ></path>
                                    <path
                                        d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    ></path>
                                </svg>
                                Preview
                            </a>

                            <button type="button" class="btn btn-secondary w-full gap-2">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2"
                                >
                                    <path
                                        opacity="0.5"
                                        d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                    ></path>
                                    <path
                                        d="M12 2L12 15M12 15L9 11.5M12 15L15 11.5"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    ></path>
                                </svg>
                                Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        async function showAlert() {
            const toast = window.Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3500,
                padding: '2em',
            });
            toast.fire({
                icon: 'success',
                title: '{!! \Session::get('success') !!}',
                padding: '2em',
            });
        }
        @if (\Session::has('success'))
            setTimeout(() => {
                window.onload = showAlert();
            }, "500");
            // {!! \Session::get('success') !!}
        @endif


        //invoice add
        document.addEventListener('alpine:init', () => {
                Alpine.data('invoiceAdd', () => ({
                    items: [],
                    selectedFile: null,
                    params: {
                        title: '',
                        invoiceNo: '',
                        to: {
                            name: '',
                            email: '',
                            address: '',
                            phone: '',
                        },

                        invoiceDate: '',
                        dueDate: '',
                        bankInfo: {
                            no: '',
                            name: '',
                            swiftCode: '',
                            country: '',
                            ibanNo: '',
                        },
                        notes: '',
                    },
                    tax: null,
                    discount: null,
                    shippingCharge: null,
                    paymentMethod: '',

                    init() {
                        //set default data
                        this.items.push({
                            id: 1,
                            title: '',
                            description: '',
                            rate: 0,
                            quantity: 1,
                            amount: 0,
                        });
                    },

                    addItem() {
                        let maxId = 0;
                        if (this.items && this.items.length) {
                            maxId = this.items.reduce((max, character) => (character.id > max ? character.id : max), this.items[0].id);
                        }
                        this.items.push({
                            id: maxId + 1,
                            title: '',
                            description: '',
                            rate: 0,
                            quantity: 1,
                            amount: 0,
                        });
                    },

                    removeItem(item) {
                        this.items = this.items.filter((d) => d.id != item.id);
                    },
                }));
            });
    </script>
@endsection
