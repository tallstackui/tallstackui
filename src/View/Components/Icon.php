<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;

class Icon extends Component
{
    protected const ACCEPTABLES = [
        'solid',
        'outline',
    ];

    public function __construct(
        public ?string $icon = null,
        public ?string $name = null,
        public bool $error = false,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $type = null,
    ) {
        $this->type ??= $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tasteui.icon'));

        if (! in_array($this->type, self::ACCEPTABLES)) {
            throw new InvalidArgumentException("The icon must be one of the following: [solid, outline]. Provided: [{$this->type}]");
        }
    }

    public function render(): View
    {
        return view('taste-ui::components.icon');
    }
}
