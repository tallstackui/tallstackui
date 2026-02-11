@php
    $customization = $classes();
@endphp

@aware(['collapsible' => null])

@if ($simple)
    <div class="{{ $customization['simple.wrapper'] }}">
        @if ($collapsible)
            <span class="{{ $customization['simple.base'] }}"
                  x-bind:class="{
                      '{{ $customization['simple.base.visible'] }}' : $store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile,
                      '{{ $customization['simple.base.hidden'] }}' : !$store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile,
                  }">{{ $text ?? $slot }}</span>
        @else
            <span class="{{ $customization['simple.base'] }}">{{ $text ?? $slot }}</span>
        @endif
    </div>
@elseif ($line)
    <div class="{{ $customization['line.wrapper.first'] }}">
        <div class="{{ $customization['line.wrapper.second'] }}" x-show="$store['tsui.side-bar'].open">
            <div class="{{ $customization['line.border'] }}"></div>
        </div>
        <div class="{{ $customization['line.wrapper.third'] }}">
            @if ($collapsible)
                <span class="{{ $customization['line.base'] }}"
                      x-bind:class="{
                          '{{ $customization['line.base.visible'] }}' : $store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile,
                          '{{ $customization['line.base.hidden'] }}' : !$store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile,
                      }">{{ $text ?? $slot }}</span>
            @else
                <span class="{{ $customization['line.base'] }}">{{ $text ?? $slot }}</span>
            @endif
        </div>
    </div>
@else
    <div class="{{ $customization['line-right.wrapper.first'] }}">
        <div class="{{ $customization['line-right.wrapper.second'] }}" x-show="$store['tsui.side-bar'].open">
            <div class="{{ $customization['line-right.border'] }}"></div>
        </div>
        <div class="{{ $customization['line-right.wrapper.third'] }}">
            @if ($collapsible)
                <span class="{{ $customization['line-right.base'] }}"
                      x-bind:class="{
                          '{{ $customization['line-right.base.visible'] }}' : $store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile,
                          '{{ $customization['line-right.base.hidden'] }}' : !$store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile,
                      }">{{ $text ?? $slot }}</span>
            @else
                <span class="{{ $customization['line-right.base'] }}">{{ $text ?? $slot }}</span>
            @endif
        </div>
    </div>
@endif
