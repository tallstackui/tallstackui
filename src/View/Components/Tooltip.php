<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Support\Personalizations\Traits\InternalColorPersonalizations;

class Tooltip extends Component implements Customizable
{
    use InternalColorPersonalizations;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = 'question-mark-circle',
        public string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?bool $solid = false,
        public ?bool $outline = false,
        public ?string $size = null,
        public ?string $position = 'top',
        public ?string $style = null,
    ) {
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : 'sm');
        $this->style = $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tallstackui.icon'));
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tooltip');
    }

    public function tallStackUiClasses(): array
    {
        return [
            'wrapper' => 'inline-flex',
            'icon' => Arr::toCssClasses([
                'h-5 w-5' => $this->size === 'sm',
                'h-6 w-6' => $this->size === 'md',
                'h-7 w-7' => $this->size === 'lg',
            ]),
        ];
    }
}
