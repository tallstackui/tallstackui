@php($personalize = $classes())

<div x-cloak
     x-data="tallstackui_toastBase()"
     x-on:tallstackui:toast.window="add($event)"
     @class([
        $personalize['wrapper.first'],
        'md:justify-start' => str_contains($configurations['position'], 'top-'),
        'md:justify-end' => str_contains($configurations['position'], 'bottom-'),
        $configurations['z-index']
    ])>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-data="tallstackui_toastLoop(toast)"
             x-show="show"
             x-ref="toast"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 @if (str_contains($configurations['position'], '-left')) sm:-translate-x-2 @else sm:translate-x-2 @endif"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             @class([
                 $personalize['wrapper.second'],
                 'md:items-start' => $configurations['position'] === 'top-left' || $configurations['position'] === 'bottom-left',
                 'md:items-end' => $configurations['position'] === 'top-right' || $configurations['position'] === 'bottom-right'
             ])>
            <div @class($personalize['wrapper.third'])>
                <div @class($personalize['wrapper.fourth'])>
                    <div class="flex-shrink-0">
                        <div x-show="toast.type === 'success'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 icon="check-circle"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['success']]) />
                        </div>
                        <div x-show="toast.type === 'error'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 icon="x-circle"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['error']]) />
                        </div>
                        <div x-show="toast.type === 'info'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 icon="information-circle"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['info']]) />
                        </div>
                        <div x-show="toast.type === 'warning'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 icon="exclamation-circle"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['warning']]) />
                        </div>
                        <div x-show="toast.type === 'question'">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 icon="question-mark-circle"
                                                 outline
                                                 @class([$personalize['icon.size'], $colors['icon']['question']]) />
                        </div>
                    </div>
                    <div @class($personalize['content.wrapper'])>
                        <p @class($personalize['content.text']) x-bind:class="{ 'font-medium' : !toast.confirm, 'font-semibold' : toast.confirm }"
                           x-text="toast.title"></p>
                        <p @class($personalize['content.description'])
                           x-text="toast.description?.substring(0, 30) + '...'"
                           x-show="toast.description && toast.expandable"></p>
                        <p @class($personalize['content.description'])
                           x-text="toast.description"
                           x-show="!toast.expandable"></p>
                        <template x-if="toast.options && (toast.options.confirm || toast.options.cancel)">
                            <div @class($personalize['buttons.wrapper.first'])>
                                <button dusk="tallstackui_toast_confirmation" @class($personalize['buttons.confirm'])
                                        x-on:click="accept(toast)"
                                        x-text="toast.options.confirm.text"></button>
                                <div x-show="toast.options.cancel">
                                    <button dusk="tallstackui_toast_rejection" @class($personalize['buttons.cancel'])
                                            x-on:click="reject(toast)"
                                            x-text="toast.options?.cancel?.text"></button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div @class($personalize['buttons.wrapper.second'])>
                        <div @class($personalize['buttons.close.wrapper'])>
                            <button x-on:click="hide()" type="button" @class($personalize['buttons.close.class'])>
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     icon="x-mark"
                                                     @class($personalize['buttons.close.size']) />
                            </button>
                        </div>
                        <div x-show="toast.expandable && toast.description" @class($personalize['buttons.expand.wrapper'])>
                            <button dusk="tallstackui_toast_expandable"
                                    x-on:click="toast.expandable = !toast.expandable"
                                    type="button"
                                    @class($personalize['buttons.expand.class'])>
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     icon="chevron-down"
                                                     @class($personalize['buttons.expand.size']) />
                            </button>
                        </div>
                    </div>
                </div>
                @if ($configurations['progress'])
                    <div @class($personalize['progress.wrapper'])>
                        <span x-ref="progress" x-bind:style="`animation-duration:${toast.timeout * 1000}ms`" @class(['animate-progress', $personalize['progress.bar']]) x-cloak></span>
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
