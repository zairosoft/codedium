@extends('layouts.layout')
@section('title', 'CRM')
@section('style')

@endsection
@section('content')
<div x-data="calendar">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right dark:text-white-light">{{ __('calendar::calendar.calendar') }}</div>
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
                <li class="before:content-['/'] before:px-1.5"><a href="javascript:;" class="text-black dark:text-white-light hover:text-black/70 dark:hover:text-white-light/70">{{ __('calendar::calendar.calendar') }}</a></li>
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
                            <div>{{ __('calendar::calendar.work') }}</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-warning"></div>
                            <div>{{ __('calendar::calendar.remind') }}</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-dark"></div>
                            <div>{{ __('calendar::calendar.training') }}</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-info"></div>
                            <div>{{ __('calendar::calendar.holiday') }}</div>
                        </div>
                        <div class="flex items-center ltr:mr-4 rtl:ml-4">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-success"></div>
                            <div>{{ __('calendar::calendar.personal') }}</div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-danger"></div>
                            <div>{{ __('calendar::calendar.meeting') }}</div>
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
                            <h3 class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]" x-text="params.id ? '{{ __('calendar::calendar.edit_activity') }}' : '{{ __('calendar::calendar.add_activity') }}'"></h3>
                            <div class="p-5">
                                <form @submit.prevent="saveEvent" method="POST" id="addEventForm">
                                    {{ csrf_field() }}
                                    <div class="mb-5">
                                        <label for="title">{{ __('calendar::calendar.name') }} :</label>
                                        <input id="title" type="text" name="title" id="title" class="form-input" placeholder="{{ __('calendar::calendar.enter_title') }}" x-model="params.title" required />
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
                                        <label for="dateStart">{{ __('calendar::calendar.from') }} :</label>
                                        <input id="dateStart" type="datetime-local" name="start_date" id="dateStart" class="form-input" placeholder="{{ __('calendar::calendar.enter_start_date') }}" x-model="params.start" :min="minStartDate" @change="startDateChange($event)" required />
                                        <div class="text-danger mt-2" id="startDateErr"></div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="dateEnd">{{ __('calendar::calendar.to') }} :</label>
                                        <input id="dateEnd" type="datetime-local" name="end_date" id="dateEnd" class="form-input" placeholder="{{ __('calendar::calendar.enter_end_date') }}" x-model="params.end" :min="minEndDate" required />
                                        <div class="text-danger mt-2" id="endDateErr"></div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="link">{{ __('calendar::calendar.link') }} :</label>
                                        <input type="url" name="link" id="link" class="form-input" placeholder="{{ __('calendar::calendar.enter_link') }}" x-model="params.link" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="description">{{ __('calendar::calendar.event_description') }} :</label>
                                        <textarea id="description" name="description" id="description" class="form-textarea min-h-[130px]" placeholder="{{ __('calendar::calendar.enter_description') }}" x-model="params.description"></textarea>
                                    </div>
                                    <div class="mb-5">
                                        <label>{{ __('calendar::calendar.badge') }}:</label>
                                        <div class="mt-3">
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio" name="badge" value="work" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">{{ __('calendar::calendar.work') }}</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-warning" name="badge" value="remind" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">{{ __('calendar::calendar.remind') }}</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-dark" name="badge" value="training" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">{{ __('calendar::calendar.training') }}</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-info" name="badge" value="holiday" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">{{ __('calendar::calendar.holiday') }}</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                <input type="radio" class="form-radio text-success" name="badge" value="personal" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">{{ __('calendar::calendar.personal') }}</span>
                                            </label>
                                            <label class="inline-flex cursor-pointer">
                                                <input type="radio" class="form-radio text-danger" name="badge" value="meeting" x-model="params.type" />
                                                <span class="ltr:pl-2 rtl:pr-2">{{ __('calendar::calendar.meeting') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex justify-end items-center mt-8">
                                        <button type="button" class="btn btn-outline-danger" @click="isAddEventModal = false">{{ __('others.cancel') }}</button>
                                        <button type="button" class="btn btn-danger ltr:ml-4 rtl:mr-4 hidden btn-delete" @click="deleteItem">{{ __('others.delete') }}</button>
                                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4" x-text="params.id ? '{{ __('calendar::calendar.edit_activity') }}' : '{{ __('calendar::calendar.add_activity') }}'"></button>
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

@endsection