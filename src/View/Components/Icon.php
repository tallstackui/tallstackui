<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Support\Concerns\BuildRawIcon;
use TallStackUi\TallStackUiComponent;

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
        #[SkipDebug]
        public bool $internal = true,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.icon');
    }
}
