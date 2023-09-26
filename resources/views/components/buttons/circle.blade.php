@php
    $customize = $customize();

    $customize['main'] ??= $customMainClasses();
    $customize['icon'] ??= $customIconClasses();
@endphp

<button type="button" {{ $attributes->class($customize['main']) }}>
    <div @class(['text-white font-semibold' => $icon === null])>
        @if ($icon)
            <x-icon :$icon
                    type="{{ config('tasteui.icon') ?? 'solid' }}"
                    @class($customize['icon'])
            />
        @else
            {{ $text ?? $slot }}
        @endif
    </div>
</button>
