<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Radio extends Component
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $position = 'right',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public bool $checked = false,
    ) {
        $this->id ??= uniqid();
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
    }

    public function render(): View
    {
        return view('taste-ui::components.form.radio');
    }
}
