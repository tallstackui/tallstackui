<?php

namespace TallStackUi\View\Components\Avatar;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Avatar extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(
        public ?string $text = null,
        public ?string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public bool $square = false,
        public bool $modelable = false,
        public ?string $size = null,
    ) {
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'inline-flex shrink-0 items-center justify-center overflow-hidden text-xl',
                'sizes' => [
                    'sm' => 'w-8 h-8 text-xs',
                    'md' => 'w-12 h-12 text-xl',
                    'lg' => 'w-14 h-14 text-2xl',
                ],
            ],
            'content' => [
                'image' => [
                    'class' => 'shrink-0 object-cover object-center text-xl',
                    'sizes' => [
                        'sm' => 'w-8 h-8 text-sm',
                        'md' => 'w-12 h-12 text-xl',
                        'lg' => 'w-14 h-14 text-2xl',
                    ],
                ],
                'text' => [
                    'class' => 'font-semibold',
                    'colors' => [
                        'colorful' => 'text-white',
                        'white-black' => 'text-neutral',
                    ],
                ],
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.avatar');
    }
}
