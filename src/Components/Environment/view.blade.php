@php
    $personalize = $classes();
@endphp

<span {{ $attributes->class([
        'rounded-md' => !$round && !$square,
        'rounded-full' => $round,
        $personalize['wrapper.class'],
        $personalize['wrapper.sizes.' . $size],
        $colors['background'],
        $colors['text'],
    ]) }}>
    @if ($left)
        {{ $left }}
    @endif
    {{ __('tallstack-ui::messages.environment.environment') }}: {{ str(app()->environment())->title() }}
    @if ($branch)
        ({{ __('tallstack-ui::messages.environment.branch') }}: <x-tallstack-ui::icon.generic.fork class="w-4 h-4" /> {{ $branch }})
    @endif
    @if ($right)
        {{ $right }}
    @endif
</span>
