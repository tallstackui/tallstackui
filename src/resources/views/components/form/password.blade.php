@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $icons = [
        'x-circle' => TallStackUi::icon('x-circle'),
        'check-circle' => TallStackUi::icon('check-circle'),
    ];
@endphp

<div @if ($rules->isNotEmpty()) x-data="tallstackui_formPassword(@js($rules), @js($generator))" @endif>
    <x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
        <div @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ]) x-data="{ show : false, toggle () { this.$el.dispatchEvent(new CustomEvent('reveal', {detail: { status: this.show }})); this.show = !this.show; } }" x-on:click.outside="rules = false">
            <input @if ($id) id="{{ $id }}" @endif
                  {{ $attributes->class([$personalize['input.base']]) }}
                   @if ($rules->isNotEmpty())
                       x-on:click="rules = true"
                       x-model.debounce="input"
                   @endif
                   :type="!show ? 'password' : 'text'">
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
                                         @class([$personalize['icon.class'], $personalize['error'] => $error])
                                         x-show="!show" />
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('eye-slash')"
                                         :$error
                                         @class([$personalize['icon.class'], $personalize['error'] => $error])
                                         x-show="show" />
                </button>
            </div>
        </div>
    </x-dynamic-component>
    @if ($rules->isNotEmpty())
        <div @class($personalize['rules.wrapper'])
             x-show="rules"
             x-transition:enter="transition duration-100 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-100 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
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
        </div>
    @endif
</div>
