@php
    if (!$livewire) throw new Exception('The [fileupload] component must be used in a Livewire component.');

    [$property,, $id] = $bind($attributes, null, $livewire);
    $personalize = $classes();
    $value = $__livewire->{$property};
    $placeholder = __('tallstack-ui::messages.fileupload.placeholder');
@endphp

<div x-data="tallstackui_formUpload(
        @js($__livewire->getId()),
        @js($property),
        @js($multiple),
        @js($error),
        @js($placeholder),
        @js(__('tallstack-ui::messages.fileupload.uploaded')))"
     x-ref="wrapper"
     x-cloak
     x-on:livewire-upload-start="uploading = true"
     x-on:livewire-upload-finish="uploading = false"
     x-on:livewire-upload-error="uploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress"
     @class(['relative rounded-md shadow-sm'])>
     <x-input :value="$placeholder"
              :$label
              :$hint
              x-on:click="show = !show"
              x-ref="input"
              readonly
              icon="arrow-up-tray"
              position="right"
              invalidate />
    {{--box--}}
    <div x-cloak
         x-show="show"
         x-on:click.away="show = false"
         x-transition:enter="transition duration-100 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         x-anchor.bottom-end="$refs.wrapper"
         @class($personalize['box.wrapper'])>
        <div @class($personalize['box.base'])>
            <div @class($personalize['box.button.wrapper'])>
                {{--dropzone--}}
                <div class="flex w-full items-center justify-center">
                    <label for="dropzone-file"
                           class="flex h-32 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 transition hover:bg-gray-100 dark:border-dark-600 dark:bg-dark-700 dark:hover:bg-dark-500">
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
                                                                                 icon="document-text"
                                                                                 class="h-5 w-5 text-primary-500 dark:text-dark-300 flex-shrink-0" />
                                                        @endif
                                                    </span>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                        {{ $file->getClientOriginalName() }}
                                                    </p>
                                                    @error ($property . '.' . $key)
                                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                                    @enderror
                                                    <p class="text-xs text-gray-500 dark:text-dark-400">
                                                        {{ \Illuminate\Support\Number::fileSize($file->getSize()) }}
                                                    </p>
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
