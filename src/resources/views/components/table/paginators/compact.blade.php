@php
// One control for every width: the page indicator replaces the number list,
// so there is no separate mobile block to fall back to.
$shell = 'inline-flex items-center gap-0.5 rounded-lg border border-gray-200 bg-white p-1 shadow-xs dark:border-dark-700 dark:bg-dark-800';
$wrapper = 'inline-flex items-center';

$nav = 'inline-flex size-7 shrink-0 items-center justify-center rounded-md outline-hidden transition duration-200 focus-visible:ring-2 focus-visible:ring-primary-500';
$navIdle = 'cursor-pointer text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-dark-200 dark:hover:bg-dark-700 dark:hover:text-white';
$navOff = 'cursor-not-allowed select-none text-gray-300 dark:text-dark-400';

// Tabular figures keep the indicator from resizing between 9 and 10.
$indicator = 'select-none px-2.5 text-sm leading-5 tabular-nums text-gray-400 dark:text-dark-300';
$position = 'font-semibold text-gray-900 dark:text-white';
@endphp

@if ($paginator->hasPages())
    <div class="mt-4">
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between gap-x-4">
            @if (!$simple)
                <p class="hidden text-sm leading-5 text-gray-500 sm:block dark:text-dark-400">
                    <span>{!! trans('Showing') !!}</span>
                    <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $paginator->firstItem() }}</span>
                    <span>{!! trans('to') !!}</span>
                    <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $paginator->lastItem() }}</span>
                    <span>{!! trans('of') !!}</span>
                    <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $paginator->total() }}</span>
                    <span>{!! trans('results') !!}</span>
                </p>
            @endif
            <span class="{{ $shell }} ml-auto">
                <span class="{{ $wrapper }}">
                    @if ($paginator->onFirstPage())
                        <span class="{{ $wrapper }}" aria-disabled="true" aria-label="{{ trans('pagination.previous') }}">
                            <span class="{{ $nav }} {{ $navOff }}" aria-hidden="true">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('chevron-left')"
                                                     internal
                                                     class="size-4" />
                            </span>
                        </span>
                    @elseif ($livewire)
                        <button type="button" wire:click="previousPage('{{ $name }}')" x-on:click="{!! $scroll !!}" wire:loading.attr="disabled" dusk="previousPage{{ $dusk }}.after" rel="prev" class="{{ $nav }} {{ $navIdle }}" aria-label="{{ trans('pagination.previous') }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('chevron-left')"
                                                 internal
                                                 class="size-4" />
                        </button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}{{ $fragment }}" dusk="previousPage{{ $dusk }}.after" rel="prev" class="{{ $nav }} {{ $navIdle }}" aria-label="{{ trans('pagination.previous') }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('chevron-left')"
                                                 internal
                                                 class="size-4" />
                        </a>
                    @endif
                </span>
                <span class="{{ $indicator }}" aria-current="page">
                    <span class="{{ $position }}">{{ $paginator->currentPage() }}</span>
                    @if (!$simple)
                        <span>/</span>
                        <span>{{ $paginator->lastPage() }}</span>
                    @endif
                </span>
                <span class="{{ $wrapper }}">
                    @if (!$paginator->hasMorePages())
                        <span class="{{ $wrapper }}" aria-disabled="true" aria-label="{{ trans('pagination.next') }}">
                            <span class="{{ $nav }} {{ $navOff }}" aria-hidden="true">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('chevron-right')"
                                                     internal
                                                     class="size-4" />
                            </span>
                        </span>
                    @elseif ($livewire)
                        <button type="button" wire:click="nextPage('{{ $name }}')" x-on:click="{!! $scroll !!}" wire:loading.attr="disabled" dusk="nextPage{{ $dusk }}.after" rel="next" class="{{ $nav }} {{ $navIdle }}" aria-label="{{ trans('pagination.next') }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('chevron-right')"
                                                 internal
                                                 class="size-4" />
                        </button>
                    @else
                        <a href="{{ $paginator->nextPageUrl() }}{{ $fragment }}" dusk="nextPage{{ $dusk }}.after" rel="next" class="{{ $nav }} {{ $navIdle }}" aria-label="{{ trans('pagination.next') }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('chevron-right')"
                                                 internal
                                                 class="size-4" />
                        </a>
                    @endif
                </span>
            </span>
        </nav>
    </div>
@endif
