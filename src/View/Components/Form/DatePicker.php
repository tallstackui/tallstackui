<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.datepicker')]
class DatePicker extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?bool $invalidate = null,
        public ?bool $range = false,
        public ?bool $timePicker = false,
        public ?bool $helpers = false,
        public ?array $disabledDates = [],
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.datepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => ['class' => [...$this->input()]],
            'error' => $this->error(),
        ]);
    }
}
