<div class="inline-flex" x-data>
    <x-dynamic-component component="taste-ui::icons.{{ $solid ? 'solid' : 'outline' }}.{{ $icon }}"
                         data-position="{{ $position }}"
                         x-tooltip="{!! $text !!}"
                        {{ $attributes->class($customize()['main'] ?? $customMainClasses()) }}
    />
</div>
