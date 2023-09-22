<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use InvalidArgumentException;

class Radio extends Component
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
        $this->size ??= $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');

        if (! in_array($this->size, ['sm', 'md', 'lg'])) {
            throw new InvalidArgumentException('Invalid size. Allowed values are: sm, md, lg.');
        }
    }

    public function render(): View
    {
        return view('taste-ui::components.form.radio');
    }

    public function getBaseClass(bool $error = false): string
    {
        return Arr::toCssClasses([
            'form-radio rounded-full transition ease-in-out duration-100',
            'border-secondary-300 focus:ring-primary-600 focus:border-primary-400 text-primary-600' => ! $error,
            'border-red-300 focus:ring-red-600 focus:border-red-400 text-red-600' => $error,
            'w-5 h-5' => $this->size === 'md',
            'w-6 h-6' => $this->size === 'lg',
        ]);
    }
}
