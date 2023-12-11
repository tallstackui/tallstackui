<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Foundation\Personalization\Traits\InteractWithValidations;

class Icon extends Component
{
    use InteractWithValidations;

    public function __construct(
        public ?string $icon = null,
        public ?string $name = null,
        public bool $error = false,
        public ?bool $solid = false,
        public ?bool $outline = false,
        public ?string $type = null,
        public ?string $left = null,
        public ?string $right = null,
    ) {
        $this->type = $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tallstackui.icon'));

        $this->validate();
    }

    public function render(): View
    {
        return view('tallstack-ui::components.icon');
    }
}
