@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $sanitize($attributes, $property, $livewire);
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
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
        x-on:keydown="navigate($event)"
        wire:ignore.self>
    <div hidden x-ref="options">{{ TallStackUi::blade()->json($options) }}</div>
    @if ($request['params'] ?? null) <div hidden x-ref="params">{{ TallStackUi::blade()->json($request['params']) }}</div> @endif
    @if ($label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$label :$error />
    @endif
    <div class="relative" x-on:click.outside="show = false">
        <button type="button"
                x-ref="button"
                @disabled($disabled)
                @class([ $personalize['input.wrapper.base'], $personalize['input.wrapper.color'] => !$error, $personalize['input.wrapper.error'] => $error])
                @if (!$disabled) x-on:click="show = !show" @endif
                {{ $attributes->only(['x-on:select', 'x-on:remove']) }}
                aria-haspopup="listbox"
                :aria-expanded="show"
                dusk="tallstackui_select_open_close">
            <div @class($personalize['input.content'])>
                <div class="flex items-center gap-2">
                    <div x-show="multiple && quantity > 0">
                        <span x-text="quantity"></span>
                    </div>
                    <div x-show="empty || !multiple">
                        <div class="flex items-center">
                            <img x-bind:src="image" class="w-6 h-6 rounded-full mr-1" x-show="image" />
                            <span @class(['text-red-500 dark:text-red-500' => $error])
                                x-bind:class="{
                                    '{{ $personalize['itens.placeholder'] }}': empty,
                                    '{{ $personalize['itens.single'] }}': !empty
                                }" x-text="placeholder"></span>
                        </div>
                    </div>
                    <div wire:ignore @class($personalize['itens.wrapper']) x-show="multiple && quantity > 0">
                        <template x-for="(select, index) in selects" :key="index">
                            <a class="cursor-pointer">
                                <div @class($personalize['itens.multiple.item'])>
                                    <div class="flex items-center">
                                        <template x-if="select.image">
                                            <img x-bind:src="select.image" class="w-3 h-3 rounded-full mr-1" />
                                        </template>
                                        <span @class($personalize['itens.multiple.label']) x-text="select[selectable.label] ?? select"></span>
                                    </div>
                                    @if (!$disabled)
                                        <div @class($personalize['itens.multiple.icon'])>
                                            <button type="button" x-on:click="$event.stopPropagation(); clear(select)">
                                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                                     :icon="TallStackUi::icon('x-mark')"
                                                                     @class($personalize['itens.multiple.icon']) />
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
                <div @class($personalize['buttons.wrapper'])>
                    @if (!$required)
                    <template x-if="!empty">
                        <button dusk="tallstackui_select_clear"
                                type="button"
                                x-on:click="$event.stopPropagation(); clear();">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('x-mark')"
                                                 @class([$personalize['buttons.size'], $personalize['buttons.base'] => !$error, $personalize['buttons.error'] => $error]) />
                        </button>
                    </template>
                    @endif
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('chevron-up-down')"
                                         @class([$personalize['buttons.size'], $personalize['buttons.base'] => !$error, $personalize['buttons.error'] => $error]) />
                </div>
            @endif
        </button>
        <x-dynamic-component :component="TallStackUi::component('floating')"
                             class="w-full overflow-auto"
                             x-anchor="$refs.button">
            <template x-if="searchable">
                <div class="relative my-2 px-2">
                    <x-dynamic-component :component="TallStackUi::component('input')"
                                         :placeholder="data_get($placeholders, 'search')"
                                         x-model.debounce.500ms="search"
                                         x-ref="search"
                                         dusk="tallstackui_select_search_input"
                                         invalidate />
                    <button type="button"
                            @class($personalize['box.button.class'])
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search?.length > 0">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                @class($personalize['box.button.icon']) />
                    </button>
                </div>
            </template>
            <ul @class($personalize['box.list.wrapper']) dusk="tallstackui_select_options" role="listbox" x-ref="list">
                @if ($request)
                    <div x-show="loading" @class($personalize['box.list.loading.wrapper'])>
                        <x-tallstack-ui::icon.generic.loading @class($personalize['box.list.loading.class']) />
                    </div>
                @endif
                <template x-for="(option, index) in available" :key="index">
                    <li x-on:click="select(option)"
                        x-on:keypress.enter="select(option)"
                        x-bind:class="{'{{ $personalize['box.list.item.selected'] }}': selected(option), '{{ $personalize['box.list.item.disabled'] }}': option.disabled === true}"
                        role="option" @class($personalize['box.list.item.wrapper'])>
                        <div @class($personalize['box.list.item.options'])>
                            <div @class($personalize['box.list.item.base'])>
                                <img @class($personalize['box.list.item.image']) x-bind:src="option.image" x-show="option.image">
                                <div class="flex flex-col ml-2">
                                    <span x-text="option[selectable.label] ?? option"></span>
                                    <span @class($personalize['box.list.item.description']) x-show="option.description" x-text="option.description"></span>
                                </div>
                            </div>
                            <div @class($personalize['box.list.item.check'])>
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="TallStackUi::icon('check')"
                                                     x-show="selected(option)"
                                                     @class($personalize['box.list.item.check']) />
                            </div>
                        </div>
                    </li>
                </template>
                @if (!$after)
                    <template x-if="!loading && available.length === 0">
                        <li class="m-2">
                            <span @class($personalize['box.list.empty'])>
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
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::component('error')" :$property />
    @endif
</div>
