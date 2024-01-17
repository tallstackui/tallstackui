<?php

namespace TallStackUi\View\Components\Form;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
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
        public ?bool $time = false,
        public ?bool $helpers = false,
        public ?string $format = null,
        public string|null|Carbon $min = null,
        public string|null|Carbon $max = null,
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

    protected function validate(): void
    {
        $min = null;
        $max = null;

        try {
            $min = Carbon::parse($this->min);
            $max = Carbon::parse($this->max);
        } catch (Exception) {
            //
        }

        if (blank($min) || blank($max)) {
            throw new InvalidArgumentException('The DatePicker [min|max] attribute must be a Carbon instance or a valid date string.');
        }
    }
}
