<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.error')]
class Error extends BaseComponent implements Personalization
{
    public function __construct(public ?string $computed = null, public bool $error = false)
    {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.error');
    }

    public function personalization(): array
    {
        return ['text' => 'mt-2 text-sm font-medium text-red-500'];
    }
}
