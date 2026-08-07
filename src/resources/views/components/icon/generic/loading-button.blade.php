@props(['loading' => false, 'delay' => null, 'spinner' => 'gradient', 'size' => 'md', 'customization' => []])

@php
    $blocks = collect($customization)
        ->filter(fn (string $value, string $key): bool => str_starts_with($key, 'spinner.'))
        ->mapWithKeys(fn (string $value, string $key): array => [substr($key, 8) => $value])
        ->all();
@endphp

<span {{ $attributes }}
      @if ($loading && $loading !== "1") wire:target="{{ $loading }}" @endif
      wire:loading.delay{{ $delay ? ".{$delay}" : "" }}
      dusk="button-loading-spinner">
    <span class="flex items-center justify-center">
        <x-dynamic-component component="ts-ui::spinner.types.{{ $spinner }}"
                             :$size
                             :customization="$blocks" />
    </span>
</span>
