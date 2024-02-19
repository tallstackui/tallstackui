<?php

namespace TallStackUi\View\Components\Step;

use Illuminate\Contracts\View\View;
use TallStackUi\View\Components\BaseComponent;

class Items extends BaseComponent
{
    public function __construct(
        public ?string $step = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?bool $completed = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.step.items');
    }
}
