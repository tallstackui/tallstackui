@php
    $customization = $classes();
@endphp

@if ($simple)
    <div class="{{ $customization['simple.wrapper'] }}">
        <span class="{{ $customization['simple.base'] }}"
              x-text="$store['tsui.side-bar'].open ? @js($text ?? $slot) : @js(str($text ?? $slot)->limit(5))"></span>
    </div>
@elseif ($line)
    <div class="{{ $customization['line.wrapper.first'] }}">
        <div class="{{ $customization['line.wrapper.second'] }}" x-show="$store['tsui.side-bar'].open">
            <div class="{{ $customization['line.border'] }}"></div>
        </div>
        <div class="{{ $customization['line.wrapper.third'] }}">
            <span class="{{ $customization['line.base'] }}"
                  x-text="$store['tsui.side-bar'].open ? @js($text ?? $slot) : @js(str($text ?? $slot)->limit(5))"></span>
        </div>
    </div>
@else
    <div class="{{ $customization['line-right.wrapper.first'] }}">
        <div class="{{ $customization['line-right.wrapper.second'] }}" x-show="$store['tsui.side-bar'].open">
            <div class="{{ $customization['line-right.border'] }}"></div>
        </div>
        <div class="{{ $customization['line-right.wrapper.third'] }}">
            <span class="{{ $customization['line-right.base'] }}"
                  x-text="$store['tsui.side-bar'].open ? @js($text ?? $slot) : @js(str($text ?? $slot)->limit(5))"></span>
        </div>
    </div>
@endif
