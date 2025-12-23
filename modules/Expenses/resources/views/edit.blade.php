@extends('layouts.layout')
@section('title', 'แก้ไขค่าใช้จ่าย')
@section('style')

@endsection
@section('content')
    <div>
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">แก้ไขค่าใช้จ่าย
            </div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <ul class="flex text-gray-500 dark:text-white-dark">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="before:content-['/'] before:px-1.5"><a href="{{ url('/expenses') }}">ค่าใช้จ่าย</a></li>
                    <li class="before:content-['/'] before:px-1.5"><a href="javascript:;"
                            class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">แก้ไข</a>
                    </li>
                </ul>
            </div>
        </div>

        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" x-data="expenseEdit">
            @csrf
            @method('PUT')
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
                                <label for="reference_number" class="mb-0 flex-1 ltr:mr-2 rtl:ml-2">เลขที่อ้างอิง</label>
                                <input type="text" name="reference_number" class="form-input w-2/3 lg:w-[250px]"
                                    value="{{ $expense->reference_number }}" readonly />
                            </div>
                            <div class="mt-4 flex items-center">
                                <label for="date" class="mb-0 flex-1 ltr:mr-2 rtl:ml-2">วันที่</label>
                                <input id="date" type="date" name="date" class="form-input w-2/3 lg:w-[250px]" required
                                    value="{{ old('date', $expense->date) }}" />
                            </div>
                        </div>
                    </div>
                    <hr class="my-6 border-[#e0e6ed] dark:border-[#1b2e4b]" />
                    <div class="mt-8 px-4">
                        <div class="flex flex-col justify-between lg:flex-row">
                            <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6">
                                <div class="text-lg font-semibold">ข้อมูล</div>
                                <div class="mt-4 flex items-center">
                                    <label for="category" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">หมวดหมู่</label>
                                    <input id="category" type="text" name="category" class="form-input flex-1"
                                        placeholder="กรอกหมวดหมู่" value="{{ old('category', $expense->category) }}" />
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="payee" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">ผู้รับเงิน</label>
                                    <input id="payee" type="text" name="payee" class="form-input flex-1"
                                        placeholder="กรอกผู้รับเงิน" required value="{{ old('payee', $expense->payee) }}" />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2">
                                <div class="text-lg font-semibold">&nbsp;</div>
                                <div class="mt-4 flex items-center">
                                    <label for="payment_method" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">ช่องทางการชำระ</label>
                                    <select id="payment_method" name="payment_method" class="form-select flex-1" required>
                                        <option value="เงินสด" {{ old('payment_method', $expense->payment_method) == 'เงินสด' ? 'selected' : '' }}>เงินสด</option>
                                        <option value="เงินโอน" {{ old('payment_method', $expense->payment_method) == 'เงินโอน' ? 'selected' : '' }}>เงินโอน</option>
                                        <option value="บัตรเครดิต" {{ old('payment_method', $expense->payment_method) == 'บัตรเครดิต' ? 'selected' : '' }}>บัตรเครดิต</option>
                                    </select>
                                </div>
                                <div class="mt-4 flex items-center">
                                    <label for="description" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">รายละเอียด</label>
                                    <input id="description" type="text" name="description" class="form-input flex-1"
                                        placeholder="กรอกรายละเอียด"
                                        value="{{ old('description', $expense->description) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>หมวดหมู่</th>
                                        <th class="w-1">จำนวน</th>
                                        <th class="w-1">ราคาต่อหน่วย</th>
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
                                                <select :name="'category_id['+i+']'" class="form-select min-w-[200px]"
                                                    x-model="item.category_id">
                                                    <option value="เลือก">เลือกหมวดหมู่</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <textarea class="form-textarea mt-4" :name="'item_description['+i+']'"
                                                    placeholder="รายละเอียด..." x-model="item.description"></textarea>
                                            </td>
                                            <td>
                                                <input type="number" class="form-input w-32" placeholder="จำนวน"
                                                    x-model="item.quantity" :name="'quantity['+i+']'" min="0" />
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-input w-32" placeholder="ราคา"
                                                    :name="'unit_price['+i+']'" x-model="item.unit_price" />
                                            </td>
                                            <td x-text="formatNumber(item.unit_price * item.quantity)"></td>
                                            <td>
                                                <button type="button" @click="removeItem(item)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="h-5 w-5">
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
                                <button type="button" class="btn btn-primary" @click="addItem()">เพิ่มรายการ</button>
                            </div>
                            <div class="sm:w-2/5">
                                <div class="mt-4 flex items-center justify-between font-semibold">
                                    <div>รวม</div>
                                    <div x-text="formatNumber(getTotal())"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 px-4">
                        <div>
                            <label for="notes">หมายเหตุ</label>
                            <textarea id="notes" name="notes" class="form-textarea min-h-[130px]"
                                placeholder="หมายเหตุ....">{{ old('notes', $expense->notes) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-6 w-full xl:mt-0 xl:w-96">
                    <div class="panel mb-5">
                        <div>
                            <label for="status">สถานะ</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="1" {{ old('status', $expense->status) == '1' ? 'selected' : '' }}>ใช้งาน
                                </option>
                                <option value="0" {{ old('status', $expense->status) == '0' ? 'selected' : '' }}>ไม่ใช้งาน
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-1">
                            <button type="submit" class="btn btn-success w-full gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                    <path
                                        d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path
                                        d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                                บันทึก
                            </button>

                            <a href="{{ route('expenses') }}" class="btn btn-outline-danger w-full gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11H7v-2h10v2z"
                                        fill="currentColor" />
                                </svg>
                                ยกเลิก
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('expenseEdit', () => ({
                items: [],

                init() {
                    // Load existing expense items
                    @foreach($expense->items as $item)
                        this.items.push({
                            id: {{ $item->id }},
                            category_id: '{{ $item->category_id }}',
                            description: '{{ $item->description ?? '' }}',
                            quantity: {{ $item->quantity }},
                            unit_price: {{ $item->unit_price }},
                        });
                    @endforeach

                        // Add one empty item if no items exist
                        if (this.items.length === 0) {
                        this.items.push({
                            id: 1,
                            category_id: '',
                            description: '',
                            quantity: 1,
                            unit_price: 0,
                        });
                    }
                },

                addItem() {
                    let maxId = 0;
                    if (this.items && this.items.length) {
                        maxId = this.items.reduce((max, character) => (character.id > max ? character.id : max), this.items[0].id);
                    }
                    this.items.push({
                        id: maxId + 1,
                        category_id: '',
                        description: '',
                        quantity: 1,
                        unit_price: 0,
                    });
                },

                removeItem(item) {
                    this.items = this.items.filter((d) => d.id != item.id);
                },

                getTotal() {
                    return this.items.reduce((total, item) => {
                        return total + (parseFloat(item.unit_price) || 0) * (parseFloat(item.quantity) || 0);
                    }, 0);
                },

                formatNumber(num) {
                    return parseFloat(num || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                }
            }));
        });
    </script>
@endsection