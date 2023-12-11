<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Icon extends BaseComponent
{
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
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.icon');
    }

    protected function validate(): void
    {
        if (in_array($this->type, ['solid', 'outline'])) {
            return;
        }

        throw new InvalidArgumentException('The icon must be one of the following: [solid, outline]');
    }
}
