<?php

namespace TasteUi\View\Components\Avatar;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;

class Index extends Component implements Customizable
{
    public function __construct(
        public string $label,
        public ?string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public bool $square = false,
        public bool $modelable = false,
        private ?string $size = null,
    ) {
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
    }

    public function render(): View
    {
        return view('taste-ui::components.avatar');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return [
            'wrapper' => Arr::toCssClasses([
                'inline-flex shrink-0 items-center justify-center overflow-hidden text-xl',
                'w-8 h-8' => $this->size === 'sm',
                'w-12 h-12' => $this->size === 'md',
                'w-14 h-14' => $this->size === 'lg',
                'rounded-full' => ! $this->square,
                TasteUi::colors()
                    ->set('bg', $this->color, 500)
                    ->merge('border', $this->color, 500)
                    ->get() => ! $this->modelable,
            ]),
            'content' => match ($this->modelable) {
                /* image */
                true => Arr::toCssClasses([
                    'shrink-0 object-cover object-center text-xl',
                    'w-8 h-8' => $this->size === 'sm',
                    'w-12 h-12' => $this->size === 'md',
                    'w-14 h-14' => $this->size === 'lg',
                    'rounded-full' => ! $this->square,
                ]),
                /* text */
                false => 'font-semibold text-white',
            },
        ];
    }
}
