@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_formUploadAsync({
        id: @js($id),
        property: @js($property),
        name: @js($name),
        route: @js($route),
        method: @js($method),
        multiple: @js((bool) $multiple),
        manual: @js((bool) $manual),
        disabled: @js((bool) $disabled),
        limit: @js($limit),
        config: @js($config),
        existing: @js($value),
        wire: @js($wire),
        live: @js($live),
        i18n: @js($i18n),
        headers: @js($headers ?? []),
     })"
     x-cloak
     {{-- The subtree is entirely Alpine owned. Letting Livewire morph it would
          re-run init() on every round trip, wiping the blob previews and
          orphaning any upload still in flight. --}}
     @if ($wire) wire:ignore @endif
     class="{{ $customization['wrapper'] }}"
     {{ $attributes->whereStartsWith('x-on:') }}
     {{ $attributes->only(['id']) }}
     dusk="tallstackui_upload_async">
    @if ($label)
        <x-dynamic-component :component="TallStackUi::prefix('label')"
                             scope="form.upload.async.label"
                             :id="$id"
                             :label="(string) $label"
                             :error="$invalid['status']" />
    @endif

    <div role="button"
         tabindex="0"
         x-on:click="pick()"
         x-on:keydown.enter.prevent="pick()"
         x-on:keydown.space.prevent="pick()"
         x-on:dragover.prevent="dragging = !disabled"
         x-on:dragleave.prevent="dragging = false"
         x-on:drop.prevent="drop($event)"
         x-bind:class="{
            '{{ $customization['dropzone.dragging'] }}': dragging && !disabled,
            '{{ $customization['dropzone.disabled'] }}': disabled,
         }"
         class="{{ $customization['dropzone.base'] }} {{ $height }}"
         dusk="tallstackui_upload_async_dropzone">
        <input type="file"
               x-ref="input"
               x-on:change="select($event)"
               x-bind:disabled="disabled"
               class="{{ $customization['dropzone.input'] }}"
               @if ($accept) accept="{{ $accept }}" @endif
               @if ($multiple) multiple @endif
               dusk="tallstackui_upload_async_input" />

        <div x-show="files.length"
             role="list"
             x-bind:class="multiple
                ? '{{ $customization['grid.multiple'] }} {{ $customization['grid.cols.'.$columns] }}'
                : '{{ $customization['grid.single'] }}'"
             class="{{ $customization['grid.wrapper'] }}">
            <template x-for="file in files" :key="file.id">
                <div role="listitem"
                     x-on:click.stop
                     x-bind:class="{ '{{ $customization['tile.error-ring'] }}': failed(file) }"
                     class="{{ $customization['tile.wrapper'] }}"
                     dusk="tallstackui_upload_async_tile">
                    <template x-if="image(file)">
                        <img x-bind:src="file.preview"
                             x-bind:alt="file.real_name"
                             x-on:click.stop="expand(file)"
                             class="{{ $customization['tile.image'] }}" />
                    </template>
                    <template x-if="!image(file)">
                        <div class="{{ $customization['tile.document'] }}">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('document-text')"
                                                 internal
                                                 class="{{ $customization['tile.document-icon'] }}" />
                            <span x-text="extension(file)" class="{{ $customization['tile.extension'] }}"></span>
                        </div>
                    </template>

                    <span x-text="file.real_name" class="{{ $customization['tile.name-overlay'] }}"></span>
                    <span x-text="size(file.size)" class="{{ $customization['tile.size-overlay'] }}"></span>

                    <button type="button"
                            x-on:click.stop="remove(file)"
                            x-bind:aria-label="@js($i18n['remove'])"
                            class="{{ $customization['tile.remove'] }}"
                            dusk="tallstackui_upload_async_remove">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('trash')"
                                             internal
                                             class="{{ $customization['tile.remove-icon'] }}" />
                    </button>

                    <div x-show="file.status === 'uploading'"
                         x-bind:style="`width: ${file.progress}%`"
                         class="{{ $customization['tile.progress'] }}"></div>

                    <div x-show="file.status === 'success'" class="{{ $customization['tile.success-mark'] }}">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('check')"
                                             internal
                                             class="h-3 w-3" />
                    </div>

                    <div x-show="failed(file)" class="{{ $customization['tile.error-badge'] }}">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('x-mark')"
                                             internal
                                             class="h-3 w-3" />
                    </div>

                    <p x-show="file.error"
                       x-text="file.error"
                       x-bind:title="file.error"
                       class="{{ $customization['tile.error-msg'] }}"></p>
                </div>
            </template>

        </div>

        <div class="{{ $customization['dropzone.placeholder'] }}">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('cloud-arrow-up')"
                                 internal
                                 x-bind:class="dragging ? '{{ $customization['dropzone.icon-bouncing'] }}' : ''"
                                 class="{{ $customization['dropzone.icon'] }}" />
            <p class="{{ $customization['dropzone.title'] }}">{{ $title }}</p>
            <p class="{{ $customization['dropzone.description'] }}">{{ $description }}</p>
            @if ($tip)
                <p class="{{ $customization['dropzone.tip'] }}">{{ $tip }}</p>
            @endif
        </div>
    </div>

    @if ($hint)
        <x-dynamic-component :component="TallStackUi::prefix('hint')"
                             scope="form.upload.async.hint"
                             :hint="(string) $hint" />
    @endif

    @if ($manual && ! $footer)
        <div x-show="files.length" class="{{ $customization['footer.wrapper'] }}">
            <p x-text="summary()" class="{{ $customization['footer.summary'] }}"></p>
            <div class="{{ $customization['footer.actions'] }}">
                <x-dynamic-component :component="TallStackUi::prefix('button')"
                                     scope="form.upload.async.clear"
                                     color="red"
                                     style="outline"
                                     x-on:click="clear()"
                                     dusk="tallstackui_upload_async_clear">
                    {{ $i18n['clear'] }}
                </x-dynamic-component>
                <x-dynamic-component :component="TallStackUi::prefix('button')"
                                     scope="form.upload.async.send"
                                     x-bind:disabled="!sendable() || disabled"
                                     x-on:click="send()"
                                     dusk="tallstackui_upload_async_send">
                    {{ $i18n['send'] }}
                </x-dynamic-component>
            </div>
        </div>
    @elseif ($footer)
        {{ $footer }}
    @endif

    @if ($error && $invalid['status'] === false)
        <div x-show="broken" class="{{ $customization['error.wrapper'] }}">
            <span class="{{ $customization['error.message'] }}">{{ $error }}</span>
        </div>
    @endif

    @if ($name)
        <template x-for="(file, index) in uploaded" :key="`hidden-${file.id}`">
            <span>
                <template x-if="multiple">
                    <span>
                        <input type="hidden" x-bind:name="`{{ $name }}[${index}][id]`" x-bind:value="file.id" />
                        <input type="hidden" x-bind:name="`{{ $name }}[${index}][path]`" x-bind:value="file.path" />
                        <input type="hidden" x-bind:name="`{{ $name }}[${index}][real_name]`" x-bind:value="file.real_name" />
                        <input type="hidden" x-bind:name="`{{ $name }}[${index}][size]`" x-bind:value="file.size" />
                        <input type="hidden" x-bind:name="`{{ $name }}[${index}][mime]`" x-bind:value="file.mime" />
                        <input type="hidden" x-bind:name="`{{ $name }}[${index}][url]`" x-bind:value="file.url" />
                    </span>
                </template>
                <template x-if="!multiple">
                    <span>
                        <input type="hidden" name="{{ $name }}[id]" x-bind:value="file.id" />
                        <input type="hidden" name="{{ $name }}[path]" x-bind:value="file.path" />
                        <input type="hidden" name="{{ $name }}[real_name]" x-bind:value="file.real_name" />
                        <input type="hidden" name="{{ $name }}[size]" x-bind:value="file.size" />
                        <input type="hidden" name="{{ $name }}[mime]" x-bind:value="file.mime" />
                        <input type="hidden" name="{{ $name }}[url]" x-bind:value="file.url" />
                    </span>
                </template>
            </span>
        </template>
    @endif

    <template x-teleport="body">
        <div x-show="preview.open"
             x-on:click="collapse()"
             x-on:keydown.escape.window="collapse()"
             role="dialog"
             aria-modal="true"
             class="{{ $customization['lightbox.backdrop'] }}"
             dusk="tallstackui_upload_async_lightbox">
            <button type="button"
                    x-on:click="collapse()"
                    x-bind:aria-label="@js($i18n['preview']['close'])"
                    class="{{ $customization['lightbox.close'] }}">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('x-mark')"
                                     internal
                                     class="{{ $customization['lightbox.close-icon'] }}" />
            </button>
            <div x-on:click.stop class="{{ $customization['lightbox.wrapper'] }}">
                <img x-bind:src="preview.src" x-bind:alt="preview.name" class="{{ $customization['lightbox.image'] }}" />
                <p x-text="preview.name"
                   x-transition.opacity
                   class="{{ $customization['lightbox.caption'] }}"></p>
            </div>
        </div>
    </template>
</div>
