<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.number')]
class Number extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?int $min = null,
        public ?int $max = null,
        public ?int $delay = 2,
        public ?bool $chevron = false,
        public ?bool $invalidate = null,
        public ?bool $centralized = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.number');
    }

    public function icons(): array
    {
        [$left, $right] = [
            $this->chevron ? 'chevron-down' : 'minus',
            $this->chevron ? 'chevron-up' : 'plus',
        ];

        return [
            'left' => $left,
            'right' => $right,
        ];
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => ['class' => [...$this->input()]],
            'buttons' => [
                'wrapper' => 'flex w-full items-center border-gray-200 dark:border-gray-700',
                'left' => [
                    'base' => 'inline-flex px-3 items-center justify-center disabled:opacity-30',
                    'size' => 'w-4 h-4',
                    'color' => 'text-gray-800 dark:text-white',
                    'error' => 'text-red-500',
                ],
                'right' => [
                    'base' => 'inline-flex px-3 items-center justify-center disabled:opacity-30',
                    'size' => 'w-4 h-4',
                    'color' => 'text-gray-800 dark:text-white',
                    'error' => 'text-red-500',
                ],
            ],
            'error' => $this->error(),
        ]);
    }
}
