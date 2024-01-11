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
        <div class="my-2 rounded-lg border border-gray-300 bg-white p-4 dark:bg-dark-700 dark:border-dark-600"
             x-show="rules"
             x-transition:enter="transition duration-100 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-100 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <h3 class="text-lg font-semibold text-red-500 dark:text-dark-300">Expected Password Format:</h3>
            <div class="mt-2 flex flex-col">
                @if ($rules->has('min'))
                    <span class="inline-flex items-center gap-1 text-gray-700 text-md dark:text-dark-300">
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="x-circle" class="h-5 w-5 text-red-500" x-show="!results.min" />
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="check-circle" class="h-5 w-5 text-green-500" x-show="results.min" />
                        <p x-bind:class="{ 'line-through' : results.min }">At least {{ $rules->get('min') }} characters</p>
                    </span>
                @endif
                @if ($rules->has('symbols'))
                    <span class="inline-flex items-center gap-1 text-gray-700 text-md dark:text-dark-300">
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="x-circle" class="h-5 w-5 text-red-500" x-show="!results.symbols" />
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="check-circle" class="h-5 w-5 text-green-500" x-show="results.symbols" />
                        <p x-bind:class="{ 'line-through' : results.symbols }">At least one symbol @if (is_string($rules->get('symbols'))) ({{ $rules->get('symbols') }}) @endif</p>
                    </span>
                @endif
                @if ($rules->has('numbers'))
                    <span class="inline-flex items-center gap-1 text-gray-700 text-md dark:text-dark-300">
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="x-circle" class="h-5 w-5 text-red-500" x-show="!results.numbers" />
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="check-circle" class="h-5 w-5 text-green-500" x-show="results.numbers" />
                        <p x-bind:class="{ 'line-through' : results.numbers }">At least one number</p>
                    </span>
                @endif
                @if ($rules->has('mixed'))
                    <span class="inline-flex items-center gap-1 text-gray-700 text-md dark:text-dark-300">
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="x-circle" class="h-5 w-5 text-red-500" x-show="!results.mixed" />
                        <x-dynamic-component :component="TallStackUi::component('icon')" icon="check-circle" class="h-5 w-5 text-green-500" x-show="results.mixed" />
                        <p x-bind:class="{ 'line-through' : results.mixed }">Uppercase and lowercase</p>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>
