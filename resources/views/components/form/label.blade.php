@php($customize = tasteui_personalization('form.label', $customization($error)))

<div @class($customize['wrapper'])>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($customize['text']) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
