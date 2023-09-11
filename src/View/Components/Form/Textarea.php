<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public int $rows = 8,
        public ?bool $resize = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.textarea');
    }
}
