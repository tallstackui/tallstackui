@php
// One grouped shell keeps both controls together; disabled states replace
// x-show so the shell never changes width at the edges.
$wrapper = 'flex items-center justify-end gap-2';
$shell = 'inline-flex items-center gap-0.5 rounded-lg border border-gray-200 bg-white p-1 shadow-xs dark:border-dark-700 dark:bg-dark-800';

$nav = 'inline-flex size-7 shrink-0 items-center justify-center rounded-md outline-hidden transition duration-200 focus-visible:ring-2 focus-visible:ring-primary-500';
$navIdle = 'cursor-pointer text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-dark-200 dark:hover:bg-dark-700 dark:hover:text-white';
$navOff = 'cursor-not-allowed select-none text-gray-300 dark:text-dark-400';

// Tabular figures keep the indicator from resizing between steps.
$indicator = 'select-none px-2 text-sm leading-5 tabular-nums text-gray-400 dark:text-dark-300';
$position = 'font-semibold text-gray-900 dark:text-white';

$finishButton = 'inline-flex cursor-pointer select-none items-center rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 text-sm font-medium text-gray-600 shadow-xs outline-hidden transition duration-200 hover:bg-gray-50 hover:text-gray-900 focus-visible:ring-2 focus-visible:ring-primary-500 dark:border-dark-700 dark:bg-dark-800 dark:text-dark-300 dark:hover:bg-dark-700/50 dark:hover:text-dark-100';
@endphp

<div class="{{ $wrapper }}" {{ $attributes->only('x-on:change') }} x-ref="buttons">
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
                    class="{{ $finishButton }}">
                {{ trans('ts-ui::messages.step.finish') }}
            </button>
        @endif
    @endif
    <span class="{{ $shell }}">
        @if ($previous)
            {{ $previous }}
        @elseif ($navigatePrevious)
            <button type="button"
                    x-on:click="previous()"
                    x-bind:disabled="selected <= 1"
                    x-bind:class="selected > 1 ? '{{ $navIdle }}' : '{{ $navOff }}'"
                    dusk="tallstackui_step_previous"
                    class="{{ $nav }}"
                    aria-label="{{ trans('ts-ui::messages.step.previous') }}">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-left')"
                                     internal
                                     class="size-4" />
            </button>
        @endif
        <span class="{{ $indicator }}" aria-current="step">
            <span class="{{ $position }}" x-text="selected"></span>
            <span>/</span>
            <span x-text="steps.length"></span>
        </span>
        @if ($next)
            {{ $next }}
        @else
            <button type="button"
                    x-on:click="next()"
                    x-bind:disabled="selected >= steps.length"
                    x-bind:class="selected < steps.length ? '{{ $navIdle }}' : '{{ $navOff }}'"
                    dusk="tallstackui_step_next"
                    class="{{ $nav }}"
                    aria-label="{{ trans('ts-ui::messages.step.next') }}">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-right')"
                                     internal
                                     class="size-4" />
            </button>
        @endif
    </span>
</div>
