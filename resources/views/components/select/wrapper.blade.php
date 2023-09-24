@props(['alpine', 'header', 'loading' => null, 'computed', 'error', 'label', 'hint'])

@php($error = $errors->has($computed))

<div x-data="{!! $alpine !!}">
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div class="relative mt-2" x-on:click.outside="show = false">
        <div @class([
                'flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 text-gray-900',
                'shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6',
                'text-red-600 ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500' => $error,
            ])
             role="combobox"
             aria-controls="options"
             aria-expanded="false">
            <div x-on:click="show = true" class="relative inset-y-0 left-0 flex items-center w-full pl-2 space-x-2 overflow-hidden transition rounded-lg">
                {!! $header !!}
            </div>
            <div class="flex items-center mr-1">
                <template x-if="!empty">
                    <button type="button" x-on:click="clear()">
                        <x-icon name="x-mark" @class(['h-5 w-5 transition hover:text-red-500', 'text-secondary-500' => !$error, 'text-red-500' => $error]) />
                    </button>
                </template>
                <div class="flex items-center mr-1">
                    <button type="button" x-on:click="show = !show">
                        <x-icon name="chevron-up-down" @class(['h-5 w-5 transition', 'text-secondary-500' => !$error, 'text-red-500' => $error]) />
                    </button>
                </div>
            </div>
        </div>
        <div class="relative">
            <div x-show="show" class="absolute z-50 w-full bg-white px-2 mt-1 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                <template x-if="searchable">
                    <div class="relative">
                        <div class="absolute z-[51] inset-y-0 flex items-center px-2 pointer-events-none">
                           <x-icon name="magnifying-glass" class="h-5 w-5 text-secondary-500" />
                        </div>
                        <x-input 
                            placeholder="{{ __('taste-ui::messages.select.input') }}"
                            x-model.debounce.500ms="search"
                            x-ref="search"
                            class="px-10"
                        />
                        <div class="pr-5">
                            <button type="button" 
                                class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer" 
                                x-on:click="clearSearch()"
                                x-show="search.length > 0"
                            >
                                <x-icon name="x-mark" class="h-5 w-5 transition hover:text-red-500 text-secondary-500" />
                            </button>
                        </div>
                    </div>
                </template>
                <ul wire:ignore class="z-50 w-full py-1 mt-1 overflow-auto text-base bg-white max-h-60 soft-scrollbar focus:outline-none sm:text-sm" id="options" role="listbox">
                    @if ($loading)
                        <div x-show="loading" class="flex items-center justify-center p-4 space-x-4">
                            <svg class="w-12 h-12 animate-spin text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    @endif
                    <template x-for="option in options" :key="option[selectable.label] ?? option">
                        <li x-on:click="select(option)"
                            class="relative py-2 pr-2 text-gray-700 transition cursor-pointer select-none hover:bg-gray-100"
                            id="option-0"
                            role="option"
                            tabindex="-1"
                            x-bind:class="{ 'font-semibold hover:text-white hover:bg-red-500': selected(option) }"
                        >
                            <div wire:ignore class="flex items-center justify-between">
                                <span class="ml-2 truncate" x-text="option[selectable.label] ?? option"></span>
                                <svg x-show="selected(option)" class="w-5 h-5 font-bold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                        </li>
                    </template>
                    <template x-if="!loading && options.length === 0">
                        <li class="m-2">
                            <span class="block w-full pr-2 text-gray-700">
                                {{ __('taste-ui::messages.select.empty') }}
                            </span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
    @if ($hint && !$error)
        <span class="mt-2 text-sm text-secondary-500">
        {{ $hint }}
    </span>
    @endif
    @error ($computed)
    <span class="mt-2 text-sm text-red-500">
        {{ $message }}
    </span>
    @enderror
</div>
