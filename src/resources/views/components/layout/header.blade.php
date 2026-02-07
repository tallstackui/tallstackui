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
    @if (!$withoutMobileButton || $left)
        <div class="{{ $customization['slots.left'] }}">
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
    {{ $slot }}
</div>
