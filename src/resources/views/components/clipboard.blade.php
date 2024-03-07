@php
    $text ??= $slot->toHtml();
    $hash = md5($text.uniqid());
    $personalize = $classes();
    $validating($text);
@endphp

<div x-data="tallstackui_clipboard(@js($text), @js($hash), @js($type), @js($placeholders['button']))" {!! $attributes->except('x-on:copy') !!}>
    @if ($type === 'input' && $label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$label />
    @endif
    <div @class(['mt-1 flex'])>
        @if ($type === 'input')
            @if ($left)
                <button data-hash="{{ $hash }}"
                        x-on:click="copy()"
                        @class([$personalize['input.buttons.base'], $personalize['input.buttons.left']])
                        dusk="tallstackui_clipboard_input_copy"
                        type="button">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('clipboard-document')"
                                         @class($personalize['input.buttons.icon.class']) />
                    <p x-ref="input-{{ $hash }}">{{ $placeholders['button']['copy'] }}</p>
                </button>
            @endif
                <div @class($personalize['input.wrapper'])>
                    <input @if ($secret) type="password" @else type="text" @endif
                        @class([
                             $personalize['input.base'],
                             $personalize['input.color.base'],
                             $personalize['input.color.background'],
                             $personalize['input.sides.left'] => $left,
                             $personalize['input.sides.right'] => ! $left,
                        ]) value="{{ $text }}" readonly>
                </div>
            @if (! $left)
                <button data-hash="{{ $hash }}"
                        x-on:click="copy()"
                        {!! $attributes->only('x-on:copy') !!}
                        @class([$personalize['input.buttons.base'], $personalize['input.buttons.right']])
                        dusk="tallstackui_clipboard_input_copy"
                        type="button">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('clipboard-document')"
                                         @class($personalize['input.buttons.icon.class']) />
                    <p x-ref="input-{{ $hash }}">{{ $placeholders['button']['copy'] }}</p>
                </button>
            @endif
        @endif
        @if ($type === 'icon')
            <button x-on:click="copy()" {!! $attributes->only('x-on:copy') !!} @class($personalize['icon.wrapper'])>
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="filled($icons['copy']) ? $icons['copy'] : TallStackUi::icon($personalize['icon.icons.copy.name'])"
                                     data-hash="{{ $hash }}"
                                     @class($personalize['icon.icons.copy.class'])
                                     dusk="tallstackui_clipboard_icon_copy"
                                     x-show="!notification" />
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="filled($icons['copied']) ? $icons['copied'] : TallStackUi::icon($personalize['icon.icons.copied.name'])"
                                     @class($personalize['icon.icons.copied.class'])
                                     x-show="notification" />
            </button>
        @endif
    </div>
    @if ($type === 'input' && $hint)
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint />
    @endif
</div>
