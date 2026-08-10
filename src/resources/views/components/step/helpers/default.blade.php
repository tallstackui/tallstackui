@php
$wrapper = 'flex items-center justify-between';
$button = 'inline-flex cursor-pointer select-none items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3.5 py-2 text-sm font-medium text-gray-600 shadow-xs outline-hidden transition duration-200 hover:bg-gray-50 hover:text-gray-900 focus-visible:ring-2 focus-visible:ring-primary-500 dark:border-dark-700 dark:bg-dark-800 dark:text-dark-300 dark:hover:bg-dark-700/50 dark:hover:text-dark-100';
@endphp

<div class="{{ $wrapper }}" {{ $attributes->only('x-on:change') }} x-ref="buttons">
    <div>
        @if ($previous)
            <div x-show="selected > 1">
                {{ $previous }}
            </div>
        @elseif ($navigatePrevious)
            <button type="button"
                    x-show="selected > 1"
                    x-on:click="previous()"
                    dusk="tallstackui_step_previous"
                    class="{{ $button }}">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-left')"
                                     internal
                                     class="size-4" />
                {{ trans('ts-ui::messages.step.previous') }}
            </button>
        @endif
    </div>
    <div>
        @if ($next)
            <div x-show="selected < steps.length">
                {{ $next }}
            </div>
        @else
            <button type="button"
                    x-show="selected < steps.length"
                    x-on:click="next()"
                    dusk="tallstackui_step_next"
                    class="{{ $button }}">
                {{ trans('ts-ui::messages.step.next') }}
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-right')"
                                     internal
                                     class="size-4" />
            </button>
        @endif
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
                        class="{{ $button }}">
                    {{ trans('ts-ui::messages.step.finish') }}
                </button>
            @endif
        @endif
    </div>
</div>
