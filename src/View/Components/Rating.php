<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Colors\Components\RatingColors;
use TallStackUi\Foundation\Support\Runtime\Components\RatingRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('rating')]
#[ColorsThroughOf(RatingColors::class)]
#[PassThroughRuntime(RatingRuntime::class)]
class Rating extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $icon = null,
        public ?int $quantity = 5,
        public float|int|null $rate = null,
        public ?string $text = null,
        public string $evaluateMethod = 'evaluate',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $static = false,
        public ?string $color = 'primary',
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $position = 'left',
    ) {
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.rating');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center gap-1',
            'button' => 'cursor-pointer transition hover:scale-125 has-[:focus]:scale-125',
            'text' => 'text-gray-700 dark:text-dark-300 font-medium',
            'star' => 'dark:text-dark-300 text-gray-300',
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
            __ts_validation_exception($this, 'The [evaluateMethod] is required.');
        }

        if ($this->quantity > 5) {
            __ts_validation_exception($this, 'The [quantity] of stars must be  equal or less than 5');
        }
    }
}
