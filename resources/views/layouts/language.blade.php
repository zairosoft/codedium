<ul x-show="open" x-transition="" x-transition.duration.300ms="" class="top-11 grid w-[280px] grid-cols-2 gap-y-2 !px-2 font-semibold text-dark ltr:-right-14 rtl:-left-14 dark:text-white-dark dark:text-white-light/90 sm:ltr:-right-2 sm:rtl:-left-2">
    @foreach (config('app.available_locales') as $key => $value)
    <li>
        <a href="{{ url('language/' . $value) }}" class="hover:text-primary @if (Lang::locale() === $value) bg-primary/10 text-primary @endif">
            <img class="h-5 w-5 rounded-full object-cover" alt="image" src="{{ asset('assets/images/flags') }}/{{ strtoupper($value) }}.svg">
            <span class="ltr:ml-3 rtl:mr-3">{{ $key }}</span>
        </a>
    </li>
    @endforeach
</ul>
