@php
    $personalize = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_formTime(
    {!! $entangle !!},
    @js($format === '24'),
    {...@js($times())},
    @js($attributes->get('required', false)),
    @js($livewire),
    @js($property),
    @js($attributes->get('value')),
    @js($attributes->only(['disabled', 'readonly'])->all()),
    @js($change))"
    x-cloak x-on:click.outside="show = false">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         {{ $attributes->except('name') }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$attributes->get('name')"
                         floatable
                         x-ref="input"
                         x-on:click="(disables['disabled'] ?? false) || (disables['readonly'] ?? false) ? false : show = !show"
                         x-on:keydown="$event.preventDefault()"
                         dusk="tallstackui_time_input"
                         class="cursor-pointer caret-transparent">
                         <x-slot:suffix class="ml-1 mr-2">
                             <div class="{{ $personalize['icon.wrapper'] }}">
                                 @if (!$attributes->has('required'))
                                    <button type="button" class="cursor-pointer" x-on:click="clear()" x-show="model">
                                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                             dusk="tallstackui_time_clear"
                                                             internal
                                                             :icon="TallStackUi::icon('x-mark')"
                                                             @class([$personalize['icon.size'], $personalize['icon.clear']]) />
                                    </button>
                                 @endif
                                <button type="button" class="cursor-pointer" x-on:click="(disables['disabled'] ?? false) || (disables['readonly'] ?? false) ? false : show = !show">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('clock')"
                                                         internal
                                                         class="{{ $personalize['icon.size'] }}" />
                                </button>
                             </div>
                         </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::prefix('floating')"
                         :floating="$personalize['floating.default']"
                         :class="$personalize['floating.class']">
        <div @class(['flex flex-col', 'mb-2' => $helper || $footer?->isNotEmpty(), 'w-full' => $format === '24'])>
            <div class="{{ $personalize['wrapper'] }}">
                <span x-text="formatted.hours" x-ref="hours" class="{{ $personalize['time'] }}"></span>
                <span class="{{ $personalize['separator'] }}">:</span>
                <span x-text="formatted.minutes" x-ref="minutes" class="{{ $personalize['time'] }}"></span>
                @if ($format === '12')
                    <div class="{{ $personalize['interval.wrapper'] }}">
                        <p class="{{ $personalize['interval.text'] }}" x-text="interval"></p>
                    </div>
                @endif
            </div>
            <div wire:ignore.self class="{{ $personalize['helper.wrapper'] }}">
                <input type="range"
                       min="{{ $format === '12' ? 1 : 0 }}"
                       max="{{ $format === '12' ? 12 : 23 }}"
                       step="{{ $stepHour ?? 1 }}"
                       x-model="hours"
                       x-on:change="change($event, 'hours');"
                       {{ $attributes->only('x-on:hour') }}
                       dusk="tallstackui_time_hours"
                       x-on:change="alert(1);"
                       x-on:mouseenter="$refs.hours.classList.add('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       x-on:mouseleave="$refs.hours.classList.remove('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       @class(['focus:outline-hidden', $personalize['range.base'], $personalize['range.thumb']])>
                <input type="range"
                       min="0"
                       max="59"
                       step="{{ $stepMinute ?? 1 }}"
                       x-model="minutes"
                       x-on:change="change($event, 'minutes');"
                       {{ $attributes->only('x-on:minute') }}
                       dusk="tallstackui_time_minutes"
                       x-on:mouseenter="$refs.minutes.classList.add('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       x-on:mouseleave="$refs.minutes.classList.remove('{{ $personalize['range.light'] }}', '{{ $personalize['range.dark'] }}')"
                       @class(['focus:outline-hidden', $personalize['range.base'], $personalize['range.thumb']])>
            </div>
            @if ($format === '12')
                <div x-ref="format" {{ $attributes->only('x-on:interval') }} class="{{ $personalize['interval.buttons.wrapper'] }}">
                    <button type="button"
                            x-on:click="select('AM')"
                            class="{{ $personalize['interval.buttons.am'] }}"
                            dusk="tallstackui_time_am">AM</button>
                    <button type="button"
                            x-on:click="select('PM')"
                            class="{{ $personalize['interval.buttons.pm'] }}"
                            dusk="tallstackui_time_pm">PM</button>
                </div>
            @endif
        </div>
        @if ($helper || $footer)
            <x-slot:footer>
                @if ($helper)
                <x-dynamic-component :component="TallStackUi::prefix('button')"
                                     :text="trans('tallstack-ui::messages.time.helper')"
                                     type="button"
                                     @class([$personalize['helper.button'], 'mt-2' => $format === '24'])
                                     x-on:click="current()"
                                     {{ $attributes->only('x-on:current') }}
                                     dusk="tallstackui_time_current"
                                     xs />
                @endif
                @if ($footer)
                    {{ $footer }}
                @endif
            </x-slot:footer>
        @endif
    </x-dynamic-component>
</div>
