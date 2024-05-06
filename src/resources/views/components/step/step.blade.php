@php
    $personalize = $classes();
@endphp

<div x-data="{ 
        selected: @if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif, 
        navigate: @js($navigate), 
        previous: @js($navigatePrevious), 
        steps: [],
    }">
    <nav @if ($variation === 'panels') class="overflow-hidden rounded-md" @endif>
        <ul role="list"
            @class($personalize['wrapper.' . $variation])>
            <template x-for="item in steps">
                <x-dynamic-component component="tallstack-ui::step.variations.{{ $variation }}"
                                        :$personalize
                                        :$navigate />
            </template>
        </ul>
    </nav>
    <div @class($personalize['content'])>
        {{ $slot }}
    </div>
    @if ($helpers)
        <div class="flex justify-between" {{ $attributes->only('x-on:change') }} x-ref="buttons">
            <div>
                @if ($navigatePrevious)
                    <button type="button"
                            x-show="selected > 1"
                            x-on:click="selected--; $refs.buttons.dispatchEvent(new CustomEvent('change', {detail: {step: selected}}));"
                            dusk="tallstackui_step_previous"
                            @class($personalize['button.wrapper'])>
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('chevron-left')"
                                             @class(['mr-1', $personalize['button.icon']]) />
                        {{ __('tallstack-ui::messages.step.previous') }}
                    </button>
                @endif
            </div>
            <div>
                <button type="button"
                        x-show="selected < steps.length"
                        x-on:click="selected++; $refs.buttons.dispatchEvent(new CustomEvent('change', {detail: {step: selected}}));"
                        dusk="tallstackui_step_next"
                        @class($personalize['button.wrapper'])>
                    {{ __('tallstack-ui::messages.step.next') }}
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-right')"
                                         @class(['ml-1', $personalize['button.icon']]) />
                </button>
                @if ($finish)
                    @if ($finish instanceof \Illuminate\View\ComponentSlot)
                        <div x-show="selected === steps.length">
                            {{ $finish }}
                        </div>
                    @else
                        <button type="button"
                                x-show="selected === steps.length"
                                x-on:click="$el.dispatchEvent(new CustomEvent('finish', {detail: {step: selected}}))"
                                dusk="tallstackui_step_finish"
                                {{ $attributes->only('x-on:finish') }}
                                @class($personalize['button.wrapper'])>
                            {{ __('tallstack-ui::messages.step.finish') }}
                        </button>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
