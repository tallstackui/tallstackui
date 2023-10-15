<?php

namespace TallStackUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Button extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public ?string $color = 'primary',
        public ?string $square = null,
        public ?string $round = null,
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $solid = true,
        public ?bool $outline = false,
        public ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'outline-none inline-flex justify-center items-center group ease-in font-semibold transition gap-x-2 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed',
                'sizes' => [
                    'xs' => 'text-xs px-1 py-0.5',
                    'sm' => 'text-sm px-2 py-1',
                    'md' => 'text-base px-4 py-2',
                    'lg' => 'text-base px-6 py-3',
                ],
            ],
            'icon' => [
                'sizes' => [
                    'xs' => 'w-3 h-3',
                    'sm' => 'w-3 h-3',
                    'md' => 'w-4 h-4',
                    'lg' => 'w-5 h-5',
                ],
                'loading' => [
                    'sizes' => [
                        'xs' => 'w-3 h-3',
                        'sm' => 'w-3 h-3',
                        'md' => 'w-4 h-4',
                        'lg' => 'w-5 h-5',
                    ],
                ],
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.button.button');
    }
}
