@php($personalize = tallstackui_personalization('toast', $personalization()))

<div x-cloak
     x-data="tallstackui_toastBase()"
     x-on:tallstackui:toast.window="add($event)"
     @class([
        $personalize['wrapper.first'],
        'md:justify-start' => str_contains($configurations['position'], 'top-'),
        'md:justify-end' => str_contains($configurations['position'], 'bottom-'),
        $configurations['z-index']
    ])
     x-show="show">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-data="tallstackui_toastLoop(toast, @js(__('tallstack-ui::messages.toast.button.ok')), @js(__('tallstack-ui::messages.toast.button.confirm')), @js(__('tallstack-ui::messages.toast.button.cancel')))"
             x-show="show"
                @class([
                    $personalize['wrapper.second'],
                    'md:items-start' => $configurations['position'] === 'top-left' || $configurations['position'] === 'bottom-left',
                    'md:items-end' => $configurations['position'] === 'top-right' || $configurations['position'] === 'bottom-right',
                ])>
            <div x-show="show"
                    @class($personalize['wrapper.third'])>
                <div @class($personalize['wrapper.fourth'])>
                    <div class="flex-shrink-0">
                        <div x-show="toast.type === 'success'">
                            <x-icon name="check-circle"
                                    outline
                                    @class([$personalize['icon.size'], 'text-green-400']) />
                        </div>
                        <div x-show="toast.type === 'error'">
                            <x-icon name="x-circle"
                                    outline
                                    @class([$personalize['icon.size'], 'text-red-400']) />
                        </div>
                        <div x-show="toast.type === 'info'">
                            <x-icon name="information-circle"
                                    outline
                                    @class([$personalize['icon.size'], 'text-blue-400']) />
                        </div>
                        <div x-show="toast.type === 'warning'">
                            <x-icon name="exclamation-circle"
                                    outline
                                    @class([$personalize['icon.size'], 'text-yellow-400']) />
                        </div>
                        <div x-show="toast.type === 'question'">
                            <x-icon name="question-mark-circle"
                                    outline
                                    @class([$personalize['icon.size'], 'text-secondary-400']) />
                        </div>
                    </div>
                    <div @class($personalize['content.wrapper'])>
                        <p @class($personalize['content.text']) x-bind:class="{ 'font-medium' : !toast.confirm, 'font-semibold' : toast.confirm }"
                           x-text="toast.title"></p>
                        <p @class($personalize['content.description']) x-text="toast.description"></p>
                        <template x-if="toast.type === 'question'">
                            <div @class($personalize['buttons.wrapper'])>
                                <button dusk="tallstackui_toast_confirmation" @class($personalize['buttons.confirm'])
                                        x-on:click="accept(toast)"
                                        x-text="toast.options.confirm.text"></button>
                                <button dusk="tallstackui_toast_rejection" @class($personalize['buttons.cancel'])
                                        x-on:click="reject(toast)"
                                        x-text="toast.options.cancel.text"></button>
                            </div>
                        </template>
                    </div>
                    <div @class($personalize['buttons.close.wrapper'])>
                        <button x-on:click="hide()" type="button" @class($personalize['buttons.close.class'])>
                            <x-icon name="x-mark" @class($personalize['buttons.close.size']) />
                        </button>
                    </div>
                </div>
                <div @class($personalize['progress.wrapper'])>
                    <span x-bind:style="`width:${progress}%`" @class($personalize['progress.bar']) x-cloak></span>
                </div>
            </div>
        </div>
    </template>
</div>
