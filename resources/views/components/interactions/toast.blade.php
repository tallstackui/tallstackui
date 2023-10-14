@php
    $customize = tallstackui_personalization('toast', $customization());
    $interal = $internals();
@endphp

<div x-cloak
     x-data="tallstackui_toastBase()"
     x-on:tallstackui:toast.window="add($event)"
     @class([
        $customize['wrapper.first'],
        $interal['wrapper.first.position'],
        $zIndex
    ])
     x-show="show">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-data="tallstackui_toastLoop(toast, @js(__('tallstack-ui::messages.toast.button.ok')), @js(__('tallstack-ui::messages.toast.button.confirm')), @js(__('tallstack-ui::messages.toast.button.cancel')))"
             x-show="show"
                @class([
                    $customize['wrapper.second'],
                    $interal['wrapper.second.position']
                ])>
            <div x-show="show"
                    @class([$customize['wrapper.third'], 'rounded-xl' => !$square])>
                <div @class($customize['wrapper.fourth'])>
                    <div class="flex-shrink-0">
                        <div x-show="toast.type === 'success'">
                            <x-icon name="check-circle" outline @class([$customize['icon.size'], 'text-green-400']) />
                        </div>
                        <div x-show="toast.type === 'error'">
                            <x-icon name="x-circle" outline @class([$customize['icon.size'], 'text-red-400']) />
                        </div>
                        <div x-show="toast.type === 'info'">
                            <x-icon name="information-circle"
                                    outline @class([$customize['icon.size'], 'text-blue-400']) />
                        </div>
                        <div x-show="toast.type === 'warning'">
                            <x-icon name="exclamation-circle"
                                    outline @class([$customize['icon.size'], 'text-yellow-400']) />
                        </div>
                        <div x-show="toast.type === 'question'">
                            <x-icon name="question-mark-circle"
                                    outline @class([$customize['icon.size'], 'text-secondary-400']) />
                        </div>
                    </div>
                    <div @class($customize['content.wrapper'])>
                        <p @class($customize['content.text']) x-bind:class="{ 'font-medium' : !toast.confirm, 'font-semibold' : toast.confirm }"
                           x-text="toast.title"></p>
                        <p @class($customize['content.description']) x-text="toast.description"></p>
                        <template x-if="toast.type === 'question'">
                            <div @class($customize['buttons.wrapper'])>
                                <button dusk="tallstackui_toast_confirmation" @class($customize['buttons.confirm'])
                                        x-on:click="accept(toast)"
                                        x-text="toast.options.confirm.text"></button>
                                <button dusk="tallstackui_toast_rejection" @class($customize['buttons.cancel'])
                                        x-on:click="reject(toast)"
                                        x-text="toast.options.cancel.text"></button>
                            </div>
                        </template>
                    </div>
                    <div @class($customize['buttons.close.wrapper'])>
                        <button x-on:click="hide()" type="button" @class($customize['buttons.close.class'])>
                            <x-icon name="x-mark" @class($customize['buttons.close.size']) />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
