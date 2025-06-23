@php
    $personalize = $classes();
@endphp

@if ($simple)
    <div class="{{ $personalize['simple.wrapper'] }}">
        <span class="{{ $personalize['simple.base'] }}" x-text="$store['tsui.side-bar'].open ? @js($text ?? $slot) : @js(str($text ?? $slot)->limit(5))"></span>
    </div>
@elseif ($line)
    <div class="{{ $personalize['line.wrapper.first'] }}">
        <div class="{{ $personalize['line.wrapper.second'] }}" x-show="$store['tsui.side-bar'].open">
            <div class="{{ $personalize['line.border'] }}"></div>
        </div>
        <div class="{{ $personalize['line.wrapper.third'] }}">
            <span class="{{ $personalize['line.base'] }}" x-text="$store['tsui.side-bar'].open ? @js($text ?? $slot) : @js(str($text ?? $slot)->limit(5))"></span>
        </div>
    </div>
@else
    <div class="{{ $personalize['line-right.wrapper.first'] }}">
        <div class="{{ $personalize['line-right.wrapper.second'] }}" x-show="$store['tsui.side-bar'].open">
            <div class="{{ $personalize['line-right.border'] }}"></div>
        </div>
        <div class="{{ $personalize['line-right.wrapper.third'] }}">
            <span class="{{ $personalize['line-right.base'] }}" x-text="$store['tsui.side-bar'].open ? @js($text ?? $slot) : @js(str($text ?? $slot)->limit(5))"></span>
        </div>
    </div>
@endif
