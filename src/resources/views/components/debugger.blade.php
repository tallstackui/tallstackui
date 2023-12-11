<div x-data>
    {!! $slot !!}
    @if ($code)
        <x-dynamic-component component="tallstack-ui::icon.solid.question-mark-circle"
                             data-position="auto"
                             x-tooltip="{!! $code !!}"
                             class="w-5 h-5 text-red-500 dark:text-white" />
    @endif
</div>
