<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Debugger extends Component
{
    public function __construct(public ?string $code = null)
    {
        //
    }

    public function render(): View
    {
        return view('tallstack-ui::components.debugger');
    }
}
