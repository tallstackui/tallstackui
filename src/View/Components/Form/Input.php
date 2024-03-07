<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.input')]
class Input extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        #[SkipDebug]
        public ?string $prefix = null,
        #[SkipDebug]
        public ?string $suffix = null,
        public ?bool $invalidate = null,
        #[SkipDebug]
        public ?string $position = 'left',
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.input');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                ...$this->input(),
                'paddings' => [
                    'left' => 'pl-8',
                    'right' => 'pr-8',
                ],
            ],
            'icon' => [
                'wrapper' => 'pointer-events-none absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-400',
                'paddings' => [
                    'left' => 'left-0 pl-2',
                    'right' => 'right-0 pr-2',
                ],
                'size' => 'h-5 w-5',
                'color' => 'text-gray-500 dark:text-dark-400',
            ],
            'error' => $this->error(),
        ]);
    }
}
