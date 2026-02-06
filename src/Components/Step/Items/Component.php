<?php

namespace TallStackUi\Components\Step\Items;

use Illuminate\Contracts\View\View;
use TallStackUi\TallStackUiComponent;

class Component extends TallStackUiComponent
{
    public function __construct(
        public int $step,
        public ?string $title = null,
        public ?string $description = null,
        public ?bool $completed = false
    ) {
        //
    }

    public function blade(): View
    {
        return view()->file(__DIR__.'/view.blade.php');
    }
}
