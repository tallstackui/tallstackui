<?php

namespace TallStackUi\View\Components\Form;

use Exception;
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
        public ?bool $withoutPeriod = null,
        // TODO: validate
        public ?string $minHour = '0',
        public ?string $maxHour = '11',
        public ?string $minMinute = '0',
        public ?string $maxMinute = '59',
        public ?string $stepHour = '1',
        public ?string $stepMinute = '1',
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.timepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'range' => [
                'base' => 'h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-700',
                'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
            ],
        ]);
    }

    /** @throws Exception */
    protected function validate(): void
    {
        //        if ((int) $this->maxHour > 23) {
        //            throw new Exception('Max hour must be less than 24');
        //        }
        //
        //        if ((int) $this->maxMinute > 59) {
        //            throw new Exception('Max minute must be less than 60');
        //        }
        //
        //        if ((int) $this->minHour < 0) {
        //            throw new Exception('Min hour must be less than 24');
        //        }
        //
        //        if ((int) $this->minMinute < 0) {
        //            throw new Exception('Min minute must be less than 60');
        //        }
    }
}
