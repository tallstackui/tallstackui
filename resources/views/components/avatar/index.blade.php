@php
    $content = $label ?? $slot->toHtml();
@endphp

<div {{ $attributes->class([
        'inline-flex shrink-0 items-center justify-center overflow-hidden bg-gray-500 text-xl',
        'w-8 h-8'      => $sm !== null,
        'w-12 h-12'    => $md !== null,
        'w-14 h-14'    => $lg !== null,
        'rounded-full' => $square === null,
        'rounded-md'   => $square !== null,
        $color,
    ]) }}>
    @if (str_contains($content, 'http'))
        <img @class([
            'shrink-0 object-cover object-center text-xl',
            'w-8 h-8'      => $sm !== null,
            'w-12 h-12'    => $md !== null,
            'w-14 h-14'    => $lg !== null,
            'rounded-full' => $square === null,
        ]) src="{{ $content }}" alt="{{ $content }}" />
    @else
        <span class="font-semibold text-white">{{ $content }}</span>
    @endif
</div>
