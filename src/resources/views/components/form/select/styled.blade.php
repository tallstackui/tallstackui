@php
    $customization = $classes();
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
        @js($lazy),
        @js($configurations['recycle']))"
     @if ($attributes->whereStartsWith('x-model'))
         x-modelable="model"
     {{ $attributes->whereStartsWith('x-model') }}
     @endif
     x-cloak
     translate="no"
     x-on:keydown="navigate($event)"
     @if ($open) x-on:select:{{ $open }}.window="$nextTick(() => show = true)" @endif
     @if ($close) x-on:select:{{ $close }}.window="show = false" @endif
     wire:ignore.self>
    <div hidden x-ref="options">{{ TallStackUi::blade()->json($options) }}</div>
    @if ($request['params'] ?? null)
        <div hidden x-ref="params">{{ TallStackUi::blade()->json($request['params']) }}</div>
    @endif
    @if ($label && !$side)
        <x-dynamic-component :component="TallStackUi::prefix('label')" scope="form.select-styled.label" :$label :$error />
    @endif
    <div class="relative" x-on:click.outside="show = false">
        <button type="button"
                x-ref="button"
                @disabled($disabled)
                @class([
                    $customization['input.wrapper.base'],
                    $customization['input.wrapper.color'] => !$error,
                    $customization['input.wrapper.error'] => $error,
                    $customization['input.wrapper.round.left'] => $side === 'left',
                    $customization['input.wrapper.round.right'] => $side === 'right',
                    $customization['input.wrapper.borderless'] => $side,
                ])
                @if (!$disabled) x-on:click="show = !show" @endif
                {{ $attributes->only(['x-on:select', 'x-on:remove']) }}
                aria-haspopup="listbox"
                :aria-expanded="show"
                dusk="tallstackui_select_open_close">
            <div class="{{ $customization['input.content.wrapper.first'] }}">
                <div class="{{ $customization['input.content.wrapper.second'] }}">
                    <div x-show="multiple && quantity > 0">
                        @if ($side)
                            <span class="{{ $customization['items.single'] }}" x-text="@js(data_get($placeholders, 'selected')).replace(':count', quantity)"></span>
                        @else
                            <span x-text="quantity"></span>
                        @endif
                    </div>
                    <div x-show="empty || !multiple">
                        <div class="{{ $customization['items.placeholder.wrapper'] }}">
                            <img x-bind:src="image" class="{{ $customization['items.image'] }}" x-show="image" />
                            <span @class(['text-red-500 dark:text-red-500' => $error && ! $side])
                                  x-bind:class="{
                                    '{{ $customization['items.placeholder.text'] }}': empty,
                                    '{{ $customization['items.single'] }}': !empty
                                }" x-text="placeholder"></span>
                        </div>
                    </div>
                    @if (!$side)
                        <div wire:ignore class="{{ $customization['items.wrapper'] }}" x-show="multiple && quantity > 0">
                            <template x-for="(select, index) in selects" :key="index">
                                <a class="cursor-pointer">
                                    <div class="{{ $customization['items.multiple.item'] }}">
                                        <div class="{{ $customization['items.multiple.label.wrapper'] }}">
                                            <template x-if="select.image">
                                                <img x-bind:src="select.image"
                                                     class="{{ $customization['items.multiple.image'] }}" />
                                            </template>
                                            <span class="{{ $customization['items.multiple.label'] }}"
                                                  x-text="select[selectable.label] ?? select"></span>
                                        </div>
                                        @if (!$disabled)
                                            <div class="{{ $customization['items.multiple.icon'] }}">
                                                <button type="button" class="cursor-pointer"
                                                        x-on:click="$event.stopPropagation(); clear(select)">
                                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                         :icon="TallStackUi::icon('x-mark')"
                                                                         internal
                                                                         class="{{ $customization['items.multiple.icon'] }}" />
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </template>
                        </div>
                    @endif
                </div>
            </div>
            @if (!$disabled)
                <div class="{{ $customization['buttons.wrapper'] }}" wire:ignore>
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
                                        @class([$customization['buttons.size'], $customization['buttons.base'] => !$error, $customization['buttons.error'] => $error]) />
                            </button>
                        </template>
                    @endif
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('chevron-up-down')"
                                         internal
                            @class([$customization['buttons.size'], $customization['buttons.base'] => !$error, $customization['buttons.error'] => $error]) />
                </div>
            @endif
        </button>
        <x-dynamic-component :component="TallStackUi::prefix('floating')"
                             scope="form.select-styled.floating"
                             :floating="$customization['floating.default']"
                             @class([$customization['floating.class'], $customization['floating.side'] => $side])
                             :position="$side === 'left' ? 'bottom-start' : 'bottom-end'"
                             x-anchor="$refs.button"
                             x-on:keydown="navigate($event)">
            <template x-if="searchable">
                <div class="{{ $customization['box.searchable.wrapper'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         scope="form.select-styled.input"
                                         :placeholder="data_get($placeholders, 'search')"
                                         x-model.debounce.500ms="search"
                                         x-ref="search"
                                         dusk="tallstackui_select_search_input"
                                         invalidate />
                    <button type="button"
                            class="{{ $customization['box.button.class'] }}"
                            x-on:click="search = ''; $refs.search.focus();"
                            x-show="search?.length > 0">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             internal
                                             class="{{ $customization['box.button.icon'] }}" />
                    </button>
                </div>
            </template>
            <ul class="{{ $customization['box.list.wrapper'] }}" dusk="tallstackui_select_options" role="listbox"
                x-ref="list">
                @if ($request)
                    <div x-show="loading" class="{{ $customization['box.list.loading.wrapper'] }}">
                        <x-ts-ui::icon.generic.loading class="{{ $customization['box.list.loading.class'] }}" />
                    </div>
                @endif
                @if ($grouped)
                    <template x-for="(option, index) in available" :key="option.__tsui_key ?? index">
                        <li>
                            <div class="{{ $customization['box.list.grouped.wrapper'] }}">
                                <div class="{{ $customization['box.list.grouped.options'] }}">
                                    <div class="{{ $customization['box.list.grouped.base'] }}">
                                        <img class="{{ $customization['box.list.grouped.image'] }}"
                                             x-bind:src="option.image" x-show="option.image">
                                        <div class="{{ $customization['box.list.grouped.description.wrapper'] }}">
                                            <span x-text="option[selectable.label] ?? option"></span>
                                            <span class="{{ $customization['box.list.grouped.description.text'] }}"
                                                  x-show="option.description" x-text="option.description"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <template x-for="(item, index) in option.value" :key="index">
                                <li x-on:click="select(item)"
                                    x-on:keypress.enter="select(item)"
                                    x-bind:class="{'{{ $customization['box.list.item.selected'] }}': selected(item), '{{ $customization['box.list.item.disabled'] }}': item.disabled === true}"
                                    role="option"
                                    class="{{ $customization['box.list.item.wrapper'] }}">
                                    <div class="{{ $customization['box.list.item.grouped'] }}">
                                        <div class="{{ $customization['box.list.item.base'] }}">
                                            <img class="{{ $customization['box.list.item.image'] }}"
                                                 x-bind:src="item[selectable.image]"
                                                 x-show="item[selectable.description]">
                                            <div class="{{ $customization['box.list.item.description.wrapper'] }}">
                                                <span x-text="item[selectable.label] ?? item"></span>
                                                <span class="{{ $customization['box.list.item.description.text'] }}"
                                                      x-show="item[selectable.description]"
                                                      x-text="item[selectable.description]"></span>
                                            </div>
                                        </div>
                                        <div class="{{ $customization['box.list.item.check'] }}">
                                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                 :icon="TallStackUi::icon('check')"
                                                                 x-show="selected(item)"
                                                                 internal
                                                                 class="{{ $customization['box.list.item.check'] }}" />
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </li>
                    </template>
                @else
                    <template x-for="(option, index) in available" :key="option.__tsui_key ?? index">
                        <li x-bind:title="option[selectable.label] ?? option" x-on:click.stop="select(option)"
                            x-on:keypress.enter="select(option)"
                            x-bind:class="{'{{ $customization['box.list.item.selected'] }}': !common ? selected(option) : selects.includes(option), '{{ $customization['box.list.item.disabled'] }}': option.disabled === true}"
                            role="option"
                            class="{{ $customization['box.list.item.wrapper'] }}">
                            <div class="{{ $customization['box.list.item.options'] }}">
                                <div class="{{ $customization['box.list.item.base'] }}">
                                    <img class="{{ $customization['box.list.item.image'] }}"
                                         x-bind:src="option[selectable.image]" x-show="option[selectable.image]">
                                    <div class="{{ $customization['box.list.item.description.wrapper'] }}">
                                        <span x-text="option[selectable.label] ?? option"></span>
                                        <span class="{{ $customization['box.list.item.description.text'] }}"
                                              x-show="option[selectable.description]"
                                              x-text="option[selectable.description]"></span>
                                    </div>
                                </div>
                                <div class="{{ $customization['box.list.item.check'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('check')"
                                                         x-show="!common ? selected(option) : selects.includes(option)"
                                                         internal
                                                         class="{{ $customization['box.list.item.check'] }}" />
                                </div>
                            </div>
                        </li>
                    </template>
                @endif
                <li x-show="@js($common) === true && available.length >= 10" x-intersect:once="load()"></li>
                @if (!$after)
                    <template x-if="!loading && available.length === 0">
                        <li class="m-2">
                            <span class="{{ $customization['box.list.empty'] }}">
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
    @if ($hint && !$error && !$side)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" scope="form.select-styled.hint" :$hint />
    @endif
    @if ($error && !$side)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="form.select-styled.error" :$property />
    @endif
</div>
