@php
    $customize = $customization($error);

    $customize['main'] ??= $customMainClasses($error);
    $customize['label'] ??= $customLabelClasses();
@endphp

<div @class($customize['label'])>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($customize['main']) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
