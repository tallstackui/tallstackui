<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
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
        public ?bool $selectable = null,
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

    final public function icons(): array
    {
        [$left, $right] = [
            $this->chevron ? 'chevron-down' : 'minus',
            $this->chevron ? 'chevron-up' : 'plus',
        ];

        return [
            'left' => TallStackUi::icon($left),
            'right' => TallStackUi::icon($right),
        ];
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'buttons' => [
                'wrapper' => 'flex w-full items-center',
                'left' => [
                    'base' => 'inline-flex pr-3 items-center justify-center disabled:opacity-30',
                    'size' => 'ml-2 h-4 w-4',
                    'color' => 'dark:text-dark-400 text-gray-500',
                    'error' => 'text-red-500',
                ],
                'right' => [
                    'base' => 'inline-flex pl-3 items-center justify-center disabled:opacity-30',
                    'size' => 'mr-2 h-4 w-4',
                    'color' => 'dark:text-dark-400 text-gray-500',
                    'error' => 'text-red-500',
                ],
            ],
            'error' => $this->error(),
        ]);
    }
}
