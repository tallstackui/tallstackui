<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('rating')]
class Rating extends BaseComponent implements Personalization
{
    public function __construct(
        public ?int $quantity = 5,
        public float|int|null $rate = null,
        public ComponentSlot|string|null $text = null,
        public string $evaluateMethod = 'evaluate',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
        $this->validate();
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.rating');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center gap-1',
            'button' => 'has-[:focus]:scale-125 cursor-pointer transition hover:scale-125',
            'text' => 'font-medium',
            'sizes' => [
                'xs' => 'h-4 w-4',
                'sm' => 'h-5 w-5',
                'md' => 'h-6 w-6',
                'lg' => 'h-7 w-7',
            ],
        ]);
    }

    protected function validate(): void
    {
        if (blank($this->evaluateMethod)) {
            throw new InvalidArgumentException('The evaluate [evaluateMethod] is required.');
        }

        if ($this->quantity > 5) {
            throw new InvalidArgumentException('The [quantity] of star in rating must be less than 5');
        }

    }
}
