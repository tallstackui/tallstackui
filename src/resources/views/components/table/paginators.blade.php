@php
$simplePagination ??= false;

if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       \$refs.persist.scrollIntoView();
    JS
    : '';
@endphp

@if ($paginator->hasPages())
    <div class="mt-4">
        <nav role="navigation" aria-label="Pagination Navigation">
            <div @class(['flex flex-1 gap-x-2 mb-4', 'justify-end' => $simplePagination, 'justify-between sm:hidden' => !$simplePagination])>
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-400 bg-gray-100 border border-gray-200 rounded-md cursor-pointer select-none dark:text-dark-500 dark:bg-dark-700 dark:border-transparent">
                            {!! trans('pagination.previous') !!}
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-500 bg-white border border-gray-300 rounded-md cursor-pointer select-none dark:text-dark-300 dark:bg-dark-600 dark:border-transparent">
                            {!! trans('pagination.previous') !!}
                        </button>
                    @endif
                </span>
                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-500 bg-white border border-gray-300 rounded-md cursor-pointer select-none dark:text-dark-300 dark:bg-dark-600 dark:border-transparent">
                            {!! trans('pagination.next') !!}
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-400 bg-gray-100 border border-gray-200 rounded-md cursor-pointer select-none dark:text-dark-500 dark:bg-dark-700 dark:border-transparent">
                            {!! trans('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>
            @if (!$simplePagination)
            <!-- Desktop Buttons -->
            <div class="hidden sm:flex sm:items-center sm:justify-between">
                <div class="mr-4">
                    <p class="text-sm leading-5 text-gray-700 dark:text-dark-300">
                        <span>{!! trans('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! trans('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! trans('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! trans('results') !!}</span>
                    </p>
                </div>
                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        <!-- Previous Page Link -->
                        <span>
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ trans('pagination.previous') }}">
                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-300 bg-gray-100 border border-gray-300 cursor-default dark:text-dark-500 dark:bg-dark-700 dark:border-transparent rounded-l-md focus:outline-hidden" aria-hidden="true">
                                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                             :icon="TallStackUi::icon('chevron-left')"
                                                             internal
                                                             class="w-5 h-5" />
                                    </span>
                                </span>
                            @else
                                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="prev" class="cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 dark:text-dark-300 dark:bg-dark-600 dark:border-transparent rounded-l-md focus:z-10 focus:outline-hidden focus:shadow-outline-blue" aria-label="{{ trans('pagination.previous') }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('chevron-left')"
                                                         internal
                                                         class="w-5 h-5" />
                                </button>
                            @endif
                        </span>
                        <!-- Pagination Elements -->
                        @foreach ($elements as $element)
                            <!-- "Three Dots" Separator -->
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300 cursor-default select-none dark:text-dark-300 dark:bg-dark-600 dark:border-transparent">{{ $element }}</span>
                                </span>
                            @endif
                            <!-- Array Of Links -->
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="relative z-10 inline-flex items-center px-4 py-2 -ml-px text-sm font-bold border cursor-default select-none text-primary-700 dark:text-dark-300 bg-primary-100 dark:bg-dark-700 border-primary-300 dark:border-transparent">
                                                    {{ $page }}
                                                </span>
                                            </span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="cursor-pointer relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-gray-600 transition duration-150 ease-in-out bg-white border border-gray-300 dark:text-dark-400 dark:bg-dark-600 dark:border-transparent focus:z-10 focus:outline-hidden focus:shadow-outline-blue" aria-label="{{ trans('Go to page :page', ['page' => $page]) }}">
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
                                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="next" class="cursor-pointer relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 dark:text-dark-300 dark:bg-dark-600 dark:border-transparent rounded-r-md focus:z-10 focus:outline-hidden focus:shadow-outline-blue" aria-label="{{ trans('pagination.next') }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('chevron-right')"
                                                         internal
                                                         class="w-5 h-5" />
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ trans('pagination.next') }}">
                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 text-gray-300 bg-gray-100 border border-gray-300 cursor-default dark:text-dark-500 dark:bg-dark-700 dark:border-transparent rounded-r-md" aria-hidden="true">
                                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                             :icon="TallStackUi::icon('chevron-right')"
                                                             internal
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
