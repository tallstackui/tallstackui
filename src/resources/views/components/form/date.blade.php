@php
    $customization = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_formDate(
     {!! $entangle !!},
     @js($range),
     @js($multiple),
     @js($format),
     {...@js($dates())},
     @js($disable->toArray()),
     @js($livewire),
     @js($property),
     @js($value),
     @js($monthYearOnly),
     @js(trans('ts-ui::messages.date.calendar')),
     @js($attributes->only(['disabled', 'readonly'])->getAttributes()),
     @js($change),
     @js($start),
     @js($only),
     @js($weekdays),
     @js($weekends))"
     x-cloak x-on:click.outside="show = false">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         scope="form.date.input"
                         {{ $attributes->except(['name', 'value'])->whereDoesntStartWith('wire:model') }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$attributes->get('name')"
                         floatable
                         x-ref="input"
                         x-on:click="(disables['disabled'] ?? false) || (disables['readonly'] ?? false) ? false : show = !show"
                         x-on:keydown="$event.preventDefault()"
                         dusk="tallstackui_date_input"
                         class="cursor-pointer {{ $customization['input.caret'] }}">
        <x-slot:suffix :class="$customization['slot.icon-spacing']">
            <div class="{{ $customization['icon.wrapper'] }}">
                <button type="button" class="cursor-pointer" x-on:click="clear()" x-show="quantity > 0"
                        {{ $attributes->only(['disabled', 'readonly', 'x-on:clear']) }} dusk="tallstackui_date_clear">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         internal
                            @class([$customization['icon.size'], $customization['icon.clear']])/>
                </button>
                <button type="button" class="cursor-pointer"
                        x-on:click="(disables['disabled'] ?? false) || (disables['readonly'] ?? false) ? false : show = !show"
                        {{ $attributes->only(['disabled', 'readonly']) }} dusk="tallstackui_date_open_close">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('calendar')"
                                         internal
                                         class="{{ $customization['icon.size'] }}" />
                </button>
            </div>
        </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::prefix('floating')"
                         scope="form.date.floating"
                         :floating="$customization['floating.default']"
                         :class="$customization['floating.class']"
                         x-bind:class="{ '{{ $customization['floating.expanded'] }}' : picker.year || picker.month }">
        <div class="{{ $customization['box.picker.button'] }}">
            <span>
                <button type="button" x-text="calendar.months[month]" x-on:click="picker.month = true"
                        class="{{ $customization['label.month'] }}"></button>
                <button type="button" x-text="year" x-on:click="picker.year = true; range.year.start = (year - 11)"
                        class="{{ $customization['label.year'] }}"></button>
            </span>
            <template x-if="picker.month">
                <div class="{{ $customization['box.picker.wrapper.first'] }}" x-cloak>
                    <div class="{{ $customization['box.picker.wrapper.second'] }}">
                        <div class="{{ $customization['box.picker.wrapper.third'] }}">
                            <button type="button" class="{{ $customization['box.picker.label'] }}"
                                    x-on:click="if (monthYearOnly) {return false}; picker.month = false">
                                <span x-text="calendar.months[month]"
                                      class="{{ $customization['label.month'] }}"></span>
                            </button>
                            <button type="button" class="{{ $customization['slot.icon-spacing-right'] }} cursor-pointer" x-on:click="now()" x-show="!monthYearOnly">
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
                </div>
            </template>
            <template x-if="picker.year">
                <div class="{{ $customization['box.picker.wrapper.first'] }}" x-cloak>
                    <div class="{{ $customization['box.picker.wrapper.second'] }}">
                        <div class="{{ $customization['box.picker.wrapper.third'] }}">
                            <div class="{{ $customization['box.picker.label'] }}">
                                <span x-text="range.year.first" class="{{ $customization['label.month'] }}"></span>
                                <span class="{{ $customization['box.picker.separator'] }}">-</span>
                                <span x-text="range.year.last" class="{{ $customization['label.month'] }}"></span>
                            </div>
                            <button type="button" class="cursor-pointer" x-on:click="now()" x-show="!monthYearOnly">
                                {{ trans('ts-ui::messages.date.helpers.today') }}
                            </button>
                            <div>
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
                </div>
            </template>
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
        <x-slot:footer>
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
                                x-on:click="select($event, day.day);"
                                x-bind:disabled="day.disabled"
                                x-bind:class="{
                                    '{{ $customization['button.today'] }}': day.isToday,
                                    '{{ $customization['button.select'] }}': !day.isToday && !day.isSelected,
                                    '{{ $customization['button.selected'] }}': day.isSelected
                                }" class="{{ $customization['button.day'] }}" x-show="!picker.year && !picker.month">
                        </button>
                    </div>
                </template>
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
        </x-slot:footer>
    </x-dynamic-component>
</div>
