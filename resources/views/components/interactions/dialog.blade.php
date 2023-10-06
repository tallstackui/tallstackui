@php($customize = tasteui_personalization('dialog', $customization()))

<div x-cloak
     x-data="tasteui_dialog(@js(__('tallstack-ui::messages.dialog.button.ok')), @js(__('tallstack-ui::messages.dialog.button.confirm')), @js(__('tallstack-ui::messages.dialog.button.cancel')))"
     x-on:tasteui:dialog.window="add($event.detail)"
     class="relative {{ $zIndex }}"
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
         @class($customize['background'])></div>
    <div @class($customize['wrapper.first'])>
        <div @class($customize['wrapper.second'])>
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @class($customize['wrapper.third'])
                 x-on:click.outside="remove()">
                <div @class($customize['buttons.close.wrapper'])>
                    <button x-on:click="remove()">
                        <x-icon name="x-mark" @class($customize['buttons.close.base']) />
                    </button>
                </div>
                <div>
                    <div @class($customize['icon.wrapper'])
                         x-bind:class="{
                            'bg-green-100' : dialog.type === 'success',
                            'bg-red-100' : dialog.type === 'error',
                            'bg-blue-100' : dialog.type === 'info',
                            'bg-yellow-100' : dialog.type === 'warning',
                            'bg-secondary-100' : dialog.type === 'question',
                        }">
                        <div x-show="dialog.type === 'success'">
                            <x-icon name="check-circle" outline @class([$customize['icon.size'], 'text-green-600']) />
                        </div>
                        <div x-show="dialog.type === 'error'">
                            <x-icon name="x-circle" outline @class([$customize['icon.size'], 'text-red-600']) />
                        </div>
                        <div x-show="dialog.type === 'info'">
                            <x-icon name="information-circle" outline @class([$customize['icon.size'], 'text-blue-600']) />
                        </div>
                        <div x-show="dialog.type === 'warning'">
                            <x-icon name="exclamation-circle" outline @class([$customize['icon.size'], 'text-yellow-600']) />
                        </div>
                        <div x-show="dialog.type === 'question'">
                            <x-icon name="question-mark-circle" outline @class([$customize['icon.size'], 'text-secondary-600']) />
                        </div>
                    </div>
                    <div @class($customize['text.wrapper'])>
                        <h3 @class($customize['text.title']) x-text="dialog.title"></h3>
                        <div @class($customize['text.description.wrapper'])>
                            <p @class($customize['text.description.text']) x-text="dialog.description"></p>
                        </div>
                    </div>
                </div>
                <div @class($customize['buttons.wrapper'])>
                    <div x-show="dialog.type === 'question'">
                        <button type="button" @class($customize['buttons.cancel']) id="tasteui_dialog_rejection" x-on:click="reject(dialog)" x-text="dialog.options?.cancel.text"></button>
                    </div>
                    <button @class($customize['buttons.confirm']) x-bind:class="{
                            'sm:w-auto' : dialog.type === 'question',
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-100' : dialog.type === 'success',
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-100' : dialog.type === 'error',
                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-100' : dialog.type === 'info',
                            'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 focus:ring-offset-yellow-100' : dialog.type === 'warning',
                            'bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 focus:ring-offset-primary-100' : dialog.type === 'question'
                        }" id="tasteui_dialog_confirmation" x-on:click="accept(dialog)" x-text="dialog.type === 'question' ? dialog.options?.confirm.text : text.ok"></button>
                </div>
            </div>
        </div>
    </div>
</div>
