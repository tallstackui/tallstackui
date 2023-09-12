<span {{ $attributes->class([
    'outline-none inline-flex items-center border px-2 py-0.5 font-bold',
    'text-xs' => $md === null && $lg === null,
    'text-sm' => $md !== null,
    'text-md' => $lg !== null,
    $color
]) }}>
    {{ $text ?? $slot }}
</span>
