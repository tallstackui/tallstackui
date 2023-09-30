@php($customize = tasteui_personalization('toast', $customization()))

<div x-cloak
     x-data="tasteui_toastBase()"
     x-on:tasteui:toast.window="add($event)"
     @class($customize['wrapper.first'])
     x-show="show">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-data="tasteui_toastLoop(toast, @js(__('taste-ui::messages.toast.button.ok')), @js(__('taste-ui::messages.toast.button.confirm')), @js(__('taste-ui::messages.toast.button.cancel')))"
             x-show="show"
             @class($customize['wrapper.second'])>
            <div x-show="show"
                 @class($customize['wrapper.third'])>
                <div @class($customize['wrapper.fourth'])>
                    <div class="flex-shrink-0">
                        <div x-show="toast.type === 'success'">
                            <x-icon name="check-circle" outline @class([$customize['icon.size'], 'text-green-400']) />
                        </div>
                        <div x-show="toast.type === 'error'">
                            <x-icon name="x-circle" outline @class([$customize['icon.size'], 'text-red-400']) />
                        </div>
                        <div x-show="toast.type === 'info'">
                            <x-icon name="information-circle" outline @class([$customize['icon.size'], 'text-blue-400']) />
                        </div>
                        <div x-show="toast.type === 'warning'">
                            <x-icon name="exclamation-circle" outline @class([$customize['icon.size'], 'text-yellow-400']) />
                        </div>
                        <div x-show="toast.type === 'question'">
                            <x-icon name="question-mark-circle" outline @class([$customize['icon.size'], 'text-secondary-400']) />
                        </div>
                    </div>
                    <div @class($customize['content.wrapper'])>
                        <p @class($customize['content.text']) x-bind:class="{ 'font-medium' : !toast.confirm, 'font-semibold' : toast.confirm }" x-text="toast.title"></p>
                        <p @class($customize['content.description']) x-text="toast.description"></p>
                        <template x-if="toast.type === 'question'">
                            <div @class($customize['buttons.wrapper'])>
                                <button id="confirmation" @class($customize['buttons.confirm'])
                                        x-on:click="accept(toast)"
                                        x-text="toast.options.confirm.text"></button>
                                <button id="cancellation" @class($customize['buttons.cancel'])
                                        x-on:click="reject(toast)"
                                        x-text="toast.options.cancel.text"></button>
                            </div>
                        </template>
                    </div>
                    <div @class($customize['buttons.close.wrapper'])>
                        <button x-on:click="hide()" type="button" @class($customize['buttons.close.base'])>
                            <x-icon name="x-mark" @class($customize['buttons.close.size']) />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
