@php
    $personalize = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_formCurrency(
    {!! $entangle !!},
    @js($decimals),
    @js($precision),
    @js($clearable),
    @js($mutate),
    @js($livewire),
    @js($property),
    @js($value),
    @js($locale))">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         {{ $attributes->whereDoesntStartWith('wire:model') }}
                         class="appearance-number-none"
                         inputmode="numeric"
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$property"
                         x-on:input="sync"
                         x-model="input">
        @if ($symbol || $currency || $clearable)
            @if (!empty($symbols['symbol']) && $symbol)
                <x-slot:prefix class="ml-2">
                    {{ is_string($symbol) && $symbol !== '1' ? $symbol : $symbols['symbol'] }}
                </x-slot:prefix>
            @endif
            <x-slot:suffix class="mr-2">
                @if (!empty($symbols['currency']) && $currency)
                    {{ is_string($currency) && $currency !== '1' ? $currency : $symbols['currency'] }}
                @endif
                @if ($clearable)
                        <div @class([
                                $personalize['clearable.wrapper'],
                                $personalize['clearable.padding.with-currency'] => $currency,
                                $personalize['clearable.padding.without-currency'] => ! $currency,
                            ]) x-show="input !== '' && clearable">
                            <button type="button" class="cursor-pointer" dusk="tallstackui_form_currency_clearable">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('x-mark')"
                                                     x-on:click="clear()"
                                                     internal
                                                     @class([
                                                         $personalize['clearable.size'],
                                                         $personalize['clearable.color'] => !$error && !$invalidate,
                                                     ]) />
                            </button>
                        </div>
                @endif
            </x-slot:suffix>
        @endif
    </x-dynamic-component>
</div>
