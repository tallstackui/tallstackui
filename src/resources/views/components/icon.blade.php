@if ($left || $right)
    <span class="inline-flex items-center gap-x-1">
@endif
    @if ($left)
        {!! $left !!}
    @endif
    @if ($internal)
        <x-dynamic-component :component="$raw('tallstack-ui::icon.')" {{ $attributes->class(['text-red-500' => $error]) }} />
    @else
        <x-blade-ui :name="$raw()" {{ $attributes->class(['text-red-500' => $error]) }} />
    @endif
    @if ($right)
        {!! $right !!}
    @endif
@if ($left || $right)
    </span>
@endif
