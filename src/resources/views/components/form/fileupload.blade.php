@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_formUpload(
        {!! $entangle !!},
        @js($attributes->get('value')))"
        x-ref="wrapper"
        x-cloak
        @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] =>
                !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] =>
                $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error,
        ])>
        <div x-on:click="show = !show"
            class="flex items-center cursor-pointer text-md w-full border-0 bg-transparent p-1 py-1.5 ring-0 ring-inset focus:ring-transparent sm:text-sm sm:leading-6">
            Selecione um arquivo
        </div>
        <div class="w-full" wire:ignore>
            <button @if ($id) id="{{ $id }}" @endif
                {{ $attributes->class($personalize['input.base']) }}>
        </div>
        <div class="flex items-center">
            <button type="button"
                @if ($attributes->get('disabled') || $attributes->get('readonly')) disabled @endif
                x-on:click="show = !show"
                dusk="tallstackui_form_color">
                <x-dynamic-component :component="TallStackUi::component('icon')" icon="arrow-up-tray" :$error @class($personalize['icon.class']) />
            </button>
        </div>
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
                <input type="range"
                    min="1"
                    max="11"
                    x-model="weight"
                    x-show="mode === 'range' && colors.length === 0"
                    dusk="tallstackui_form_range"
                    @class([
                        $personalize['box.range.base'],
                        $personalize['box.range.thumb'],
                    ])>
                <div @class($personalize['box.button.wrapper'])>

                    <div class="flex items-center justify-center w-full ">
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
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

                    <div class="w-full max-h-64 soft-scrollbar overflow-auto">
                        <!-- File Uploading Progress Form -->
                        <div
                            class="flex flex-col bg-white rounded-xl dark:bg-slate-800 dark:border-gray-700 max-h-40">
                            <!-- Body -->
                            <div class="py-5 px-2 space-y-7">
                                <div>
                                    <!-- Uploading File Content -->
                                    <div class="mb-2 flex justify-between items-center">
                                        <div class="flex items-center gap-x-3">
                                            <span
                                                class="w-8 h-8 flex justify-center items-center border border-gray-200 text-gray-500 rounded-lg dark:border-neutral-700">
                                                <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="17 8 12 3 7 8" />
                                                    <line x1="12" x2="12" y1="3" y2="15" />
                                                </svg>
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    file-01.html</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">7 KB</p>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center gap-x-2">
                                            <a class="text-gray-500 hover:text-gray-800" href="#">
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <rect width="4" height="16" x="6" y="4" />
                                                    <rect width="4" height="16" x="14" y="4" />
                                                </svg>
                                            </a>
                                            <a class="text-gray-500 hover:text-gray-800" href="#">
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                    <line x1="10" x2="10" y1="11"
                                                        y2="17" />
                                                    <line x1="14" x2="14" y1="11"
                                                        y2="17" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Uploading File Content -->

                                    <!-- Progress Bar -->
                                    <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700"
                                        role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
                                        <div class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-blue-500"
                                            style="width: 100%"></div>
                                    </div>
                                    <!-- End Progress Bar -->
                                </div>

                                <div>
                                    <!-- Uploading File Content -->
                                    <div class="mb-2 flex justify-between items-center">
                                        <div class="flex items-center gap-x-3">
                                            <span
                                                class="w-8 h-8 flex justify-center items-center border border-gray-200 text-gray-500 rounded-lg dark:border-neutral-700">
                                                <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="17 8 12 3 7 8" />
                                                    <line x1="12" x2="12" y1="3"
                                                        y2="15" />
                                                </svg>
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    file-02-video.mp4</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">105.5 MB</p>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center gap-x-2">
                                            <a class="text-gray-500 hover:text-gray-800" href="#">
                                                <svg class="flex-shrink-0 w-4 h-4 text-red-500"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-exclamation-triangle-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                </svg>
                                            </a>
                                            <a class="text-gray-500 hover:text-gray-800" href="#">
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                    <line x1="10" x2="10" y1="11"
                                                        y2="17" />
                                                    <line x1="14" x2="14" y1="11"
                                                        y2="17" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Uploading File Content -->

                                    <!-- Progress Bar -->
                                    <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700"
                                        role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
                                        <div class="flex flex-col justify-center rounded-full overflow-hidden bg-red-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-blue-500"
                                            style="width: 100%"></div>
                                    </div>
                                    <!-- End Progress Bar -->
                                </div>

                                <div>
                                    <!-- Uploading File Content -->
                                    <div class="mb-2 flex justify-between items-center">
                                        <div class="flex items-center gap-x-3">
                                            <span
                                                class="w-8 h-8 flex justify-center items-center border border-gray-200 text-gray-500 rounded-lg dark:border-neutral-700">
                                                <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="17 8 12 3 7 8" />
                                                    <line x1="12" x2="12" y1="3"
                                                        y2="15" />
                                                </svg>
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    file-001-ui-cover.jpg</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">55 KB</p>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center gap-x-2">
                                            <a class="text-gray-500 hover:text-gray-800" href="#">
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <rect width="4" height="16" x="6" y="4" />
                                                    <rect width="4" height="16" x="14" y="4" />
                                                </svg>
                                            </a>
                                            <a class="text-gray-500 hover:text-gray-800" href="#">
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                    <line x1="10" x2="10" y1="11"
                                                        y2="17" />
                                                    <line x1="14" x2="14" y1="11"
                                                        y2="17" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Uploading File Content -->

                                    <!-- Progress Bar -->
                                    <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700"
                                        role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
                                        <div class="flex flex-col justify-center rounded-full overflow-hidden bg-green-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-blue-500"
                                            style="width: 55%"></div>
                                    </div>
                                    <!-- End Progress Bar -->
                                </div>
                            </div>
                            <!-- End Body -->
                        </div>
                        <!-- End File Uploading Progress Form -->
                    </div>

                    <div class="w-full mt-4">
                        <button type="button"
                            class="py-3 px-5 w-full inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-gray-200 text-gray-500 hover:bg-gray-300 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            + Adicionar mais arquivos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
