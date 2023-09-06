<?php

namespace TasteUi\View\Components\Base;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

abstract class TasteUi extends Component
{
    abstract public function blade(): string;

    public function render(): View
    {
        return view('taste-ui::'.$this->blade());
    }
}
