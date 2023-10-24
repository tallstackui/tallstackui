@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $live = str($directive)->contains('.live');
    $customize = tallstackui_personalization('select.styled', $personalization());
@endphp

<div x-data="tallstackui_select(
        @if ($live) @entangle($property).live @else @entangle($property) @endif,
        @js($request),
        @js($selectable),
        @js($options),
        @js($multiple),
        @js($selectable !== []),
        @js($placeholders['default']),
        @js($searchable),
        @js($common)
    )" x-cloak>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <div class="relative" x-on:click.outside="show = false">
        <button type="button"
                @disabled($disabled)
                @class([
                    $customize['button.wrapper.base'],
                    $customize['button.wrapper.color'] => !$error,
                    $customize['button.wrapper.error'] => $error
                ])
                @if (!$disabled) x-on:click="show = !show" @endif
                aria-haspopup="listbox"
                :aria-expanded="show"
                dusk="tallstackui_select_open_close">
            <div @class($customize['button.content'])>
                <div class="flex gap-2">
                    <template x-if="multiple && quantity > 0">
                        <span x-text="quantity"></span>
                    </template>
                    <template x-if="empty || (!multiple && @js($placeholders['default']) !== placeholder)">
                        <span @class(['truncate', 'text-red-500 dark:text-red-500' => $error])
                              x-bind:class="{
                                'text-gray-400 dark:text-dark-400': empty,
                                'text-gray-600 dark:text-dark-300': !empty
                              }" x-text="placeholder"></span>
                    </template>
                    <div class="truncate" x-show="multiple && quantity > 0">
                        <template x-for="(selected, index) in selecteds" :key="selected[selectable.label] ?? selected">
                            <a class="cursor-pointer">
                                <div @class($customize['itens.multiple.item'])>
                                    <span x-text="selected[selectable.label] ?? selected"></span>
                                    @if (!$disabled)
                                        <x-icon name="x-mark"
                                                x-on:click="clear(selected); show = true"
                                                @class($customize['itens.multiple.icon'])
                                        />
                                    @endif
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            @if (!$disabled)
                <div @class($customize['buttons.wrapper'])>
                    <template x-if="!empty">
                        <button dusk="tallstackui_select_clear" type="button" x-on:click="clear(); show = true">
                            <x-icon name="x-mark" @class([
                                $customize['buttons.size'],
                                $customize['buttons.base'] => !$error,
                                $customize['buttons.error'] => $error
                            ]) />
                        </button>
                    </template>
                    <x-icon name="chevron-up-down" @class([
                        $customize['buttons.size'],
                        $customize['buttons.base'] => !$error,
                        $customize['buttons.error'] => $error
                    ]) />
                </div>
            @endif
        </button>
        <div wire:ignore x-show="show" x-cloak style="display: none;" @class($customize['box.wrapper']) x-ref="select">
            <template x-if="searchable">
                <div class="relative px-2 my-2">
                    <x-input :placeholder="$placeholders['search']"
                             x-model.debounce.500ms="search"
                             x-ref="search"
                             dusk="tallstackui_select_search_input"
                             :validate="false"
                    />
                    <button type="button"
                            @class([$customize['box.button.class']])
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search.length > 0">
                        <x-icon name="x-mark" @class($customize['box.button.icon']) />
                    </button>
                </div>
            </template>
            <ul @class($customize['box.list.wrapper']) dusk="tallstackui_select_options" role="listbox">
                @if ($request)
                    <div x-show="loading" @class($customize['box.list.loading.wrapper'])>
                        <x-tallstack-ui::icon.others.loading @class($customize['box.list.loading.class']) />
                    </div>
                @endif
                <template x-for="(option, index) in options" :key="option[selectable.label] ?? option">
                    <li x-on:click="select(option)"
                        x-on:keypress.enter="select(option)"
                        x-bind:class="{ 'font-semibold hover:text-white hover:bg-red-500 dark:hover:bg-red-500': selected(option) }"
                        role="option" @class($customize['box.list.item.wrapper'])>
                        <div wire:ignore @class($customize['box.list.item.class'])>
                            <span class="ml-2 truncate" x-text="option[selectable.label] ?? option"></span>
                            <x-icon name="check" x-show="selected(option)" class="h-5 w-5 font-bold"/>
                        </div>
                    </li>
                </template>
                @if (!$after)
                    <template x-if="!loading && options.length === 0">
                        <li class="m-2">
                            <span @class($customize['message'])>
                                {{ $placeholders['empty'] }}
                            </span>
                        </li>
                    </template>
                @else
                    <div x-show="!loading && options.length === 0">
                        {!! $after !!}
                    </div>
                @endif
            </ul>
        </div>
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error && $property)
        <x-error :computed="$property" :$error/>
    @endif
</div>
