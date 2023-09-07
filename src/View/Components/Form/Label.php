<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Label extends Component
{
    public function __construct(
        public ?string $for = null,
        public ?string $label = null,
        public ?string $text = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.label');
    }
}
