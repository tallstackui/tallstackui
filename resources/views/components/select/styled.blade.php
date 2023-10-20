@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $customize = tallstackui_personalization('select.styled', $personalization());
@endphp

<x-wrapper.select :$label :$error :computed="$property" :$hint :$after :$disabled>
    @if (!str($directive)->contains('.live'))
        <x-slot:alpine>
            tallstackui_selectStyled(@entangle($property), @js($searchable), @js($multiple), @js($selectable !== []), @js($selectable), @js($options), @js($placeholder))
        </x-slot:alpine>
    @else
        <x-slot:alpine>
            tallstackui_selectStyled(@entangle($property).live, @js($searchable), @js($multiple), @js($selectable !== []), @js($selectable), @js($options), @js($placeholder))
        </x-slot:alpine>
    @endif
    <x-slot:header>
        <div class="flex gap-2">
            <template x-if="(!multiple && !empty) || quantity === 0">
                <span @class(['truncate', 'text-red-500' => $error])
                      x-bind:class="{
                        'text-gray-400 dark:text-dark-400': empty,
                        'text-gray-600 dark:text-dark-300': !empty
                      }" x-text="placeholder"></span>
            </template>
            <template x-if="multiple && quantity > 0">
                <span x-text="quantity"></span>
            </template>
            <div class="truncate" x-show="multiple">
                <template x-for="(selected, index) in selecteds" :key="selected[selectable.label] ?? selected">
                    <a class="cursor-pointer">
                        <div @class(['transition', $customize['item']])>
                            <span x-text="selected[selectable.label] ?? selected"></span>
                            @if (!$disabled)
                                <x-icon name="x-mark"
                                        x-on:click="clear(selected); show = true"
                                        @class($customize['icon'])
                                />
                            @endif
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </x-slot:header>
</x-wrapper.select>
