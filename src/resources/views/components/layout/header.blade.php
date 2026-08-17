@php
    $customization = $classes();
@endphp

<div {{ $attributes->class([$customization['wrapper.base'], $customization['wrapper.sizes.'.$size]]) }}>
    <button x-show="$store['tsui.side-bar'].collapsible"
            x-on:click="$store['tsui.side-bar'].toggle()"
            x-bind:aria-expanded="!$store['tsui.side-bar'].collapsed"
            x-cloak
            type="button"
            aria-label="Toggle sidebar"
            class="{{ $customization['collapse.class'] }}">
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :icon="TallStackUi::icon($customization['collapse.icon'])"
                             internal
                             class="{{ $customization['collapse.icon.size'] }}" />
    </button>
    @if (!$withoutMobileButton)
        <button x-on:click="tallStackUiMenuMobile = !tallStackUiMenuMobile" type="button"
                x-bind:aria-expanded="tallStackUiMenuMobile"
                aria-label="Open menu"
                class="{{ $customization['button.class'] }}">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('bars-4')"
                                 internal
                                 class="{{ $customization['button.icon.size'] }}" />
        </button>
    @endif
    @if ($left || $middle || $right)
        <div @class([
            $customization['slots.wrapper'],
            $customization['slots.wrapper-right-only'] => !$left && !$middle && $right,
            $customization['slots.wrapper-multi-slot'] => ((int) isset($left) + (int) isset($middle) + (int) isset($right)) >= 2,
        ])>
            @if ($left)
                <div class="{{ $customization['slots.left'] }}">
                    {{ $left }}
                </div>
            @endif
            @if ($middle)
                <div class="{{ $customization['slots.middle'] }}">
                    {{ $middle }}
                </div>
            @endif
            @if ($right)
                <div class="{{ $customization['slots.right'] }}">
                    {{ $right }}
                </div>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
