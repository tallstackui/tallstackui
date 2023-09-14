<div class="inline-flex" x-data>
    <x-dynamic-component component="taste-ui::icons.{{ $solid ? 'solid' : 'outline' }}.{{ $icon }}"
                         x-tooltip="{!! $text !!}"
                        {{ $attributes->class([
                            'h-5 w-5'            => $size === 'sm',
                            'h-6 w-6'            => $size === 'md',
                            'h-7 w-7'            => $size === 'lg',
                            'text-primary-500'   => $color === 'primary',
                            'text-secondary-500' => $color === 'secondary',
                            'text-green-500'     => $color === 'green',
                            'text-red-500'       => $color === 'red',
                            'text-yellow-500'    => $color === 'yellow',
                            'text-blue-500'      => $color === 'blue',
                        ]) }}
    />
</div>
