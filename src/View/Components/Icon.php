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
        public ?bool $solid = false,
        public ?bool $outline = false,
        public ?string $type = null,
    ) {
        $this->type ??= $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tallstackui.icon'));

        if (! in_array($this->type, self::ACCEPTABLES)) {
            throw new InvalidArgumentException('The icon must be one of the following: [solid, outline]');
        }
    }

    public function render(): View
    {
        return view('tallstack-ui::components.icon');
    }
}
