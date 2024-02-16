@php($personalize = $classes())

<div x-data="tallstackui_step(@if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif)"
     {{ $attributes->only('x-on:change') }}
     x-cloak>
    <nav aria-label="Form Step">
        <ul role="list" @class($personalize['wrapper.' . $variation])>
             <template x-for="item in steps">
                @include("tallstack-ui::components.step.variation.$variation")
            </template>
        </ul>
    </nav>

    <div role="tablist" @class($personalize['content'])>
        {{ $slot }}
    </div>

    @if ($helpers)
        <div class="flex justify-between">
            <div>
                <button type="button" x-show="selected > 1" x-on:click="selected--;" class="inline-flex items-center">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-left')"
                                         class="w-5 h-5" />
                    {{ __('tallstack-ui::messages.step.previous') }}
                </button>
            </div>
            <div>
                <button type="button" x-show="selected < steps.length" x-on:click="selected++;" class="inline-flex items-center">
                    {{ __('tallstack-ui::messages.step.next') }}
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-right')"
                                         class="w-5 h-5" />
                </button>
                @if ($finish)
                    <button type="button" x-show="selected == steps.length" x-on:click="finish()" {{ $attributes->only('x-on:finish') }}>
                        {{ __('tallstack-ui::messages.step.finish') }}
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
