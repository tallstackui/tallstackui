@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $value = $sanitize($attributes, $property, $livewire);
@endphp

@if (!$livewire && $property)
    <input hidden id="{{ $id }}" name="{{ $property }}">
@endif

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_formTag({!! $entangle !!}, @js($limit), @js($prefix), @js($livewire), @js($property), @js($value))"
         x-cloak
         x-on:click="$refs.input.focus()"
         {!! $attributes->whereStartsWith('x-on')->except('x-on:erase') !!}
         @class([
            '!block',
            $personalize['input.class.wrapper'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
         ])>
        <div @class($personalize['wrapper'])>
            <template x-for="(tag, index) in (model ?? [])" :key="index">
                <span @class($personalize['label.base'])>
                    <span x-text="tag"></span>
                    <button type="button" x-on:click="remove(index)">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         name="x-mark"
                                         :class="$personalize['label.icon']" />
                    </button>
                </span>
            </template>
            <input {{ $attributes->whereDoesntStartWith('wire:model')
                        // We need to remove the value and name attributes to avoid
                        // conflicts when component is used in non-livewire mode
                        ->except(['value', 'name'])
                        ->class([
                            $personalize['input.class.base'],
                            $personalize['input.class.color.base'] => !$error,
                            $personalize['input.class.color.background'],
                            $personalize['error'] => $error
                        ]) }}
                   x-on:keydown="add($event)"
                   x-on:keydown.backspace="remove(model?.length - 1, $event)"
                   x-model="tag"
                   x-ref="input"
                   enterkeyhint="done">
        </div>
        <button type="button"
                x-on:click="erase()"
                x-show="model?.length > 0"
                dusk="tallstackui_tag_erase"
                @class($personalize['button.wrapper'])
                {!! $attributes->only('x-on:erase') !!}>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 :class="$personalize['button.icon']"
                                 name="x-mark" />
        </button>
    </div>
</x-dynamic-component>
