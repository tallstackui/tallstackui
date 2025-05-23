@php
    $personalize = $classes();
@endphp

@if ($simple)
    <div class="{{ $personalize['simple.wrapper'] }}">
        <span class="{{ $personalize['simple.base'] }}">{{ $text ?? $slot }}</span>
    </div>
@elseif ($line)
    <div class="{{ $personalize['line.wrapper.first'] }}">
        <div class="{{ $personalize['line.wrapper.second'] }}">
            <div class="{{ $personalize['line.border'] }}"></div>
        </div>
        <div class="{{ $personalize['line.wrapper.third'] }}">
            <span class="{{ $personalize['line.base'] }}">{{ $text ?? $slot }}</span>
        </div>
    </div>
@else
    <div class="{{ $personalize['line-right.wrapper.first'] }}">
        <div class="{{ $personalize['line-right.wrapper.second'] }}">
            <div class="{{ $personalize['line-right.border'] }}"></div>
        </div>
        <div class="{{ $personalize['line-right.wrapper.third'] }}">
            <span class="{{ $personalize['line-right.base'] }}">{{ $text ?? $slot }}</span>
        </div>
    </div>
@endif
