@php($customize = tallstackui_personalization('dialog', $personalization()))

<div x-cloak
     x-data="tallstackui_dialog(@js(__('tallstack-ui::messages.dialog.button.ok')), @js(__('tallstack-ui::messages.dialog.button.confirm')), @js(__('tallstack-ui::messages.dialog.button.cancel')))"
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
         @class($customize['background'])></div>
    <div @class([$customize['wrapper.first'], 'backdrop-blur-sm' => $configurations['blur']])>
        <div @class($customize['wrapper.second'])>
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @class($customize['wrapper.third'])
                 @if (!$configurations['uncloseable']) x-on:click.outside="remove()" @endif>
                <div @class($customize['buttons.close.wrapper'])>
                    <button x-on:click="remove()">
                        <x-icon name="x-mark" @class($customize['buttons.close.icon']) />
                    </button>
                </div>
                <div>
                    <div @class($customize['icon.wrapper'])
                         x-bind:class="{
                            'bg-green-100 dark:bg-dark-600' : dialog.type === 'success',
                            'bg-red-100 dark:bg-dark-600' : dialog.type === 'error',
                            'bg-blue-100 dark:bg-dark-600' : dialog.type === 'info',
                            'bg-yellow-100 dark:bg-dark-600' : dialog.type === 'warning',
                            'bg-secondary-100 dark:bg-dark-600' : dialog.type === 'question',
                        }">
                        <div x-show="dialog.type === 'success'">
                            <x-icon name="check-circle"
                                    outline
                                    @class([$customize['icon.size'], 'text-green-600 dark:text-green-500']) />
                        </div>
                        <div x-show="dialog.type === 'error'">
                            <x-icon name="x-circle"
                                    outline
                                    @class([$customize['icon.size'], 'text-red-600 dark:text-red-500']) />
                        </div>
                        <div x-show="dialog.type === 'info'">
                            <x-icon name="information-circle"
                                    outline
                                    @class([$customize['icon.size'], 'text-blue-600 dark:text-blue-500']) />
                        </div>
                        <div x-show="dialog.type === 'warning'">
                            <x-icon name="exclamation-circle"
                                    outline
                                    @class([$customize['icon.size'], 'text-yellow-600 dark:text-yellow-500']) />
                        </div>
                        <div x-show="dialog.type === 'question'">
                            <x-icon name="question-mark-circle"
                                    outline
                                    @class([$customize['icon.size'], 'text-secondary-600 dark:text-secondary-500']) />
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
                        <x-button color="white"
                                  outline
                                  class="w-full text-sm"
                                  x-on:click="reject(dialog)"
                                  x-text="dialog.options?.cancel.text"
                                  dusk="tallstackui_dialog_rejection"
                        />
                    </div>
                    <button @class($customize['buttons.confirm']) x-bind:class="{
                            'sm:w-auto' : dialog.type === 'question',
                            'col-span-full' : dialog.type !== 'question',
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-100 dark:ring-offset-green-900' : dialog.type === 'success',
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-100 dark:ring-offset-red-900' : dialog.type === 'error',
                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-100 dark:ring-offset-blue-900' : dialog.type === 'info',
                            'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 focus:ring-offset-yellow-100 dark:ring-offset-yellow-900' : dialog.type === 'warning',
                            'bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 focus:ring-offset-primary-100 dark:ring-offset-primary-900' : dialog.type === 'question'
                        }" dusk="tallstackui_dialog_confirmation"
                           x-on:click="accept(dialog)"
                           x-text="dialog.type === 'question' ? dialog.options.confirm.text : text.ok"></button>
                </div>
            </div>
        </div>
    </div>
</div>
