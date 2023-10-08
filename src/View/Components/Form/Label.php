<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Label extends Component implements Customizable
{
    public function __construct(
        public ?string $for = null,
        public ?string $label = null,
        public ?string $text = null,
        public bool    $error = false,
    )
    {
        //
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.label');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function tallStackUiClasses(): array
    {
        return [
            'wrapper' => 'flex justify-between font-medium text-gray-600',
            'text' => 'block text-sm font-semibold',
            'error' => 'text-red-600',
        ];
    }
}
