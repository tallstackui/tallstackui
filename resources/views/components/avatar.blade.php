@php($customize = tasteui_personalization('avatar', $customization()))

<div {{ $attributes->class($customize['wrapper']) }}>
    @if ($modelable)
        <img @class($customize['content']) src="{{ $label ?? $slot }}" alt="{{ $alt() }}"/>
    @else
        <span @class($customize['content'])>{{ $label ?? $slot }}</span>
    @endif
</div>
