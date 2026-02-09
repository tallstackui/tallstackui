@php
    $customization = $classes();
@endphp

<div x-cloak
     x-data="tallstackui_toastBase(@js(session()->pull('ts-ui:toast')), @js($configurations['position']), @js($ts_ui__flash))"
     x-on:ts-ui:toast.window="add($event)"
     @class([
        $customization['wrapper.first'],
        $configurations['z-index']
    ]) x-bind:class="{ 'md:justify-start' : position.includes('top-') === true, 'md:justify-end' : position.includes('bottom-') === true }">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-data="tallstackui_toastLoop(toast)"
             x-show="show"
             x-ref="toast"
             x-on:mouseenter="toast.expandable = false"
             class="{{ $customization['wrapper.second'] }}"
             x-bind="transition"
             x-bind:class="{ 'md:items-start' : position === 'top-left' || position === 'bottom-left', 'md:items-end' : position === 'top-right' || position === 'bottom-right' }">
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
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['success']]) />
                        </div>
                        <div x-show="toast.type === 'error'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('x-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['error']]) />
                        </div>
                        <div x-show="toast.type === 'info'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('information-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['info']]) />
                        </div>
                        <div x-show="toast.type === 'warning'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('exclamation-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['warning']]) />
                        </div>
                        <div x-show="toast.type === 'question'">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('question-mark-circle')"
                                                 outline
                                                 internal
                                    @class([$customization['icon.size'], $ts_ui__colorful ? 'text-white' : $colors['icon']['question']]) />
                        </div>
                    </div>
                    <div class="{{ $customization['content.wrapper'] }}">
                        <p @class([$ts_ui__colorful ? 'text-sm font-medium text-white' : $customization['content.text']])
                           x-bind:class="{ 'font-medium' : !toast.confirm, 'font-semibold' : toast.confirm }"
                           x-html="toast.title"></p>
                        <p @class([$ts_ui__colorful ? 'mt-1 text-sm text-white/80' : $customization['content.description']])
                           x-html="toast.description"
                           x-show="!toast.expandable"
                           x-bind:class="{ 'truncate': toast.expandable }"
                           x-collapse.min.20px></p>
                        <template x-if="toast.options && (toast.options.confirm?.text || toast.options.cancel?.text)">
                            <div class="{{ $customization['buttons.wrapper.first'] }}"
                                 x-bind:class="{ 'gap-x-2' : toast.options.confirm && toast.options.cancel }">
                                <button dusk="tallstackui_toast_confirmation"
                                        @class([$customization['buttons.confirm'], $ts_ui__colorful ? 'text-white font-bold!' : $colors['text']['confirm']])
                                        x-on:click="accept(toast)"
                                        x-text="toast.options?.confirm?.text"></button>
                                <div x-show="toast.options.cancel">
                                    <button dusk="tallstackui_toast_rejection"
                                            @class([$customization['buttons.cancel'], $ts_ui__colorful ? 'text-white/80' : $colors['text']['cancel']])
                                            x-on:click="reject(toast)"
                                            x-text="toast.options?.cancel?.text"></button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="{{ $customization['buttons.wrapper.second'] }}">
                        <div class="{{ $customization['buttons.close.wrapper'] }}">
                            <button x-on:click="hide(true, false)" type="button"
                                    class="{{ $ts_ui__colorful ? 'inline-flex text-white focus:outline-hidden focus:ring-0 cursor-pointer' : $customization['buttons.close.class'] }}">
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
                                    class="{{ $ts_ui__colorful ? 'inline-flex text-white focus:outline-hidden focus:ring-0' : $customization['buttons.expand.class'] }}">
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
                         class="{{ $ts_ui__colorful ? 'relative h-1 w-full rounded-full bg-white/20' : $customization['progress.wrapper'] }}">
                        <span x-ref="progress" x-bind:style="`animation-duration:${toast.timeout * 1000}ms`"
                              @class(['animate-progress', $ts_ui__colorful ? 'bg-white absolute h-full w-24 duration-300 ease-linear' : $customization['progress.bar']]) x-cloak></span>
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
