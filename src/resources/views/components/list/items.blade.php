@aware(['compact' => false])

@php
    $customization = $classes();
@endphp

<div @class([
        $customization['wrapper'] => ! $compact,
        $customization['wrapper-compact'] => $compact,
     ])
     data-list-row
     data-list-on
     data-list-name="{{ $name }}"
     data-list-caption="{{ $content['searchable'] }}"
     x-show="match(@js($name), @js($content['searchable']))"
     x-bind:data-list-on="match(@js($name), @js($content['searchable']))"
     x-init="register(@js($name), @js($content['searchable']))">
    <div class="{{ $customization['content.wrapper'] }}">
        <div class="{{ $customization['content.inner'] }}">
            <span class="{{ $customization['name'] }}">{{ $name }}</span>
            @if ($content['caption'] !== null)
                <span class="{{ $customization['caption'] }}">{{ $content['caption'] }}</span>
            @endif
            @if (isset($slot) && ! $slot->isEmpty())
                {{ $slot }}
            @endif
        </div>
        @if ($content['action'] !== null || $content['menu'] !== null)
            <div class="{{ $customization['content.aside'] }}">
                {{ $content['action'] }}
                @if ($content['menu'] !== null)
                    <div class="{{ $customization['menu.wrapper'] }}" x-data="{ show: false }">
                        <div x-ref="dropdown"
                             class="relative"
                             x-on:click.outside="show = false"
                             x-on:select="show = false">
                            <button type="button"
                                    class="{{ $customization['menu.trigger'] }}"
                                    x-on:click="show = ! show"
                                    x-bind:aria-expanded="show"
                                    aria-haspopup="menu"
                                    dusk="tallstackui_list_items_menu">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('ellipsis-vertical')"
                                                     internal
                                                     class="{{ $customization['menu.icon'] }}" />
                            </button>
                            <x-dynamic-component :component="TallStackUi::prefix('floating')"
                                                 scope="list.items.menu"
                                                 :floating="$customization['menu.floating']"
                                                 @class([
                                                     $customization['menu.widths.xxs'],
                                                     $customization['menu.widths.xs'],
                                                     $customization['menu.widths.sm'],
                                                     $customization['menu.widths.md'],
                                                     $customization['menu.widths.lg'],
                                                     $customization['menu.widths.xl'],
                                                     $customization['menu.widths.2xl'],
                                                 ])
                                                 offset="5"
                                                 position="bottom-end"
                                                 x-anchor="$refs.dropdown"
                                                 :data-tsui-dropdown-size="$size"
                                                 :data-tsui-dropdown-width="$width"
                                                 role="menu">{{ $content['menu'] }}</x-dynamic-component>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
