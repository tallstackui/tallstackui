@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $sanitize($attributes, $property, $livewire);
@endphp

@if (!$livewire && $property)
    <input hidden id="{{ $id }}" name="{{ $property }}">
@endif

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_formTag({!! $entangle !!}, @js($livewire), @js($property), @js($value))"
         x-cloak
         x-on:click="$refs.input.focus()"
         @class([
            'relative',
            $personalize['input.class.wrapper'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
         ])>
        <div @class($personalize['wrapper'])>
            <template x-if="model?.length > 0">
                <template x-for="(tag, index) in model" :key="index">
                <span @class($personalize['label.base'])>
                    <span x-text="tag"></span>
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         name="x-mark"
                                         :class="$personalize['label.icon']"
                                         x-on:click="remove(index)" />
                </span>
                </template>
            </template>
            <input {{ $attributes->whereDoesntStartWith('wire:model')
                        // We need to remove the value and name attributes to avoid
                        // conflicts when component is used in non-livewire mode
                        ->except(['value', 'name'])
                        ->class([
                            $personalize['input.class.base'],
                            $personalize['input.class.color.background'],
                            $personalize['error'] => $error
                        ]) }}
                   x-on:keydown="add($event)"
                   x-on:keydown.backspace="remove(model?.length - 1)"
                   x-model="tag"
                   x-ref="input">
        </div>
        <div x-show="model?.length > 0" @class($personalize['button.wrapper'])>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 name="x-mark"
                                 :class="$personalize['button.base']"
                                 x-on:click="erase()" />
        </div>
    </div>
</x-dynamic-component>
