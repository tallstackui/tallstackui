<?php

namespace TasteUi\View\Components\Interactions;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dialog extends Component
{
    public function __construct(
        public ?string $zIndex = 'z-50',
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.interactions.dialog');
    }
}
