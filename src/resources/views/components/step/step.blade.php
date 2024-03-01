@php($personalize = $classes())

<div x-data="tallstackui_step(@if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif, @js($navigate))"
     x-cloak
     {{ $attributes->only('x-on:change') }}>
    <nav aria-label="Form Step">
        <div @class(['overflow-hidden rounded-md' => $variation === 'panels'])>
            <ul role="list"
                @class($personalize['wrapper.' . $variation])>
                <template x-for="item in steps">
                    @include("tallstack-ui::components.step.variations.$variation", ['personalize' => $personalize])
                </template>
            </ul>
        </div>
    </nav>
    <div role="steplist"
         @class($personalize['content'])>
        {{ $slot }}
    </div>
    @if ($helpers)
        <div class="flex justify-between">
            <div>
                <button type="button"
                        x-show="selected > 1"
                        x-on:click="selected--"
                        dusk="tallstackui_step_previous"
                        @class($personalize['button.wrapper'])>
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-left')"
                                         @class(['mr-1', $personalize['button.icon']]) />
                    {{ __('tallstack-ui::messages.step.previous') }}
                </button>
            </div>
            <div>
                <button type="button"
                        x-show="selected < steps.length"
                        x-on:click="selected++"
                        dusk="tallstackui_step_next"
                        @class($personalize['button.wrapper'])>
                    {{ __('tallstack-ui::messages.step.next') }}
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-right')"
                                         @class(['ml-1', $personalize['button.icon']]) />
                </button>
                @if ($finish)
                    @if ($finish instanceof \Illuminate\View\ComponentSlot)
                        <div x-show="selected == steps.length">
                            {{ $finish }}
                        </div>
                    @else
                        <button type="button"
                                x-show="selected == steps.length"
                                x-on:click="finish()"
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
