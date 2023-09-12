<div class="mb-1 flex justify-between">
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class([
            'block text-sm font-medium',
            'text-gray-700' => !$error,
            'text-red-600'  => $error,
        ]) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
