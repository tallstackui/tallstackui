@php
    $simplePagination ??= false;

    if (! isset($scrollTo)) {
        $scrollTo = "body";
    }

    $scrollIntoViewJsSnippet =
        $scrollTo !== false
            ? <<<JS
               \$refs.persist.scrollIntoView();
            JS
            : "";
@endphp

@if ($paginator->hasPages())
    <div class="mt-4">
        <nav role="navigation" aria-label="Pagination Navigation">
            <div
                @class(["mb-4 flex flex-1 gap-x-2", "justify-end" => $simplePagination, "justify-between sm:hidden" => ! $simplePagination])
            >
                <span>
                    @if ($paginator->onFirstPage())
                        <span
                            class="dark:text-dark-500 dark:bg-dark-700 relative inline-flex cursor-pointer items-center rounded-md border border-gray-200 bg-gray-100 px-4 py-2 text-sm leading-5 font-medium text-gray-400 select-none dark:border-transparent"
                        >
                            {!! trans("pagination.previous") !!}
                        </span>
                    @else
                        <button
                            type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            dusk="previousPage{{ $paginator->getPageName() == "page" ? "" : "." . $paginator->getPageName() }}.before"
                            class="dark:text-dark-300 dark:bg-dark-600 relative inline-flex cursor-pointer items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-500 select-none dark:border-transparent"
                        >
                            {!! trans("pagination.previous") !!}
                        </button>
                    @endif
                </span>
                <span>
                    @if ($paginator->hasMorePages())
                        <button
                            type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            dusk="nextPage{{ $paginator->getPageName() == "page" ? "" : "." . $paginator->getPageName() }}.before"
                            class="dark:text-dark-300 dark:bg-dark-600 relative inline-flex cursor-pointer items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-500 select-none dark:border-transparent"
                        >
                            {!! trans("pagination.next") !!}
                        </button>
                    @else
                        <span
                            class="dark:text-dark-500 dark:bg-dark-700 relative inline-flex cursor-pointer items-center rounded-md border border-gray-200 bg-gray-100 px-4 py-2 text-sm leading-5 font-medium text-gray-400 select-none dark:border-transparent"
                        >
                            {!! trans("pagination.next") !!}
                        </span>
                    @endif
                </span>
            </div>
            @if (! $simplePagination)
                <!-- Desktop Buttons -->
                <div class="hidden sm:flex sm:items-center sm:justify-between">
                    <div class="mr-4">
                        <p
                            class="dark:text-dark-300 text-sm leading-5 text-gray-700"
                        >
                            <span>{!! trans("Showing") !!}</span>
                            <span class="font-medium">
                                {{ $paginator->firstItem() }}
                            </span>
                            <span>{!! trans("to") !!}</span>
                            <span class="font-medium">
                                {{ $paginator->lastItem() }}
                            </span>
                            <span>{!! trans("of") !!}</span>
                            <span class="font-medium">
                                {{ $paginator->total() }}
                            </span>
                            <span>{!! trans("results") !!}</span>
                        </p>
                    </div>
                    <div>
                        <span
                            class="relative z-0 inline-flex rounded-md shadow-sm"
                        >
                            <!-- Previous Page Link -->
                            <span>
                                @if ($paginator->onFirstPage())
                                    <span
                                        aria-disabled="true"
                                        aria-label="{{ trans("pagination.previous") }}"
                                    >
                                        <span
                                            class="dark:text-dark-500 dark:bg-dark-700 relative inline-flex cursor-default items-center rounded-l-md border border-gray-300 bg-gray-100 px-2 py-2 text-sm leading-5 font-medium text-gray-300 focus:outline-hidden dark:border-transparent"
                                            aria-hidden="true"
                                        >
                                            <x-dynamic-component
                                                :component="TallStackUi::prefix('icon')"
                                                :icon="TallStackUi::icon('chevron-left')"
                                                internal
                                                class="h-5 w-5"
                                            />
                                        </span>
                                    </span>
                                @else
                                    <button
                                        type="button"
                                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        dusk="previousPage{{ $paginator->getPageName() == "page" ? "" : "." . $paginator->getPageName() }}.after"
                                        rel="prev"
                                        class="dark:text-dark-300 dark:bg-dark-600 focus:shadow-outline-blue relative inline-flex cursor-pointer items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm leading-5 font-medium text-gray-500 transition duration-150 ease-in-out focus:z-10 focus:outline-hidden dark:border-transparent"
                                        aria-label="{{ trans("pagination.previous") }}"
                                    >
                                        <x-dynamic-component
                                            :component="TallStackUi::prefix('icon')"
                                            :icon="TallStackUi::icon('chevron-left')"
                                            internal
                                            class="h-5 w-5"
                                        />
                                    </button>
                                @endif
                            </span>
                            <!-- Pagination Elements -->
                            @foreach ($elements as $element)
                                <!-- "Three Dots" Separator -->
                                @if (is_string($element))
                                    <span aria-disabled="true">
                                        <span
                                            class="dark:text-dark-300 dark:bg-dark-600 relative -ml-px inline-flex cursor-default items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 select-none dark:border-transparent"
                                        >
                                            {{ $element }}
                                        </span>
                                    </span>
                                @endif

                                <!-- Array Of Links -->
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        <span
                                            wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}"
                                        >
                                            @if ($page == $paginator->currentPage())
                                                <span aria-current="page">
                                                    <span
                                                        class="text-primary-700 dark:text-dark-300 bg-primary-100 dark:bg-dark-700 border-primary-300 relative z-10 -ml-px inline-flex cursor-default items-center border px-4 py-2 text-sm font-bold select-none dark:border-transparent"
                                                    >
                                                        {{ $page }}
                                                    </span>
                                                </span>
                                            @else
                                                <button
                                                    type="button"
                                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                                    class="dark:text-dark-400 dark:bg-dark-600 focus:shadow-outline-blue relative -ml-px inline-flex cursor-pointer items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-600 transition duration-150 ease-in-out focus:z-10 focus:outline-hidden dark:border-transparent"
                                                    aria-label="{{ trans("Go to page :page", ["page" => $page]) }}"
                                                >
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
                                    <button
                                        type="button"
                                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        dusk="nextPage{{ $paginator->getPageName() == "page" ? "" : "." . $paginator->getPageName() }}.after"
                                        rel="next"
                                        class="dark:text-dark-300 dark:bg-dark-600 focus:shadow-outline-blue relative -ml-px inline-flex cursor-pointer items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm leading-5 font-medium text-gray-500 transition duration-150 ease-in-out focus:z-10 focus:outline-hidden dark:border-transparent"
                                        aria-label="{{ trans("pagination.next") }}"
                                    >
                                        <x-dynamic-component
                                            :component="TallStackUi::prefix('icon')"
                                            :icon="TallStackUi::icon('chevron-right')"
                                            internal
                                            class="h-5 w-5"
                                        />
                                    </button>
                                @else
                                    <span
                                        aria-disabled="true"
                                        aria-label="{{ trans("pagination.next") }}"
                                    >
                                        <span
                                            class="dark:text-dark-500 dark:bg-dark-700 relative -ml-px inline-flex cursor-default items-center rounded-r-md border border-gray-300 bg-gray-100 px-2 py-2 text-sm leading-5 font-medium text-gray-300 dark:border-transparent"
                                            aria-hidden="true"
                                        >
                                            <x-dynamic-component
                                                :component="TallStackUi::prefix('icon')"
                                                :icon="TallStackUi::icon('chevron-right')"
                                                internal
                                                class="h-5 w-5"
                                            />
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
