<a {{ $attributes->class([
        'flex cursor-pointer items-center rounded-md px-2 py-2 text-sm transition-colors duration-150 text-secondary-600 hover:bg-gray-100',
        'border-t border-t-gray-100' => $separator,
        'gap-x-2' => $icon !== null,
    ]) }} role="menuitem" tabindex="-1" id="menu-item-0">
    @if ($icon && $position === 'left')
        <x-icon :$icon class="h-5 w-5 text-gray-500" />
    @endif
    {!! $text ?? $slot !!}
    @if ($icon && $position === 'right ')
        <x-icon :$icon class="h-5 w-5 text-gray-500" />
    @endif
</a>
