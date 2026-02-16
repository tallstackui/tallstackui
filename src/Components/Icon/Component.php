<?php

namespace TallStackUi\Components\Icon;

use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Support\Concerns\BuildRawIcon;
use TallStackUi\TallStackUiComponent;

class Component extends TallStackUiComponent
{
    use BuildRawIcon;

    public function __construct(
        public ?string $icon = null,
        public ?string $name = null,
        public bool $error = false,
        #[SkipDebug]
        public ?string $type = null,
        #[SkipDebug]
        public ComponentSlot|string|null $left = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
        #[SkipDebug]
        public bool $internal = true,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.icon.main');
    }
}
