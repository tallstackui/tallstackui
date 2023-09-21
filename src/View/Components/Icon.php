<?php

namespace TasteUi\View\Components;

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
        public ?bool $solid = null,
        public bool $error = false,
        public ?string $style = null,
    ) {
        $default = config('tasteui.icon');

        $this->style ??= $default ?? 'solid';

        if (! in_array($this->style, self::ACCEPTABLES)) {
            throw new InvalidArgumentException('The icon style must be one of the following: [solid, outline].');
        }
    }

    public function render(): View
    {
        return view('taste-ui::components.icon');
    }
}
