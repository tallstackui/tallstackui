<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.input');
    }
}
