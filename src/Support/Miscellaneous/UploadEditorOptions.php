<?php

namespace TallStackUi\Support\Miscellaneous;

use Livewire\Component;
use TallStackUi\TallStackUiComponent;
use WeakMap;

class UploadEditorOptions
{
    private const FORMATS = ['png', 'jpeg', 'webp'];

    private const MODES = ['crop', 'rotate'];

    // A WeakMap so the counters die with the render that created them.
    private static ?WeakMap $sequences = null;

    public function __construct(
        private readonly TallStackUiComponent $component,
        private readonly bool|string|null $editor,
        private readonly ?string $aspect,
        private readonly array $configuration,
        private readonly string $id,
        private readonly ?Component $livewire = null,
    ) {
        //
    }

    public function __invoke(): ?array
    {
        $editor = $this->editor ?? $this->configuration['editor'] ?? false;

        if ($editor === false || $editor === '') {
            return null;
        }

        if ($editor !== true && ! in_array($editor, self::MODES, true)) {
            __ts_validation_exception($this->component, 'The [editor] value must be one of: true, crop, rotate.');
        }

        $aspect = $this->aspect ?? $this->configuration['aspect'] ?? null;

        if ($aspect !== null && ! preg_match('/^[1-9]\d*:[1-9]\d*$/', $aspect)) {
            __ts_validation_exception($this->component, 'The [aspect] value must follow the [width:height] format, like [16:9].');
        }

        $format = $this->configuration['format'] ?? null;

        if ($format !== null && ! in_array($format, self::FORMATS, true)) {
            __ts_validation_exception($this->component, 'The [format] value must be one of: png, jpeg, webp.');
        }

        [$width, $height] = $aspect ? array_map('intval', explode(':', $aspect)) : [null, null];

        return [
            'modal' => $this->modal(),
            'crop' => $editor === true || $editor === 'crop',
            'rotate' => $editor === true || $editor === 'rotate',
            'aspect' => $aspect ? $width / $height : null,
            'quality' => (float) ($this->configuration['quality'] ?? 0.92),
            'format' => $format,
        ];
    }

    // The client reaches the modal by id through window events, so two uploads
    // bound to the same property need distinct ids. The render order is the
    // same on every render of a component, so the suffix survives Livewire updates.
    private function modal(): string
    {
        self::$sequences ??= new WeakMap;

        $scope = $this->livewire ?? request();

        $sequences = self::$sequences[$scope] ?? [];
        $sequences[$this->id] = ($sequences[$this->id] ?? 0) + 1;

        self::$sequences[$scope] = $sequences;

        $sequence = $sequences[$this->id];

        return str('upload-editor-'.$this->id.($sequence > 1 ? '-'.$sequence : ''))->slug()->toString();
    }
}
