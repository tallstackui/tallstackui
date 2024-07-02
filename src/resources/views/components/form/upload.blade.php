@php
    \TallStackUi\Foundation\Exceptions\MissingLivewireException::throwIf($livewire, 'upload');
    [$property] = $bind($attributes, livewire: $livewire);
    $personalize = $classes();
    $value = data_get($this, $property);
    if (is_null($property)) throw new Exception('The [upload] component requires a property to bind using [wire:model].');
@endphp

<div x-data="tallstackui_formUpload(
        @js($this->getId()),
        @js($property),
        @js($multiple),
        @js($error),
        @js($static),
        @js($placeholder),
        @js(__('tallstack-ui::messages.upload.uploaded')),
        @js($overflow))"
     x-cloak
     x-on:livewire-upload-start="uploading = true"
     x-on:livewire-upload-finish="uploading = false"
     x-on:livewire-upload-error="uploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress"
     class="relative" x-on:click.outside="show = false">
     @if ($static) <p hidden x-ref="placeholder">{{ $placeholder }}</p> @endif
        <x-dynamic-component :component="TallStackUi::component('input')"
                             :value="$placeholder"
                             :$label
                             :$hint
                             x-on:click="show = !show"
                             x-ref="input"
                             class="cursor-pointer caret-transparent"
                             x-on:keydown="$event.preventDefault()"
                             spellcheck="false"
                             dusk="tallstackui_upload_input"
                             invalidate
                             floatable>
                             <x-slot:suffix>
                                <button type="button" x-on:click="show = !show">
                                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                                         :icon="TallStackUi::icon('arrow-up-tray')"
                                                         @class($personalize['icon']) />
                                </button>
                             </x-slot:suffix>
        </x-dynamic-component>
    @if ($preview)
        <template x-teleport="body">
            <div x-show="preview" 
                 x-on:click="preview = false; $nextTick(() => show = true)"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @class($personalize['preview.backdrop'])
                 dusk="tallstackui_file_preview_backdrop">
                    <div @class($personalize['preview.wrapper'])>
                        <button @class($personalize['preview.button.wrapper']) x-on:click="preview = false; $nextTick(() => show = true)">
                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                 :icon="TallStackUi::icon('x-mark')"
                                                 :class="$personalize['preview.button.icon']" />
                        </button>
                        <img x-bind:src="image" @class($personalize['preview.image'])>
                    </div>
            </div>
        </template>
    @endif
     <x-dynamic-component :component="TallStackUi::component('floating')" class="p-3 w-full">
         @if (!$static)
         <div @class(['flex flex-col w-full items-center justify-center', 'mb-2' => $footer->isNotEmpty()])>
             <div @class($personalize['placeholder.wrapper']) :class="{ 'bg-primary-100': dragging }">
                 <div class="inline-flex items-center justify-center space-x-2">
                     <x-dynamic-component :component="TallStackUi::component('icon')"
                                          :icon="TallStackUi::icon('cloud-arrow-up')"
                                          @class($personalize['placeholder.icon.class']) />
                     <p @class($personalize['placeholder.title'])>
                         {{ __('tallstack-ui::messages.upload.upload') }}
                     </p>
                 </div>
                 @if (is_string($tip))
                     <p @class($personalize['placeholder.tip'])>{{ $tip }}</p>
                 @else
                     {{ $tip }}
                 @endif
                 <input id="{{ $property }}"
                        type="file"
                        dusk="tallstackui_file_select"
                        @if (!app()->runningUnitTests()) @class($personalize['placeholder.input']) @endif
                        x-ref="files"
                        {{ $attributes->only(['accept', 'x-on:upload']) }}
                        x-on:change="upload()"
                        x-on:dragover="dragging = true"
                        x-on:dragleave="dragging = false"
                        x-on:drop="dragging = false;"
                        @if ($multiple) multiple @endif />
             </div>
         </div>
         @endif
         <div @class([$personalize['error.wrapper'], 'mb-2' => $footer->isNotEmpty()]) x-show="@js($error) && error">
             <p @class($personalize['error.message']) x-text="warning"></p>
         </div>
         <div x-show="uploading"
              role="progressbar"
              @class([$personalize['upload.wrapper'], 'mb-2' => $footer->isNotEmpty()])>
             <div @class($personalize['upload.progress']) x-bind:style="'width: ' + progress + '%'"></div>
         </div>
         @if ($value)
             <div @class($personalize['item.wrapper']) x-ref="items">
                 <ul role="list" @class($personalize['item.ul'])>
                     @foreach($adapter($value) as $key => $file)
                         <li @class([$personalize['item.li'], 'py-2' => is_array($value) && count($value) > 1])>
                             <div class="flex min-w-0 gap-x-4">
                                 @if ($file['is_image'])
                                 <img src="{{ $file['url'] }}"
                                      dusk="tallstackui_file_preview"
                                      @if ($preview) x-on:click="image = @js($file['url']); preview = true; show = false" @endif
                                      @class([$personalize['item.image'], 'cursor-pointer' => $preview])>
                                 @else
                                     <x-dynamic-component :component="TallStackUi::component('icon')"
                                                          :icon="TallStackUi::icon('document-text')"
                                                          :class="$personalize['item.document']" />
                                 @endif
                                 <div class="min-w-0 flex-auto">
                                     <p @class($personalize['item.title'])>{{ $file['real_name'] }}</p>
                                     <x-dynamic-component :component="TallStackUi::component('error')"
                                                          :property="is_array($value) ? $property . '.' . $key : $property" />
                                     @if ($file['size'] !== null)
                                         <p @class($personalize['item.size'])>
                                             <span>{{ __('tallstack-ui::messages.upload.size') }}: </span>
                                             <span>{{ $file['size'] }}</span>
                                         </p>
                                     @endif
                                 </div>
                             </div>
                             <div class="flex shrink-0 flex-col items-end">
                                 @if ($delete)
                                     <button type="button"
                                             {{ $attributes->only('x-on:remove') }}
                                             x-on:click="remove(@js($deleteMethod), @js($file))">
                                         <x-dynamic-component :component="TallStackUi::component('icon')"
                                                              :icon="TallStackUi::icon('trash')"
                                                              @class($personalize['item.delete']) />
                                     </button>
                                 @endif
                             </div>
                         </li>
                     @endforeach
                 </ul>
             </div>
         @elseif ($static === true)
             <div @class($personalize['static.empty.wrapper'])>
                 <x-dynamic-component :component="TallStackUi::component('icon')"
                                      :icon="TallStackUi::icon('photo')"
                                      :class="$personalize['static.empty.icon']" />
                 <h3 @class($personalize['static.empty.title'])>
                     {{ __('tallstack-ui::messages.upload.static.empty.title') }}
                 </h3>
                 <p @class($personalize['static.empty.description'])>
                     {{ __('tallstack-ui::messages.upload.static.empty.description') }}
                 </p>
             </div>
         @endif
         @if ($footer->isNotEmpty())
             @unless ($footer->attributes->has('when-uploaded') && !$value)
                 <x-slot:footer>
                     {{ $footer }}
                 </x-slot:footer>
             @endunless
         @endif
     </x-dynamic-component>
</div>
