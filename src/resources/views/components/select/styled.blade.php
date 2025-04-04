@php
    $personalize = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<div x-data="tallstackui_select(
        {!! $entangle !!},
        @js($request),
        @js($selectable),
        @js($multiple),
        @js($placeholder),
        @js($searchable),
        @js($common),
        @js($required),
        @js($livewire),
        @js($property),
        @js($value),
        @js($limit),
        @js($change),
        @js($configurations['unfiltered']),
        @js($lazy))"
        @if ($attributes->whereStartsWith('x-model'))
            x-modelable="model"
            {{ $attributes->whereStartsWith('x-model') }}
        @endif
        x-cloak
        x-on:keydown="navigate($event)"
        wire:ignore>
    <div hidden x-ref="options">{{ TallStackUi::blade()->json($options) }}</div>
    @if ($request['params'] ?? null) <div hidden x-ref="params">{{ TallStackUi::blade()->json($request['params']) }}</div> @endif
    @if ($label)
        <x-dynamic-component :component="TallStackUi::prefix('label')" :$label :$error />
    @endif
    <div class="relative" x-on:click.outside="show = false">
        <button type="button"
                x-ref="button"
                @disabled($disabled)
                @class([$personalize['input.wrapper.base'], $personalize['input.wrapper.color'] => !$error, $personalize['input.wrapper.error'] => $error])
                @if (!$disabled) x-on:click="show = !show" @endif
                {{ $attributes->only(['x-on:select', 'x-on:remove']) }}
                aria-haspopup="listbox"
                :aria-expanded="show"
                dusk="tallstackui_select_open_close">
            <div class="{{ $personalize['input.content.wrapper.first'] }}">
                <div class="{{ $personalize['input.content.wrapper.second'] }}">
                    <div x-show="multiple && quantity > 0">
                        <span x-text="quantity"></span>
                    </div>
                    <div x-show="empty || !multiple">
                        <div class="{{ $personalize['items.placeholder.wrapper'] }}">
                            <img x-bind:src="image" class="{{ $personalize['items.image'] }}" x-show="image" />
                            <span @class(['text-red-500 dark:text-red-500' => $error])
                                x-bind:class="{
                                    '{{ $personalize['items.placeholder.text'] }}': empty,
                                    '{{ $personalize['items.single'] }}': !empty
                                }" x-text="placeholder"></span>
                        </div>
                    </div>
                    <div wire:ignore class="{{ $personalize['items.wrapper'] }}" x-show="multiple && quantity > 0">
                        <template x-for="(select, index) in selects" :key="index">
                            <a class="cursor-pointer">
                                <div class="{{ $personalize['items.multiple.item'] }}">
                                    <div class="{{ $personalize['items.multiple.label.wrapper'] }}">
                                        <template x-if="select.image">
                                            <img x-bind:src="select.image" class="{{ $personalize['items.multiple.image'] }}" />
                                        </template>
                                        <span class="{{ $personalize['items.multiple.label'] }}" x-text="select[selectable.label] ?? select"></span>
                                    </div>
                                    @if (!$disabled)
                                        <div class="{{ $personalize['items.multiple.icon'] }}">
                                            <button type="button" class="cursor-pointer" x-on:click="$event.stopPropagation(); clear(select)">
                                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                     :icon="TallStackUi::icon('x-mark')"
                                                                     internal
                                                                     class="{{ $personalize['items.multiple.icon'] }}" />
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            @if (!$disabled)
                <div class="{{ $personalize['buttons.wrapper'] }}" wire:ignore>
                    @if (!$required)
                    <template x-if="!empty">
                        <button dusk="tallstackui_select_clear"
                                id="select-clear"
                                type="button"
                                class="cursor-pointer"
                                x-on:click="$event.stopPropagation(); clear();">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('x-mark')"
                                                 internal
                                                 @class([$personalize['buttons.size'], $personalize['buttons.base'] => !$error, $personalize['buttons.error'] => $error]) />
                        </button>
                    </template>
                    @endif
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('chevron-up-down')"
                                         internal
                                         @class([$personalize['buttons.size'], $personalize['buttons.base'] => !$error, $personalize['buttons.error'] => $error]) />
                </div>
            @endif
        </button>
        <x-dynamic-component :component="TallStackUi::prefix('floating')"
                             :floating="$personalize['floating.default']"
                             :class="$personalize['floating.class']"
                             x-anchor="$refs.button">
            <template x-if="searchable">
                <div class="{{ $personalize['box.searchable.wrapper'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         :placeholder="data_get($placeholders, 'search')"
                                         x-model.debounce.500ms="search"
                                         x-ref="search"
                                         dusk="tallstackui_select_search_input"
                                         invalidate />
                    <button type="button"
                            class="{{ $personalize['box.button.class'] }}"
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search?.length > 0">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             internal
                                             class="{{ $personalize['box.button.icon'] }}" />
                    </button>
                </div>
            </template>
            <ul class="{{ $personalize['box.list.wrapper'] }}" dusk="tallstackui_select_options" role="listbox" x-ref="list" wire:ignore.self>
                @if ($request)
                    <div x-show="loading" class="{{ $personalize['box.list.loading.wrapper'] }}">
                        <x-tallstack-ui::icon.generic.loading class="{{ $personalize['box.list.loading.class'] }}" />
                    </div>
                @endif
                @if ($grouped)
                <template x-for="(option, index) in available" :key="option.__tsui_key ?? index">
                    <li>
                        <div class="{{ $personalize['box.list.grouped.wrapper'] }}">
                            <div class="{{ $personalize['box.list.grouped.options'] }}">
                                <div class="{{ $personalize['box.list.grouped.base'] }}">
                                    <img class="{{ $personalize['box.list.grouped.image'] }}" x-bind:src="option.image" x-show="option.image">
                                    <div class="{{ $personalize['box.list.grouped.description.wrapper'] }}">
                                        <span x-text="option[selectable.label] ?? option"></span>
                                        <span class="{{ $personalize['box.list.grouped.description.text'] }}" x-show="option.description" x-text="option.description"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <template x-for="(item, index) in option.value" :key="index">
                            <li x-on:click="select(item)"
                                x-on:keypress.enter="select(item)"
                                x-bind:class="{'{{ $personalize['box.list.item.selected'] }}': selected(item), '{{ $personalize['box.list.item.disabled'] }}': item.disabled === true}"
                                role="option"
                                class="{{ $personalize['box.list.item.wrapper'] }}">
                                <div class="{{ $personalize['box.list.item.grouped'] }}">
                                    <div class="{{ $personalize['box.list.item.base'] }}">
                                        <img class="{{ $personalize['box.list.item.image'] }}" x-bind:src="item[selectable.image]" x-show="item[selectable.description]">
                                        <div class="{{ $personalize['box.list.item.description.wrapper'] }}">
                                            <span x-text="item[selectable.label] ?? item"></span>
                                            <span class="{{ $personalize['box.list.item.description.text'] }}" x-show="item[selectable.description]" x-text="item[selectable.description]"></span>
                                        </div>
                                    </div>
                                    <div class="{{ $personalize['box.list.item.check'] }}">
                                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                             :icon="TallStackUi::icon('check')"
                                                             x-show="selected(item)"
                                                             internal
                                                             class="{{ $personalize['box.list.item.check'] }}" />
                                    </div>
                                </div>
                            </li>
                        </template>
                    </li>
                </template>
                @else
                <template x-for="(option, index) in available" :key="option.__tsui_key ?? index">
                    <li x-on:click="select(option)"
                        x-on:keypress.enter="select(option)"
                        x-bind:class="{'{{ $personalize['box.list.item.selected'] }}': selected(option), '{{ $personalize['box.list.item.disabled'] }}': option.disabled === true}"
                        role="option"
                        class="{{ $personalize['box.list.item.wrapper'] }}">
                        <div class="{{ $personalize['box.list.item.options'] }}">
                            <div class="{{ $personalize['box.list.item.base'] }}">
                                <img class="{{ $personalize['box.list.item.image'] }}" x-bind:src="option[selectable.image]" x-show="option[selectable.image]">
                                <div class="{{ $personalize['box.list.item.description.wrapper'] }}">
                                    <span x-text="option[selectable.label] ?? option"></span>
                                    <span class="{{ $personalize['box.list.item.description.text'] }}" x-show="option[selectable.description]" x-text="option[selectable.description]"></span>
                                </div>
                            </div>
                            <div class="{{ $personalize['box.list.item.check'] }}">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('check')"
                                                     x-show="selected(option)"
                                                     internal
                                                     class="{{ $personalize['box.list.item.check'] }}" />
                            </div>
                        </div>
                    </li>
                </template>
                @endif
                <li x-show="@js($common) === true && available.length >= 10" x-intersect:once="load()"></li>
                @if (!$after)
                    <template x-if="!loading && available.length === 0">
                        <li class="m-2">
                            <span class="{{ $personalize['box.list.empty'] }}">
                                {{ data_get($placeholders, 'empty') }}
                            </span>
                        </li>
                    </template>
                @else
                    <div x-show="!loading && available.length === 0">
                        {!! $after !!}
                    </div>
                @endif
            </ul>
        </x-dynamic-component>
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::prefix('error')" :$property />
    @endif
</div>
