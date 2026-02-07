@php
    $customization = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint
                     :$invalidate>
    <div x-data="tallstackui_formTag({!! $entangle !!}, @js($limit), @js($prefix), @js($livewire), @js($property), @js($value))"
         x-cloak
         x-on:click="$refs.input.focus()"
            {{ $attributes->whereStartsWith('x-on')->except('x-on:erase') }}
            @class([
               '!block',
               $customization['input.wrapper'],
               $customization['input.color.base'] => !$error,
               $customization['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
               $customization['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
               $customization['error'] => $error
            ])>
        <div class="{{ $customization['wrapper'] }}">
            <template x-for="(tag, index) in (model ?? [])" :key="index">
                <span class="{{ $customization['label.base'] }}">
                    <span x-text="tag"></span>
                    <button type="button" {!! $attributes->only('x-on:remove') !!} x-on:click="remove(index)">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             internal
                                             :class="$customization['label.icon']" />
                    </button>
                </span>
            </template>
            <input {{ $attributes->whereDoesntStartWith('wire:model')
                        // We need to remove the value and name attributes to avoid
                        // conflicts when component is used in non-livewire mode
                        ->except(['value', 'name'])
                        ->class([
                            'w-4',
                            $customization['input.base'],
                            $customization['input.color.base'] => !$error,
                            $customization['input.color.background'],
                            $customization['error'] => $error
                        ]) }}
                   x-on:keydown="add($event)"
                   x-on:keydown.backspace="remove(model?.length - 1, $event)"
                   x-model="tag"
                   x-ref="input"
                   enterkeyhint="done">
        </div>
        <button type="button"
                x-on:click.prevent="erase()"
                x-show="model?.length > 0"
                dusk="tallstackui_tag_erase"
                class="{{ $customization['button.wrapper'] }}"
                {{ $attributes->only('x-on:erase') }}>
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('x-mark')"
                                 internal
                                 :class="$customization['button.icon']" />
        </button>
    </div>
</x-dynamic-component>
