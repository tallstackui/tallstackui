<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

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
        $this->position = $this->position === 'right' ? 'right' : 'left';
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.password');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'base' => $this->tallStackUiInputClasses(),
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5 text-gray-400',
            ],
            'error' => 'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
        ]);
    }
}
