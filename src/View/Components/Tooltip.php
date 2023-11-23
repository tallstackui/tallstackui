<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

#[SoftPersonalization('tooltip')]
class Tooltip extends Component implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = 'question-mark-circle',
        public string $color = 'primary',
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $solid = false,
        public ?bool $outline = false,
        public ?string $size = null,
        public ?bool $right = null,
        public ?bool $bottom = null,
        public ?bool $left = null,
        public ?string $style = null,
        public ?string $position = null,
    ) {
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : 'sm');
        $this->style = $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tallstackui.icon'));
        $this->position = $this->right ? 'right' : ($this->bottom ? 'bottom' : ($this->left ? 'left' : 'top'));

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'color' => 'bg-black',
            'sizes' => [
                'sm' => 'h-5 w-5',
                'md' => 'h-6 w-6',
                'lg' => 'h-7 w-7',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tooltip');
    }
}
