<?php

namespace TallStackUi\Components\Form\Number;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\FormDefaultInputClasses;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\NumberRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.number')]
#[PassThroughRuntime(NumberRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use FormDefaultInputClasses;

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
        return view('ts-ui::components.form.number');
    }

    public function customization(): array
    {
        return Arr::dot([
            'input' => [
                ...$this->input(),
                'centralized' => 'text-center',
                'caret' => 'caret-transparent',
                'appearance' => 'appearance-number-none',
                'wrapper-centralized' => 'justify-between',
            ],
            'buttons' => [
                'wrapper' => 'flex w-full items-center',
                'left' => [
                    'base' => 'inline-flex pr-3 items-center justify-center disabled:opacity-30 cursor-pointer',
                    'centralized' => 'order-first',
                    'size' => 'ml-2 h-4 w-4',
                    'color' => 'dark:text-dark-400 text-gray-500',
                    'error' => 'text-red-500',
                ],
                'right' => [
                    'base' => 'inline-flex pl-3 items-center justify-center disabled:opacity-30 cursor-pointer',
                    'separator' => 'border-l border-gray-200 dark:border-gray-600',
                    'size' => 'mr-2 h-4 w-4',
                    'color' => 'dark:text-dark-400 text-gray-500',
                    'error' => 'text-red-500',
                ],
            ],
            'error' => $this->error(),
        ]);
    }

    final public function mode(): string
    {
        if (is_null($this->min) || $this->min < 0) {
            return 'text';
        }

        if ($this->step && $this->step < 1) {
            return 'decimal';
        }

        return 'numeric';
    }

    final public function pattern(): string
    {
        if (is_null($this->min) || $this->min < 0) {
            return '-?[0-9]*[.,]?[0-9]*';
        }

        if ($this->step && $this->step < 1) {
            return '[0-9]*[.,]?[0-9]*';
        }

        return '[0-9]*';
    }
}
