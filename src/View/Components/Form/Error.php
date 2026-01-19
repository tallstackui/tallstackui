<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('form.error')]
class Error extends TallStackUiComponent implements Personalization
{
    public function __construct(public ?string $property = null)
    {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.error');
    }

    public function personalization(): array
    {
        return ['text' => 'mt-1 block text-sm font-medium text-red-500'];
    }
}
