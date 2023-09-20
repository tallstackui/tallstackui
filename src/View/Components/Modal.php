<?php

namespace TasteUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class Modal extends Component
{
    public function __construct(
        public ?string $title = null,
        public bool $blur = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.modal');
    }
}
