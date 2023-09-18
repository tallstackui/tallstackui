@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp

<x-taste-ui::select.wrapper :$label :$error :$computed :$hint>
    <x-slot name="alpine">
        tasteui_selectStyled(@entangle($computed), @js($searchable), @js($selectable !== []), @js($selectable), @js($options), @js($placeholder))
    </x-slot>
    <x-slot name="header">
        <span @class(['truncate font-medium', 'text-red-500' => $error]) x-bind:class="{ 'text-gray-400': empty }" x-text="placeholder"></span>
    </x-slot>
</x-taste-ui::select.wrapper>