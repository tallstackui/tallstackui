<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Checkbox extends Component implements Customizable
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $position = 'right',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public bool $checked = false,
    ) {
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.checkbox');
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
            'base' => Arr::toCssClasses([
                'form-checkbox rounded transition ease-in-out duration-100',
                'border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400',
                'w-5 h-5' => $this->size === 'md',
                'w-6 h-6' => $this->size === 'lg',
            ]),
            'error' => 'border border-red-300 text-red-600 focus:ring-red-600 focus:border-red-400',
        ]);
    }
}
