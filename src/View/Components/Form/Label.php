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
        public bool $error = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.label');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return [
            'wrapper' => 'mb-1 flex justify-between text-gray-700',
            'text' => 'block text-sm font-medium',
            'error' => 'text-red-600',
        ];
    }
}
