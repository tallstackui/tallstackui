<?php

namespace TasteUi\View\Components\Form;

use TasteUi\View\Components\Base\TasteUi;

class Input extends TasteUi
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = null,
        public ?string $placeholder = null,
    ) {
        //
    }

    public function blade(): string
    {
        return 'components.form.input';
    }
}
