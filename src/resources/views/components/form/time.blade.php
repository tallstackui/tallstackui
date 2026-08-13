@php
    $customization = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_formTime(
    {!! $entangle !!},
    @js($format === '24'),
    {...@js($boundaries)},
    @js($attributes->get('required', false)),
    @js($livewire),
    @js($property),
    @js($attributes->get('value')),
    @js($disabled),
    @js($readonly),
    @js($change))"
     x-cloak x-on:click.outside="show = false">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         scope="form.time.input"
                         {{ $attributes->except('name')->whereDoesntStartWith('wire:model') }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$property"
                         floatable
                         x-ref="input"
                         x-on:click="!locked() && (show = !show)"
                         x-on:keydown="$event.preventDefault()"
                         dusk="tallstackui_time_input"
                         class="cursor-pointer {{ $customization['input.caret'] }}">
        <x-slot:suffix :class="$customization['slot.spacing']">
            <div class="{{ $customization['icon.wrapper'] }}">
                @if (!$attributes->has('required'))
                    <button type="button" class="cursor-pointer" x-on:click="clear()" x-show="model" @disabled($locked)>
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             dusk="tallstackui_time_clear"
                                             internal
                                             :icon="TallStackUi::icon('x-mark')"
                                @class([$customization['icon.size'], $customization['icon.clear']]) />
                    </button>
                @endif
                <button type="button" class="cursor-pointer" x-on:click="show = !show" @disabled($locked)>
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
        <div @class(['flex', $customization['wrapper-floating.base'], $customization['wrapper-floating.with-helper-or-footer'] => $helper || $footer?->isNotEmpty(), $customization['wrapper-floating.wide-format'] => $format === '24'])>
            <div class="{{ $customization['wrapper'] }}">
                <span x-text="formatted.hours"
                      x-ref="hours"
                      x-on:wheel.prevent="scroll($event, 'hours')"
                      x-on:pointerdown.prevent="grab($event, 'hours'); $el.classList.add('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                      x-on:pointermove="drag($event)"
                      x-on:pointerup="release(); $el.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                      x-on:pointercancel="release(); $el.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                      dusk="tallstackui_time_drag_hours"
                      class="{{ $customization['time'] }}"></span>
                <span class="{{ $customization['separator'] }}">:</span>
                <span x-text="formatted.minutes"
                      x-ref="minutes"
                      x-on:wheel.prevent="scroll($event, 'minutes')"
                      x-on:pointerdown.prevent="grab($event, 'minutes'); $el.classList.add('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                      x-on:pointermove="drag($event)"
                      x-on:pointerup="release(); $el.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                      x-on:pointercancel="release(); $el.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                      dusk="tallstackui_time_drag_minutes"
                      class="{{ $customization['time'] }}"></span>
                @if ($format === '12')
                    <div class="{{ $customization['interval.wrapper'] }}">
                        <p class="{{ $customization['interval.text'] }}" x-text="interval"></p>
                    </div>
                @endif
            </div>
            <div wire:ignore.self class="{{ $customization['helper.wrapper'] }}">
                <input type="range"
                       min="{{ $boundaries['hour']['min'] }}"
                       max="{{ $boundaries['hour']['max'] }}"
                       step="{{ $stepHour ?? 1 }}"
                       x-model="hours"
                       x-ref="rangeHours"
                       x-on:change="change($event, 'hours');"
                       x-on:wheel.prevent="scroll($event, 'hours')"
                       {{ $attributes->only('x-on:hour') }}
                       dusk="tallstackui_time_hours"
                       x-on:mouseenter="$refs.hours.classList.add('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                       x-on:mouseleave="$refs.hours.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                        @class([$customization['range.focus'], $customization['range.base'], $customization['range.thumb']])>
                <input type="range"
                       min="{{ $boundaries['minute']['min'] }}"
                       max="{{ $boundaries['minute']['max'] }}"
                       step="{{ $stepMinute ?? 1 }}"
                       x-model="minutes"
                       x-ref="rangeMinutes"
                       x-on:change="change($event, 'minutes');"
                       x-on:wheel.prevent="scroll($event, 'minutes')"
                       {{ $attributes->only('x-on:minute') }}
                       dusk="tallstackui_time_minutes"
                       x-on:mouseenter="$refs.minutes.classList.add('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                       x-on:mouseleave="$refs.minutes.classList.remove('{{ $customization['range.light'] }}', '{{ $customization['range.dark'] }}')"
                        @class([$customization['range.focus'], $customization['range.base'], $customization['range.thumb']])>
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
                                         @class([$customization['helper.button'], $customization['helper.button-format-24'] => $format === '24'])
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
