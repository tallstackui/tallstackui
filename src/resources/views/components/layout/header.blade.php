@php
    $customization = $classes();
@endphp

<div {{ $attributes->class([
     $customization['wrapper'],
    'justify-start' => $left && ! $middle && ! $right,
    'justify-center' => ! $left && $middle && ! $right,
    'justify-end' => ! $left && ! $middle && $right,
    'justify-between' => ((int) isset($left) + (int) isset($middle) + (int) isset($right)) >= 2,
]) }}>
    <div class="{{ $customization['slots.left'] }}">
        <button x-show="$store['tsui.side-bar'].collapsible"
                x-on:click="$store['tsui.side-bar'].toggle()"
                x-cloak
                type="button"
                class="{{ $customization['collapse.class'] }}">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon($customization['collapse.icon'])"
                                 internal
                                 class="{{ $customization['collapse.icon.size'] }}" />
        </button>
        @if (!$withoutMobileButton)
            <button x-on:click="tallStackUiMenuMobile = !tallStackUiMenuMobile" type="button"
                    class="{{ $customization['button.class'] }}">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('bars-4')"
                                     internal
                                     class="{{ $customization['button.icon.size'] }}" />
            </button>
        @endif
        @if ($left)
            {{ $left }}
        @endif
    </div>
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
    {{ $slot }}
</div>
