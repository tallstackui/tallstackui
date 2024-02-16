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
                <button type="button" x-show="selected > 1" x-on:click="selected--;" class="inline-flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-70">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-left')"
                                         class="w-4 h-4 mr-1" />
                    {{ __('tallstack-ui::messages.step.previous') }}
                </button>
            </div>
            <div>
                <button type="button" x-show="selected < steps.length" x-on:click="selected++;" class="inline-flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-70">
                    {{ __('tallstack-ui::messages.step.next') }}
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-right')"
                                         class="w-4 h-4 ml-1" />
                </button>
                @if ($finish)
                    <button type="button" 
                            x-show="selected == steps.length" 
                            x-on:click="finish()" {{ $attributes->only('x-on:finish') }}
                            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-70">
                        {{ __('tallstack-ui::messages.step.finish') }}
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
