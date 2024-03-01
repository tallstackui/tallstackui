@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $icons = ['x-circle' => TallStackUi::icon('x-circle'), 'check-circle' => TallStackUi::icon('check-circle')];
@endphp

<div @if ($rules->isNotEmpty()) x-data="tallstackui_formPassword(@js($rules), @js($generator))" @endif class="relative">
    <x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
        <div @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ]) x-ref="input" x-data="{ show : false, toggle () { this.$el.dispatchEvent(new CustomEvent('reveal', {detail: { status: this.show }})); this.show = !this.show; } }" x-on:click.outside="rules = false">
            <input @if ($id) id="{{ $id }}" @endif
                  {{ $attributes->except('autocomplete')->class([$personalize['input.base']]) }}
                   @if ($rules->isNotEmpty())
                       x-on:click="rules = true"
                       x-model.debounce="input"
                   @endif
                   :type="!show ? 'password' : 'text'" autocomplete="{{ $attributes->get('autocomplete', 'off') }}">
            <div @class($personalize['icon.wrapper']) x-cloak>
                @if ($generator)
                    <div class="mr-2">
                        <button type="button" x-ref="generator" dusk="tallstackui_form_password_generate" class="flex items-center" x-on:click="generator(); show = true;" {!! $attributes->only('x-on:generate') !!}>
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('arrow-path')"
                                                 :$error
                                                 @class($personalize['icon.class']) />
                        </button>
                    </div>
                @endif
                <button type="button"
                        class="flex justify-center mr-2"
                        dusk="tallstackui_form_password_reveal"
                        {{ $attributes->only('x-on:reveal') }}
                        x-on:click="toggle()">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('eye')"
                                         :$error
                                         x-show="!show"
                                         @class([$personalize['icon.class'], $personalize['error'] => $error]) />
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('eye-slash')"
                                         :$error
                                         x-show="show"
                                         @class([$personalize['icon.class'], $personalize['error'] => $error]) />
                </button>
            </div>
            @if ($rules->isNotEmpty())
                <x-dynamic-component :component="TallStackUi::component('floating')"
                                     class="p-3 w-full"
                                     x-show="rules"
                                     x-anchor="$refs.input">
                    <h3 @class($personalize['rules.title'])>{{ __('tallstack-ui::messages.password.rules.title') }}</h3>
                    <div @class($personalize['rules.block'])>
                        @if ($rules->has('min'))
                            <span @class($personalize['rules.items.base'])>
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['x-circle']"
                                                     :class="$personalize['rules.items.icons.error']"
                                                     x-show="!results.min" />
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['check-circle']"
                                                     :class="$personalize['rules.items.icons.success']"
                                                     x-show="results.min" />
                                <p x-bind:class="{ 'line-through' : results.min }">{{ __('tallstack-ui::messages.password.rules.formats.min', ['min' => $rules->get('min')]) }}</p>
                            </span>
                        @endif
                        @if ($rules->has('symbols'))
                            <span @class($personalize['rules.items.base'])>
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['x-circle']"
                                                     :class="$personalize['rules.items.icons.error']"
                                                     x-show="!results.symbols" />
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['check-circle']"
                                                     :class="$personalize['rules.items.icons.success']"
                                                     x-show="results.symbols" />
                                <p x-bind:class="{ 'line-through' : results.symbols }">{{ __('tallstack-ui::messages.password.rules.formats.symbols', ['symbols' => $rules->get('symbols')]) }}</p>
                            </span>
                        @endif
                        @if ($rules->has('numbers'))
                            <span @class($personalize['rules.items.base'])>
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['x-circle']"
                                                     :class="$personalize['rules.items.icons.error']"
                                                     x-show="!results.numbers" />
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['check-circle']"
                                                     :class="$personalize['rules.items.icons.success']"
                                                     x-show="results.numbers" />
                                <p x-bind:class="{ 'line-through' : results.numbers }">{{ __('tallstack-ui::messages.password.rules.formats.numbers') }}</p>
                            </span>
                        @endif
                        @if ($rules->has('mixed'))
                            <span @class($personalize['rules.items.base'])>
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['x-circle']"
                                                     :class="$personalize['rules.items.icons.error']"
                                                     x-show="!results.mixed" />
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     :icon="$icons['check-circle']"
                                                     :class="$personalize['rules.items.icons.success']"
                                                     x-show="results.mixed" />
                                <p x-bind:class="{ 'line-through' : results.mixed }">{{ __('tallstack-ui::messages.password.rules.formats.mixed') }}</p>
                            </span>
                        @endif
                    </div>
                </x-dynamic-component>
            @endif
        </div>
    </x-dynamic-component>
</div>
