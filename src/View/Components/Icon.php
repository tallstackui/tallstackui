<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SkipDebug;

class Icon extends BaseComponent
{
    public function __construct(
        public ?string $icon = null,
        public ?string $name = null,
        public bool $error = false,
        public ?bool $solid = false,
        public ?bool $outline = false,
        #[SkipDebug]
        public ?string $type = null,
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $right = null,
    ) {
        $this->type = $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tallstackui.icon', 'solid')); //TODO: icons
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.icon');
    }
}
