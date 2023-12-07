<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('form.number')]
class Number extends Component implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?int $min = null,
        public ?int $max = null,
        public ?int $delay = 2,
        public ?bool $chevron = false,
    ) {
        $this->id ??= uniqid();
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
                'wrapper' => 'flex items-center border-gray-200 -gap-y-px divide-x divide-gray-200 dark:divide-gray-700 dark:border-gray-700',
                'left' => [
                    'base' => 'inline-flex px-3 items-center justify-center',
                    'size' => 'w-4 h-4',
                    'color' => 'text-gray-800 dark:text-white',
                    'error' => 'text-red-500',
                ],
                'right' => [
                    'base' => 'inline-flex px-3 items-center justify-center',
                    'size' => 'w-4 h-4',
                    'color' => 'text-gray-800 dark:text-white',
                    'error' => 'text-red-500',
                ],
            ],
            'error' => $this->error(),
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.number');
    }
}
