<?php

namespace TallStackUi\Components\Form\Error;

use Illuminate\Contracts\View\View;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.error')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(public ?string $property = null)
    {
        //
    }

    public function blade(): View
    {
        return view()->file(__DIR__.'/view.blade.php');
    }

    public function customization(): array
    {
        return ['text' => 'mt-1 block text-sm font-medium text-red-500'];
    }
}
