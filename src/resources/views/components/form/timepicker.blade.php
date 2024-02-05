@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<div x-data="tallstackui_formTimePicker({!! $entangle !!}, @js($format === '24'))"
    {{ $attributes->only(['x-on:hour', 'x-on:minute']) }}
    x-ref="wrapper"
    x-cloak>
    <x-dynamic-component :component="TallStackUi::component('input')"
                         {{ $attributes->whereStartsWith('wire:model') }}
                         :$label
                         :$hint
                         :$invalidate
                         readonly
                         icon="clock"
                         position="right"
                         x-ref="input"
                         x-on:click="show = !show"
                         dusk="tallstackui_timepicker_input"
                         class="cursor-pointer" />
    <div x-cloak
        x-show="show"
        x-on:click.away="show = false"
        x-transition:enter="transition duration-100 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-anchor.bottom-end="$refs.wrapper"
        @class($personalize['wrapper.first'])>
        <div @class($personalize['wrapper.second'])>
            <div @class($personalize['wrapper.third'])>
                <span x-text="formatted.hours"
                    x-ref="hours"
                    @class($personalize['time'])>
                </span>
                <span  @class($personalize['separator'])>:</span>
                <span x-text="formatted.minutes"
                    x-ref="minutes"
                    @class($personalize['time'])>
                </span>
                @if ($format === '12')
                    <div @class($personalize['format.wrapper'])>
                        <div @class($personalize['format.size'])>
                            <input type="radio"
                                   id="am"
                                   x-model="interval"
                                   value="AM"
                                   @class($personalize['format.input'])>
                            <label for="am"
                                   dusk="tallstackui_timepicker_am"
                                   @class([$personalize['format.color'], $personalize['format.am.label']])>
                                <div @class($personalize['format.am.title'])>AM</div>
                            </label>
                        </div>
                        <div @class($personalize['format.size'])>
                            <input type="radio"
                                   id="pm"
                                   x-model="interval"
                                   value="PM"
                                   @class($personalize['format.input'])>
                            <label for="pm"
                                   dusk="tallstackui_timepicker_pm"
                                   @class([$personalize['format.color'], $personalize['format.pm.label']])>
                                <div @class($personalize['format.pm.title'])>PM</div>
                            </label>
                        </div>
                    </div>
                @endif
            </div>
            <div wire:ignore.self @class([$personalize['helper.wrapper'], 'mb-2' => !$helper])>
                <input type="range"
                       min="{{ $format === '12' ? 1 : 0 }}"
                       max="{{ $format === '12' ? 12 : 23 }}"
                       step="{{ $stepHour ?? 1 }}"
                       x-model="hours"
                       dusk="tallstackui_timepicker_hours"
                       x-on:mouseenter="$refs.hours.classList.add('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       x-on:mouseleave="$refs.hours.classList.remove('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       @class(['focus:outline-none', $personalize['range.base'], $personalize['range.thumb']])>
                <input type="range"
                       min="0"
                       max="59"
                       step="{{ $stepMinute ?? 1 }}"
                       x-model="minutes"
                       dusk="tallstackui_timepicker_minutes"
                       x-on:mouseenter="$refs.minutes.classList.add('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       x-on:mouseleave="$refs.minutes.classList.remove('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       @class(['focus:outline-none', $personalize['range.base'], $personalize['range.thumb']])>
            </div>
            @if ($helper)
                <div class="mt-4">
                    <x-dynamic-component :component="TallStackUi::component('button')"
                                         :text="__('tallstack-ui::messages.timepicker.helper')"
                                         type="button"
                                         @class([$personalize['helper.button']])
                                         x-on:click="current()"
                                         {{ $attributes->only('x-on:current') }}
                                         dusk="tallstackui_timepicker_current"
                                         xs />
                </div>
            @endif
            @if ($footer)
                {{ $footer }}
            @endif
        </div>
    </div>
</div>
