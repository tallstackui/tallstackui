@php
    $customization = $classes();
@endphp

<div x-cloak
     x-data="tallstackui_commandPalette(@js($request),@js($selectable),@js($configurations['shortcut']),@js($configurations['recycle']),@js($configurations['url'] ?? null),@js($attributes->has('x-on:select')),@js($id))"
     x-on:command-palette:{{ $open }}.window="open()"
     x-on:command-palette:{{ $close }}.window="close()"
     {{ $attributes->whereStartsWith('x-on:') }}>
    <div x-show="show"
         @if (!$ts_ui__flash)
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
         @endif
         @class([$customization['backdrop'], $configurations['zIndex'], $customization['blur.'.($configurations['blur'] === true ? 'sm' : $configurations['blur'])] ?? null => $configurations['blur']])></div>
    <div x-show="show"
         x-on:click.self="close()"
         x-on:keydown.escape.window="close()"
         @if (!$ts_ui__flash)
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
         @endif
         @class([
             $customization['wrapper'],
             $configurations['zIndex'],
             $customization['positions.bottom'] => !$configurations['centered'],
             $customization['positions.center'] => $configurations['centered'],
         ])>
        <div @class([
                 $customization['box'],
                 $customization['box.radius.default'] => !$configurations['centered'],
                 $customization['box.radius.center'] => $configurations['centered'],
             ])
             dusk="tallstackui_command_palette">
            <div @class([$customization['input.wrapper']])
                 x-bind:class="{ 'border-b-0!': available.length === 0 && (!search || loading || !fetched) }">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('magnifying-glass')"
                                     internal
                                     @class([$customization['input.icon']]) />
                <input x-ref="search"
                       x-model="search"
                       x-on:keydown.arrow-down.prevent="navigate('next')"
                       x-on:keydown.arrow-up.prevent="navigate('previous')"
                       x-on:keydown.enter.prevent="available[selected] && selectOption(available[selected])"
                       type="text"
                       placeholder="{{ data_get($placeholders, 'search') }}"
                       dusk="tallstackui_command_palette_search"
                       @class([$customization['input.base']]) />
                <div x-show="loading" @class([$customization['input.loading']])>
                    <x-ts-ui::icon.generic.loading class="h-5 w-5 animate-spin text-dark-400" />
                </div>
            </div>
            <div x-ref="list"
                 x-on:mousemove="_keyboard = false"
                 @class([$customization['list'], 'command-palette-scrollbar' => $configurations['scrollbar']])
                 x-show="available.length > 0 || (search && !loading && fetched)">
                <template x-for="(option, index) in available" :key="option.__tsui_key ?? index">
                    <button type="button"
                            x-on:click="selectOption(option)"
                            x-on:mouseenter="mouseHover(index)"
                            role="option"
                            :class="{
                                '{{ $customization['option.active'] }}': selected === index,
                                '{{ $customization['option.disabled'] }}': option.disabled === true,
                            }"
                            @class([$customization['option.base']])>
                        <template x-if="option[selectable.image]">
                            <img :src="option[selectable.image]"
                                 @class([$customization['option.image']]) />
                        </template>
                        <template x-if="!option[selectable.image] && option[selectable.icon]">
                            <div x-html="option[selectable.icon]"
                                 @class([$customization['option.icon']])></div>
                        </template>
                        <div @class([$customization['option.content']])>
                            <span x-text="option[selectable.label]"
                                  @class([$customization['option.label']])></span>
                            <template x-if="option[selectable.description]">
                                <span x-text="option[selectable.description]"
                                      @class([$customization['option.description']])></span>
                            </template>
                        </div>
                    </button>
                </template>
                <div x-show="search && available.length === 0 && !loading && fetched">
                    @if (isset($empty))
                        {{ $empty }}
                    @else
                        <p @class([$customization['empty']])>{{ data_get($placeholders, 'empty') }}</p>
                    @endif
                </div>
            </div>
            @if ($configurations['elements'])
                <div x-show="available.length > 0 || (search && !loading && fetched)"
                     @class([$customization['footer']])>
                    <span>↑↓ {{ data_get($placeholders, 'navigate') }}</span>
                    <span>↵ {{ data_get($placeholders, 'select') }}</span>
                    <span>esc {{ data_get($placeholders, 'close') }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
