@php($customize = tallstackui_personalization('avatar', $customization()))

<div {{ $attributes->class([$customize['wrapper'], $customize['internal.wrapper.color']]) }}>
    @if ($modelable)
        <img @class($customize['content.image']) src="{{ $label }}" alt="{{ $alt() }}"/>
    @elseif ($text || $slot->isNotEmpty())
        <span @class($customize['content.text'])>{{ $text ?? $slot }}</span>
    @else
        <svg @class($customize['content.text']) fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    @endif
</div>
