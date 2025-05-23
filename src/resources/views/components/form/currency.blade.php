@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_formCurrency(
    {!! $entangle !!},
    @js($decimals),
    @js($precision),
    @js($clearable),
    @js($locale))">
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         {{ $attributes->whereDoesntStartWith('wire:model') }}
                         :$label
                         :$hint
                         :$invalidate
                         :alternative="$property"
                         x-on:input="sync"
                         x-model="input">
        @if ($indicators)
            @if (!empty($symbols['symbol']))
                <x-slot:prefix class="ml-2">
                    {{ $symbols['symbol'] }}
                </x-slot:prefix>
            @endif
            <x-slot:suffix class="mr-2">
                @if (!empty($symbols['currency']))
                    {{ $symbols['currency'] }}
                @endif
                @if ($clearable)
                        <div @class([ $personalize['clearable.wrapper'], $personalize['clearable.padding']]) x-show="input !== '' && clearable">
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
