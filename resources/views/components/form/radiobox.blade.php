<div class="flex items-center">
    <label @if ($id) for="{{ $id }}" @endif class="relative inline-flex cursor-pointer items-center">
        <input @if ($id) for="{{ $id }}" @endif type="radio" {{ $attributes->class([
                'form-radio rounded-full transition ease-in-out duration-100',
                'border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400',
                'w-5 h-5' => $md !== null,
                'w-6 h-6' => $lg !== null,
            ]) }}>
    </label>
</div>
