@php
    $customization = $classes();
@endphp

<div @if ($actions) x-data="tallstackui_qrCode(@js($export))" @endif
     {{ $attributes->class([$customization['wrapper']]) }}>
    <svg @if ($actions) x-ref="code" @endif
         viewBox="{{ $viewbox }}"
         xmlns="http://www.w3.org/2000/svg"
         role="img"
         aria-label="{{ $link }}"
         dusk="tallstackui_qr_code"
         @class([$customization['code'], $customization['sizes.' . $scale], $colors['text']])>
        <path fill="currentColor" shape-rendering="crispEdges" d="{{ $path }}" />
        @if ($mark && $mark['type'] === 'icon')
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="$mark['value']"
                                 internal
                                 class="{{ $customization['watermark.icon'] }}"
                                 x="{{ $mark['x'] }}"
                                 y="{{ $mark['y'] }}"
                                 width="{{ $mark['size'] }}"
                                 height="{{ $mark['size'] }}"
                                 dusk="tallstackui_qr_code_watermark" />
        @elseif ($mark)
            <text x="{{ $mark['x'] }}"
                  y="{{ $mark['y'] }}"
                  fill="currentColor"
                  font-size="{{ $mark['font'] }}"
                  font-family="{{ $customization['watermark.family'] }}"
                  font-weight="{{ $customization['watermark.weight'] }}"
                  text-anchor="middle"
                  dominant-baseline="central"
                  dusk="tallstackui_qr_code_watermark">{{ $mark['value'] }}</text>
        @endif
    </svg>
    @if ($actions)
        <div class="{{ $customization['actions.wrapper'] }}">
            @if ($copy)
                <button type="button"
                        x-on:click="copy()"
                        class="{{ $customization['actions.button'] }}"
                        title="{{ __('ts-ui::messages.qr-code.copy') }}"
                        aria-label="{{ __('ts-ui::messages.qr-code.copy') }}"
                        dusk="tallstackui_qr_code_copy">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('clipboard-document')"
                                         internal
                                         class="{{ $customization['actions.icon'] }}"
                                         x-show="!copied" />
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('check')"
                                         internal
                                         class="{{ $customization['actions.icon'] }}"
                                         x-show="copied"
                                         x-cloak />
                </button>
            @endif
            @if ($format)
                <button type="button"
                        x-on:click="download()"
                        class="{{ $customization['actions.button'] }}"
                        title="{{ __('ts-ui::messages.qr-code.download') }}"
                        aria-label="{{ __('ts-ui::messages.qr-code.download') }}"
                        dusk="tallstackui_qr_code_download">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-down-tray')"
                                         internal
                                         class="{{ $customization['actions.icon'] }}" />
                </button>
            @endif
        </div>
    @endif
</div>
