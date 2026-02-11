@php
  $personalize = $classes();
@endphp

<div class="relative" x-cloak x-data="tallstackui_formUpload(
    @js($this->getId()),
    @js($property),
    @js($multiple),
    @js($error),
    @js($static),
    @js($placeholder),
    @js(trans('tallstack-ui::messages.upload.uploaded')),
    @js($overflow),
    @js($closeAfterUpload),
    @js($chunk ?? false),
    @js(config('tallstackui.settings.form.upload.chunk', 1048576)))" x-on:click.outside="show = false" x-on:livewire-upload-error="uploading = false"
  x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" x-on:livewire-upload-start="uploading = true">
  @if ($static)
    <p hidden x-ref="placeholder">{{ $placeholder }}</p>
  @endif
  <x-dynamic-component :$hint :component="TallStackUi::prefix('input')" :value="$placeholder" class="cursor-pointer caret-transparent" dusk="tallstackui_upload_input" floatable invalidate
    spellcheck="false" x-on:click="show = !show" x-on:keydown="$event.preventDefault()" x-ref="input">
    <x-slot:suffix class="ml-1 mr-2">
      <button class="cursor-pointer" type="button" x-on:click="show = !show">
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon('arrow-up-tray')" class="{{ $personalize['icon'] }}" internal />
      </button>
    </x-slot:suffix>
    <x-slot:label>
      @if ($label)
        <x-dynamic-component :$id :$label :component="TallStackUi::prefix('label')" :error="$errors->has($property)" x-on:click="$event.preventDefault()" />
      @endif
    </x-slot:label>
  </x-dynamic-component>
  @if ($invalid['status'])
    <span class="{{ $personalize['invalid'] }}">
      {{ trans('tallstack-ui::messages.upload.invalid') }}
    </span>
  @endif
  @if ($preview)
    <template x-teleport="body">
      <div class="{{ $personalize['preview.backdrop'] }}" dusk="tallstackui_file_preview_backdrop" x-on:click="preview = false; $nextTick(() => show = true)"
        x-show="preview" x-transition:enter-end="opacity-100" x-transition:enter-start="opacity-0" x-transition:enter="ease-out duration-300"
        x-transition:leave-end="opacity-0" x-transition:leave-start="opacity-100" x-transition:leave="ease-in duration-200">
        <div class="{{ $personalize['preview.wrapper'] }}">
          <button class="{{ $personalize['preview.button.base'] }}" x-on:click="preview = false; $nextTick(() => show = true)">
            <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon('x-mark')" class="{{ $personalize['preview.button.icon'] }}" internal />
          </button>
          <img class="{{ $personalize['preview.image'] }}" x-bind:src="image">
        </div>
      </div>
    </template>
  @endif
  <x-dynamic-component :class="$personalize['floating.class']" :component="TallStackUi::prefix('floating')" :floating="$personalize['floating.default']" dusk="tallstackui_upload_floating">
    @if (!$static)
      <div @class([
          'flex flex-col w-full items-center justify-center',
          'mb-2' => $footer?->isNotEmpty(),
      ])>
        <div :class="{ 'bg-primary-100': dragging }" class="{{ $personalize['placeholder.wrapper'] }}">
          <div class="{{ $personalize['placeholder.icon.wrapper'] }}">
            <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon('cloud-arrow-up')" class="{{ $personalize['placeholder.icon.class'] }}" internal />
            <p class="{{ $personalize['placeholder.title'] }}">
              {{ trans('tallstack-ui::messages.upload.upload') }}
            </p>
          </div>
          @if (is_string($tip))
            <p class="{{ $personalize['placeholder.tip'] }}">{{ $tip }}</p>
          @else
            {{ $tip }}
          @endif
          <input {{ $attributes->only(['accept', 'x-on:upload']) }} @if ($multiple) multiple @endif
            @if (!app()->runningUnitTests()) class="{{ $personalize['placeholder.input'] }}" @endif dusk="tallstackui_file_select" id="{{ $property }}"
            type="file" x-on:change="upload()" x-on:dragleave="dragging = false" x-on:dragover="dragging = true" x-on:drop="dragging = false;"
            x-ref="files" />
        </div>
      </div>
    @endif
    <div @class([
        $personalize['error.wrapper'],
        'mb-2' => $footer?->isNotEmpty(),
    ]) x-show="@js($error) && error">
      <p class="{{ $personalize['error.message'] }}" x-text="warning"></p>
    </div>
    <div @class([
        $personalize['upload.wrapper'],
        'mb-2' => $footer?->isNotEmpty(),
    ]) role="progressbar" x-show="uploading">
      <div class="{{ $personalize['upload.progress'] }}" x-bind:style="'width: ' + progress + '%'"></div>
    </div>
    @if ($value)
      <div class="{{ $personalize['item.wrapper'] }}" x-ref="items">
        <ul class="{{ $personalize['item.ul'] }}" role="list">
          @foreach ($adapter($value) as $key => $file)
            <li @class([
                $personalize['item.li'],
                'py-2' => is_array($value) && count($value) > 1,
            ])>
              <div class="flex min-w-0 gap-x-4">
                @if ($file['is_image'])
                  <img @if ($preview) x-on:click="image = @js($file['url']); preview = true; show = false" @endif
                    @class([$personalize['item.image'], 'cursor-pointer' => $preview]) dusk="tallstackui_file_preview" src="{{ $file['url'] }}">
                @else
                  <x-dynamic-component :class="$personalize['item.document']" :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon('document-text')" internal />
                @endif
                <div class="min-w-0 flex-auto">
                  <p class="{{ $personalize['item.title'] }}">{{ $file['real_name'] }}</p>
                  <x-dynamic-component :component="TallStackUi::prefix('error')" :property="is_array($value) ? $property . '.' . $key : $property" />
                  @if ($file['size'] !== null)
                    <p class="{{ $personalize['item.size'] }}">
                      <span>{{ trans('tallstack-ui::messages.upload.size') }}: </span>
                      <span>{{ $file['size'] }}</span>
                    </p>
                  @endif
                </div>
              </div>
              <div class="flex shrink-0 flex-col items-end">
                @if ($delete)
                  <button {{ $attributes->only('x-on:remove') }} class="cursor-pointer" type="button"
                    x-on:click="remove(@js($deleteMethod), @js($file))">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon('trash')" class="{{ $personalize['item.delete'] }}" internal />
                  </button>
                @endif
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    @elseif ($static === true)
      <div class="{{ $personalize['static.empty.wrapper'] }}">
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :icon="TallStackUi::icon('photo')" class="{{ $personalize['static.empty.icon'] }}" internal />
        <h3 class="{{ $personalize['static.empty.title'] }}">
          {{ trans('tallstack-ui::messages.upload.static.empty.title') }}
        </h3>
        <p class="{{ $personalize['static.empty.description'] }}">
          {{ trans('tallstack-ui::messages.upload.static.empty.description') }}
        </p>
      </div>
    @endif
    @if ($footer?->isNotEmpty())
      @unless ($footer->attributes->has('when-uploaded') && !$value)
        <x-slot:footer>
          {{ $footer }}
        </x-slot:footer>
      @endunless
    @endif
  </x-dynamic-component>
</div>
