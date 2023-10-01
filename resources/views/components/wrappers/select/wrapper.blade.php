@props(['alpine', 'header', 'loading' => null, 'computed', 'error', 'label', 'hint'])

@php($error = $errors->has($computed))

<div x-data="{!! $alpine !!}" x-cloak>
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div @class(config('tasteui.wrappers.select.wrapper')) x-on:click.outside="show = false">
        <div @class([config('tasteui.wrappers.select.div.input'), config('tasteui.wrappers.select.div.error') => $error])
             role="combobox"
             aria-controls="options"
             aria-expanded="false">
            <div x-on:click="show = true" @class(config('tasteui.wrappers.select.header'))>
                {!! $header !!}
            </div>
            <div @class(config('tasteui.wrappers.select.buttons.wrapper'))>
                <template x-if="!empty">
                    <button type="button" x-on:click="clear()">
                        <x-icon name="x-mark" @class([
                            config('tasteui.wrappers.select.buttons.x-mark.base'),
                            config('tasteui.wrappers.select.buttons.x-mark.normal') => !$error,
                            config('tasteui.wrappers.select.buttons.x-mark.error') => $error
                        ]) />
                    </button>
                </template>
                <div class="mr-1 flex items-center">
                    <button type="button" x-on:click="show = !show">
                        <x-icon name="chevron-up-down" @class([
                            config('tasteui.wrappers.select.buttons.up-down.base'),
                            config('tasteui.wrappers.select.buttons.up-down.normal') => !$error,
                            config('tasteui.wrappers.select.buttons.up-down.error') => $error
                        ]) />
                    </button>
                </div>
            </div>
        </div>
        <div x-show="show" @class(config('tasteui.wrappers.select.box.wrapper'))>
            <template x-if="searchable">
                <div class="relative px-2">
                    <x-input placeholder="{{ __('taste-ui::messages.select.input') }}"
                             x-model.debounce.500ms="search"
                             x-ref="search"
                             :validate="false"
                    />
                    <button type="button"
                            @class(config('tasteui.wrappers.select.box.button.base'))
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search.length > 0">
                        <x-icon name="x-mark" @class(config('tasteui.wrappers.select.box.button.icon')) />
                    </button>
                </div>
            </template>
            <ul wire:ignore @class(config('tasteui.wrappers.select.box.list.wrapper')) id="options" role="listbox">
                @if ($loading)
                    <div x-show="loading" @class(config('tasteui.wrappers.select.box.list.loading.wrapper')) class="flex items-center justify-center p-4 space-x-4">
                        <svg @class(config('tasteui.wrappers.select.box.list.loading.base')) xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                @endif
                <template x-for="option in options" :key="option[selectable.label] ?? option">
                    <li x-on:click="select(option)"
                        @class(config('tasteui.wrappers.select.box.list.item.wrapper'))
                        id="option-0"
                        role="option"
                        tabindex="-1"
                        x-bind:class="{ 'font-semibold hover:text-white hover:bg-red-500': selected(option) }"
                    >
                        <div wire:ignore @class(config('tasteui.wrappers.select.box.list.item.base'))>
                            <span class="ml-2 truncate" x-text="option[selectable.label] ?? option"></span>
                            <x-icon name="check" x-show="selected(option)" class="h-5 w-5 font-bold" />
                        </div>
                    </li>
                </template>
                <template x-if="!loading && options.length === 0">
                    <li class="m-2">
                        <span @class(config('tasteui.wrappers.select.message'))>
                            {{ __('taste-ui::messages.select.empty') }}
                        </span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
    @if ($hint && !$error)
        <x-hint :$hint />
    @endif
    @error ($computed)
        <x-error :$computed :$error />
    @enderror
</div>
