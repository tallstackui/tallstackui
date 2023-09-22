@php
    $content = $label ?? $slot->toHtml();
    $baseContentClass = $baseContentClass();
@endphp

<div {{ $attributes->class($baseClass()) }}>
    @if ($modelable)
        <img @class($baseContentClass) src="{{ $content }}" alt="{{ $alt() }}" />
    @else
        <span @class($baseContentClass)>{{ $content }}</span>
    @endif
</div>
