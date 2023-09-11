@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp

<div>
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div class="relative mt-2 rounded-md shadow-sm" x-data="{ show : false }">
        <div class="absolute inset-y-0 right-0 flex items-center pr-2.5">
            <div class="cursor-pointer" x-on:click="show = !show">
                <x-icon icon="eye" :$error solid class="h-5 w-5" x-show="!show" />
                <x-icon icon="eye-slash" :$error solid class="h-5 w-5" x-show="show" />
            </div>
        </div>

        <input @if ($id) id="{{ $id }}" @endif
            {{ $attributes->class([
                    'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300',
                    'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 transition',
                    'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500' => $error,
                ])
            }} :type="!show ? 'password' : 'text'">
    </div>
    @if ($hint && !$error)
        <span class="mt-2 text-sm text-secondary-500">
            {{ $hint }}
        </span>
    @endif
    @error ($computed)
        <span class="mt-2 text-sm text-red-500">
            {{ $message }}
        </span>
    @enderror
</div>
