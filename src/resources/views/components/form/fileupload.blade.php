@php
    if (!$livewire) throw new Exception('The [fileupload] component must be used in a Livewire component.');

    [$property,, $id] = $bind($attributes, null, $livewire);
    $personalize = $classes();
    $value = $__livewire->{$property};
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$label :$hint>
    <div x-data="tallstackui_formUpload(@js($__livewire->getId()), @js($property), @js($multiple), @js($error), @js(__('tallstack-ui::messages.fileupload.uploaded')))"
        x-ref="wrapper"
        x-cloak
        x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish="uploading = false"
        x-on:livewire-upload-error="uploading = false"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        @class([
            // input.wrapper
            'focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md px-2 ring-1 transition focus-within:ring-2',
            // input.class.base
            'w-full border-0 bg-transparent ring-0 ring-inset focus:ring-transparent sm:text-sm sm:leading-6',
            // input.class.color.base
            'dark:ring-dark-600 dark:text-dark-300 dark:placeholder-dark-500 text-gray-600 ring-gray-300 placeholder:text-gray-400',
        ])>
        <input placeholder="{{ __('tallstack-ui::messages.fileupload.placeholder') }}"
               x-on:click="show = !show"
               class="flex w-full cursor-pointer items-center border-0 bg-transparent p-1 ring-0 ring-inset text-md py-1.5 focus:ring-transparent sm:text-sm sm:leading-6"
               x-ref="input"
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
                            class="flex h-32 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-bray-800 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="inline-flex items-center justify-center space-x-2">
                                <x-dynamic-component :component="TallStackUi::component('icon')"
                                                     icon="cloud-arrow-up"
                                                     class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('tallstack-ui::messages.fileupload.upload') }}
                                </p>
                            </div>
                            @if (is_string($tip))
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $tip }}</p>
                            @else
                                {{ $tip }}
                            @endif
                            <input id="dropzone-file"
                                   type="file"
                                   class="hidden"
                                   x-ref="files"
                                   x-on:change="upload()"
                                   @if ($multiple) multiple @endif />
                        </label>
                    </div>

                    <div class="mt-2 flex w-full items-center justify-center" x-show="@js($error) && error">
                        <p class="font-semibold text-red-500" x-text="warning"></p>
                    </div>

                    {{--progress--}}
                    <div x-show="uploading" class="mt-2 flex h-1 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700"
                         role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
                        <div class="flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full bg-green-600 text-center text-xs text-white transition duration-500"
                             x-bind:style="'width: ' + progress + '%'"></div>
                    </div>

                    {{--files--}}
                    <div class="max-h-64 w-full overflow-auto soft-scrollbar" x-ref="items">
                        @if ($deletable && !empty($value) && is_array($value))
                            <div class="mt-2 inline-flex gap-x-2">
                                <button type="button"
                                        class="text-gray-500 hover:text-gray-800"
                                        x-on:click="reset(@js($methods['all']))">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                         icon="trash"
                                                         class="h-4 w-4 flex-shrink-0 text-red-500" />
                                </button>
                            </div>
                        @endif
                        <!-- File Uploading Progress Form -->
                        <div class="flex max-h-40 flex-col rounded-xl bg-white dark:border-gray-700 dark:bg-slate-800">
                            <!-- Body -->
                            @if ($value)
                            <div class="px-2 py-2 space-y-7">
                                    @php /** @var \Illuminate\Http\UploadedFile $file */ @endphp
                                    @foreach(\Illuminate\Support\Arr::wrap($value) as $key => $file)
                                        <div>
                                            <!-- Uploading File Content -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-x-3">
                                                    <span class="flex flex-1 items-center justify-center rounded-lg text-gray-500 dark:border-neutral-700">
                                                        @if (in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif']))
                                                            <img class="h-12 w-12 rounded-full"
                                                                 src="{{ $file->temporaryUrl() }}">
                                                        @else
                                                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                                                 icon="photo"
                                                                                 class="h-5 w-5 flex-shrink-0" />
                                                        @endif
                                                    </span>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                            {{ $file->getClientOriginalName() }}
                                                        </p>
                                                        @if ($errors->has($property . '.' . $key))
                                                            <p class="text-xs text-red-500">{{ $errors->first($property . '.' . $key) }}</p>
                                                        @endif
                                                        <p class="text-xs text-gray-500 dark:text-gray-500">{{ \Illuminate\Support\Number::fileSize($file->getSize()) }}</p>
                                                    </div>
                                                </div>
                                                @if ($deletable)
                                                    <div class="inline-flex items-center gap-x-2">
                                                        <button type="button"
                                                                class="text-gray-500 hover:text-gray-800"
                                                                x-on:click="remove(@js($methods['single']), @js($file->getClientOriginalName()), @js($file->getFilename()))">
                                                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                                                 icon="trash"
                                                                                 class="h-4 w-4 flex-shrink-0 text-red-500" />
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- End Uploading File Content -->
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <!-- End Body -->
                        </div>
                        <!-- End File Uploading Progress Form -->
                    </div>
                </div>
                @if ($footer->isNotEmpty())
                    <div class="mb-2 w-full px-2">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>
