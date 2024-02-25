@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_datepicker(
            {!! $entangle !!},
            @js($range),
            @js($multiple),
            @js($format),
            @js($minDate),
            @js($maxDate),
            @js($minYear),
            @js($maxYear),
            @js($disable))"
         x-cloak
         x-on:click.outside="picker.common = false">
        <div x-ref="anchor"
                @class([
                    $personalize['input.class.wrapper'],
                    $personalize['input.class.color.base'] => !$error,
                    $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
                    $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
                    $personalize['error'] => $error
                ])>
            <input type="text"
                   readonly
                   x-model="value"
                   {{ $attributes->whereDoesntStartWith('wire:model') }}
                   x-on:click="picker.common = !picker.common; picker.year = false;"
                   x-on:keydown.escape="picker.common = false"
                   @class(['cursor-pointer', $personalize['input.class.base']]) />
            <div @class(['mr-2', $personalize['icon.input']])>
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     icon="x-mark"
                                     class="w-5 h-5 hover:text-red-500"
                                     x-show="value"
                                     x-on:click="clear()" />
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     icon="calendar"
                                     @class(['w-5 h-5', $personalize['error'] => $error])
                                     x-on:click="picker.common = !picker.common" />
            </div>
        </div>
        <div x-show="picker.common"
             x-anchor.bottom-end.offset.10="$refs.anchor"
             x-transition:enter="transition duration-100 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-on:click.away="if (@js($range) && date.end || !@js($range)) picker.common = false"
             @class($personalize['box.wrapper'])>
            <div class="flex items-center justify-between mb-4">
                <div @class($personalize['box.picker.button'])>
                    <span>
                        <span x-text="period.month[month]" x-on:click="picker.month = true"  @class($personalize['label.month'])></span>
                        <span x-text="year" x-on:click="toggleYear()" @class($personalize['label.year'])></span>
                    </span>
                    {{-- Month --}}
                    <template x-if="picker.month">
                        <div @class($personalize['box.picker.wrapper.first']) x-cloak>
                            <div @class($personalize['box.picker.wrapper.second'])>
                                <div @class($personalize['box.picker.wrapper.third'])>
                                    <div @class($personalize['box.picker.label']) x-on:click="picker.month = false">
                                        <span x-text="period.month[month]" @class($personalize['label.month'])></span>
                                    </div>
                                </div>
                                <template x-for="(monthRange, index) in period.month">
                                    <div @class($personalize['box.picker.range'])
                                         x-bind:class="{ '{{ $personalize['button.today'] }}': month === index }"
                                         x-on:click="selectMonth($event, index)"
                                         x-text="monthRange.substring(0, 3)">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    {{-- Year --}}
                    <template x-if="picker.year">
                        <div @class($personalize['box.picker.wrapper.first']) x-cloak>
                            <div @class($personalize['box.picker.wrapper.second'])>
                                <div @class($personalize['box.picker.wrapper.third'])>
                                    <div @class($personalize['box.picker.label'])>
                                        <span x-text="range.year.first" @class($personalize['label.month'])></span>
                                        <span class="mx-1">-</span>
                                        <span x-text="range.year.last" @class($personalize['label.month'])></span>
                                    </div>
                                    <button x-on:click="range.year.start = new Date().getFullYear(); selectYear($event, new Date().getFullYear())">
                                        {{ __('tallstack-ui::messages.datepicker.today') }}
                                    </button>
                                    <div>
                                        <button @class($personalize['button.navigate'])
                                                x-on:click="previousYearRange($event)"
                                                x-on:mousedown="if (!interval) interval = setInterval(() => previousYearRange($event), 200);"
                                                x-on:touchstart="if (!interval) interval = setInterval(() => previousYearRange($event), 200);"
                                                x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                                                x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                                                x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" @class($personalize['icon.navigate']) />
                                        </button>
                                        <button @class($personalize['button.navigate'])
                                                x-on:click="nextYearRange($event)"
                                                x-on:mousedown="if (!interval) interval = setInterval(() => nextYearRange($event), 200);"
                                                x-on:touchstart="if (!interval) interval = setInterval(() => nextYearRange($event), 200);"
                                                x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                                                x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                                                x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                                            <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" @class($personalize['icon.navigate']) />
                                        </button>
                                    </div>
                                </div>
                                <template x-for="range in yearRange()">
                                    <button type="button" @class($personalize['box.picker.range'])
                                         x-bind:class="{ '{{ $personalize['button.today'] }}': range === new Date().getFullYear() }"
                                         x-bind:disabled="range.disabled"
                                         x-on:click="selectYear($event, range.year)"
                                         x-text="range.year">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
                <div>
                    <button type="button"
                            @class($personalize['button.navigate'])
                            x-on:click="previousMonth()"
                            x-on:mousedown="if (!interval) interval = setInterval(() => previousMonth(), 200);"
                            x-on:touchstart="if (!interval) interval = setInterval(() => previousMonth(), 200);"
                            x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-left" @class($personalize['icon.navigate']) />
                    </button>
                    <button type="button" @class($personalize['button.navigate'])
                            x-on:click="nextMonth()"
                            x-on:mousedown="if (!interval) interval = setInterval(() => nextMonth(), 200);"
                            x-on:touchstart="if (!interval) interval = setInterval(() => nextMonth(), 200);"
                            x-on:mouseup="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:mouseleave="if (interval) { clearInterval(interval); interval = null; }"
                            x-on:touchend="if (interval) { clearInterval(interval); interval = null; }">
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="chevron-right" @class($personalize['icon.navigate']) />
                    </button>
                </div>
            </div>
            {{-- DoW --}}
            <div class="grid grid-cols-7 mb-3">
                <template x-for="(day, index) in period.week" :key="index">
                    <div class="px-0.5">
                        <div x-text="day" @class($personalize['label.days'])></div>
                    </div>
                </template>
            </div>
            <div class="grid grid-cols-7">
                <template x-for="blank in blanks">
                    <div class="p-1 text-sm text-center border border-transparent"></div>
                </template>
                <template x-for="(day, index) in days" :key="index">
                    <div class="mb-2"
                         :class="{
                            'rounded-l-full': new Date(day.instance).getTime() === new Date(date.start).getTime(),
                            'rounded-r-full w-7 h-7': new Date(day.instance).getTime() === new Date(date.end).getTime(),
                            '{{ $personalize['range'] }}': dateInterval(day.instance) === true,
                         }">
                        <button x-text="day.day"
                                x-on:click="clicked(day.day)"
                                x-bind:disabled="day.disabled"
                                :class="{
                                    '{{ $personalize['button.today'] }}': isToday(day.day) === true,
                                    '{{ $personalize['button.select'] }}': isToday(day.day) === false && selectedDate(day.day) === false && !day.disabled,
                                    '{{ $personalize['button.selected'] }}': selectedDate(day.day) === true
                                }"
                                @class($personalize['button.day'])>
                        </button>
                    </div>
                </template>
            </div>
            {{-- Helpers --}}
            @if (!$helpers->isEmpty() && $multiple === false)
                <div @class($personalize['wrapper.helpers'])>
                    @foreach ($helpers as $helper)
                        @if ($helpers->contains($helper))
                            <button x-on:click="change('{{ $helper }}')" @class($personalize['button.helpers'])>{{ __('tallstack-ui::messages.datepicker.' . $helper) }}</button>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-dynamic-component>
