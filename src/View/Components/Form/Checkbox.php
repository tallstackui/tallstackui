<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Checkbox extends Component
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
        $this->id ??= uniqid();
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
    }

    public function render(): View
    {
        return view('taste-ui::components.form.checkbox');
    }

    public function getBaseClass(): string
    {
        return Arr::toCssClasses([
            'form-checkbox rounded transition ease-in-out duration-100',
            'border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400',
            'w-5 h-5' => $this->md !== null,
            'w-6 h-6' => $this->lg !== null,
        ]);
    }

    public function getBaseLabel(): string
    {
        return 'relative inline-flex cursor-pointer items-center';
    }

    public function getBaseWrapper(?bool $error = false): array
    {
        $text = Arr::toCssClasses([
            'font-medium',
            'text-gray-700' => !$error,
            'text-red-600'  => $error,
        ]);

        return [
            'left'  => 'mr-2 text-sm',
            'right' => 'ml-2 text-sm',
            'text'  => $text,
        ];
    }
}
