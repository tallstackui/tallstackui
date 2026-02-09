@php
    $customization = $classes();
@endphp

<span {{ $attributes->class([
        'rounded-md' => !$round && !$square,
        'rounded-full' => $round,
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
        <x-ts-ui::icon.generic.fork class="w-4 h-4" /> {{ $branch }})
    @endif
    @if ($right)
        {{ $right }}
    @endif
</span>
