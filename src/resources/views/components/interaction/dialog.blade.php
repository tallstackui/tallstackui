@php
    $personalize = $classes();
    $flash = session()->pull('tallstackui:dialog');
@endphp

<div x-cloak
     x-data="tallstackui_dialog(@js($flash), @js(__('tallstack-ui::messages.dialog.button')), @js($configurations['overflow'] ?? false))"
     x-on:tallstackui:dialog.window="add($event.detail)"
     @class(['relative', $configurations['z-index']])
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true"
     x-show="show">
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @class($personalize['background'])></div>
    <div @class([$personalize['wrapper.first'], 'backdrop-blur-sm' => $configurations['blur']])>
        <div @class($personalize['wrapper.second'])>
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @class($personalize['wrapper.third'])
                 @if (!$configurations['persistent']) x-on:click.outside="remove(true)" @endif>
                <div @class($personalize['buttons.close.wrapper'])>
                    <button x-on:click="remove()">
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             @class($personalize['buttons.close.icon']) />
                    </button>
                </div>
                <div>
                    <div @class($personalize['icon.wrapper'])
                         x-bind:class="{
                            '{{ $colors['icon']['background']['success'] }}': dialog.type === 'success',
                            '{{ $colors['icon']['background']['error'] }}': dialog.type === 'error',
                            '{{ $colors['icon']['background']['info'] }}': dialog.type === 'info',
                            '{{ $colors['icon']['background']['warning'] }}': dialog.type === 'warning',
                            '{{ $colors['icon']['background']['question'] }}': dialog.type === 'question'
                        }">
                        <div x-show="dialog.type === 'success'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('check-circle')"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['icon']['success']]) />
                        </div>
                        <div x-show="dialog.type === 'error'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('x-circle')"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['icon']['error']]) />
                        </div>
                        <div x-show="dialog.type === 'info'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('information-circle')"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['icon']['info']]) />
                        </div>
                        <div x-show="dialog.type === 'warning'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('exclamation-circle')"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['icon']['warning']]) />
                        </div>
                        <div x-show="dialog.type === 'question'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('question-mark-circle')"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['icon']['question']]) />
                        </div>
                    </div>
                    <div @class($personalize['text.wrapper'])>
                        <h3 @class($personalize['text.title']) x-html="dialog.title"></h3>
                        <div class="mt-2">
                            <p @class($personalize['text.content']) x-html="dialog.description"></p>
                        </div>
                    </div>
                </div>
                <div @class($personalize['buttons.wrapper'])>
                    <div x-show="dialog.options?.cancel">
                        <x-dynamic-component :component="TallStackUi::component('button')"
                                             :color="$colors['cancel']"
                                             class="w-full text-sm"
                                             x-on:click="reject(dialog, $el)"
                                             x-text="dialog.options?.cancel?.text"
                                             dusk="tallstackui_dialog_rejection" />
                    </div>
                    <button @class($personalize['buttons.confirm']) x-bind:class="{
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
                </div>
            </div>
        </div>
    </div>
</div>
