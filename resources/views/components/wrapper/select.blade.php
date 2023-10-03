@php
    $customize = tasteui_personalization('wrapper.select', $customization());
    $error = $errors->has($computed);
@endphp

<div x-data="{!! $alpine !!}" x-cloak>
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div @class($customize['wrapper']) x-on:click.outside="show = false">
        <div @class([$customize['div.base'], $customize['div.error'] => $error])
             role="combobox"
             aria-controls="options"
             aria-expanded="false">
            <div x-on:click="show = true" @class($customize['header'])>
                {!! $header !!}
            </div>
            <div @class($customize['buttons.wrapper']))>
                <template x-if="!empty">
                    <button id="tasteui_select_clear" type="button" x-on:click="clear()">
                        <x-icon name="x-mark" @class([$customize['buttons.mark.base'], $customize['buttons.x-mark.error'] => $error]) />
                    </button>
                </template>
                <div class="mr-1 flex items-center">
                    <button id="tasteui_select_open_close" type="button" x-on:click="show = !show">
                        <x-icon name="chevron-up-down" @class([$customize['buttons.down.base'], $customize['buttons.up-down.error'] => $error]) />
                    </button>
                </div>
            </div>
        </div>
        <div x-show="show" @class($customize['box.wrapper'])>
            <template x-if="searchable">
                <div class="relative px-2">
                    <x-input placeholder="{{ __('taste-ui::messages.select.input') }}"
                             x-model.debounce.500ms="search"
                             x-ref="search"
                             id="tasteui_select_search_input"
                             :validate="false"
                    />
                    <button type="button"
                            @class($customize['box.button.base']))
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search.length > 0">
                        <x-icon name="x-mark" @class($customize['box.button.icon']) />
                    </button>
                </div>
            </template>
            <ul wire:ignore @class($customize['box.list.wrapper']) id="tasteui_select_options" role="listbox">
                @if ($loading)
                    <div x-show="loading" @class($customize['box.list.loading.wrapper']) class="flex items-center justify-center p-4 space-x-4">
                        <svg @class($customize['box.list.loading.base']) xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                @endif
                <template x-for="option in options" :key="option[selectable.label] ?? option">
                    <li x-on:click="select(option)"
                        @class($customize['box.list.item.wrapper'])
                        role="option"
                        tabindex="-1"
                        x-bind:class="{ 'font-semibold hover:text-white hover:bg-red-500': selected(option) }"
                    >
                        <div wire:ignore @class($customize['box.list.item.base'])>
                            <span class="ml-2 truncate" x-text="option[selectable.label] ?? option"></span>
                            <x-icon name="check" x-show="selected(option)" class="h-5 w-5 font-bold" />
                        </div>
                    </li>
                </template>
                <template x-if="!loading && options.length === 0">
                    <li class="m-2">
                        <span @class($customize['message'])>
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
