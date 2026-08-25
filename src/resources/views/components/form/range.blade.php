@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint
                     :$invalidate :wrapper="$customization['input.wrapper']">
    @if ($dual)
        <div x-data="tallstackui_formRange({!! $entangle !!}, @js($initial), @js($min), @js($max), @js($step), @js($disabled), @js($readonly))"
             {{ $attributes->except('name')->whereDoesntStartWith('wire:model')->class([
                    $customization['dual.wrapper.base'],
                    $customization['dual.wrapper.sizes.' . $size],
                    $customization['dual.wrapper.locked'] => $locked,
                ]) }}
             dusk="tallstackui_form_range_dual">
            <div @class([$customization['dual.track.base'], $customization['dual.track.sizes.' . $size]])></div>
            <div x-bind:style="progressStyle"
                 dusk="tallstackui_form_range_progress"
                 @class([$customization['dual.track.progress'], $customization['dual.track.sizes.' . $size], $colors['progress']])></div>
            <input @if ($id) id="{{ $id }}" @endif
            type="range"
                   min="{{ $min }}"
                   max="{{ $max }}"
                   step="{{ $step }}"
                   value="{{ $initial[0] }}"
                   @if ($attributes->get('name')) name="{{ $attributes->get('name') }}" @endif
                   x-ref="start"
                   x-bind:value="start"
                   x-bind:style="{ zIndex: startOnTop ? 20 : 10 }"
                   x-on:input="moveStart($event)"
                   x-on:change="sync()"
                   @if ($readonly)
                       x-on:pointerdown.prevent
                       x-on:keydown="window.tallstackui_lockKeydown($event)"
                       aria-readonly="true"
                   @endif
                   @disabled($disabled)
                   @class([$customization['dual.input.base'], $customization['dual.input.sizes.' . $size], $colors['thumb']])
                   dusk="tallstackui_form_range_start">
            <input type="range"
                   min="{{ $min }}"
                   max="{{ $max }}"
                   step="{{ $step }}"
                   value="{{ $initial[1] }}"
                   @if ($attributes->get('name')) name="{{ $attributes->get('name') }}" @endif
                   x-ref="end"
                   x-bind:value="end"
                   x-bind:style="{ zIndex: startOnTop ? 10 : 20 }"
                   x-on:input="moveEnd($event)"
                   x-on:change="sync()"
                   @if ($readonly)
                       x-on:pointerdown.prevent
                       x-on:keydown="window.tallstackui_lockKeydown($event)"
                       aria-readonly="true"
                   @endif
                   @disabled($disabled)
                   @class([$customization['dual.input.base'], $customization['dual.input.sizes.' . $size], $colors['thumb']])
                   dusk="tallstackui_form_range_end">
            @if ($tooltip)
                <div x-show="dragging === 'start'"
                     x-bind:style="{ left: percent(start) + '%' }"
                     x-cloak
                     @class([$customization['dual.tooltip.wrapper']])>
                    <div x-text="start"
                         dusk="tallstackui_form_range_tooltip_start"
                         @class([$customization['dual.tooltip.base']])></div>
                </div>
                <div x-show="dragging === 'end'"
                     x-bind:style="{ left: percent(end) + '%' }"
                     x-cloak
                     @class([$customization['dual.tooltip.wrapper']])>
                    <div x-text="end"
                         dusk="tallstackui_form_range_tooltip_end"
                         @class([$customization['dual.tooltip.base']])></div>
                </div>
            @endif
        </div>
    @else
        <input @if ($id) id="{{ $id }}" @endif
        type="range"
               @if ($min !== null) min="{{ $min }}" @endif
               @if ($max !== null) max="{{ $max }}" @endif
               @if ($step !== null) step="{{ $step }}" @endif
               @if ($value !== null) value="{{ $value }}" @endif
               {{ $attributes->class([
                    $customization['input.base'],
                    $customization['input.sizes.' . $size],
                    $customization['input.locked'] => $locked,
                    $colors['thumb'],
                ]) }}
               @if ($readonly)
                   x-data
                   x-on:pointerdown.prevent
                   x-on:keydown="window.tallstackui_lockKeydown($event)"
                   aria-readonly="true"
               @endif
               dusk="tallstackui_form_range_input">
    @endif
</x-dynamic-component>
