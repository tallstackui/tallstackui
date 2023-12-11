@props(['attributes' => null])

<div x-data>
    {!! $slot !!}
    @if ($attributes)
        <x-dynamic-component component="tallstack-ui::icon.solid.code-bracket-square"
                             data-position="auto"
                             x-tooltip="{!! $attributes !!}"
                             class="w-4 h-4 text-red-500 dark:text-white" />
    @endif
</div>
