@php
    $customization = $classes();
@endphp

@if ($errors->isNotEmpty())
    <div wire:key="errors-{{ uniqid() }}" class="{{ $customization['outer'] }}"
         x-data="{ show : true }"
         x-show="show">
        <div {{ $attributes->except('x-on:close')->class([
                $customization['wrapper'],
                $colors['background'],
                $customization['paddingless.wrapper'] => $paddingless,
                $customization['shadowless'] => $shadowless,
                $customization['bordered'] => $bordered,
                $colors['bordered'] => $bordered,
            ]) }}>
            @if (! $withoutTitle || $close)
                <div @class([
                        $customization['title.wrapper'],
                        'justify-between' => ! $withoutTitle,
                        'justify-end' => $withoutTitle,
                        $customization['title.divider'] => ! $withoutTitle,
                        $colors['border'] => ! $withoutTitle,
                        $customization['paddingless.title'] => $paddingless,
                    ])>
                    @unless ($withoutTitle)
                        <span @class([$customization['title.text'], $colors['text']])>
                            @if ($icon !== null)
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon($icon)"
                                                     internal
                                                     class="{{ $customization['title.icon'] }}"
                                                     outline />
                            @endif
                            {{ trans($title, ['count' => $count($errors)]) }}
                        </span>
                    @endunless
                    @if ($close)
                        <button dusk="tallstackui_errors_close_button"
                                class="cursor-pointer"
                                {{ $attributes->only('x-on:close') }}
                                x-on:click="show = false; $dispatch('close')">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('x-mark')"
                                                 internal
                                    @class([$customization['close'], $colors['text']]) />
                        </button>
                    @endif
                </div>
            @endif
            <div @class([$customization['body.wrapper'], $customization['paddingless.body'] => $paddingless])>
                <ul @class([
                        $customization['body.list'],
                        'list-disc' => ! $listNumeric,
                        'list-decimal' => $listNumeric,
                        $colors['text'],
                    ])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
            @if (is_string($footer))
                <p @class([$customization['slots.footer.wrapper'], $customization['paddingless.footer'] => $paddingless])>{{ $footer }}</p>
            @elseif ($footer !== null)
                <div {{ $bag->class([$customization['slots.footer.wrapper'], $customization['paddingless.footer'] => $paddingless]) }}>
                    @if ($align)
                        <div @class([$customization['slots.footer.base'], $customization['slots.footer.'.$align]])>
                            {{ $footer }}
                        </div>
                    @else
                        {{ $footer }}
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif
