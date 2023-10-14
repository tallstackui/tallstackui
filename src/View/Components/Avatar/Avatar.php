<?php

namespace TallStackUi\View\Components\Avatar;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalize;
use TallStackUi\Support\Personalizations\Traits\InternalColorPersonalizations;

class Avatar extends Component implements Personalize
{
    use InternalColorPersonalizations;

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
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => Arr::toCssClasses([
                'inline-flex shrink-0 items-center justify-center overflow-hidden text-xl',
                'w-8 h-8 text-xs' => $this->size === 'sm',
                'w-12 h-12 text-xl' => $this->size === 'md',
                'w-14 h-14 text-2xl' => $this->size === 'lg',
                // TODO: internal
                'rounded-full' => ! $this->square,
                'border-2' => ! $this->modelable,
            ]),
            'content' => [
                'image' => Arr::toCssClasses([
                    'shrink-0 object-cover object-center text-xl',
                    'w-8 h-8 text-sm' => $this->size === 'sm',
                    'w-12 h-12 text-xl' => $this->size === 'md',
                    'w-14 h-14 text-2xl' => $this->size === 'lg',
                    'rounded-full' => ! $this->square,
                ]),
                'text' => Arr::toCssClasses([
                    'font-semibold',
                    'text-white' => $this->color !== 'white',
                    'text-neutral' => $this->color === 'white' || $this->color === 'black',
                ]),
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.avatar');
    }
}
