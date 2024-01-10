@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_formTag({!! $entangle !!})"
         x-cloak
         x-on:click="$refs.input.focus()"
         class="relative flex w-full rounded-md px-2 placeholder:text-gray-400 text-gray-600 ring-1 ring-gray-300 transition focus-within:ring-primary-600 focus-within:ring-2 focus:ring-primary-600 dark:ring-dark-600 dark:text-dark-300 dark:placeholder-dark-500 focus-within:focus:ring-primary-600 dark:focus-within:ring-primary-600">
        <div class="flex flex-wrap gap-x-2 border-0 bg-white pr-4 pb-1.5 dark:bg-dark-800">
            <template x-for="(tag, index) in model" :key="index">
                <span class="inline-flex h-8 items-center rounded-lg bg-gray-100 px-2 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-200 mt-1.5 py space-x-1 dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600">
                    <span x-text="tag"></span>
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         name="x-mark"
                                         class="h-4 w-4 cursor-pointer text-red-500"
                                         x-on:click="remove(index)" />
                </span>
            </template>
            <input class="flex flex-grow items-center border-0 border-transparent bg-white pb-1 text-gray-600 outline-none pt focus:outline-none focus:ring-0 dark:bg-dark-800 dark:text-dark-300 dark:placeholder-dark-500"
                   {{ $attributes->whereDoesntStartWith('wire:model') }}
                   x-on:keydown="add($event)"
                   x-on:keydown.backspace="remove(model.length - 1)"
                   x-model="tag"
                   x-ref="input">
        </div>
        <div x-show="model.length > 0" class="absolute inset-y-0 right-2 flex items-center text-secondary-500 dark:text-dark-400" >
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 name="x-mark"
                                 class="h-5 w-5 cursor-pointer text-red-500"
                                 x-on:click="erase()" />
        </div>
    </div>
</x-dynamic-component>
