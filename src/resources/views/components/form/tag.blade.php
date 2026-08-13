@php
    $customization = $classes();
@endphp

@if (!$livewire && $property)
    <input hidden name="{{ $property }}">
@endif

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint
                     :$invalidate>
    <div x-data="tallstackui_formTag({!! $entangle !!}, @js($limit), @js($lazy), @js($prefix), @js($livewire), @js($property), @js($value), @js($options), @js($listable), @js($disabled), @js($readonly))"
         x-cloak
         @if ($listable) x-ref="anchor" @endif
         x-on:click="$refs.input.focus()"
            {{ $attributes->whereStartsWith('x-on')->except('x-on:erase') }}
            @class([
               'block!',
               $customization['input.wrapper'],
               $customization['input.color.base'] => !$error,
               $customization['input.color.background'] => !$locked,
               $customization['input.color.disabled'] => $locked,
               $customization['error'] => $error
            ])>
        <div class="{{ $customization['wrapper'] }}">
            <template x-for="(tag, index) in (model ?? [])" :key="index">
                <span class="{{ $customization['label.base'] }}">
                    <span x-text="tag"></span>
                    <button type="button" {!! $attributes->only('x-on:remove') !!} @disabled($locked) x-on:click="remove(index)">
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
                   x-on:keydown="navigate($event) || add($event)"
                   x-on:keydown.backspace="remove(model?.length - 1, $event)"
                   @if ($listable)
                       x-on:click="toggle()"
                       x-on:input="open()"
                   @endif
                   x-model="tag"
                   x-ref="input"
                   enterkeyhint="done">
        </div>
        <button type="button"
                x-on:click.prevent="erase()"
                @disabled($locked)
                x-show="model?.length > 0"
                dusk="tallstackui_tag_erase"
                class="{{ $customization['button.wrapper'] }}"
                {{ $attributes->only('x-on:erase') }}>
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('x-mark')"
                                 internal
                                 :class="$customization['button.icon']" />
        </button>
        @if ($listable)
            <x-dynamic-component :component="TallStackUi::prefix('floating')"
                                 scope="form.tag.floating"
                                 :floating="$customization['floating.default']"
                                 :class="$customization['floating.class']"
                                 position="bottom-start"
                                 x-show="show"
                                 x-ref="floating">
                <ul class="{{ $customization['box.wrapper'] }}"
                    role="listbox"
                    dusk="tallstackui_tag_options">
                    <template x-for="(option, index) in available" :key="option">
                        <li role="option"
                            x-text="option"
                            x-bind:aria-selected="highlighted === index"
                            x-bind:class="{ '{{ $customization['box.highlighted'] }}': highlighted === index }"
                            x-on:mouseenter="highlighted = index"
                            x-on:click="pick(option)"
                            class="{{ $customization['box.item'] }}"
                            dusk="tallstackui_tag_option"></li>
                    </template>
                    <template x-if="available.length === 0">
                        <li class="{{ $customization['box.empty'] }}">{{ data_get($placeholders, 'empty') }}</li>
                    </template>
                </ul>
                @if ($after)
                    <div class="{{ $customization['box.after'] }}" dusk="tallstackui_tag_after">{{ $after }}</div>
                @endif
            </x-dynamic-component>
        @endif
    </div>
</x-dynamic-component>
