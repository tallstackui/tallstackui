<div class="flex items-center">
    @if ($label && $position === 'left')
        <span class="mr-2 text-sm">
            <p class="font-medium text-gray-700">{{ $label }}</p>
        </span>
    @endif
    <label @if ($id) for="{{ $id }}" @endif class="relative inline-flex cursor-pointer items-center">
        <input @if ($id) for="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
                'form-checkbox rounded transition ease-in-out duration-100',
                'border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400',
                'w-5 h-5' => $md !== null,
                'w-6 h-6' => $lg !== null,
            ]) }} @checked($checked)>
    </label>
    @if ($label && $position === 'right')
        <span class="ml-2 text-sm">
            <p class="font-medium text-gray-700">{{ $label }}</p>
        </span>
    @endif
</div>
