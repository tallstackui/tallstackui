<div class="flex justify-between mb-1">
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-400']) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
