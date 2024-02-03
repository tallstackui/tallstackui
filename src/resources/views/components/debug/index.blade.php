@props(['code' => null])

<div x-data>
    {!! $slot !!}
    @if ($code)
        {{--TODO: needs to apply the logic--}}
        <x-dynamic-component component="tallstack-ui::icon.solid.code-bracket-square"
                             data-position="auto"
                             x-tooltip="{!! $code !!}"
                             class="w-4 h-4 text-red-500 dark:text-white" />
    @endif
</div>
