<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\View\Components\BaseComponent;

class Items extends BaseComponent
{
    public function __construct(
        public ?string $tab = null,
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $right = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.tab.items');
    }
}
