@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate class="flex">
    @if ($side === 'left')
        {{ $selector }}
    @endif
    <div class="flex grow items-stretch ring-inset focus-within:z-50">
        <input type="text" @class([
            'rounded-r-md' => $side === 'left',
            'rounded-l-md' => $side === 'right',
            'focus:ring-primary-600 dark:focus:ring-primary-600 block w-full border-0 py-1.5 text-gray-900 ring-1 ring-gray-300 placeholder:text-gray-400 focus:ring-2 sm:text-sm sm:leading-6 dark:ring-dark-600 dark:text-dark-300 text-gray-600 ring-gray-300 dark:bg-dark-800 bg-white'
        ]) value="TallStackUI" readonly="">
    </div>
    @if ($side === 'right')
        {{ $selector }}
    @endif
</x-dynamic-component>
