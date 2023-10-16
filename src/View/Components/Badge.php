<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Badge extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'right',
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $solid = null,
        public ?bool $outline = null,
        public ?string $color = 'primary',
        public ?bool $square = false,
        public ?bool $round = false,
        public ?string $size = null,
        public ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : 'sm');
        $this->position = $this->position === 'right' ? 'right' : 'left';

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'outline-none inline-flex items-center border px-2 py-0.5 font-bold',
                'sizes' => [
                    'sm' => 'text-xs',
                    'md' => 'text-sm',
                    'lg' => 'text-md',
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
