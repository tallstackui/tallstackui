<?php

namespace TallStackUi\Components\Tab\Items;

use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Support\Runtime\Components\TabItemsRuntime;
use TallStackUi\TallStackUiComponent;

#[PassThroughRuntime(TabItemsRuntime::class)]
class Component extends TallStackUiComponent
{
    public function __construct(
        public ?string $tab = null,
        public ?string $title = null,
        public ?string $href = null,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
        #[SkipDebug]
        public ComponentSlot|string|null $left = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.tab.items');
    }
}
