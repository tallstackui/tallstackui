@php
    $customization = $classes();
@endphp

<div x-cloak
     x-data="tallstackui_toastBase(@js(session()->pull('ts-ui:toast')), @js($configurations['position']), @js($ts_ui__flash))"
     x-on:ts-ui:toast.window="add($event)"
     @class([
        $customization['wrapper.first'],
        $configurations['z-index']
    ]) x-bind:class="{ '{{ $customization['wrapper.position.top-x'] }}' : position.includes('top-') === true, '{{ $customization['wrapper.position.bottom-x'] }}' : position.includes('bottom-') === true }">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-data="tallstackui_toastLoop(toast)"
             x-show="show"
             x-ref="toast"
             x-on:mouseenter="toast.expandable = false"
             class="{{ $customization['wrapper.second'] }}"
             x-bind="transition"
             x-bind:class="{ '{{ $customization['wrapper.position.x-left'] }}' : position === 'top-left' || position === 'bottom-left', '{{ $customization['wrapper.position.x-right'] }}' : position === 'top-right' || position === 'bottom-right' }">
            <div class="{{ $customization['wrapper.third'] }}"
                 @if($ts_ui__colorful)
                     x-bind:class="({
                         'success': @js($colors['background']['success']),
                         'error': @js($colors['background']['error']),
                         'info': @js($colors['background']['info']),
                         'warning': @js($colors['background']['warning']),
                         'question': @js($colors['background']['question']),
                     })[toast.type]"
                    @endif>
                <div class="{{ $customization['wrapper.fourth'] }}">
                    <div class="shrink-0">
                        <div x-show="toast.type === 'success'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('check-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? $customization['colorful.icon'] : $colors['icon']['success']]) />
                        </div>
                        <div x-show="toast.type === 'error'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('x-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? $customization['colorful.icon'] : $colors['icon']['error']]) />
                        </div>
                        <div x-show="toast.type === 'info'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('information-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? $customization['colorful.icon'] : $colors['icon']['info']]) />
                        </div>
                        <div x-show="toast.type === 'warning'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('exclamation-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? $customization['colorful.icon'] : $colors['icon']['warning']]) />
                        </div>
                        <div x-show="toast.type === 'question'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('question-mark-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? $customization['colorful.icon'] : $colors['icon']['question']]) />
                        </div>
                    </div>
                    <div class="{{ $customization['content.wrapper'] }}">
                        <p @class([$ts_ui__colorful ? $customization['colorful.title'] : $customization['content.text']])
                           x-bind:class="{ '{{ $customization['content.text'] }}' : !toast.confirm, '{{ $customization['content.text-confirm'] }}' : toast.confirm }"
                           x-html="toast.title"></p>
                        <p @class([$ts_ui__colorful ? $customization['colorful.description'] : $customization['content.description']])
                           x-html="toast.description"
                           x-show="!toast.expandable"
                           x-bind:class="{ 'truncate': toast.expandable }"
                           x-collapse.min.20px></p>
                        <template x-if="toast.options && (toast.options.confirm?.text || toast.options.cancel?.text)">
                            <div class="{{ $customization['buttons.wrapper.first'] }}"
                                 x-bind:class="{ '{{ $customization['buttons.wrapper.first-with-actions'] }}' : toast.options.confirm && toast.options.cancel }">
                                <button dusk="tallstackui_toast_confirmation"
                                        @class([$customization['buttons.confirm'], $ts_ui__colorful ? $customization['colorful.confirm'] : $colors['text']['confirm']])
                                        x-on:click="accept(toast)"
                                        x-text="toast.options?.confirm?.text"></button>
                                <div x-show="toast.options.cancel">
                                    <button dusk="tallstackui_toast_rejection"
                                            @class([$customization['buttons.cancel'], $ts_ui__colorful ? $customization['colorful.cancel'] : $colors['text']['cancel']])
                                            x-on:click="reject(toast)"
                                            x-text="toast.options?.cancel?.text"></button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="{{ $customization['buttons.wrapper.second'] }}">
                        <div class="{{ $customization['buttons.close.wrapper'] }}">
                            <button x-on:click="hide(true, false)" type="button"
                                    class="{{ $ts_ui__colorful ? $customization['colorful.close'] : $customization['buttons.close.class'] }}">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('x-mark')"
                                                     dusk="tallstackui_toast_close"
                                                     internal
                                                     class="{{ $customization['buttons.close.size'] }}" />
                            </button>
                        </div>
                        <div x-show="toast.expandable && toast.description"
                             class="{{ $customization['buttons.expand.wrapper'] }}">
                            <button dusk="tallstackui_toast_expandable"
                                    x-on:click="toast.expandable = !toast.expandable"
                                    type="button"
                                    class="{{ $ts_ui__colorful ? $customization['colorful.expand'] : $customization['buttons.expand.class'] }}">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('chevron-down')"
                                                     internal
                                                     class="{{ $customization['buttons.expand.size'] }}" />
                            </button>
                        </div>
                    </div>
                </div>
                @if ($configurations['progress'])
                    <div x-show="!toast.persistent"
                         class="{{ $ts_ui__colorful ? $customization['colorful.progress.wrapper'] : $customization['progress.wrapper'] }}">
                        <span x-ref="progress" x-bind:style="`animation-duration:${toast.timeout * 1000}ms`"
                              @class([$customization['progress.animation'], $ts_ui__colorful ? $customization['colorful.progress.bar'] : $customization['progress.bar']]) x-cloak></span>
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
