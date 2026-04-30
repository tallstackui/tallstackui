@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_autocomplete({!! $entangle !!}, @js($items), @js($request), @js((bool) $strict), @js($lazy))"
     @if ($attributes->whereStartsWith('x-model'))
         x-modelable="model"
         {{ $attributes->whereStartsWith('x-model') }}
     @endif
     x-cloak
     translate="no"
     x-on:keydown="navigate($event)"
     x-effect="show && $refs.anchor && $refs.floating && $nextTick(() => $refs.floating.style.width = $refs.anchor.offsetWidth + 'px')"
     {{ $attributes->only(['x-on:select', 'x-on:clear', 'x-on:open', 'x-on:close']) }}>
    @if ($request['params'] ?? null)
        <div hidden x-ref="params">{{ TallStackUi::blade()->json($request['params']) }}</div>
    @endif
    <x-dynamic-component :component="TallStackUi::prefix('input')"
                         scope="form.autocomplete.input"
                         :id="$id"
                         :label="$label"
                         :hint="$hint"
                         :prefix="$prefix"
                         :placeholder="$placeholder"
                         :invalidate="$invalidate"
                         :disabled="$disabled"
                         floatable
                         x-ref="input"
                         x-model="search"
                         x-on:click="toggle()"
                         x-on:input.debounce.250ms="onInput()"
                         autocomplete="off"
                         spellcheck="false"
                         role="combobox"
                         :aria-expanded="'show'"
                         dusk="tallstackui_autocomplete_input">
        <x-slot:suffix>
            @if ($suffix)
                <span class="{{ $customization['adornment.suffix'] }}">{{ $suffix }}</span>
            @endif
            <div class="{{ $customization['icon.wrapper'] }}">
                <template x-if="loading">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-path')"
                                         internal
                                         class="{{ $customization['icon.loading'] }}" />
                </template>
                @if ($clearable)
                    <button type="button"
                            x-show="!loading && (search || selected)"
                            x-on:click="clear()"
                            dusk="tallstackui_autocomplete_clear">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             internal
                                             class="{{ $customization['icon.clear'] }}" />
                    </button>
                @endif
            </div>
        </x-slot:suffix>
    </x-dynamic-component>
    <x-dynamic-component :component="TallStackUi::prefix('floating')"
                         scope="form.autocomplete.floating"
                         :floating="$customization['floating.default']"
                         :class="$customization['floating.class']"
                         position="bottom-start"
                         x-show="show"
                         x-ref="floating">
        <ul class="{{ $customization['box.list.wrapper'] }}"
            role="listbox"
            dusk="tallstackui_autocomplete_options">
            <div x-show="loading" class="{{ $customization['box.list.loading.wrapper'] }}">
                <x-ts-ui::icon.generic.loading class="{{ $customization['box.list.loading.class'] }}" />
            </div>
            <template x-if="!loading">
                <template x-for="(item, index) in available" :key="index">
                    <li role="option"
                        x-bind:aria-selected="selected && selected.value === item.value"
                        x-bind:data-index="index"
                        x-bind:class="{
                            '{{ $customization['box.list.item.highlighted'] }}': highlighted === index,
                            '{{ $customization['box.list.item.selected'] }}': selected && selected.value === item.value,
                            '{{ $customization['box.list.item.disabled'] }}': item.disabled,
                        }"
                        x-on:mouseenter="highlighted = index"
                        x-on:click="!item.disabled && pick(item)"
                        class="{{ $customization['box.list.item.wrapper'] }}"
                        dusk="tallstackui_autocomplete_option">
                        <div class="{{ $customization['box.list.item.base'] }}">
                            <template x-if="item.image">
                                <img class="{{ $customization['box.list.item.image'] }}" x-bind:src="item.image" alt="">
                            </template>
                            <div class="{{ $customization['box.list.item.content'] }}">
                                <span class="{{ $customization['box.list.item.value'] }}" x-text="item.value"></span>
                                <template x-if="item.description">
                                    <span class="{{ $customization['box.list.item.description'] }}" x-text="item.description"></span>
                                </template>
                            </div>
                        </div>
                    </li>
                </template>
            </template>
            @if (!$after)
                <template x-if="!loading && available.length === 0">
                    <li>
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
