@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_editor({
            id: @js($id),
            value: @js($wireable ? '' : $value),
            entangle: {!! $entangle !!},
            markdown: @js($configurations['markdown']),
            classes: @js((object) $configurations['output_classes']),
            prefix: @js($configurations['output_classes_prefix']),
            toolbar: @js($configurations['toolbar']),
            upload: @js($upload),
            sanitization: @js($configurations['sanitization']),
            dialogs: @js($dialogs),
            readonly: @js($readonly),
            disabled: @js($disabled),
            i18n: @js($i18n),
         })"
         x-cloak
         @if ($wireable) wire:ignore @endif
         x-bind:class="{
            @js($customization['wrapper.fullscreen']): fullscreen,
            @js($customization['wrapper.disabled']): @js($disabled),
         }"
         {{ $attributes->class([$customization['wrapper.base']])->except(['name', 'value']) }}
         @if ($disabled || $readonly) aria-disabled="true" @endif
         dusk="tallstackui_editor">
        @unless ($readonly || $disabled)
            <div role="toolbar"
                 aria-label="Editor toolbar"
                 x-on:keydown.arrow-right.prevent="roving(1)"
                 x-on:keydown.arrow-left.prevent="roving(-1)"
                 x-ref="toolbar"
                 class="{{ $customization['toolbar.wrapper'] }}">
                @foreach ($layout as $item)
                    @if ($item['type'] === 'divider')
                        <div aria-hidden="true" data-tsui-editor-divider
                             class="{{ $customization['toolbar.divider'] }}"></div>
                    @else
                        @switch($item['slug'])
                            @case('style')
                                <x-dynamic-component :component="TallStackUi::prefix('dropdown')" scope="editor.toolbar"
                                                     width="xs" size="sm">
                                    <x-slot:action>
                                        <button type="button"
                                                x-on:mousedown.prevent
                                                x-on:click="capture(); show = !show"
                                                x-bind:aria-expanded="show"
                                                aria-haspopup="true"
                                                x-tooltip="{{ $i18n['tooltip']['style'] }}"
                                                data-position="bottom"
                                                data-tooltip-delay="flash"
                                                dusk="tallstackui_editor_style"
                                                class="{{ $customization['toolbar.dropdown.trigger'] }}">
                                            <span x-text="blockLabel()"></span>
                                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                 :icon="TallStackUi::icon('chevron-down')" internal
                                                                 class="h-3 w-3" />
                                        </button>
                                    </x-slot:action>
                                    @foreach (['h1', 'h2', 'h3'] as $level)
                                        <x-dynamic-component :component="TallStackUi::prefix('dropdown.items')"
                                                             :text="$i18n['style'][$level]"
                                                             :class="$customization['toolbar.dropdown.style.'.$level]"
                                                             x-on:mousedown.prevent="toggleBlock('{{ $level }}'); show = false"
                                                             x-on:keydown.enter.prevent="toggleBlock('{{ $level }}'); show = false"
                                                             x-bind:class="blockType === '{{ $level }}' && '{{ $customization['toolbar.dropdown.active'] }}'" />
                                    @endforeach
                                    <x-dynamic-component :component="TallStackUi::prefix('dropdown.items')"
                                                         :text="$i18n['style']['paragraph']"
                                                         :class="$customization['toolbar.dropdown.style.paragraph']"
                                                         x-on:mousedown.prevent="toggleBlock('p'); show = false"
                                                         x-on:keydown.enter.prevent="toggleBlock('p'); show = false"
                                                         x-bind:class="blockType === 'p' && '{{ $customization['toolbar.dropdown.active'] }}'" />
                                </x-dynamic-component>
                                @break

                            @case('blockquote')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="toggleBlockquote()"
                                        x-bind:aria-pressed="activeFormats.blockquote"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.blockquote }"
                                        x-tooltip="{{ $i18n['tooltip']['blockquote'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_blockquote"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <span class="font-serif text-base leading-none">&rdquo;</span>
                                </button>
                            @break

                            @case('bold')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('bold')"
                                        x-bind:aria-pressed="activeFormats.bold"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.bold }"
                                        x-tooltip="{{ $i18n['tooltip']['bold'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        data-tsui-shortcut="mod+b"
                                        dusk="tallstackui_editor_bold"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <strong>B</strong>
                                </button>
                            @break

                            @case('italic')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('italic')"
                                        x-bind:aria-pressed="activeFormats.italic"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.italic }"
                                        x-tooltip="{{ $i18n['tooltip']['italic'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        data-tsui-shortcut="mod+i"
                                        dusk="tallstackui_editor_italic"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <em>I</em>
                                </button>
                            @break

                            @case('underline')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('underline')"
                                        x-bind:aria-pressed="activeFormats.underline"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.underline }"
                                        x-tooltip="{{ $i18n['tooltip']['underline'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        data-tsui-shortcut="mod+u"
                                        dusk="tallstackui_editor_underline"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <span class="underline">U</span>
                                </button>
                            @break

                            @case('strikethrough')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('strikeThrough')"
                                        x-bind:aria-pressed="activeFormats.strikethrough"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.strikethrough }"
                                        x-tooltip="{{ $i18n['tooltip']['strikethrough'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_strikethrough"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <span class="line-through">S</span>
                                </button>
                            @break

                            @case('ordered-list')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('insertOrderedList')"
                                        x-bind:aria-pressed="activeFormats.orderedList"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.orderedList }"
                                        x-tooltip="{{ $i18n['tooltip']['ordered_list'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_ordered_list"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('numbered-list')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                            @break

                            @case('unordered-list')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('insertUnorderedList')"
                                        x-bind:aria-pressed="activeFormats.unorderedList"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.unorderedList }"
                                        x-tooltip="{{ $i18n['tooltip']['unordered_list'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_unordered_list"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('list-bullet')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                            @break

                            @case('indent')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="shiftIndent(1)"
                                        x-tooltip="{{ $i18n['tooltip']['indent'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_indent"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('chevron-double-right')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                                @break

                            @case('outdent')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="shiftIndent(-1)"
                                        x-tooltip="{{ $i18n['tooltip']['outdent'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_outdent"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('chevron-double-left')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                            @break

                            @case('align')
                                <x-dynamic-component :component="TallStackUi::prefix('dropdown')" scope="editor.toolbar"
                                                     width="sm" size="sm">
                                    <x-slot:action>
                                        <button type="button"
                                                x-on:mousedown.prevent
                                                x-on:click="capture(); show = !show"
                                                x-bind:aria-expanded="show"
                                                aria-haspopup="true"
                                                x-tooltip="{{ $i18n['tooltip']['align'] }}"
                                                data-position="bottom"
                                                data-tooltip-delay="flash"
                                                dusk="tallstackui_editor_align"
                                                class="{{ $customization['toolbar.dropdown.trigger'] }}">
                                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                 :icon="TallStackUi::icon('bars-3-bottom-left')" internal
                                                                 class="{{ $customization['toolbar.icon'] }}" />
                                        </button>
                                    </x-slot:action>
                                    @foreach (['left' => 'justifyLeft', 'center' => 'justifyCenter', 'right' => 'justifyRight', 'justify' => 'justifyFull'] as $side => $command)
                                        <x-dynamic-component :component="TallStackUi::prefix('dropdown.items')"
                                                             :text="$i18n['align'][$side]"
                                                             x-on:mousedown.prevent="exec('{{ $command }}'); show = false"
                                                             x-on:keydown.enter.prevent="exec('{{ $command }}'); show = false"
                                                             x-bind:class="activeFormats.{{ $command }} && '{{ $customization['toolbar.dropdown.active'] }}'" />
                                    @endforeach
                                </x-dynamic-component>
                            @break

                            @case('code')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="toggleCode()"
                                        x-bind:aria-pressed="activeFormats.code"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.code }"
                                        x-tooltip="{{ $i18n['tooltip']['code'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_code"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('code-bracket')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                                @break

                            @case('code-block')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="toggleCodeBlock()"
                                        x-bind:aria-pressed="activeFormats.codeBlock"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.codeBlock }"
                                        x-tooltip="{{ $i18n['tooltip']['code_block'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_code_block"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('code-bracket-square')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                                @break

                            @case('clear-format')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="clearFormat()"
                                        x-tooltip="{{ $i18n['tooltip']['clear_format'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        data-tsui-shortcut="mod+backslash"
                                        dusk="tallstackui_editor_clear_format"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('backspace')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                                @break

                            @case('link')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="openLink()"
                                        x-bind:aria-pressed="activeFormats.link"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): activeFormats.link }"
                                        x-tooltip="{{ $i18n['tooltip']['link'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        data-tsui-shortcut="mod+k"
                                        dusk="tallstackui_editor_link"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('link')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                                @break

                            @case('image')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="openImage()"
                                        x-tooltip="{{ $i18n['tooltip']['image'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_image"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('photo')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                                @break

                            @case('hr')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="insertRule()"
                                        x-tooltip="{{ $i18n['tooltip']['hr'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_hr"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('minus')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                            @break

                            @case('undo')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('undo')"
                                        x-tooltip="{{ $i18n['tooltip']['undo'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        data-tsui-shortcut="mod+z"
                                        dusk="tallstackui_editor_undo"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('arrow-uturn-left')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                            @break

                            @case('redo')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="exec('redo')"
                                        x-tooltip="{{ $i18n['tooltip']['redo'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        data-tsui-shortcut="mod+shift+z"
                                        dusk="tallstackui_editor_redo"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                         :icon="TallStackUi::icon('arrow-uturn-right')" internal
                                                         class="{{ $customization['toolbar.icon'] }}" />
                                </button>
                            @break

                            @case('fullscreen')
                                <button type="button"
                                        x-on:mousedown.prevent
                                        x-on:click="toggleFullscreen()"
                                        x-bind:aria-pressed="fullscreen"
                                        x-bind:class="{ @js($customization['toolbar.button.active']): fullscreen }"
                                        x-tooltip="{{ $i18n['tooltip']['fullscreen'] }}"
                                        data-position="bottom"
                                        data-tooltip-delay="flash"
                                        dusk="tallstackui_editor_fullscreen"
                                        class="{{ $customization['toolbar.button.base'] }}">
                                    <template x-if="!fullscreen">
                                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                             :icon="TallStackUi::icon('arrows-pointing-out')" internal
                                                             class="{{ $customization['toolbar.icon'] }}" />
                                    </template>
                                    <template x-if="fullscreen">
                                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                             :icon="TallStackUi::icon('arrows-pointing-in')" internal
                                                             class="{{ $customization['toolbar.icon'] }}" />
                                    </template>
                                </button>
                            @break
                        @endswitch
                    @endif
                @endforeach
            </div>
        @endunless

        <div class="{{ $customization['editable.container'] }}">
            <div class="{{ $customization['editable.wrapper'] }}">
                <div x-ref="editable"
                     role="textbox"
                     aria-multiline="true"
                     aria-label="{{ is_string($label) && filled($label) ? $label : $configurations['placeholder'] }}"
                     @if ($required) aria-required="true" @endif
                     @if ($error) aria-invalid="true" @endif
                     spellcheck="{{ $spellcheck ? 'true' : 'false' }}"
                     contenteditable="{{ $readonly || $disabled ? 'false' : 'true' }}"
                     data-placeholder="{{ $configurations['placeholder'] }}"
                     x-bind:data-empty="empty"
                     x-on:input="handleInput($event)"
                     x-on:blur="syncFromDom()"
                     x-on:paste="handlePaste($event)"
                     x-on:keydown="handleKeydown($event)"
                     style="min-height: {{ $configurations['heights']['min'] }}; max-height: {{ $configurations['heights']['max'] }};"
                     dusk="tallstackui_editor_editable"
                     @class([
                         $customization['editable.content'],
                         $customization['editable.placeholder'],
                         $customization['editable.typography.headings'],
                         $customization['editable.typography.lists'],
                         $customization['editable.typography.code'],
                         $customization['editable.typography.link'],
                         $customization['editable.typography.image'],
                         $customization['editable.typography.paragraph'],
                         $customization['editable.typography.quote'],
                         $customization['editable.typography.rule'],
                     ])></div>
            </div>
        </div>

        @if ($configurations['counters'])
            <div role="status" aria-live="polite" class="{{ $customization['footer.wrapper'] }}">
                <span class="{{ $customization['footer.counter'] }}"
                      x-text="formatCount(words, @js($i18n['counters']['words']))"></span>
                <span aria-hidden="true">&middot;</span>
                <span class="{{ $customization['footer.counter'] }}"
                      x-text="formatCount(lines, @js($i18n['counters']['lines']))"></span>
            </div>
        @endif

        @unless ($readonly || $disabled)
            <x-dynamic-component :component="TallStackUi::prefix('modal')"
                                 :id="$dialogs['link']"
                                 scope="editor.modal.link"
                                 size="sm"
                                 center="md"
                                 :title="$i18n['link']['title']"
                                 x-on:close="dialogClosed()">
                <div class="{{ $customization['dialog.fields'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         :id="$dialogs['link'].'-text'"
                                         :label="$i18n['link']['text']"
                                         x-model="linkText"
                                         x-ref="linkText" />
                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         :id="$dialogs['link'].'-url'"
                                         :label="$i18n['link']['url']"
                                         type="url"
                                         x-model="linkUrl"
                                         x-on:keydown.enter.prevent="insertLink()" />
                </div>

                <x-slot:footer between>
                    <x-dynamic-component :component="TallStackUi::prefix('button')"
                                         color="secondary"
                                         sm
                                         round
                                         block
                                         x-on:click="closeDialog()"
                                         :text="$i18n['link']['cancel']" />
                    <x-dynamic-component :component="TallStackUi::prefix('button')"
                                         x-on:click="insertLink()"
                                         x-bind:disabled="!linkUrl || !validLinkUrl"
                                         dusk="tallstackui_editor_link_insert"
                                         sm
                                         round
                                         block
                                         :text="$i18n['link']['insert']" />
                </x-slot:footer>
            </x-dynamic-component>

            <x-dynamic-component :component="TallStackUi::prefix('modal')"
                                 :id="$dialogs['image']"
                                 scope="editor.modal.image"
                                 size="md"
                                 center="md"
                                 :title="$i18n['image']['title']"
                                 x-on:close="dialogClosed()">
                <div class="{{ $customization['dialog.fields'] }}">
                    @if ($upload['enabled'])
                        <div role="button"
                             tabindex="0"
                             x-on:click="$refs.file.click()"
                             x-on:keydown.enter.prevent="$refs.file.click()"
                             dusk="tallstackui_editor_image_upload"
                             class="{{ $customization['image.upload.area'] }}">
                            <span class="{{ $customization['image.upload.button'] }}">
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon('arrow-up-tray')" internal
                                                     class="h-4 w-4" />
                                <span x-text="uploading ? uploadProgress + '%' : @js($i18n['image']['upload'])"></span>
                            </span>
                            <span class="{{ $customization['image.upload.hint'] }}">
                                {{ str_replace(':size', $upload['readable'], $i18n['image']['upload_hint']) }}
                            </span>
                            <input type="file"
                                   x-ref="file"
                                   class="hidden"
                                   accept="{{ implode(',', $upload['mimes']) }}"
                                   x-on:change="uploadImage($event.target.files[0]); $event.target.value = ''" />
                            <template x-if="uploading">
                                <div class="{{ $customization['image.upload.progress.wrapper'] }}">
                                    <div class="{{ $customization['image.upload.progress.bar'] }}"
                                         x-bind:style="`width: ${uploadProgress}%`"></div>
                                </div>
                            </template>
                        </div>
                        <div class="{{ $customization['image.divider'] }}">
                            <span></span>{{ $i18n['image']['or_url'] }}<span></span>
                        </div>
                    @endif

                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         :id="$dialogs['image'].'-url'"
                                         :label="$i18n['image']['url']"
                                         type="url"
                                         x-model="imageUrl"
                                         x-on:keydown.enter.prevent="insertImage()"
                                         x-ref="imageUrl" />

                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         :id="$dialogs['image'].'-alt'"
                                         :label="$i18n['image']['alt']"
                                         :hint="$i18n['image']['alt_hint']"
                                         x-model="imageAlt" />

                    <template x-if="imageUrl && validImageUrl">
                        <img x-bind:src="imageUrl" alt=""
                             x-on:error="imageError = @js($i18n['image']['errors']['url'])"
                             x-on:load="imageError = ''"
                             class="{{ $customization['image.preview'] }}" />
                    </template>

                    <template x-if="imageError">
                        <span role="alert" aria-live="assertive" x-text="imageError"
                              class="{{ $customization['dialog.error'] }}"></span>
                    </template>
                </div>

                <x-slot:footer between>
                    <x-dynamic-component :component="TallStackUi::prefix('button')"
                                         color="secondary"
                                         sm
                                         round
                                         block
                                         x-on:click="closeDialog()"
                                         :text="$i18n['image']['cancel']" />
                    <x-dynamic-component :component="TallStackUi::prefix('button')"
                                         x-on:click="insertImage()"
                                         x-bind:disabled="!imageUrl || !validImageUrl || uploading"
                                         dusk="tallstackui_editor_image_insert"
                                         sm
                                         block
                                         round
                                         :text="$i18n['image']['insert']" />
                </x-slot:footer>
            </x-dynamic-component>

        @endunless

        @if ($name)
            <input type="hidden" name="{{ $name }}" x-bind:value="content" />
        @endif
    </div>
</x-dynamic-component>
