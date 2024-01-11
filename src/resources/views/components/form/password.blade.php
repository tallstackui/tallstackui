@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<div @if ($rules->isNotEmpty()) x-data="tallstackui_formPassword(@js($rules))" @endif>
    <x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate password>
        <div @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ])>
            <div @class($personalize['icon.wrapper']) x-cloak>
                <div class="cursor-pointer" x-on:click="show = !show">
                    <x-dynamic-component :component="TallStackUi::component('icon')" icon="eye" :$error @class($personalize['icon.class']) x-show="!show" />
                    <x-dynamic-component :component="TallStackUi::component('icon')" icon="eye-slash" :$error @class($personalize['icon.class']) x-show="show" />
                </div>
            </div>
            <input @if ($id) id="{{ $id }}" @endif
                  {{ $attributes->class([$personalize['input.base']]) }}
                   @if ($rules->isNotEmpty())
                       x-on:click="rules = true"
                       x-on:click.outside="rules = false"
                       x-model.debounce.250ms="input"
                   @endif
                   :type="!show ? 'password' : 'text'">
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
                                             icon="x-circle"
                                             :class="$personalize['rules.items.icons.error']"
                                             x-show="!results.min" />
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="check-circle"
                                             :class="$personalize['rules.items.icons.success']"
                                             x-show="results.min" />
                        <p x-bind:class="{ 'line-through' : results.min }">{{ __('tallstack-ui::messages.password.rules.formats.min', ['min' => $rules->get('min')]) }}</p>
                    </span>
                @endif
                @if ($rules->has('symbols'))
                    <span @class($personalize['rules.items.base'])>
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="x-circle"
                                             :class="$personalize['rules.items.icons.error']"
                                             x-show="!results.symbols" />
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="check-circle"
                                             :class="$personalize['rules.items.icons.success']"
                                             x-show="results.symbols" />
                        <p x-bind:class="{ 'line-through' : results.symbols }">{{ __('tallstack-ui::messages.password.rules.formats.symbols', ['symbols' => $rules->get('symbols')]) }}</p>
                    </span>
                @endif
                @if ($rules->has('numbers'))
                    <span @class($personalize['rules.items.base'])>
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="x-circle"
                                             :class="$personalize['rules.items.icons.error']"
                                             x-show="!results.numbers" />
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="check-circle"
                                             :class="$personalize['rules.items.icons.success']"
                                             x-show="results.numbers" />
                        <p x-bind:class="{ 'line-through' : results.numbers }">{{ __('tallstack-ui::messages.password.rules.formats.numbers') }}</p>
                    </span>
                @endif
                @if ($rules->has('mixed'))
                    <span @class($personalize['rules.items.base'])>
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="x-circle"
                                             :class="$personalize['rules.items.icons.error']"
                                             x-show="!results.mixed" />
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             icon="check-circle"
                                             :class="$personalize['rules.items.icons.success']"
                                             x-show="results.mixed" />
                        <p x-bind:class="{ 'line-through' : results.mixed }">{{ __('tallstack-ui::messages.password.rules.formats.mixed') }}</p>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>
