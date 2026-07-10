@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_dial(@js($hover))"
     @class([$customization['position.'.$position]])
     x-on:mouseenter="enter()"
     x-on:mouseleave="leave()"
     x-on:click.outside="show = false"
     x-on:keydown.escape.window="show = false"
     {{ $attributes->only('x-on:open') }}>
    <div x-anchor.{{ $anchor }}.offset.10="$refs.button || $el"
         x-show="show"
         x-on:click="show = false"
         @if (!$ts_ui__flash)
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="{{ $transition['start'] }}"
             x-transition:enter-end="{{ $transition['end'] }}"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="{{ $transition['end'] }}"
             x-transition:leave-end="{{ $transition['start'] }}"
         @endif
         @class([$customization['items'], $customization['items-vertical'] => !$horizontal])>
        {{ $slot }}
    </div>
    <button type="button"
            dusk="tallstackui_dial_toggle"
            x-ref="button"
            x-on:click="toggle()"
            aria-haspopup="true"
            x-bind:aria-expanded="show"
            @class([
                $customization['button.rounded'] => !$square,
                $colors['background'],
                $customization['button.base'],
                $customization['button.sizes.'.$size],
            ])>
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :icon="TallStackUi::icon($icon)"
                             internal
                             @class([$customization['icon.base'], $customization['icon.sizes.'.$size], $colors['icon']])
                             x-bind:class="{ '{{ $customization['icon.rotated'] }}': {{ $preventRotate ? 'false' : 'show' }} }" />
    </button>
</div>
