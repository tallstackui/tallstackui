<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

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
        return view('taste-ui::components.form.checkbox');
    }

    public function customize(bool $error = false): array
    {
        return [
            'main' => $this->customMainClasses($error),
        ];
    }

    public function customMainClasses(bool $error = false): string
    {
        return Arr::toCssClasses([
            'form-checkbox rounded transition ease-in-out duration-100',
            'border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400' => ! $error,
            'border-red-300 text-red-600 focus:ring-red-600 focus:border-red-400' => $error,
            'w-5 h-5' => $this->size === 'md',
            'w-6 h-6' => $this->size === 'lg',
        ]);
    }

    /**
     * TODO: it should be used when wrapper is ready to be personalized.
     */
    public function labelBaseClass(): string
    {
        return 'relative inline-flex cursor-pointer items-center';
    }

    /**
     * TODO: it should be used when wrapper is ready to be personalized.
     */
    public function labelElement(bool $error = false): array
    {
        $text = Arr::toCssClasses(['font-medium', 'text-gray-700' => ! $error, 'text-red-600' => $error]);

        return [
            'left' => 'mr-2 text-sm',
            'right' => 'ml-2 text-sm',
            'text' => $text,
        ];
    }
}
