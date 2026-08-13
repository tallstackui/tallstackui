@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')"
                     :$id
                     :$property
                     :$error
                     :$label
                     :$hint
                     :$invalidate
                     :wrapper="$customization['wrapper']">
    <div x-data="tallstackui_swap(
            {!! $entangle !!},
            {{ TallStackUi::blade()->json($options) }},
            @js($preview),
            @js($vertical),
            @js($loop),
            @js($disabled),
            @js($readonly),
            @js($livewire),
            @js($value),
            @js($change))"
         @if ($attributes->whereStartsWith('x-model'))
             x-modelable="model"
         {{ $attributes->whereStartsWith('x-model') }}
         @endif
         @if ($tooltip)
             x-tooltip="{{ $tooltip }}"
         @endif
         wire:ignore.self
         dusk="tallstackui_swap"
         {{ $attributes->only('x-on:swap') }}
         @class([
             $customization['input.base'],
             $customization['input.color'] => !$error,
             $customization['input.error'] => $error,
             $customization['input.background'] => !$locked,
             $customization['input.locked'] => $locked,
             $customization['input.block'] => $block,
         ])>
        @if (!$livewire && $property)
            <input hidden
                   name="{{ $property }}"
                   x-ref="input"
                   @if ($attributes->has('value')) value="{{ $attributes->get('value') }}" @endif>
        @endif
        <button type="button"
                dusk="tallstackui_swap_prev"
                class="{{ $customization['button.base'] }}"
                x-on:click="previous()"
                @if ($vertical)
                    x-on:keydown.up.prevent="previous()"
                    x-on:keydown.down.prevent="next()"
                @else
                    x-on:keydown.left.prevent="previous()"
                    x-on:keydown.right.prevent="next()"
                @endif
                x-bind:disabled="locked() || (!loop && slot <= 0)">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon($vertical ? 'chevron-up' : 'chevron-left')"
                                 internal
                                 class="{{ $customization['button.icon'] }}" />
        </button>
        <div @if ($id) id="{{ $id }}" @endif
             dusk="tallstackui_swap_viewport"
             x-ref="viewport"
             x-on:pointerdown="start($event)"
             x-on:pointermove="move($event)"
             x-on:pointerup="end()"
             x-on:pointercancel="end()"
             @class([
                 $customization['viewport.base'],
                 $customization['viewport.touch.'.($vertical ? 'vertical' : 'horizontal')],
                 $customization['viewport.width.preview'] => $preview && !$block,
                 $customization['viewport.width.base'] => !$preview && !$block,
                 $customization['viewport.width.block'] => $block,
                 $customization['viewport.mask'] => $preview,
                 $customization['viewport.draggable'] => !$locked,
             ])>
            <div x-ref="track"
                 x-bind:style="{ transform }"
                 x-on:transitionend.self="settle()"
                 @if (!$ts_ui__flash)
                     x-bind:class="dragging || jumping ? '' : '{{ $customization['track.transition'] }}'"
                 @endif
                 @class([
                     $customization['track.base'],
                     $customization['track.vertical'] => $vertical,
                 ])>
                <template x-for="(option, position) in items" :key="position">
                    <div x-text="option.label"
                         @if ($preview)
                             x-bind:class="position === slot
                                ? '{{ $customization['item.fade.active'] }}'
                                : '{{ $customization['item.fade.inactive'] }}'"
                         @endif
                         @class([
                             $customization['item.base'],
                             $customization['item.sizes.'.($preview ? 'preview' : 'base')],
                             $customization['item.transition'] => $preview && !$ts_ui__flash,
                         ])></div>
                </template>
            </div>
        </div>
        <button type="button"
                dusk="tallstackui_swap_next"
                class="{{ $customization['button.base'] }}"
                x-on:click="next()"
                @if ($vertical)
                    x-on:keydown.up.prevent="previous()"
                    x-on:keydown.down.prevent="next()"
                @else
                    x-on:keydown.left.prevent="previous()"
                    x-on:keydown.right.prevent="next()"
                @endif
                x-bind:disabled="locked() || (!loop && slot >= items.length - 1)">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon($vertical ? 'chevron-down' : 'chevron-right')"
                                 internal
                                 class="{{ $customization['button.icon'] }}" />
        </button>
    </div>
</x-dynamic-component>
