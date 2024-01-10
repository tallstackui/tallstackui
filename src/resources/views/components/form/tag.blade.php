@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_formTag({!! $entangle !!})"
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
            <template x-for="(tag, index) in model" :key="index">
                <span @class($personalize['label.base'])>
                    <span x-text="tag"></span>
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         name="x-mark"
                                         :class="$personalize['label.icon']"
                                         x-on:click="remove(index)" />
                </span>
            </template>
            <input {{ $attributes->whereDoesntStartWith('wire:model')
                        ->class([
                            $personalize['input.class.base'],
                            $personalize['input.class.color.background'],
                            $personalize['error'] => $error
                        ]) }}
                   x-on:keydown="add($event)"
                   x-on:keydown.backspace="remove(model.length - 1)"
                   x-model="tag"
                   x-ref="input">
        </div>
        <div x-show="model.length > 0" @class($personalize['icon.wrapper'])>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 name="x-mark"
                                 :class="$personalize['icon.base']"
                                 x-on:click="erase()" />
        </div>
    </div>
</x-dynamic-component>
