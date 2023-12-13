@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $live = str($directive)->contains('.live');
    $personalize = tallstackui_personalization('select.styled', $personalization());
@endphp

<div x-data="tallstackui_select(
        {{ TallStackUi::blade()->entangle($attributes->wire('model')) }},
        @js($request),
        @js($selectable),
        @js($options),
        @js($multiple),
        @js($placeholders['default']),
        @js($searchable),
        @js($common)
    )" x-cloak wire:ignore.self>
    <div hidden x-ref="options">{{ TallStackUi::blade()->json($options) }}</div>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <div class="relative" x-on:click.outside="show = false">
        <button type="button"
                x-ref="button"
                @disabled($disabled)
                @class([ $personalize['input.wrapper.base'], $personalize['input.wrapper.color'] => !$error, $personalize['input.wrapper.error'] => $error])
                @if (!$disabled) x-on:click="show = !show" @endif
                aria-haspopup="listbox"
                :aria-expanded="show"
                dusk="tallstackui_select_open_close">
            <div @class($personalize['input.content'])>
                <div class="flex gap-2">
                    <div x-show="multiple && quantity > 0">
                        <span x-text="quantity"></span>
                    </div>
                    <div x-show="empty || !multiple">
                        <span @class(['truncate', 'text-red-500 dark:text-red-500' => $error])
                              x-bind:class="{
                                '{{ $personalize['itens.placeholder'] }}': empty,
                                '{{ $personalize['itens.single'] }}': !empty
                              }" x-text="placeholder"></span>
                    </div>
                    <div wire:ignore class="truncate" x-show="multiple && quantity > 0">
                        <template x-for="select in selects" :key="select[selectable.label] ?? select">
                            <a class="cursor-pointer">
                                <div @class($personalize['itens.multiple.item'])>
                                    <span x-text="select[selectable.label] ?? select"></span>
                                    @if (!$disabled)
                                        <x-icon name="x-mark"
                                                x-on:click="clear(select)"
                                                @class($personalize['itens.multiple.icon'])
                                        />
                                    @endif
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            @if (!$disabled)
                <div @class($personalize['buttons.wrapper'])>
                    <template x-if="!empty">
                        <button dusk="tallstackui_select_clear" type="button" x-on:click="clear(); show = true;">
                            <x-icon name="x-mark" @class([
                                $personalize['buttons.size'],
                                $personalize['buttons.base'] => !$error,
                                $personalize['buttons.error'] => $error
                            ]) />
                        </button>
                    </template>
                    <x-icon name="chevron-up-down" @class([
                        $personalize['buttons.size'],
                        $personalize['buttons.base'] => !$error,
                        $personalize['buttons.error'] => $error
                    ]) />
                </div>
            @endif
        </button>
        <div x-show="show"
             x-cloak
             style="display: none;"
             x-transition:enter="transition ease-out duration-75"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100"
             x-anchor.offset.5="$refs.button"
             @class($personalize['box.wrapper'])
             x-ref="select">
            <template x-if="searchable">
                <div class="relative px-2 my-2">
                    <x-input :placeholder="$placeholders['search']"
                             x-model.debounce.500ms="search"
                             x-ref="search"
                             dusk="tallstackui_select_search_input"
                             :validate="false"
                    />
                    <button type="button"
                            @class($personalize['box.button.class'])
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search?.length > 0">
                        <x-icon name="x-mark" @class($personalize['box.button.icon']) />
                    </button>
                </div>
            </template>
            <ul @class($personalize['box.list.wrapper']) dusk="tallstackui_select_options" role="listbox">
                @if ($request)
                    <div x-show="loading" @class($personalize['box.list.loading.wrapper'])>
                        <x-tallstack-ui::icon.others.loading @class($personalize['box.list.loading.class']) />
                    </div>
                @endif
                <template x-for="(option, index) in available" :key="option[selectable.label] ?? option">
                    <li x-on:click="select(option)"
                        x-on:keypress.enter="select(option)"
                        x-bind:class="{ '{{ $personalize['box.list.item.selected'] }}': selected(option) }"
                        role="option" @class($personalize['box.list.item.wrapper'])>
                        <div @class($personalize['box.list.item.options'])>
                            <span class="ml-2 truncate" x-text="option[selectable.label] ?? option"></span>
                            <x-icon name="check"
                                    x-show="selected(option)" @class($personalize['box.list.item.check']) />
                        </div>
                    </li>
                </template>
                @if (!$after)
                    <template x-if="!loading && available.length === 0">
                        <li class="m-2">
                            <span @class($personalize['box.list.empty'])>
                                {{ $placeholders['empty'] }}
                            </span>
                        </li>
                    </template>
                @else
                    <div x-show="!loading && available.length === 0">
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
