<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Traits\MergeAttributes;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.color')]
class Color extends BaseComponent implements Personalization
{
    use MergeAttributes;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $picker = false,
        public Collection|array $colors = [],
        #[SkipDebug]
        public ?string $mode = null,
        public ?bool $invalidate = null,
        public ?bool $selectable = null,
    ) {
        $this->mode = $this->picker ? 'picker' : 'range';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.color');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'selected' => [
                'wrapper' => 'flex items-center',
                'base' => 'dark:border-dark-700 h-6 w-6 rounded shadow',
            ],
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5',
            ],
            'box' => [
                'base' => 'shadow-xs dark:bg-dark-700 soft-scrollbar max-h-60 overflow-auto rounded-md bg-white py-4',
                'range' => [
                    'base' => 'mb-4 h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-600',
                    'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                ],
                'button' => [
                    'wrapper' => 'mx-auto flex w-[17rem] flex-wrap items-center justify-center gap-1',
                    'base' => 'rounded shadow-lg',
                    'color' => 'flex h-5 w-5 cursor-pointer items-center justify-center rounded',
                    'icon' => 'h-3 w-3',
                ],
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $colors = collect($this->colors);

        if ($colors->isEmpty()) {
            return;
        }

        $colors->each(function (string $color) {
            if (! str($color)->startsWith('#')) {
                throw new InvalidArgumentException('All the [colors] must starts with #');
            }
        });
    }
}
