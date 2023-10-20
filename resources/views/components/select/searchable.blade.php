@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $error = $property && $errors->has($property);
    $customize = tallstackui_personalization('select.searchable', $personalization());
@endphp

<x-wrapper.select :$label :$error :computed="$property" :$hint :$after :$disable loading>
    @if (!str($directive)->contains('.live'))
        <x-slot:alpine>
            tallstackui_selectSearchable(@entangle($property), @js($request), @js($selectable), @js($multiple), @js($placeholder))
        </x-slot:alpine>
    @else
        <x-slot:alpine>
            tallstackui_selectSearchable(@entangle($property).live, @js($request), @js($selectable), @js($multiple), @js($placeholder))
        </x-slot:alpine>
    @endif
    <x-slot:header>
        <div class="flex gap-2">
            <template x-if="!multiple || quantity === 0">
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
                    <a class="cursor-pointer" @if (!$disable) x-on:click="clear(selected); show = true" @endif>
                        <div @class(['transition', $customize['item']])>
                            <span x-text="selected[selectable.label] ?? selected"></span>
                            @if (!$disable)
                                <x-icon name="x-mark" @class($customize['icon']) />
                            @endif
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </x-slot:header>
</x-wrapper.select>
