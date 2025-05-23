@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_formCurrency({!! $entangle !!}, @js($decimals), @js($precision), @js($locale))">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         {{ $attributes }}
                         :$label
                         :$hint
                         :$invalidate
                         x-model="input">
        @if ($indicators)
            @if (!empty($symbols['symbol']))
                <x-slot:prefix class="ml-2 mr-1">
                    {{ $symbols['symbol'] }}
                </x-slot:prefix>
            @endif
            @if (!empty($symbols['currency']))
                <x-slot:suffix class="mr-2">
                    {{ $symbols['currency'] }}
                </x-slot:suffix>
            @endif
        @endif
    </x-dynamic-component>
</div>
