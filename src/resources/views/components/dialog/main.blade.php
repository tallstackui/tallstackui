@php
    $customization = $classes();
@endphp

<div x-cloak
     x-data="tallstackui_dialog(@js(session()->pull('ts-ui:dialog')), @js(trans('ts-ui::messages.dialog.button')), @js($configurations['overflow'] ?? false))"
     x-on:ts-ui:dialog.window="add($event.detail)"
     @class(['relative', $configurations['z-index']])
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     x-show="show">
    <div x-show="show"
         @if (!$ts_ui__flash)
             x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @endif
         class="{{ $customization['background'] }}"></div>
    <div @class([$customization['wrapper.first'], 'backdrop-blur-sm' => $configurations['blur']])>
        <div class="{{ $customization['wrapper.second'] }}">
            <div x-show="show"
                 @if (!$ts_ui__flash)
                     x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @endif
                 class="{{ $customization['wrapper.third'] }}"
                 @if($ts_ui__colorful)
                     x-bind:class="({
                         'success': @js($colors['background']['success']),
                         'error': @js($colors['background']['error']),
                         'info': @js($colors['background']['info']),
                         'warning': @js($colors['background']['warning']),
                         'question': @js($colors['background']['question']),
                     })[dialog.type]"
                 @endif
                 @if (!$configurations['persistent']) x-on:click.outside="top_ui && remove(true)" @endif>
                <div class="{{ $customization['buttons.close.wrapper'] }}">
                    <button x-on:click="remove()">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             dusk="tallstackui_dialog_close"
                                             internal
                                             class="{{ $ts_ui__colorful ? 'h-5 w-5 cursor-pointer text-white' : $customization['buttons.close.icon'] }}" />
                    </button>
                </div>
                <div>
                    <div class="{{ $customization['icon.wrapper'] }}"
                         x-bind:class="{
                            @if($ts_ui__colorful)
                                'bg-white/20': true,
                            @else
                                '{{ $colors['icon']['background']['success'] }}': dialog.type === 'success',
                                '{{ $colors['icon']['background']['error'] }}': dialog.type === 'error',
                                '{{ $colors['icon']['background']['info'] }}': dialog.type === 'info',
                                '{{ $colors['icon']['background']['warning'] }}': dialog.type === 'warning',
                                '{{ $colors['icon']['background']['question'] }}': dialog.type === 'question',
                            @endif
                        }">
                        <div x-show="dialog.type === 'success'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('check-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['icon']['success']]) />
                        </div>
                        <div x-show="dialog.type === 'error'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('x-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['icon']['error']]) />
                        </div>
                        <div x-show="dialog.type === 'info'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('information-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['icon']['info']]) />
                        </div>
                        <div x-show="dialog.type === 'warning'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('exclamation-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['icon']['warning']]) />
                        </div>
                        <div x-show="dialog.type === 'question'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('question-mark-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['icon']['question']]) />
                        </div>
                    </div>
                    <div class="{{ $customization['text.wrapper'] }}">
                        <h3 @class([$ts_ui__colorful ? 'text-lg font-semibold leading-6 text-white' : $customization['text.title']]) x-html="dialog.title"></h3>
                        <div class="{{ $customization['text.description.wrapper'] }}">
                            <p @class([$ts_ui__colorful ? 'text-sm text-white/80' : $customization['text.description.text']]) x-html="dialog.description"></p>
                        </div>
                    </div>
                </div>
                <div class="{{ $customization['buttons.wrapper'] }}">
                    <div x-show="dialog.options?.cancel">
                        @if ($ts_ui__colorful)
                            <button @class([$customization['buttons.confirm'], $colors['colorful']['cancel'], 'w-full'])
                                    x-on:click="reject(dialog, $el)"
                                    x-text="dialog.options?.cancel?.text"
                                    dusk="tallstackui_dialog_rejection"></button>
                        @else
                            <x-dynamic-component :component="TallStackUi::prefix('button')"
                                                 scope="dialog.button"
                                                 :color="$colors['cancel']"
                                                 class="w-full text-sm focus:ring-0! focus:ring-offset-0!"
                                                 x-on:click="reject(dialog, $el)"
                                                 x-text="dialog.options?.cancel?.text"
                                                 dusk="tallstackui_dialog_rejection" />
                        @endif
                    </div>
                    @if ($ts_ui__colorful)
                        <button class="{{ $customization['buttons.confirm'] }}" x-bind:class="{
                                'sm:w-auto' : dialog.options?.cancel,
                                'col-span-full' : !dialog.options?.cancel,
                                'bg-white/20 hover:bg-white/30 text-white font-bold! focus:ring-white/50': true,
                            }" dusk="tallstackui_dialog_confirmation"
                                x-on:click="accept(dialog, $el)"
                                x-show="dialog.options?.confirm"
                                x-text="dialog.options?.confirm?.text ?? text.ok"></button>
                    @else
                        <button class="{{ $customization['buttons.confirm'] }}" x-bind:class="{
                                'sm:w-auto' : dialog.options?.cancel,
                                'col-span-full' : !dialog.options?.cancel,
                                '{{ $colors['confirm']['success'] }}': dialog.type === 'success',
                                '{{ $colors['confirm']['error'] }}': dialog.type === 'error',
                                '{{ $colors['confirm']['info'] }}': dialog.type === 'info',
                                '{{ $colors['confirm']['warning'] }}': dialog.type === 'warning',
                                '{{ $colors['confirm']['question'] }}': dialog.type === 'question'
                            }" dusk="tallstackui_dialog_confirmation"
                                x-on:click="accept(dialog, $el)"
                                x-show="dialog.options?.confirm"
                                x-text="dialog.options?.confirm?.text ?? text.ok"></button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
