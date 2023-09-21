<div x-cloak
     x-data="tasteui_dialog(@js(__('taste-ui::messages.dialog.button.ok')), @js(__('taste-ui::messages.dialog.button.confirm')), @js(__('taste-ui::messages.dialog.button.cancel')))"
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
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-sm transform overflow-hidden rounded-lg bg-white p-4 text-left shadow-xl transition-all sm:my-8"
                 x-on:click.outside="remove()">
                <div class="flex justify-end">
                    <button x-on:click="remove()">
                        <x-icon icon="x-mark"
                                class="h-5 w-5 cursor-pointer text-gray-400"
                        />
                    </button>
                </div>
                <div>
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full"
                         x-bind:class="{
                            'bg-green-100' : dialog.type === 'success',
                            'bg-red-100' : dialog.type === 'error',
                            'bg-blue-100' : dialog.type === 'info',
                            'bg-yellow-100' : dialog.type === 'warning',
                            'bg-secondary-100' : dialog.type === 'question',
                        }">
                        <div x-show="dialog.type === 'success'">
                            <x-icon icon="check-circle" class="h-8 w-8 text-green-600" />
                        </div>
                        <div x-show="dialog.type === 'error'">
                            <x-icon icon="x-circle" class="h-8 w-8 text-red-600" />
                        </div>
                        <div x-show="dialog.type === 'info'">
                            <x-icon icon="information-circle" class="h-8 w-8 text-blue-600" />
                        </div>
                        <div x-show="dialog.type === 'warning'">
                            <x-icon icon="exclamation-circle" class="h-8 w-8 text-yellow-600" />
                        </div>
                        <div x-show="dialog.type === 'question'">
                            <x-icon icon="question-mark-circle" class="h-8 w-8 text-secondary-600" />
                        </div>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-semibold leading-6 text-gray-700"
                            x-text="dialog.title"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="dialog.description"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6" x-bind:class="{ 'space-y-2 sm:flex sm:justify-end sm:space-x-2 sm:space-y-0' : dialog.type === 'question' }">
                    <div x-show="dialog.type === 'question'">
                        <button type="button" 
                                @class([
                                    'mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold',
                                    'text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto',
                                ]) x-on:click="reject(dialog)" x-text="cancelButtonText"></button>
                    </div>
                    <button @class([
                            'inline-flex w-full items-center justify-center rounded px-4 py-2 text-sm transition',
                            'font-semibold text-white outline-none ease-in group focus:ring-2 focus:ring-offset-2',
                        ]) x-bind:class="{
                            'sm:w-auto' : dialog.type === 'question',
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-100' : dialog.type === 'success',
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-100' : dialog.type === 'error',
                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-100' : dialog.type === 'info',
                            'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 focus:ring-offset-yellow-100' : dialog.type === 'warning',
                            'bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 focus:ring-offset-primary-100' : dialog.type === 'question'
                        }" x-on:click="accept(dialog)" x-text="confirmButtonText"></button>
                </div>
            </div>
        </div>
    </div>
</div>
