@extends('layouts.layout')
@section('title', 'ปฏิทิน')
@section('style')
<link href='{{ Module::asset('calendar:css/fullcalendar.min.css')}}' rel='stylesheet' />
<style>
.work {
    background-color: #4361eecc;
    border-color: #4361eecc;
}
.work:hover {
    background-color: #4361ee;
    border-color: #4361ee;
}
.meeting {
    background-color: #e7515acc;
    border-color: #e7515acc;
}
.meeting:hover {
    background-color: #e7515a;
    border-color: #e7515a;
}
.training {
    background-color: #3b3f5ccc;
    border-color: #3b3f5ccc;
}
.training:hover {
    background-color: #3b3f5c;
    border-color: #3b3f5c;
}
.remind {
    background-color: #e2a03fcc;
    border-color: #e2a03fcc;
}
.remind:hover {
    background-color: #e2a03f;
    border-color: #e2a03f;
}
.holiday {
    background-color: #2196f3cc;
    border-color: #2196f3cc;
}
.holiday:hover {
    background-color: #2196f3;
    border-color: #2196f3;
}
.personal {
    background-color: #00ab55cc;
    border-color: #00ab55cc;
}
.personal:hover {
    background-color: #00ab55;
    border-color: #00ab55;
}
</style>
@endsection
@section('content')
<div x-data="calendar">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">ปฏิทิน</div>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <ul class="flex text-gray-500 dark:text-white-dark text-sm">
                <li>
                    <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0">
                            <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="currentColor" stroke-width="1.5"></path>
                            <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </a>
                </li>
                <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">ปฏิทิน</a></li>
            </ul>
        </div>
    </div>
    <div class="panel">
        <div class="mb-5">
            <div class="mb-4 flex items-center sm:flex-row flex-col sm:justify-between justify-center">
                <div class="sm:mb-0 mb-4">
                    <div class="flex items-center mt-2 flex-wrap sm:justify-start justify-center">
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-primary"></div>
                            <div>งาน</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-warning"></div>
                            <div>เตือนความจำ</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-dark"></div>
                            <div>ฝึกอบรม</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-info"></div>
                            <div>วันหยุด</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-success"></div>
                            <div>ส่วนตัว</div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-danger"></div>
                            <div>ประชุม</div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" @click="editEvent()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    {{ __('others.add') }}
                </button>
                <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto hidden" :class="isAddEventModal && '!block'">
                    <div class="flex items-center justify-center min-h-screen px-4" @click.self="isAddEventModal = false">
                        <div x-show="isAddEventModal" x-transition x-transition.duration.300 class="panel border-0 p-0 rounded-lg overflow-hidden md:w-full max-w-lg w-[90%] my-8">
                            <button type="button" class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark" @click="isAddEventModal = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <h3 class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]" x-text="params.id ? 'แก้ไขกิจกรรม' : 'เพิ่มกิจกรรม'"></h3>
                            <div class="p-5">
                                <form @submit.prevent="saveEvent" method="POST" id="addEventForm">
                                    {{ csrf_field() }}
                                    <div class="mb-5">
                                        <label for="title">ชื่อ :</label>
                                        <input id="title" type="text" name="title" id="title" class="form-input" placeholder="ป้อนชื่อกิจกรรม" x-model="params.title" required />
                                        <div class="text-danger mt-2" id="titleErr"></div>
                                    </div>
                                    {{-- <div class="mb-5">
                                        <label for="user">ผู้เข้าร่วม :</label>
                                        <select id="user" class="form-select" name="user" x-model="params.user">
                                            <option value="">เลือกผู้เข้าร่วม</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="mb-5">
                                        <label for="dateStart">จาก :</label>
                                        <input id="dateStart" type="datetime-local" name="start_date" id="dateStart" class="form-input" placeholder="วันที่เริ่มกิจกรรม" x-model="params.start" :min="minStartDate" @change="startDateChange($event)" required />
                                        <div class="text-danger mt-2" id="startDateErr"></div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="dateEnd">ถึง :</label>
                                        <input id="dateEnd" type="datetime-local" name="end_date" id="dateEnd" class="form-input" placeholder="วันที่สิ้นสุดกิจกรรม" x-model="params.end" :min="minEndDate" required />
                                        <div class="text-danger mt-2" id="endDateErr"></div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="link">ลิงค์ :</label>
                                        <input type="url" name="link" id="link" class="form-input" placeholder="ป้อนลิงค์" x-model="params.link" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="description">คำอธิบาย :</label>
                                        <textarea id="description" name="description" id="description" class="form-textarea min-h-[130px]" placeholder="ป้อนคำอธิบาย" x-model="params.description"></textarea>
                                    </div>
                                    <div class="mb-5">
                                        <label>ป้าย:</label>
                                        <div class="mt-3">
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio" name="badge" value="work" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">งาน</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-warning" name="badge" value="remind" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">เตือนความจำ</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-dark" name="badge" value="training" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">ฝึกอบรม</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-info" name="badge" value="holiday" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">วันหยุด</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-success" name="badge" value="personal" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">ส่วนตัว</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer">
                                                <input type="radio" class="form-radio text-danger" name="badge" value="meeting" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">ประชุม</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex justify-end items-center mt-8">
                                        <button type="button" class="btn btn-outline-danger" @click="isAddEventModal = false">ยกเลิก</button>
                                        <button type="button" class="btn btn-danger ltr:ml-4 rtl:mr-4 hidden btn-delete" @click="deleteItem">ลบ</button>
                                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4" x-text="params.id ? 'แก้ไขกิจกรรม' : 'สร้างกิจกรรม'"></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="calendar-wrapper" id='calendar'></div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src='{{ Module::asset('calendar:js/fullcalendar.min.js') }}'></script>
<script src='{{ Module::asset('calendar:js/locales-all.global.min.js') }}'></script>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("calendar", () => ({
            defaultParams: ({
                id: null,
                user: '',
                title: '',
                link: '',
                start: '',
                end: '',
                description: '',
                type: 'primary'
            }),
            params: {
                id: null,
                user: '',
                title: '',
                link: '',
                start: '',
                end: '',
                description: '',
                type: 'primary'
            },
            isAddEventModal: false,
            minStartDate: '',
            minEndDate: '',
            calendar: null,
            now: new Date(),
            events: [],
            init() {
                this.events = [
                    @foreach ($events as $event)
                    {
                        id: {{ $event->id }},
                        user: '{{ $event->title }}',
                        title: '{{ $event->title }}',
                        link: '{{ $event->link }}',
                        start: '{{ $event->start_date }}',
                        end: '{{ $event->end_date }}',
                        className: '{{ $event->badge }}',
                        description: '{{ $event->description }}',
                    },
                    @endforeach
                ];
                var calendarEl = document.getElementById('calendar');
                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: '{{ app()->getLocale() }}',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay',
                    },
                    editable: true,
                    dayMaxEvents: true,
                    selectable: true,
                    droppable: true,
                    eventClick: (event) => {
                        this.editEvent(event);
                    },
                    select: (event) => {
                        this.editDate(event);
                    },
                    events: this.events,
                });
                this.calendar.render();
                this.$watch('$store.app.sidebar', () => {
                    setTimeout(() => {
                        this.calendar.render();
                    }, 300);
                });
            },
            getMonth(dt, add = 0) {
                let month = dt.getMonth() + 1 + add;
                return dt.getMonth() < 10 ? '0' + month : month;
            },
            editEvent(data) {
                this.params = JSON.parse(JSON.stringify(this.defaultParams));
                if (data) {
                    let obj = JSON.parse(JSON.stringify(data.event));
                    this.params = {
                        id: obj.id ? obj.id : null,
                        title: obj.title ? obj.title : null,
                        user: obj.extendedProps ? obj.extendedProps.user : '',
                        start: this.dateFormat(obj.start),
                        end: this.dateFormat(obj.end),
                        link: obj.extendedProps ? obj.extendedProps.link : '',
                        type: obj.classNames ? obj.classNames[0] : 'primary',
                        description: obj.extendedProps ? obj.extendedProps.description : '',
                    };

                    this.minStartDate = new Date();
                    this.minEndDate = this.dateFormat(obj.start);
                } else {
                    this.minStartDate = new Date();
                    this.minEndDate = new Date();
                }

                document.querySelector('.btn-delete').classList.remove('hidden');
                this.isAddEventModal = true;
            },
            editDate(data) {
                let obj = {
                    event: {
                        start: data.start,
                        end: data.end,
                    },
                };
                this.editEvent(obj);
            },
            deleteItem() {
                const dataID = {
                    'id': this.params.id
                };
                fetch("{{ route('calendar.delete') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('input[name=_token]').value
                    },
                    body: JSON.stringify(dataID)
                }).then((res) => res.json()).then((response) => {
                    this.showMessage('บันทึกกิจกรรมสำเร็จ', 'success');
                    this.isAddEventModal = false;
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                });
            },
            dateFormat(dt) {
                dt = new Date(dt);
                const month = dt.getMonth() + 1 < 10 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
                const date = dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate();
                const hours = dt.getHours() < 10 ? '0' + dt.getHours() : dt.getHours();
                const mins = dt.getMinutes() < 10 ? '0' + dt.getMinutes() : dt.getMinutes();
                dt = dt.getFullYear() + '-' + month + '-' + date + 'T' + hours + ':' + mins;
                return dt;
            },
            saveEvent() {
                if (!this.params.title) {
                    return true;
                }
                if (!this.params.start) {
                    return true;
                }
                if (!this.params.end) {
                    return true;
                }
                if (this.params.id) {
                    let event = this.events.find((d) => d.id == this.params.id);
                    event.user = this.params.user;
                    event.title = this.params.title;
                    event.start = this.params.start;
                    event.end = this.params.end;
                    event.link = this.params.link;
                    event.description = this.params.description;
                    event.className = this.params.type;
                    const data = {
                        'id': this.params.id,
                        'title': this.params.title,
                        'start_date': this.params.start,
                        'end_date': this.params.end,
                        'link': this.params.link,
                        'description': this.params.description,
                        'badge': this.params.type
                    };
                    fetch("{{ route('calendar.update') }}", {
                        method: "PUT",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': document.querySelector('input[name=_token]').value
                        },
                        body: JSON.stringify(data)
                    }).then((res) => res.json()).then((response) => {
                        this.showMessage('บันทึกกิจกรรมสำเร็จ', 'success');
                    });
                } else {
                    let maxEventId = 0;
                    const data = {
                        'title': this.params.title,
                        'start_date': this.params.start,
                        'end_date': this.params.end,
                        'link': this.params.link,
                        'description': this.params.description,
                        'badge': this.params.type,
                        'is_active': 0,
                    };
                    fetch("{{ route('calendar.store') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': document.querySelector('input[name=_token]').value
                        },
                        body: JSON.stringify(data)
                    }).then((res) => res.json()).then((response) => {
                        maxEventId = response.id;
                        this.showMessage('บันทึกกิจกรรมสำเร็จ', 'success');
                    });
                    let event = {
                        id: maxEventId,
                        user: this.params.user,
                        title: this.params.title,
                        start: this.params.start,
                        end: this.params.end,
                        link: this.params.link,
                        description: this.params.description,
                        className: this.params.type,
                    };
                    this.events.push(event);
                }
                this.calendar.getEventSources()[0].refetch();
                this.isAddEventModal = false;
            },
            startDateChange(event) {
                const dateStr = event.target.value;
                if (dateStr) {
                    this.minEndDate = this.dateFormat(dateStr);
                    this.params.end = '';
                }
            },

            showMessage(msg = '', type = 'success') {
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 4000,
                });
                toast.fire({
                    icon: type,
                    title: msg,
                    padding: '2em',
                });
            }
        }));
    });
</script>
@endsection
