@php
    $customization = $classes();
@endphp

<span {{ $attributes->class([
        $customization['border.radius.' . $rounded] => !$square,
        $customization['wrapper.class'],
        $customization['wrapper.sizes.' . $size],
        $colors['background'],
        $colors['text'],
    ]) }}>
    @if ($left)
        {{ $left }}
    @endif
    {{ __('ts-ui::messages.environment.environment') }}: {{ str(app()->environment())->title() }}
    @if ($branch)
        ({{ __('ts-ui::messages.environment.branch') }}:
        <x-ts-ui::icon.generic.fork :class="$customization['icon.fork-size']" /> {{ $branch }})
    @endif
    @if ($right)
        {{ $right }}
    @endif
</span>
