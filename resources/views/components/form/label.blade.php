<div @class($labelClass())>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($baseClass($error)) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
