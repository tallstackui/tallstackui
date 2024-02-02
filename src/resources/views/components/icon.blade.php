@php($span = $left || $right)

@if ($span)
    <span class="inline-flex items-center gap-x-1">
@endif
    @if ($left)
        {!! $left !!}
    @endif
    {{--TODO: add a logic here--}}
    <x-dynamic-component component="tallstack-ui::icon.{{ $type }}.{{ $icon ?? $name }}" {{ $attributes->class(['text-red-500' => $error]) }} />
    @if ($right)
        {!! $right !!}
    @endif
@if ($span)
    </span>
@endif
