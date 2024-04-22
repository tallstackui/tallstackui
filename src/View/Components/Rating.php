<?php

namespace TallStackUi\View\Components;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\View\ComponentSlot;
use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('rating')]
class Rating extends BaseComponent implements Personalization
{
    public function __construct(
        public ?int $star = 5,
        public float|int|null $rate = null,
        public ComponentSlot|string|null $text = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        #[SkipDebug]
        public ?string $size = null,
    ) {
       //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.rating');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }

    protected function validate(): void
    {
        if ($this->star > 5) {
            throw new InvalidArgumentException('The number of [star] in rating must be less than 5');
        }

    }
}
