<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Components\Concerns\BuildRawIcon;
use TallStackUi\Foundation\TallStackUiComponent;

class Icon extends TallStackUiComponent
{
    use BuildRawIcon;

    public function __construct(
        public ?string $icon = null,
        public ?string $name = null,
        public bool $error = false,
        #[SkipDebug]
        public ?string $type = null,
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $right = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.icon');
    }
}
