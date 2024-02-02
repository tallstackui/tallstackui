<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.timepicker')]
class TimePicker extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $invalidate = null,
        public ?bool $helper = null,
        public ?string $format = '12',
        public ?string $stepHour = '1',
        public ?string $stepMinute = '1',
    ) {
        $this->format = $this->format === '12' ? '12' : '24';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.timepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'range' => [
                'base' => 'h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-dark-700',
                'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 dark:[&::-webkit-slider-thumb]:bg-dark-400 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
            ],
        ]);
    }
}
