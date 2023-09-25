@php
    $customize = $customize();

    $customize['main'] ??= $customMainClass();
    $customize['content'] ??= $customContentClass();
@endphp

<div {{ $attributes->class($customize['main']) }}>
    @if ($modelable)
        <img @class($customize['content']) src="{{ $label ?? $slot }}" alt="{{ $alt() }}" />
    @else
        <span @class($customize['content'])>{{ $label ?? $slot }}</span>
    @endif
</div>
