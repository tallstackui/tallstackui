@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('modal')"
                     :id="$editing['modal']"
                     scope="form.upload.editor.modal"
                     size="3xl"
                     center="md"
                     paddingless
                     x-on:close="dismiss()"
                     x-on:pointermove.window="drag($event)"
                     x-on:pointerup.window="release()">
    <x-slot:title>
        <span x-text="editor.name" class="{{ $customization['name'] }}"></span>
    </x-slot:title>
    <div class="{{ $customization['wrapper'] }}" dusk="tallstackui_upload_editor">
        <div data-tsui-editor="stage" class="{{ $customization['stage.wrapper'] }}">
            <div class="{{ $customization['stage.frame'] }}"
                 x-bind:style="`width: ${editor.width}px; height: ${editor.height}px`">
                <canvas data-tsui-editor="canvas"
                        class="{{ $customization['stage.canvas'] }}"
                        dusk="tallstackui_upload_editor_canvas"></canvas>
                @if ($editing['crop'])
                    <div x-on:pointerdown.prevent="press($event, 'move')"
                         x-bind:style="`left: ${editor.crop.x}px; top: ${editor.crop.y}px; width: ${editor.crop.width}px; height: ${editor.crop.height}px`"
                         class="{{ $customization['crop.box'] }}"
                         dusk="tallstackui_upload_editor_crop">
                        <div class="{{ $customization['crop.grid'] }}">
                            @foreach (range(1, 9) as $cell)
                                <div class="{{ $customization['crop.cell'] }}"></div>
                            @endforeach
                        </div>
                        @foreach (['n', 's', 'e', 'w', 'ne', 'nw', 'se', 'sw'] as $handle)
                            <span x-on:pointerdown.prevent.stop="press($event, @js($handle))"
                                  class="{{ $customization['crop.handle.base'] }} {{ $customization['crop.handle.'.$handle] }}"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-slot:footer between>
        <div class="{{ $customization['footer.tools'] }}">
            @if ($editing['rotate'])
                <button type="button"
                        x-on:click="rotate(-90)"
                        x-tooltip="{{ trans('ts-ui::messages.upload.editor.rotate_left') }}"
                        aria-label="{{ trans('ts-ui::messages.upload.editor.rotate_left') }}"
                        class="{{ $customization['footer.tool'] }}"
                        dusk="tallstackui_upload_editor_rotate_left">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-left')"
                                         internal
                                         class="{{ $customization['footer.tool-icon'] }}" />
                </button>
                <button type="button"
                        x-on:click="rotate(90)"
                        x-tooltip="{{ trans('ts-ui::messages.upload.editor.rotate_right') }}"
                        aria-label="{{ trans('ts-ui::messages.upload.editor.rotate_right') }}"
                        class="{{ $customization['footer.tool'] }}"
                        dusk="tallstackui_upload_editor_rotate_right">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('arrow-uturn-right')"
                                         internal
                                         class="{{ $customization['footer.tool-icon'] }}" />
                </button>
            @endif
            <button type="button"
                    x-on:click="reset()"
                    x-tooltip="{{ trans('ts-ui::messages.upload.editor.reset') }}"
                    aria-label="{{ trans('ts-ui::messages.upload.editor.reset') }}"
                    class="{{ $customization['footer.tool'] }}"
                    dusk="tallstackui_upload_editor_reset">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('arrow-path')"
                                     internal
                                     class="{{ $customization['footer.tool-icon'] }}" />
            </button>
        </div>
        <div class="{{ $customization['footer.actions'] }}">
            <x-dynamic-component :component="TallStackUi::prefix('button')"
                                 scope="form.upload.editor.cancel"
                                 color="secondary"
                                 style="outline"
                                 x-on:click="cancel()"
                                 dusk="tallstackui_upload_editor_cancel">
                {{ trans('ts-ui::messages.upload.editor.cancel') }}
            </x-dynamic-component>
            <x-dynamic-component :component="TallStackUi::prefix('button')"
                                 scope="form.upload.editor.apply"
                                 x-on:click="apply()"
                                 dusk="tallstackui_upload_editor_apply">
                {{ trans('ts-ui::messages.upload.editor.apply') }}
            </x-dynamic-component>
        </div>
    </x-slot:footer>
</x-dynamic-component>
