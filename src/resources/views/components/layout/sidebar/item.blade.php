@aware([
    'smart' => null,
    'navigate' => null,
    'navigateHover' => null,
    'collapsible' => null,
    'thinScroll' => null,
    'thickScroll' => null,
])

@php
    $customization = $classes();
    $active = $current || ($smart && $matches());
@endphp

@if ($visible)
    @if ($slot->isNotEmpty())
        <li x-data="{ show : @js($opened ?? \Illuminate\Support\Str::contains($slot, 'ts-ui-group-opened') ?? false), flyout : false, timer : null }"
            @if ($collapsible) x-effect="if (! railed) flyout = false" @endif>
            <button type="button"
                    x-ref="button"
                    @class([$customization['group.button'], $customization['group.button.gap'] => ! $collapsible])
                    @if ($collapsible)
                        x-on:click="railed ? (flyout = ! flyout) : (show = ! show)"
                        x-on:mouseenter="if (railed) { clearTimeout(timer); flyout = true }"
                        x-on:mouseleave="timer = setTimeout(() => flyout = false, 150)"
                        x-bind:aria-expanded="railed ? flyout : show"
                        x-bind:aria-haspopup="railed ? 'menu' : null"
                        x-bind:class="{
                            '{{ $customization['group.button.gap'] }}' : ! railed,
                            '{{ $customization['group.button.collapsed'] }}' : railed,
                        }"
                    @else
                        x-on:click="show = ! show"
                        x-bind:aria-expanded="show"
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
                              '{{ $customization['group.text.visible'] }}' : ! railed,
                              '{{ $customization['group.text.hidden'] }}' : railed,
                          }">{{ $text }}</span>
                @else
                    {{ $text }}
                @endif
                @if ($badge !== null)
                    <span @class([$customization['group.badge'], $customization['group.badge.visible'] => ! $collapsible])
                          @if ($collapsible)
                              x-bind:class="{
                                  '{{ $customization['group.badge.visible'] }}' : ! railed,
                                  '{{ $customization['group.badge.hidden'] }}' : railed,
                              }"
                          @endif>
                        <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                             scope="sidebar.item.badge"
                                             :color="$badgeColor"
                                             round
                                             xs>{{ $badge }}</x-dynamic-component>
                    </span>
                    @if ($collapsible)
                        <span class="{{ $customization['group.dot'] }}" x-show="railed" x-cloak>
                            <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                                 scope="sidebar.item.badge"
                                                 :color="$badgeColor"
                                                 round
                                                 xs
                                                 class="size-2! p-0!" />
                        </span>
                    @endif
                @endif
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-down')"
                                     internal
                                     class="{{ $customization['group.icon.collapse.base'] }}"
                                     x-bind:class="{ '{{ $customization['group.icon.collapse.rotate'] }}': show }"
                                     x-show="{{ $collapsible ? '! railed' : 'true' }}" />
            </button>
            <ul x-show="show{{ $collapsible ? ' && ! railed' : '' }}" class="{{ $customization['group.group'] }}" x-data x-ref="parent">
                {{ $slot }}
            </ul>
            @if ($collapsible)
                <x-dynamic-component :component="TallStackUi::prefix('floating')"
                                     scope="sidebar.item.flyout"
                                     :floating="$customization['group.flyout.wrapper']"
                                     position="right-start"
                                     offset="21"
                                     x-show="flyout"
                                     x-anchor="$refs.button"
                                     x-effect="if (flyout) { $nextTick(() => window.tallstackui_sidebarFlyout($el, $refs.button)) }"
                                     x-on:mouseenter="clearTimeout(timer)"
                                     x-on:mouseleave="timer = setTimeout(() => flyout = false, 150)"
                                     role="menu">
                    <div x-data="{ railed : false }"
                         data-flyout-scroll
                         @class([
                             $customization['group.flyout.scroll'],
                             $customization['group.flyout.scrollbar.thin'] => $thinScroll,
                             $customization['group.flyout.scrollbar.thick'] => $thickScroll,
                         ])>
                        <p class="{{ $customization['group.flyout.header'] }}">{{ $text }}</p>
                        <ul class="{{ $customization['group.flyout.items'] }}">
                            {{ $slot }}
                        </ul>
                    </div>
                </x-dynamic-component>
            @endif
        </li>
    @else
        <li class="{{ $customization['item.wrapper.base'] }}"
            x-bind:class="{ '{{ $customization['item.wrapper.border'] }}' : $refs.parent !== undefined }">
            <a @if ($route || $href) href="{{ $route ?? $href }}" @endif
               @if ($active) aria-current="page" @endif
               {{ $attributes->class([
                   $customization['item.state.base'],
                   $customization['item.state.gap'] => ! $collapsible,
                   $customization['item.state.normal'] => ! $active,
                   \Illuminate\Support\Arr::toCssClasses(['ts-ui-group-opened', $customization['item.state.current']]) => $active,
               ]) }}
               @if ($collapsible)
                   x-bind:class="{
                       '{{ $customization['item.state.gap'] }}' : ! railed,
                       '{{ $customization['item.state.collapsed'] }}' : railed,
                   }"
                   x-tooltip="{{ $text }}"
                   data-position="right"
                   data-tooltip-delay="flash"
                   x-bind:data-tooltip-disabled="! railed"
               @endif
               @if ($navigate && ! $href)
                   wire:navigate
               @elseif ($navigateHover && ! $href)
                   wire:navigate.hover
               @endif>
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
                              '{{ $customization['item.text.visible'] }}' : ! railed,
                              '{{ $customization['item.text.hidden'] }}' : railed,
                          }">{{ $text }}</span>
                @else
                    {{ $text }}
                @endif
                @if ($badge !== null)
                    <span @class([$customization['item.badge'], $customization['item.badge.visible'] => ! $collapsible])
                          @if ($collapsible)
                              x-bind:class="{
                                  '{{ $customization['item.badge.visible'] }}' : ! railed,
                                  '{{ $customization['item.badge.hidden'] }}' : railed,
                              }"
                          @endif>
                        <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                             scope="sidebar.item.badge"
                                             :color="$badgeColor"
                                             round
                                             xs>{{ $badge }}</x-dynamic-component>
                    </span>
                    @if ($collapsible)
                        <span class="{{ $customization['item.dot'] }}" x-show="railed" x-cloak>
                            <x-dynamic-component :component="TallStackUi::prefix('badge')"
                                                 scope="sidebar.item.badge"
                                                 :color="$badgeColor"
                                                 round
                                                 xs
                                                 class="size-2! p-0!" />
                        </span>
                    @endif
                @endif
            </a>
        </li>
    @endif
@endif
