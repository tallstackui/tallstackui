<?php

namespace TallStackUi\Support\Miscellaneous;

use TallStackUi\TallStackUiComponent;

class UploadEditorOptions
{
    private const FORMATS = ['png', 'jpeg', 'webp'];

    private const MODES = ['crop', 'rotate'];

    public function __construct(
        private readonly TallStackUiComponent $component,
        private readonly bool|string|null $editor,
        private readonly ?string $aspect,
        private readonly array $configuration,
        private readonly string $id,
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
            // The client opens the modal through window events named after its id.
            'modal' => str('upload-editor-'.$this->id)->slug()->toString(),
            'crop' => $editor === true || $editor === 'crop',
            'rotate' => $editor === true || $editor === 'rotate',
            'aspect' => $aspect ? $width / $height : null,
            'quality' => (float) ($this->configuration['quality'] ?? 0.92),
            'format' => $format,
        ];
    }
}
