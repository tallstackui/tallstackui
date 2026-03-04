@php
    $customization = $classes();
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
    @js($attributes->only(['disabled', 'readonly'])->getAttributes()),
    @js($change))"
     x-cloak x-on:click.outside="show = false">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         scope="form.time.input"
                         {{ $attributes->except('name')->whereDoesntStartWith('wire:model') }}
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
            <div class="{{ $customization['icon.wrapper'] }}">
                @if (!$attributes->has('required'))
                    <button type="button" class="cursor-pointer" x-on:click="clear()" x-show="model">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             dusk="tallstackui_time_clear"
                                             internal
                                             :icon="TallStackUi::icon('x-mark')"
                                @class([$customization['icon.size'], $customization['icon.clear']]) />
                    </button>
                @endif
                <button type="button" class="cursor-pointer"
                        x-on:click="(disables['disabled'] ?? false) || (disables['readonly'] ?? false) ? false : show = !show">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('clock')"
                                         internal
                                         class="{{ $customization['icon.size'] }}" />
                </button>
            </div>
        </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::prefix('floating')"
                         scope="form.time.floating"
                         :floating="$customization['floating.default']"
                         :class="$customization['floating.class']">
        <div @class(['flex flex-col', 'mb-2' => $helper || $footer?->isNotEmpty(), 'w-full' => $format === '24'])>
            <div class="{{ $customization['wrapper'] }}">
                <span x-text="formatted.hours" x-ref="hours" class="{{ $customization['time'] }}"></span>
                <span class="{{ $customization['separator'] }}">:</span>
                <span x-text="formatted.minutes" x-ref="minutes" class="{{ $customization['time'] }}"></span>
                @if ($format === '12')
                    <div class="{{ $customization['interval.wrapper'] }}">
                        <p class="{{ $customization['interval.text'] }}" x-text="interval"></p>
                    </div>
                @endif
            </div>
            <div wire:ignore.self class="{{ $customization['helper.wrapper'] }}">
                <input type="range"
                       min="{{ $format === '12' ? 1 : 0 }}"
                       max="{{ $format === '12' ? 12 : 23 }}"
                       step="{{ $stepHour ?? 1 }}"
                       x-model="hours"
                       x-on:change="change($event, 'hours');"
                       {{ $attributes->only('x-on:hour') }}
                       dusk="tallstackui_time_hours"
                       x-on:change="alert(1);"
                       x-on:mouseenter="$refs.hours.classList.add('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                       x-on:mouseleave="$refs.hours.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                        @class(['focus:outline-hidden', $customization['range.base'], $customization['range.thumb']])>
                <input type="range"
                       min="0"
                       max="59"
                       step="{{ $stepMinute ?? 1 }}"
                       x-model="minutes"
                       x-on:change="change($event, 'minutes');"
                       {{ $attributes->only('x-on:minute') }}
                       dusk="tallstackui_time_minutes"
                       x-on:mouseenter="$refs.minutes.classList.add('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                       x-on:mouseleave="$refs.minutes.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                        @class(['focus:outline-hidden', $customization['range.base'], $customization['range.thumb']])>
            </div>
            @if ($format === '12')
                <div x-ref="format"
                     {{ $attributes->only('x-on:interval') }} class="{{ $customization['interval.buttons.wrapper'] }}">
                    <button type="button"
                            x-on:click="select('AM')"
                            class="{{ $customization['interval.buttons.am'] }}"
                            dusk="tallstackui_time_am">AM
                    </button>
                    <button type="button"
                            x-on:click="select('PM')"
                            class="{{ $customization['interval.buttons.pm'] }}"
                            dusk="tallstackui_time_pm">PM
                    </button>
                </div>
            @endif
        </div>
        @if ($helper || $footer)
            <x-slot:footer>
                @if ($helper)
                    <x-dynamic-component :component="TallStackUi::prefix('button')"
                                         scope="form.time.button"
                                         :text="trans('ts-ui::messages.time.helper')"
                                         type="button"
                                         @class([$customization['helper.button'], 'mt-2' => $format === '24'])
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
