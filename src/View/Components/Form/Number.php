<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\NumberRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.number')]
#[PassThroughRuntime(NumberRuntime::class)]
class Number extends TallStackUiComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?int $min = null,
        public ?int $max = null,
        public ?int $delay = 2,
        public ?bool $selectable = null,
        public ?bool $chevron = false,
        public ?bool $invalidate = null,
        public ?bool $centralized = null,
        public int|float $step = 1,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.number');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'buttons' => [
                'wrapper' => 'flex w-full items-center',
                'left' => [
                    'base' => 'inline-flex pr-3 items-center justify-center disabled:opacity-30 cursor-pointer',
                    'size' => 'ml-2 h-4 w-4',
                    'color' => 'dark:text-dark-400 text-gray-500',
                    'error' => 'text-red-500',
                ],
                'right' => [
                    'base' => 'inline-flex pl-3 items-center justify-center disabled:opacity-30 cursor-pointer',
                    'size' => 'mr-2 h-4 w-4',
                    'color' => 'dark:text-dark-400 text-gray-500',
                    'error' => 'text-red-500',
                ],
            ],
            'error' => $this->error(),
        ]);
    }
}
