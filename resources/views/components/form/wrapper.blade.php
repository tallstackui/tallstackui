@props(['computed', 'error', 'label', 'hint', 'password' => null])

<div>
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div class="relative mt-2 rounded-md shadow-sm" @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
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
