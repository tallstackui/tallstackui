@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="app()"
        x-ref="wrapper"
        x-cloak
        @class([
            // input.wrapper
            'focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md px-2 ring-1 transition focus-within:ring-2',
            // input.class.base
            'w-full border-0 bg-transparent ring-0 ring-inset focus:ring-transparent sm:text-sm sm:leading-6',
            // input.class.color.base
            'dark:ring-dark-600 dark:text-dark-300 dark:placeholder-dark-500 text-gray-600 ring-gray-300 placeholder:text-gray-400',
        ])>
        <input x-on:click="show = !show"
               placeholder="Selecione um arquivo" 
               class="flex w-full cursor-pointer items-center border-0 bg-transparent p-1 ring-0 ring-inset text-md py-1.5 focus:ring-transparent sm:text-sm sm:leading-6"
               readonly>
        {{--box--}}
        <div x-cloak
            x-show="show"
            x-on:click.away="show = false"
            x-transition:enter="transition duration-100 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-anchor.bottom-end="$refs.wrapper"
            @class($personalize['box.wrapper'])>
            <div @class($personalize['box.base'])>
                <div @class($personalize['box.button.wrapper'])>

                    {{--dropzone--}}
                    <div class="flex w-full items-center justify-center">
                        <label for="dropzone-file"
                            class="flex h-40 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-bray-800 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="mb-4 h-8 w-8 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                        class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX.
                                    800x400px)</p>
                            </div>
                            <input id="dropzone-file" type="file" class="hidden" />
                        </label>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-2 flex h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700"
                         role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
                        <div class="flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full bg-green-600 text-center text-xs text-white transition duration-500 dark:bg-blue-500"
                             style="width: 55%"></div>
                    </div>
                    <!-- End Progress Bar -->

                    {{--files--}}
                    <div class="max-h-64 w-full overflow-auto soft-scrollbar">
                        <!-- File Uploading Progress Form -->
                        <div
                            class="flex max-h-40 flex-col rounded-xl bg-white dark:border-gray-700 dark:bg-slate-800">
                            <!-- Body -->
                            <div class="px-2 py-2 space-y-7">
                                {{--Loop...--}}
                                <div>
                                    <!-- Uploading File Content -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-x-3">
                                            <span class="flex flex-1 items-center justify-center rounded-lg text-gray-500 dark:border-neutral-700">
                                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                                     name="photo"
                                                                     class="h-5 w-5 flex-shrink-0" />
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    file-01.html</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">7 KB</p>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center gap-x-2">
                                            <a class="text-gray-500 hover:text-gray-800" href="#">
                                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                                     icon="trash"
                                                                     class="h-4 w-4 flex-shrink-0 text-red-500" />
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Uploading File Content -->
                                </div>
                            </div>
                            <!-- End Body -->
                        </div>
                        <!-- End File Uploading Progress Form -->
                    </div>

                    <div class="mt-4 w-full">
                        <button type="button"
                            class="inline-flex w-full items-center justify-center gap-x-2 rounded-lg border border-transparent bg-gray-200 px-5 py-3 text-sm font-semibold text-gray-500 hover:bg-gray-300 disabled:pointer-events-none disabled:opacity-50 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            + Adicionar mais arquivos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>

<script>
    function app() {
      return {
        show: false,
      }
    }
</script>