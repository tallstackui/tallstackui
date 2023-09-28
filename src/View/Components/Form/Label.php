<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

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

    public function customization(bool $error = false): array
    {
        return [
            ...$this->tasteUiMainClasses($error),
        ];
    }

    public function tasteUiMainClasses(bool $error = false): array
    {
        return [
            'wrapper' => Arr::toCssClasses([
                'mb-1 flex justify-between',
                'text-gray-700' => ! $error,
                'text-red-600' => $error,
            ]),
            'text' => 'block text-sm font-medium',
        ];
    }
}
