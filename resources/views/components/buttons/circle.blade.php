<button type="button" {{ $attributes->class([
        'outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 w-9 h-9',
        'focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded-full',
        $color
    ]) }}>
    <div @class(['text-white font-semibold' => $icon === null])>
        @if ($icon)
            <x-dynamic-component component="taste-ui::icons.{{ $solid ? 'solid' : 'outline' }}.{{ $icon }}"
                                 class="h-4 w-4 text-white"
            />
        @else
            {{ $text ?? $slot }}
        @endif
    </div>
</button>
