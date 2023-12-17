@php($personalize = $classes())

<a href="{{ $formatted }}" {{ $attributes->class([
        'inline-flex',
        'font-bold' => $bold,
        'underline' => $underline,
        'flex items-center gap-x-1' => $icon,
        $personalize['sizes.'.$size],
        $colors['text'],
    ]) }} @if ($blank) target="_blank" @endif>
    @if ($icon && $position === 'left')
        <x-icon :$icon class="w-4 h-4" />
    @endif
    {!! $text ?? $slot !!}
    @if ($icon && $position === 'right')
        <x-icon :$icon class="w-4 h-4" />
    @endif
</a>

