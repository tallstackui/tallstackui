<?php

namespace TallStackUi\View\Components\DatePicker;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('datepicker')]
class DatePicker extends BaseComponent implements Personalization
{
    public function __construct(
        
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.datepicker.datepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }
}
