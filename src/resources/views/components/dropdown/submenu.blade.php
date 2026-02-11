@php
    $customization = $classes();
@endphp

<div x-data="{ show: false }"
     x-on:click.outside="show = false"
     role="menu"
     aria-haspopup="true"
     x-bind:aria-expanded="show">
    <button @class([$customization['item'], $customization['border'] => $separator])
            type="button"
            x-on:click="show = !show; $refs.dropdown.dispatchEvent(new CustomEvent('open', {detail: {status: show}}))"
            x-ref="button"
            aria-expanded="show">
        @if ($position === 'left-start')
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('chevron-left')"
                                 internal
                                 class="{{ $customization['submenu.left'] }}" />
        @endif
        <div class="{{ $customization['wrapper'] }}">
            @if ($icon)
                <x-dynamic-component :component="TallStackUi::prefix('icon')" internal :$icon
                                     class="{{ $customization['icon'] }}" />
            @endif
            {{ $text }}
        </div>
        @if ($position === 'right-start')
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('chevron-right')"
                                 internal
                                 class="{{ $customization['submenu.right'] }}" />
        @endif
    </button>
    <x-dynamic-component :component="TallStackUi::prefix('floating')"
                         scope="dropdown.submenu.floating"
                         :floating="$customization['floating.default']"
                         :$position
                         offset="8"
                         x-show="show"
                         x-anchor="$refs.button"
                         role="submenu">
        <x-slot:transition>
            {!! $transitions() !!}
        </x-slot:transition>
        <div @class($customization['slot'])>
            {!! $slot !!}
        </div>
    </x-dynamic-component>
</div>
