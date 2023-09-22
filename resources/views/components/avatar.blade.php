@php($content = $label ?? $slot->toHtml())

<div {{ $attributes->class($getBaseClass()) }}>
    @if ($modelable)
        <img @class($getBaseImageClass()) src="{{ $content }}" alt="{{ $alt() }}" />
    @else
        <span @class($getBaseSpanClass())>{{ $content }}</span>
    @endif
</div>
