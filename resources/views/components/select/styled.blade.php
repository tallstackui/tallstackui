@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tasteui_personalization('select.styled', $customization());
@endphp

<x-taste-ui::wrappers.select.wrapper :$label :$error :$computed :$hint>
    <x-slot:alpine>
        tasteui_selectStyled(@entangle($computed), @js($searchable), @js($multiple), @js($selectable !== []), @js($selectable), @js($options), @js($placeholder))
    </x-slot:alpine>
    <x-slot:header>
        <div class="flex gap-2">
            <template x-if="(!multiple && !empty) || quantity === 0">
                <span @class(['truncate', 'text-red-500' => $error]) x-bind:class="{ 'text-gray-400': empty, 'text-gray-600': !empty }" x-text="placeholder"></span>
            </template>
            <template x-if="multiple && quantity > 0">
                <span x-text="quantity"></span>
            </template>
            <div class="truncate" x-show="multiple">
                <template x-for="(selected, index) in selecteds" :key="selected[selectable.label] ?? selected">
                    <a href="#" class="cursor-pointer" x-on:click="clear(selected);">
                        <div @class($customize['multiple'])>
                            <span x-text="selected[selectable.label] ?? selected"></span>
                            <x-icon name="x-mark" @class($customize['icon']) />
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </x-slot:header>
</x-taste-ui::wrappers.select.wrapper>
