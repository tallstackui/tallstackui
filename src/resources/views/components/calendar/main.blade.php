@php
    $customization = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_calendar(
     {!! $entangle !!},
     @js($range),
     @js($multiple),
     @js($double),
     @js($format),
     {...@js($dates())},
     @js($disable->toArray()),
     @js($livewire),
     @js($property),
     @js($value),
     @js($monthYearOnly),
     @js($lockMonthYear),
     @js(trans('ts-ui::messages.date.calendar')),
     @js($change),
     @js($start),
     @js($only),
     @js($weekdays),
     @js($weekends))"
     class="{{ $customization['wrapper.outer'] }}"
     dusk="tallstackui_calendar"
     x-cloak>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::prefix('label')" scope="calendar.label" :label="$label" />
    @endif

    <div @class([
        $customization['wrapper.body'],
        $customization['wrapper.single'] => ! $double,
        $customization['wrapper.dual'] => $double,
    ])>
        <div @class(['relative', $customization['wrapper.single'] => $double])>
            <div class="{{ $customization['box.picker.button'] }}">
                <span class="{{ $customization['box.picker.button-label-wrapper'] }}">
                    <button type="button"
                            x-ref="monthAnchor"
                            x-text="calendar.months[month]"
                            x-on:click="toggleMonthPicker()"
                            @class([
                                $customization['label.month'],
                                $customization['label.locked'] => $lockMonthYear,
                            ])></button>
                    <button type="button"
                            x-ref="yearAnchor"
                            x-text="year"
                            x-on:click="toggleYearPicker()"
                            @class([
                                $customization['label.year'],
                                $customization['label.locked'] => $lockMonthYear,
                            ])></button>
                </span>

                {{-- Month picker floating popover --}}
                <x-dynamic-component :component="TallStackUi::prefix('floating')"
                                     scope="calendar.floating"
                                     x-show="picker.month"
                                     x-anchor="$refs.monthAnchor"
                                     :floating="$customization['floating.default']"
                                     :class="$customization['floating.class']">
                    <div class="{{ $customization['box.picker.wrapper.second'] }}">
                        <div class="{{ $customization['box.picker.wrapper.third'] }}">
                            <button type="button" class="{{ $customization['box.picker.label'] }}"
                                    x-on:click="if (monthYearOnly) {return false}; picker.month = false">
                                <span x-text="calendar.months[month]"
                                      class="{{ $customization['label.month'] }}"></span>
                            </button>
                            <button type="button" class="{{ $customization['box.picker.today'] }}" x-on:click="now()" x-show="!monthYearOnly">
                                {{ trans('ts-ui::messages.date.helpers.today') }}
                            </button>
                        </div>
                        <template x-for="(months, index) in calendar.months" :key="index">
                            <button class="{{ $customization['box.picker.range'] }}"
                                    type="button"
                                    x-bind:class="{ '{{ $customization['button.today'] }}': month === index }"
                                    x-on:click="selectMonth($event, index)"
                                    x-text="months.substring(0, 3)">
                            </button>
                        </template>
                    </div>
                </x-dynamic-component>

                {{-- Year picker floating popover --}}
                <x-dynamic-component :component="TallStackUi::prefix('floating')"
                                     scope="calendar.floating"
                                     x-show="picker.year"
                                     x-anchor="$refs.yearAnchor"
                                     :floating="$customization['floating.default']"
                                     :class="$customization['floating.class']">
                    <div class="{{ $customization['box.picker.wrapper.second'] }}">
                        <div class="{{ $customization['box.picker.wrapper.third'] }}">
                            <div class="{{ $customization['box.picker.label'] }}">
                                <span x-text="range.year.first" class="{{ $customization['label.month'] }}"></span>
                                <span class="{{ $customization['box.picker.separator'] }}">-</span>
                                <span x-text="range.year.last" class="{{ $customization['label.month'] }}"></span>
                            </div>
                            <button type="button" class="{{ $customization['box.picker.today'] }}" x-on:click="now()" x-show="!monthYearOnly">
                                {{ trans('ts-ui::messages.date.helpers.today') }}
                            </button>
                            <div class="{{ $customization['box.picker.navigate-wrapper'] }}">
                                <button type="button"
                                        dusk="tallstackui_date_previous_year"
                                        class="{{ $customization['button.navigate'] }}"
                                        x-on:pointerdown="if (!interval) { previousYear($event); interval = setInterval(() => previousYear($event), 200); }"
                                        x-on:pointerup="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:pointerleave="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:pointercancel="if (interval) { clearInterval(interval); interval = null; }">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('chevron-left')"
                                                         internal
                                                         class="{{ $customization['icon.navigate'] }}" />
                                </button>
                                <button type="button"
                                        dusk="tallstackui_date_next_year"
                                        class="{{ $customization['button.navigate'] }}"
                                        x-on:pointerdown="if (!interval) { nextYear($event); interval = setInterval(() => nextYear($event), 200); }"
                                        x-on:pointerup="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:pointerleave="if (interval) { clearInterval(interval); interval = null; }"
                                        x-on:pointercancel="if (interval) { clearInterval(interval); interval = null; }">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('chevron-right')"
                                                         internal
                                                         class="{{ $customization['icon.navigate'] }}" />
                                </button>
                            </div>
                        </div>
                        <template x-for="(range, index) in yearRange()" :key="index">
                            <button type="button" class="{{ $customization['box.picker.range'] }}"
                                    x-bind:class="{ '{{ $customization['button.today'] }}': range.year === year }"
                                    x-bind:disabled="range.disabled"
                                    x-on:click="selectYear($event, range.year)"
                                    x-text="range.year">
                            </button>
                        </template>
                    </div>
                </x-dynamic-component>
                <div>
                    <button type="button"
                            dusk="tallstackui_date_previous_month"
                            class="{{ $customization['button.navigate'] }}"
                            x-on:pointerdown="if (!interval) { previousMonth(); interval = setInterval(() => previousMonth(), 200); }"
                            x-on:pointerup="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:pointerleave="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:pointercancel="if (interval) { clearInterval(interval); interval = null; }">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('chevron-left')"
                                             internal
                                             class="{{ $customization['icon.navigate'] }}" />
                    </button>
                    <button type="button"
                            class="{{ $customization['button.navigate'] }}"
                            dusk="tallstackui_date_next_month"
                            x-on:pointerdown="if (!interval) { nextMonth(); interval = setInterval(() => nextMonth(), 200); }"
                            x-on:pointerup="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:pointerleave="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:pointercancel="if (interval) { clearInterval(interval); interval = null; }">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('chevron-right')"
                                             internal
                                             class="{{ $customization['icon.navigate'] }}" />
                    </button>
                </div>
            </div>
            <div class="{{ $customization['wrapper.days-header'] }}" x-show="!monthYearOnly">
                <template x-for="(day, index) in calendar.week" :key="index">
                    <div class="{{ $customization['label.days-cell'] }}">
                        <div x-text="day.slice(0, 3)" class="{{ $customization['label.days'] }}"></div>
                    </div>
                </template>
            </div>
            <div class="{{ $customization['wrapper.days-grid'] }}" x-show="!monthYearOnly">
                <template x-for="(blank, index) in blanks" :key="index">
                    <div class="{{ $customization['button.blank'] }}"></div>
                </template>
                <template x-for="(day, index) in days" :key="index">
                    <div class="{{ $customization['button.day-wrapper'] }}"
                         x-bind:class="{
                            '{{ $customization['range.start'] }}': day.isStart,
                            '{{ $customization['range.end'] }}': day.isEnd,
                            '{{ $customization['range.between'] }}': day.isBetween,
                         }">
                        <button type="button"
                                x-text="day.day"
                                {{ $attributes->only('x-on:select') }}
                                x-on:click="select($event, day);"
                                x-bind:disabled="day.disabled"
                                x-bind:class="{
                                    '{{ $customization['button.today'] }}': day.isToday,
                                    '{{ $customization['button.select'] }}': !day.isToday && !day.isSelected,
                                    '{{ $customization['button.selected'] }}': day.isSelected
                                }" class="{{ $customization['button.day'] }}">
                        </button>
                    </div>
                </template>
            </div>
        </div>

        @if ($double)
            <div @class(['relative', $customization['wrapper.single']])>
                <div class="{{ $customization['box.picker.button'] }}">
                    <span>
                        <span x-text="calendar.months[secondaryMonth]" class="{{ $customization['label.month'] }}"></span>
                    </span>
                </div>
                <div class="{{ $customization['wrapper.days-header'] }}" x-show="!monthYearOnly">
                    <template x-for="(day, index) in calendar.week" :key="index">
                        <div class="{{ $customization['label.days-cell'] }}">
                            <div x-text="day.slice(0, 3)" class="{{ $customization['label.days'] }}"></div>
                        </div>
                    </template>
                </div>
                <div class="{{ $customization['wrapper.days-grid'] }}" x-show="!monthYearOnly">
                    <template x-for="(blank, index) in blanksSecondary" :key="index">
                        <div class="{{ $customization['button.blank'] }}"></div>
                    </template>
                    <template x-for="(day, index) in daysSecondary" :key="index">
                        <div class="{{ $customization['button.day-wrapper'] }}"
                             x-bind:class="{
                                '{{ $customization['range.start'] }}': day.isStart,
                                '{{ $customization['range.end'] }}': day.isEnd,
                                '{{ $customization['range.between'] }}': day.isBetween,
                             }">
                            <button type="button"
                                    x-text="day.day"
                                    {{ $attributes->only('x-on:select') }}
                                    x-on:click="select($event, day);"
                                    x-bind:disabled="day.disabled"
                                    x-bind:class="{
                                        '{{ $customization['button.today'] }}': day.isToday,
                                        '{{ $customization['button.select'] }}': !day.isToday && !day.isSelected,
                                        '{{ $customization['button.selected'] }}': day.isSelected
                                    }" class="{{ $customization['button.day'] }}">
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        @endif
    </div>

    @if ($helpers)
        <div class="{{ $customization['wrapper.helpers'] }}">
            @foreach (['yesterday', 'today', 'tomorrow'] as $helper)
                <button type="button"
                        dusk="tallstackui_date_helper_{{ $helper }}"
                        x-on:click="helper($event, @js($helper))"
                        class="{{ $customization['button.helpers'] }}">
                    {{ trans('ts-ui::messages.date.helpers.' . $helper) }}
                </button>
            @endforeach
        </div>
    @endif

    @if ($hint)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" scope="calendar.hint" :hint="$hint" />
    @endif
</div>
