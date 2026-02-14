@php
    $customization = $classes();
@endphp

@aware(['smart' => null, 'navigate' => null, 'navigateHover' => null, 'collapsible' => null])

@if ($visible)
    @if ($slot->isNotEmpty())
        <li x-data="{ show : @js($opened ?? \Illuminate\Support\Str::contains($slot, 'ts-ui-group-opened') ?? false) }">
            <button x-on:click="show = !show"
                    type="button"
                    class="{{ $customization['group.button'] }}"
                    @if ($collapsible)
                        x-tooltip="{{ $text }}"
                        data-position="right"
                        x-effect="$el._tippy && ($store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile ? $el._tippy.disable() : $el._tippy.enable())"
                    @endif>
                @if ($icon instanceof \Illuminate\View\ComponentSlot)
                    {{ $icon }}
                @elseif ($icon)
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon($icon)"
                                         internal
                                         class="{{ $customization['group.icon.base'] }}" />
                @endif
                @if ($collapsible)
                    <span class="{{ $customization['group.text'] }}"
                          x-bind:class="{
                              '{{ $customization['group.text.visible'] }}' : ($store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile) || $store['tsui.side-bar'].mobile,
                              '{{ $customization['group.text.hidden'] }}' : !($store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile),
                          }">{{ $text }}</span>
                @else
                    {{ $text }}
                @endif
                @if ($badge !== null)
                    @if ($collapsible)
                        <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                             scope="sidebar.item.badge"
                                             :color="$badgeColor"
                                             round
                                             xs
                                             class="{{ $customization['group.badge'] }}"
                                             x-bind:class="{
                                                 '{{ $customization['group.badge.visible'] }}' : ($store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile) || $store['tsui.side-bar'].mobile,
                                                 '{{ $customization['group.badge.hidden'] }}' : !($store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile),
                                             }">{{ $badge }}</x-dynamic-component>
                    @else
                        <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                             scope="sidebar.item.badge"
                                             :color="$badgeColor"
                                             round
                                             xs
                                             class="{{ $customization['group.badge'] }}">{{ $badge }}</x-dynamic-component>
                    @endif
                @endif
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-down')"
                                     internal
                                     class="{{ $customization['group.icon.collapse.base'] }}"
                                     x-bind:class="{ '{{ $customization['group.icon.collapse.rotate'] }}': show }"
                                     x-show="!{{ $collapsible ? 'true' : 'false' }} || $store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile" />
            </button>
            <ul x-show="show && (!@js($collapsible) || $store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile)" class="{{ $customization['group.group'] }}" x-data x-ref="parent">
                {{ $slot }}
            </ul>
        </li>
    @else
        <li class="{{ $customization['item.wrapper.base'] }}"
            x-bind:class="{ '{{ $customization['item.wrapper.border'] }}' : $refs.parent !== undefined }">
            <a @if ($route || $href) href="{{ $route ?? $href }}" @endif
            @class([
                $customization['item.state.base'],
                $customization['item.state.normal'] => ! $current || (! $smart && ! $matches()),
                \Illuminate\Support\Arr::toCssClasses(['ts-ui-group-opened', $customization['item.state.current']]) => $current || ($smart && $matches()),
            ]) x-bind:class="{'{{ $customization['item.state.collapsed'] }}' : @js($collapsible) && ! $store['tsui.side-bar'].open && ! $store['tsui.side-bar'].mobile }"
               @if ($collapsible)
                   x-tooltip="{{ $text }}"
                   data-position="right"
                   x-effect="$el._tippy && ($store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile ? $el._tippy.disable() : $el._tippy.enable())"
               @endif
               @if ($navigate && ! $href)
                   wire:navigate
               @elseif ($navigateHover && ! $href)
                   wire:navigate.hover
                    @endif
                    {{ $attributes }}>
                @if ($icon instanceof \Illuminate\View\ComponentSlot)
                    {{ $icon }}
                @elseif ($icon)
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon($icon)"
                                         internal
                                         class="{{ $customization['item.icon'] }}" />
                @endif
                @if ($collapsible)
                    <span class="{{ $customization['item.text'] }}"
                          x-bind:class="{
                              '{{ $customization['item.text.visible'] }}' : ($store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile) || $store['tsui.side-bar'].mobile,
                              '{{ $customization['item.text.hidden'] }}' : !($store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile),
                          }">{{ $text }}</span>
                @else
                    {{ $text }}
                @endif
                @if ($badge !== null)
                    @if ($collapsible)
                        <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                             scope="sidebar.item.badge"
                                             :color="$badgeColor"
                                             round
                                             xs
                                             class="{{ $customization['item.badge'] }}"
                                             x-bind:class="{
                                                 '{{ $customization['item.badge.visible'] }}' : ($store['tsui.side-bar'].open && !$store['tsui.side-bar'].mobile) || $store['tsui.side-bar'].mobile,
                                                 '{{ $customization['item.badge.hidden'] }}' : !($store['tsui.side-bar'].open || $store['tsui.side-bar'].mobile),
                                             }">{{ $badge }}</x-dynamic-component>
                    @else
                        <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                             scope="sidebar.item.badge"
                                             :color="$badgeColor"
                                             round
                                             xs
                                             class="{{ $customization['item.badge'] }}">{{ $badge }}</x-dynamic-component>
                    @endif
                @endif
            </a>
        </li>
    @endif
@endif
