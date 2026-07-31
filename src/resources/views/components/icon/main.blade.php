@php
    $customization = $classes();
@endphp

@if ($left || $right)
    <span class="inline-flex items-center gap-x-1">
@endif
        @if ($left)
            {!! $left !!}
        @endif
        @if ($internal)
            <x-dynamic-component :component="$raw('ts-ui::icon.')" {{ $attributes->class([$scale ? $customization['sizes.' . $scale] : '' => (bool) $scale, $tone ? $colors['text'] : '' => (bool) $tone, 'text-red-500' => $error]) }} />
        @else
            <x-blade-ui :name="$raw()" {{ $attributes->class([$scale ? $customization['sizes.' . $scale] : '' => (bool) $scale, $tone ? $colors['text'] : '' => (bool) $tone, 'text-red-500' => $error]) }} />
        @endif
        @if ($right)
            {!! $right !!}
        @endif
        @if ($left || $right)
    </span>
@endif
