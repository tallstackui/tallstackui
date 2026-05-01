@php
    $customization = $classes();
@endphp

<div class="{{ $customization['wrapper'] }}"
     data-list-row
     data-list-name="{{ $name }}"
     data-list-caption="{{ $caption }}"
     x-show="match(@js($name), @js($caption ?? ''))"
     x-init="register(@js($name), @js($caption ?? ''))">
    <div class="{{ $customization['content.wrapper'] }}">
        <div class="{{ $customization['content.inner'] }}">
            <span class="{{ $customization['name'] }}">{{ $name }}</span>
            @if ($caption !== null && $caption !== '')
                <span class="{{ $customization['caption'] }}">{{ $caption }}</span>
            @endif
            @if (isset($slot) && ! $slot->isEmpty())
                {{ $slot }}
            @endif
        </div>
        @if ($menu instanceof \Illuminate\View\ComponentSlot ? ! $menu->isEmpty() : ! empty($menu))
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
                                         offset="5"
                                         position="bottom-end"
                                         x-anchor="$refs.dropdown"
                                         role="menu">{{ $menu }}</x-dynamic-component>
                </div>
            </div>
        @endif
    </div>
</div>
