<?php

namespace TasteUi\View\Components\Form;

use TasteUi\View\Components\Base\TasteUi;

class Label extends TasteUi
{
    public function __construct(
        public ?string $text = null,
        public ?string $for = null,
    ) {
        //
    }

    public function blade(): string
    {
        return 'components.form.label';
    }
}
