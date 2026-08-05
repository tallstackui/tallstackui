@php
    $customization = $classes();
@endphp

@aware(['collapsible' => null])

@if ($simple)
    <div @class([$customization['simple.wrapper'], $customization['simple.wrapper.visible'] => ! $collapsible])
         @if ($collapsible)
             x-bind:class="{
                 '{{ $customization['simple.wrapper.visible'] }}' : ! railed,
                 '{{ $customization['simple.wrapper.hidden'] }}' : railed,
             }"
         @endif>
        @if ($collapsible)
            <span class="{{ $customization['simple.base'] }}"
                  x-bind:class="{
                      '{{ $customization['simple.base.visible'] }}' : ! railed,
                      '{{ $customization['simple.base.hidden'] }}' : railed,
                  }">{{ $text ?? $slot }}</span>
        @else
            <span class="{{ $customization['simple.base'] }}">{{ $text ?? $slot }}</span>
        @endif
    </div>
@elseif ($line)
    <div @class([$customization['line.wrapper.first'], $customization['line.wrapper.first.visible'] => ! $collapsible])
         @if ($collapsible)
             x-bind:class="{
                 '{{ $customization['line.wrapper.first.visible'] }}' : ! railed,
                 '{{ $customization['line.wrapper.first.hidden'] }}' : railed,
             }"
         @endif>
        <div class="{{ $customization['line.wrapper.second'] }}">
            <div class="{{ $customization['line.border'] }}"></div>
        </div>
        <div class="{{ $customization['line.wrapper.third'] }}">
            @if ($collapsible)
                <span class="{{ $customization['line.base'] }}"
                      x-bind:class="{
                          '{{ $customization['line.base.visible'] }}' : ! railed,
                          '{{ $customization['line.base.hidden'] }}' : railed,
                      }">{{ $text ?? $slot }}</span>
            @else
                <span class="{{ $customization['line.base'] }}">{{ $text ?? $slot }}</span>
            @endif
        </div>
    </div>
@else
    <div @class([$customization['line-right.wrapper.first'], $customization['line-right.wrapper.first.visible'] => ! $collapsible])
         @if ($collapsible)
             x-bind:class="{
                 '{{ $customization['line-right.wrapper.first.visible'] }}' : ! railed,
                 '{{ $customization['line-right.wrapper.first.hidden'] }}' : railed,
             }"
         @endif>
        <div class="{{ $customization['line-right.wrapper.second'] }}">
            <div class="{{ $customization['line-right.border'] }}"></div>
        </div>
        <div class="{{ $customization['line-right.wrapper.third'] }}">
            @if ($collapsible)
                <span class="{{ $customization['line-right.base'] }}"
                      x-bind:class="{
                          '{{ $customization['line-right.base.visible'] }}' : ! railed,
                          '{{ $customization['line-right.base.hidden'] }}' : railed,
                      }">{{ $text ?? $slot }}</span>
            @else
                <span class="{{ $customization['line-right.base'] }}">{{ $text ?? $slot }}</span>
            @endif
        </div>
    </div>
@endif
