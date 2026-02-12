<div x-show="selected === @js($tab)" role="tabpanel" x-init="tabs.push({ tab: @js($tab), title: @js($title), right: @js($content['right']), left: @js($content['left']), when: @js($when), navigate: @js((bool) $navigate), navigateHover: @js((bool) $navigateHover) }); @if($shouldRender && $when) selected = @js($tab); @endif" aria-labelledby="{{ $tab }}">
    @if ($shouldRender)
        {{ $slot }}
    @endif
</div>
