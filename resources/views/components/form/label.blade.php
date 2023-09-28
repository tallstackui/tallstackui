@php($customize = tasteui_personalization('form.label', $customization()))

<div @class([$customize['wrapper'], $customize['error'] => $error])>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($customize['text']) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
