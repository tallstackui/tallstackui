@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_formPassword({!! $entangle !!}, @js($rules ?? []), @js($typingOnly))" class="relative" x-cloak x-on:click.outside="rules = false">
     <x-dynamic-component :component="TallStackUi::prefix('input')"
                          {{ $attributes->merge($password)->except('autocomplete') }}
                          :$label
                          :$hint
                          :$invalidate
                          ::type="!show ? 'password' : 'text'"
                          floatable
                          autocomplete="{{ $attributes->get('autocomplete', 'off') }}"
                          x-on:paste="paste($event)"
                          x-on:keydown="indicator($event)"
                          x-on:keyup="indicator($event)">
         <x-slot:suffix class="ml-1 mr-2">
             <div @class([$personalize['icon.wrapper'], 'justify-between gap-2']) x-cloak>
                 @if (!$mixedCase)
                     <div x-show="caps">
                         <x-tallstack-ui::icon.generic.password-capslock-indicator class="h-5 w-5 text-red-500" />
                     </div>
                 @endif
                 @if ($generator)
                     <button type="button" x-ref="generator" dusk="tallstackui_form_password_generate" x-on:click="generator(); show = true;" {!! $attributes->only('x-on:generate') !!}>
                         <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                              :icon="TallStackUi::icon('arrow-path')"
                                              :$error
                                              internal
                                              class="{{ $personalize['icon.class'] }}" />
                     </button>
                 @endif
                 <button type="button"
                         dusk="tallstackui_form_password_reveal"
                         {{ $attributes->only('x-on:reveal') }}
                         x-on:click="toggle()">
                         <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                              :icon="TallStackUi::icon('eye')"
                                              :$error
                                              internal
                                              x-show="!show"
                                              class="{{ $personalize['icon.class'] }}" />
                         <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                              :icon="TallStackUi::icon('eye-slash')"
                                              :$error
                                              internal
                                              x-show="show"
                                              class="{{ $personalize['icon.class'] }}" />
                 </button>
             </div>
         </x-slot:suffix>
     </x-dynamic-component>
    @if ($rules?->isNotEmpty())
        <x-dynamic-component :component="TallStackUi::prefix('floating')"
                             :floating="$personalize['floating.default']"
                             :class="$personalize['floating.class']"
                             x-show="rules">
            <h3 class="{{ $personalize['rules.title'] }}">{{ trans('tallstack-ui::messages.password.rules.title') }}</h3>
            <div class="{{ $personalize['rules.block'] }}">
                @if ($rules->has('min'))
                    <span class="{{ $personalize['rules.items.base'] }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="$icon['x-circle']"
                                                 :class="$personalize['rules.items.icons.error']"
                                                 internal
                                                 x-show="!results.min" />
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="$icon['check-circle']"
                                                 :class="$personalize['rules.items.icons.success']"
                                                 internal
                                                 x-show="results.min" />
                            <p x-bind:class="{ 'line-through' : results.min }">{{ trans('tallstack-ui::messages.password.rules.formats.min', ['min' => $rules->get('min')]) }}</p>
                        </span>
                @endif
                @if ($rules->has('symbols'))
                    <span class="{{ $personalize['rules.items.base'] }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="$icon['x-circle']"
                                                 :class="$personalize['rules.items.icons.error']"
                                                 internal
                                                 x-show="!results.symbols" />
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="$icon['check-circle']"
                                                 :class="$personalize['rules.items.icons.success']"
                                                 internal
                                                 x-show="results.symbols" />
                            <p x-bind:class="{ 'line-through' : results.symbols }">{{ trans('tallstack-ui::messages.password.rules.formats.symbols', ['symbols' => $rules->get('symbols')]) }}</p>
                        </span>
                @endif
                @if ($rules->has('numbers'))
                    <span class="{{ $personalize['rules.items.base'] }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="$icon['x-circle']"
                                                 :class="$personalize['rules.items.icons.error']"
                                                 internal
                                                 x-show="!results.numbers" />
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="$icon['check-circle']"
                                                 :class="$personalize['rules.items.icons.success']"
                                                 internal
                                                 x-show="results.numbers" />
                            <p x-bind:class="{ 'line-through' : results.numbers }">{{ trans('tallstack-ui::messages.password.rules.formats.numbers') }}</p>
                        </span>
                @endif
                @if ($rules->has('mixed'))
                    <span class="{{ $personalize['rules.items.base'] }}">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="$icon['x-circle']"
                                             :class="$personalize['rules.items.icons.error']"
                                             internal
                                             x-show="!results.mixed" />
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="$icon['check-circle']"
                                             :class="$personalize['rules.items.icons.success']"
                                             internal
                                             x-show="results.mixed" />
                        <p x-bind:class="{ 'line-through' : results.mixed }">{{ trans('tallstack-ui::messages.password.rules.formats.mixed') }}</p>
                    </span>
                @endif
            </div>
        </x-dynamic-component>
    @endif
</div>
