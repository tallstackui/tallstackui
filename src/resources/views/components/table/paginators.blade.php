@php
$simplePagination ??= false;

if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

@if ($paginator->hasPages())
    <div class="mt-4">
        <nav role="navigation" aria-label="Pagination Navigation">
            <!-- Mobile Buttons -->
            <div @class(['flex flex-1 gap-x-2 mb-4', 'justify-end' => $simplePagination, 'justify-between sm:hidden' => !$simplePagination])>
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 dark:text-dark-500 bg-gray-100 dark:bg-dark-700 border border-gray-200 dark:border-transparent cursor-default leading-5 rounded-md select-none cursor-pointer">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-dark-300 bg-white dark:bg-dark-600 border border-gray-300 dark:border-transparent leading-5 rounded-md select-none cursor-pointer">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>
                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-dark-300 bg-white dark:bg-dark-600 border border-gray-300 dark:border-transparent leading-5 rounded-md select-none cursor-pointer">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 dark:text-dark-500 bg-gray-100 dark:bg-dark-700 border border-gray-200 dark:border-transparent cursor-default leading-5 rounded-md select-none cursor-pointer">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>
            @if (!$simplePagination)
            <!-- Desktop Buttons -->
            <div class="hidden sm:flex sm:items-center sm:justify-between">
                <div class="mr-4">
                    <p class="text-sm text-gray-700 dark:text-dark-300 leading-5">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>
                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        <!-- Previous Page Link -->
                        <span>
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-300 dark:text-dark-500 bg-gray-100 dark:bg-dark-700 border border-gray-300 dark:border-transparent cursor-default rounded-l-md leading-5 focus:outline-none" aria-hidden="true">
                                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                                             :icon="TallStackUi::icon('chevron-left')"
                                                             class="w-5 h-5" />
                                    </span>
                                </span>
                            @else
                                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 dark:text-dark-300 bg-white dark:bg-dark-600 border border-gray-300 dark:border-transparent rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                         :icon="TallStackUi::icon('chevron-left')"
                                                         class="w-5 h-5" />
                                </button>
                            @endif
                        </span>
                        <!-- Pagination Elements -->
                        @foreach ($elements as $element)
                            <!-- "Three Dots" Separator -->
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-dark-300 bg-white dark:bg-dark-600 border border-gray-300 dark:border-transparent cursor-default leading-5 select-none">{{ $element }}</span>
                                </span>
                            @endif
                            <!-- Array Of Links -->
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="relative z-10 inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-primary-700 dark:text-dark-300 bg-primary-100 dark:bg-dark-700 border border-primary-300 dark:border-transparent cursor-default select-none">
                                                    {{ $page }}
                                                </span>
                                            </span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-600 dark:text-dark-400 bg-white dark:bg-dark-600 border border-gray-300 dark:border-transparent leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach
                        <span>
                            <!-- Next Page Link -->
                            @if ($paginator->hasMorePages())
                                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 dark:text-dark-300 bg-white dark:bg-dark-600 border border-gray-300 dark:border-transparent rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                         :icon="TallStackUi::icon('chevron-right')"
                                                         class="w-5 h-5" />
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-300 dark:text-dark-500 bg-gray-100 dark:bg-dark-700 border border-gray-300 dark:border-transparent cursor-default rounded-r-md leading-5" aria-hidden="true">
                                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                                             :icon="TallStackUi::icon('chevron-right')"
                                                             class="w-5 h-5" />
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
            @endif
        </nav>
    </div>
@endif
