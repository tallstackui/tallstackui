<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\View\Components\Form\Traits\DefaultInputClasses;

class Password extends Component implements Customizable
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = 'eye',
        public ?string $position = 'right',
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.password');
    }

    public function customization(bool $error = false): array
    {
        return [
            'main' => $this->customMainClasses($error),
            'icon' => $this->customIconClasses($error),
        ];
    }

    public function customMainClasses(bool $error = false): string
    {
        return $this->tasteUiInputClasses($error);
    }

    public function customIconClasses(bool $error = false): array
    {
        return [
            'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
            'class' => Arr::toCssClasses(['h-5 w-5', 'text-gray-400' => ! $error]),
        ];
    }
}
