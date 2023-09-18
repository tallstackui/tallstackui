@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp

<x-taste-ui::select.wrapper :$label :$error :$computed :$hint>
    <x-slot name="alpine">
        tasteui_selectMultiple(@entangle($computed), @js($searchable), @js($selectable !== []), @js($selectable), @js($options), @js($placeholder))
    </x-slot>
    <x-slot name="header">
        <div class="flex gap-2">
            <template x-if="quantity === 0">
                <span @class(['truncate font-medium', 'text-red-500' => $error]) x-bind:class="{ 'text-gray-400': empty }" x-text="placeholder"></span>
            </template>
            <template x-if="quantity > 0">
                <span x-text="quantity"></span>
            </template>
            <template x-for="(selected, index) in selecteds" :key="selected[selectable.label] ?? selected">
                <a href="#" class="cursor-pointer" x-on:click="clear(selected);">
                    <div class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 space-x-1">
                        <span x-text="selected[selectable.label] ?? selected"></span>
                        <x-icon icon="x-mark" class="h-4 w-4 transition text-gray-700 hover:text-red-500" />
                    </div>
                </a>
            </template>
        </div>
    </x-slot>
</x-taste-ui::select.wrapper>