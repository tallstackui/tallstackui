<span {{ $attributes->class($customize()['main'] ?? $customMainClasses()) }}>
    {{ $text ?? $slot }}
</span>
