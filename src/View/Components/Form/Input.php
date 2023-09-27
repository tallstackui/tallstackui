<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\View\Components\Form\Traits\DefaultInputClasses;

class Input extends Component implements Customizable
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.input');
    }

    public function customization(bool $error = false): array
    {
        return [
            ...$this->tasteUiMainClasses($error),
        ];
    }

    public function tasteUiMainClasses(bool $error = false): array
    {
        return Arr::dot([
            'base' => Arr::toCssClasses([
                $this->tasteUiInputClasses($error),
                'pl-10' => $this->icon && ($this->position === null || $this->position === 'left'),
            ]),
            'icon' => [
                'wrapper' => Arr::toCssClasses([
                    'pointer-events-none absolute inset-y-0 flex items-center text-secondary-500',
                    'left-0 pl-3' => $this->position === null || $this->position === 'left',
                    'right-0 pr-3' => $this->position === 'right',
                ]),
                'size' => 'h-5 w-5',
            ],
        ], 'main.');
    }
}
