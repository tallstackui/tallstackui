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
    <div x-anchor.{{ $anchor }}.offset.10="$refs.button"
         x-show="show"
         @if (!$ts_ui__flash)
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-75"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-75"
         @endif
         @class([$customization['items'], 'flex-col' => !$horizontal])>
        {{ $slot }}
    </div>
    <button type="button"
            dusk="tallstackui_dial_toggle"
            x-ref="button"
            x-on:click="toggle()"
            aria-haspopup="true"
            x-bind:aria-expanded="show"
            @class([
                'rounded-full' => !$square,
                $colors['background'],
                $customization['button.base'],
                $customization['button.sizes.'.$size],
            ])>
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :icon="TallStackUi::icon($icon)"
                             internal
                             @class([$customization['icon.base'], $customization['icon.sizes.'.$size], $colors['icon']])
                             x-bind:class="{ 'rotate-45': show }" />
    </button>
</div>
