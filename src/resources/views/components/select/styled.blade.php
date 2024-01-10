@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $transform($attributes, $property, $livewire);
@endphp

@if (!$livewire && $property)
    <input hidden id="{{ $id }}" name="{{ $property }}">
@endif

<div x-data="tallstackui_select(
        {!! $entangle !!},
        @js($request),
        @js($selectable),
        @js($options),
        @js($multiple),
        @js($placeholder),
        @js($searchable),
        @js($common),
        @js($required),
        @js($livewire),
        @js($property),
        @js($value),
        @js($limit),
        @js($change($attributes, $__livewire ?? null, $livewire)))"
        x-cloak
        wire:ignore.self>
    <div hidden x-ref="options">{{ TallStackUi::blade()->json($options) }}</div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$label :$error />
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
                <div class="flex items-center gap-2">
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
                        <template x-for="select in selects" :key="select[selectable.value] ?? select">
                            <a class="cursor-pointer">
                                <div @class($personalize['itens.multiple.item'])>
                                    <span x-text="select[selectable.label] ?? select"></span>
                                    @if (!$disabled)
                                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                                             icon="x-mark"
                                                             x-on:click="clear(select)"
                                                             @class($personalize['itens.multiple.icon']) />
                                    @endif
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            @if (!$disabled && !$required)
                <div @class($personalize['buttons.wrapper'])>
                    <template x-if="!empty">
                        <button dusk="tallstackui_select_clear" type="button" x-on:click="clear(); show = true;">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 icon="x-mark"
                                                 @class([$personalize['buttons.size'], $personalize['buttons.base'] => !$error, $personalize['buttons.error'] => $error]) />
                        </button>
                    </template>
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         icon="chevron-up-down"
                                         @class([$personalize['buttons.size'], $personalize['buttons.base'] => !$error, $personalize['buttons.error'] => $error]) />
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
                <div class="relative my-2 px-2">
                    <x-dynamic-component :component="TallStackUi::component('input')"
                                         :placeholder="$placeholders['search']"
                                         x-model.debounce.500ms="search"
                                         x-ref="search"
                                         dusk="tallstackui_select_search_input"
                                         invalidate />
                    <button type="button"
                            @class($personalize['box.button.class'])
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search?.length > 0">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="x-mark"
                                             @class($personalize['box.button.icon']) />
                    </button>
                </div>
            </template>
            <ul @class($personalize['box.list.wrapper']) dusk="tallstackui_select_options" role="listbox">
                @if ($request)
                    <div x-show="loading" @class($personalize['box.list.loading.wrapper'])>
                        <x-tallstack-ui::icon.others.loading @class($personalize['box.list.loading.class']) />
                    </div>
                @endif
                <template x-for="(option, index) in available" :key="option[selectable.value] ?? option">
                    <li x-on:click="select(option)"
                        x-on:keypress.enter="select(option)"
                        x-bind:class="{'{{ $personalize['box.list.item.selected'] }}': selected(option), '{{ $personalize['box.list.item.disabled'] }}': option.disabled === true}"
                        role="option" @class($personalize['box.list.item.wrapper'])>
                        <div @class($personalize['box.list.item.options'])>
                            <div class="flex items-center">
                                <img @class($personalize['box.list.item.image']) x-bind:src="option.img" x-show="option.img !== null">
                                <span class="ml-2 truncate" x-text="option[selectable.label] ?? option"></span>
                            </div>
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 icon="check"
                                                 x-show="selected(option)"
                                                 @class($personalize['box.list.item.check']) />
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
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::component('error')" :$property />
    @endif
</div>
