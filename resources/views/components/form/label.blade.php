<div @class($getLabelClass())>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($getBaseClass($error)) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
