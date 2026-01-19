@php
    $personalize = $classes();
@endphp

<div {{ $attributes->class([
     $personalize['wrapper'],
    'justify-start' => $left && ! $middle && ! $right,
    'justify-center' => ! $left && $middle && ! $right,
    'justify-end' => ! $left && ! $middle && $right,
    'justify-between' => ((int) isset($left) + (int) isset($middle) + (int) isset($right)) >= 2,
]) }}>
    @if (!$withoutMobileButton || $left)
    <div class="{{ $personalize['slots.left'] }}">
        @if (!$withoutMobileButton)
        <button x-on:click="tallStackUiMenuMobile = !tallStackUiMenuMobile" type="button" class="{{ $personalize['button.class'] }}">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('bars-4')"
                                 internal
                                 class="{{ $personalize['button.icon.size'] }}" />
        </button>
        @endif
        @if ($left)
            {{ $left }}
        @endif
    </div>
    @endif
    @if ($middle)
    <div class="{{ $personalize['slots.middle'] }}">
        {{ $middle }}
    </div>
    @endif
    @if ($right)
    <div class="{{ $personalize['slots.right'] }}">
        {{ $right }}
    </div>
    @endif
    {{ $slot }}
</div>
