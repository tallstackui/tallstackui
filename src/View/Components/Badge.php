<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Traits\InteractWithProviders;

#[SoftPersonalization('badge')]
class Badge extends Component implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'right',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        public ?bool $square = false,
        public ?bool $round = false,
        public ?string $size = null,
        public ?bool $solid = true,
        public ?bool $outline = null,
        public ?bool $light = null,
        public ?string $style = null,
        public ?string $left = null,
        public ?string $right = null,
    ) {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->sm ? 'sm' : 'xs'));
        $this->position = $this->position === 'right' ? 'right' : 'left';

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'outline-none inline-flex items-center border px-2 py-0.5 font-bold',
                'sizes' => [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-md',
                    'lg' => 'text-lg',
                ],
            ],
            'icon' => 'h-3 w-3',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.badge');
    }
}
