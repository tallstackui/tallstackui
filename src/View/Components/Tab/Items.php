<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\View\Components\BaseComponent;

class Items extends BaseComponent
{
    public function __construct(
        public ?string $tab = null,
        #[SkipDebug]
        public ComponentSlot|string|null $left = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.tab.items');
    }
}
