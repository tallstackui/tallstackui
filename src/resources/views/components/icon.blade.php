@php
    $span = $left || $right;
    $type = $outline ? 'outline' : ($solid ? 'solid' : config('tallstackui.icon'));
@endphp

@if ($span)
    <span class="inline-flex items-center gap-x-1">
@endif
    @if ($left)
        {!! $left !!}
    @endif
    <x-dynamic-component component="tallstack-ui::icon.{{ $type }}.{{ $svg ?? $extract($attributes) }}" {{ $attributes->class(['text-red-500' => $error]) }} />
    @if ($right)
        {!! $right !!}
    @endif
@if ($span)
    </span>
@endif
