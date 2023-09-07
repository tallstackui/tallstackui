<?php

namespace TasteUi\View\Components;

use TasteUi\View\Components\Base\TasteUi;

class Icon extends TasteUi
{
    public function __construct(
        public ?string $name = null,
        public ?string $solid = null,
        public ?string $style = null,
    ) {
        $this->style = $this->solid !== null ? 'solid' : 'outline';
    }

    public function blade(): string
    {
        return 'components.icon';
    }
}
