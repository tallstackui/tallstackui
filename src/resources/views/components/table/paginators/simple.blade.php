@php
$group = 'inline-flex items-center gap-1.5';
// Plain spans would create line boxes, and the items nested two levels deep
// (current page, chevrons) would sit on a different baseline than the others.
$wrapper = 'inline-flex items-center';
$rail = 'inline-flex items-center gap-0.5 rounded-full p-1 dark:bg-dark-800';

$slot = 'relative inline-flex min-w-8 items-center justify-center rounded-full px-2.5 py-1.5 text-sm leading-5 outline-hidden transition duration-200 focus-visible:ring-2 focus-visible:ring-primary-500';
$idle = 'cursor-pointer font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 hover:shadow-xs dark:text-dark-300 dark:hover:bg-dark-700 dark:hover:text-dark-100';
$current = 'cursor-default select-none bg-primary-600 font-semibold text-white shadow-sm';
$separator = 'cursor-default select-none font-medium text-gray-400 dark:text-dark-500';

$nav = 'inline-flex size-9 shrink-0 items-center justify-center rounded-full outline-hidden transition duration-200 focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-dark-900';
$navIdle = 'cursor-pointer text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-dark-300 dark:hover:bg-dark-700 dark:hover:text-dark-100';
$navOff = 'cursor-not-allowed select-none text-gray-300 dark:text-dark-500';

$button = 'relative inline-flex select-none items-center rounded-full px-4 py-2 text-sm font-medium leading-5 outline-hidden transition duration-200';
$enabled = 'cursor-pointer bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-gray-900 focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:bg-dark-700 dark:text-dark-200 dark:hover:bg-dark-600 dark:hover:text-dark-100 dark:focus-visible:ring-offset-dark-900';
$disabled = 'cursor-not-allowed select-none text-gray-300 dark:text-dark-500';
@endphp

@if ($paginator->hasPages())
    <div class="mt-4">
        <nav role="navigation" aria-label="Pagination Navigation">
            <div @class(['flex flex-1 gap-x-2', 'justify-end' => $simple, 'justify-between sm:hidden' => !$simple])>
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="{{ $button }} {{ $disabled }}" aria-disabled="true">
                            {!! trans('pagination.previous') !!}
                        </span>
                    @elseif ($livewire)
                        <button type="button" wire:click="previousPage('{{ $name }}')" x-on:click="{!! $scroll !!}" wire:loading.attr="disabled" dusk="previousPage{{ $dusk }}.before" class="{{ $button }} {{ $enabled }}">
                            {!! trans('pagination.previous') !!}
                        </button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}{{ $fragment }}" rel="prev" dusk="previousPage{{ $dusk }}.before" class="{{ $button }} {{ $enabled }}">
                            {!! trans('pagination.previous') !!}
                        </a>
                    @endif
                </span>
                <span>
                    @if (!$paginator->hasMorePages())
                        <span class="{{ $button }} {{ $disabled }}" aria-disabled="true">
                            {!! trans('pagination.next') !!}
                        </span>
                    @elseif ($livewire)
                        <button type="button" wire:click="nextPage('{{ $name }}')" x-on:click="{!! $scroll !!}" wire:loading.attr="disabled" dusk="nextPage{{ $dusk }}.before" class="{{ $button }} {{ $enabled }}">
                            {!! trans('pagination.next') !!}
                        </button>
                    @else
                        <a href="{{ $paginator->nextPageUrl() }}{{ $fragment }}" rel="next" dusk="nextPage{{ $dusk }}.before" class="{{ $button }} {{ $enabled }}">
                            {!! trans('pagination.next') !!}
                        </a>
                    @endif
                </span>
            </div>
            @if (!$simple)
            <div class="hidden sm:flex sm:items-center sm:justify-between">
                <div class="mr-4">
                    <p class="text-sm leading-5 text-gray-500 dark:text-dark-400">
                        <span>{!! trans('Showing') !!}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $paginator->firstItem() }}</span>
                        <span>{!! trans('to') !!}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $paginator->lastItem() }}</span>
                        <span>{!! trans('of') !!}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $paginator->total() }}</span>
                        <span>{!! trans('results') !!}</span>
                    </p>
                </div>
                <div>
                    <span class="{{ $group }}">
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
                                <button type="button" wire:click="previousPage('{{ $name }}')" x-on:click="{!! $scroll !!}" dusk="previousPage{{ $dusk }}.after" rel="prev" class="{{ $nav }} {{ $navIdle }}" aria-label="{{ trans('pagination.previous') }}">
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
                        <span class="{{ $rail }}">
                            @foreach ($elements as $element)
                                @if (is_string($element))
                                    <span class="{{ $wrapper }}" aria-disabled="true">
                                        <span class="{{ $slot }} {{ $separator }}">{{ $element }}</span>
                                    </span>
                                @endif
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        <span class="{{ $wrapper }}" wire:key="paginator-{{ $name }}-page{{ $page }}">
                                            @if ($page == $paginator->currentPage())
                                                <span class="{{ $wrapper }}" aria-current="page">
                                                    <span class="{{ $slot }} {{ $current }}">{{ $page }}</span>
                                                </span>
                                            @elseif ($livewire)
                                                <button type="button" wire:click="gotoPage({{ $page }}, '{{ $name }}')" x-on:click="{!! $scroll !!}" class="{{ $slot }} {{ $idle }}" aria-label="{{ trans('Go to page :page', ['page' => $page]) }}">
                                                    {{ $page }}
                                                </button>
                                            @else
                                                <a href="{{ $url }}{{ $fragment }}" class="{{ $slot }} {{ $idle }}" aria-label="{{ trans('Go to page :page', ['page' => $page]) }}">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        </span>
                                    @endforeach
                                @endif
                            @endforeach
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
                                <button type="button" wire:click="nextPage('{{ $name }}')" x-on:click="{!! $scroll !!}" dusk="nextPage{{ $dusk }}.after" rel="next" class="{{ $nav }} {{ $navIdle }}" aria-label="{{ trans('pagination.next') }}">
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
                </div>
            </div>
            @endif
        </nav>
    </div>
@endif
