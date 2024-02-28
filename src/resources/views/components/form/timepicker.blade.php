@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $attributes->get('value');
@endphp

@if (!$livewire && $property)
    <input hidden id="{{ $id }}" name="{{ $property }}">
@endif

<div x-data="tallstackui_formTimePicker(
    {!! $entangle !!},
    @js($format === '24'),
    @js($livewire),
    @js($property),
    @js($value))"
    {{ $attributes->only(['x-on:hour', 'x-on:minute']) }}
    x-cloak
    x-on:click.outside="show = false">
    <x-dynamic-component :component="TallStackUi::component('input')"
                         {{ $attributes->except('name') }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$attributes->get('name')"
                         x-ref="input"
                         x-on:click="show = !show"
                         dusk="tallstackui_timepicker_input"
                         class="cursor-pointer caret-transparent">
                         <x-slot:suffix>
                             <div class="flex items-center gap-1">
                                <button type="button" x-on:click="show = !show">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                         :icon="TallStackUi::icon('clock')"
                                                         @class($personalize['icon']) />
                                </button>
                             </div>
                         </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::component('floating')"
                         second-wrapper="flex items-center justify-between"
                         size="w-[18rem]">
        <div @class(['flex flex-col', 'mb-4' => $helper || $footer->isNotEmpty(), 'w-full' => $format === '24'])>
            <div @class($personalize['wrapper'])>
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
        </div>
        @if ($helper || $footer)
            <x-slot:footer>
                @if ($helper)
                <x-dynamic-component :component="TallStackUi::component('button')"
                                     :text="__('tallstack-ui::messages.timepicker.helper')"
                                     type="button"
                                     @class([$personalize['helper.button']])
                                     x-on:click="current()"
                                     {{ $attributes->only('x-on:current') }}
                                     dusk="tallstackui_timepicker_current"
                                     xs />
                @endif
                @if ($footer)
                    {{ $footer }}
                @endif
            </x-slot:footer>
        @endif
    </x-dynamic-component>
</div>
