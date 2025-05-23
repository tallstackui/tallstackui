@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_formCurrency({!! $entangle !!}, @js($locale))">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         {{ $attributes }}
                         :$label
                         :$hint
                         :$invalidate
                         prefix="$"
                         suffix="USD"
                         x-model="input">
        <x-slot:prefix class="ml-2 mr-1">
            $
        </x-slot:prefix>
        <x-slot:suffix class="mr-2">
            USD
        </x-slot:suffix>
    </x-dynamic-component>
</div>
