@php($personalize = $classes())

<div x-data="tallstackui_step(@if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif)"
     x-cloak
     {{ $attributes->only('x-on:change') }}>
    <nav aria-label="Form Step">
        <ul role="list"
            @class($personalize['wrapper.' . $variation])>
            <template x-for="item in steps">
                @include("tallstack-ui::components.step.variation.$variation")
            </template>
        </ul>
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
                        x-on:click="selected--;"
                        class="dark:focus:ring-gray-70 mb-2 me-2 inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-left')"
                                         class="mr-1 h-4 w-4" />
                    {{ __('tallstack-ui::messages.step.previous') }}
                </button>
            </div>
            <div>
                <button type="button"
                        x-show="selected < steps.length"
                        x-on:click="selected++;"
                        class="dark:focus:ring-gray-70 mb-2 me-2 inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700">
                    {{ __('tallstack-ui::messages.step.next') }}
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-right')"
                                         class="ml-1 h-4 w-4" />
                </button>
                @if ($finish)
                    <button type="button"
                            x-show="selected == steps.length"
                            x-on:click="finish()"
                            {{ $attributes->only('x-on:finish') }}
                            class="dark:focus:ring-gray-70 mb-2 me-2 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700">
                        {{ __('tallstack-ui::messages.step.finish') }}
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
