@php
    $customization = $classes();
@endphp

<div x-data="{ show: false, size: 'md', width: 'md' }"
     x-init="
        const parent = $el.closest('[data-tsui-dropdown-size]');
        if (parent) {
            size = parent.dataset.tsuiDropdownSize;
            width = parent.dataset.tsuiDropdownWidth ?? size;
        }
     "
     x-on:click.outside="show = false"
     role="menu"
     aria-haspopup="true"
     x-bind:aria-expanded="show">
    <button @class([
                $customization['item.base'],
                $customization['item.sizes.xs'],
                $customization['item.sizes.sm'],
                $customization['item.sizes.md'],
                $customization['item.sizes.lg'],
                $customization['border'] => $separator,
            ])
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
                                     @class([
                                         $customization['icon.sizes.xs'],
                                         $customization['icon.sizes.sm'],
                                         $customization['icon.sizes.md'],
                                         $customization['icon.sizes.lg'],
                                     ]) />
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
                         @class([
                             $customization['floating.widths.xxs'],
                             $customization['floating.widths.xs'],
                             $customization['floating.widths.sm'],
                             $customization['floating.widths.md'],
                             $customization['floating.widths.lg'],
                             $customization['floating.widths.xl'],
                             $customization['floating.widths.2xl'],
                         ])
                         :$position
                         offset="8"
                         x-show="show"
                         x-anchor="$refs.button"
                         x-bind:data-tsui-dropdown-size="size"
                         x-bind:data-tsui-dropdown-width="width"
                         role="submenu">
        <x-slot:transition>
            {!! $transitions() !!}
        </x-slot:transition>
        <div @class($customization['slot'])>
            {!! $slot !!}
        </div>
    </x-dynamic-component>
</div>
