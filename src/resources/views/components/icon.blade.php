@php
    $span = $left || $right;
@endphp

@if ($span)
    <span class="inline-flex items-center gap-x-1">
@endif
    @if ($left)
        {!! $left !!}
    @endif
    <x-dynamic-component :component="'tallstack-ui::icon.'.$icon()" {{ $attributes->class(['text-red-500' => $error]) }} />
    @if ($right)
        {!! $right !!}
    @endif
@if ($span)
    </span>
@endif
