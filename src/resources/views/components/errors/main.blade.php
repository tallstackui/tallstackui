@php
    $customization = $classes();
@endphp

@if ($errors->isNotEmpty())
    <div wire:key="errors-{{ uniqid() }}" class="w-full"
         x-data="{ show : true }"
         x-show="show">
        <div {{ $attributes->except('x-on:close')->class([
                $customization['wrapper'],
                $colors['background']
            ]) }}>
            <div @class([$customization['title.wrapper'], $colors['border']])>
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
            <div class="{{ $customization['body.wrapper'] }}">
                <ul @class([$customization['body.list'], $colors['text']])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
            @if (is_string($footer))
                <p class="{{ $customization['slots.footer'] }}">{{ $footer }}</p>
            @else
                {{ $footer }}
            @endif
        </div>
    </div>
@endif
