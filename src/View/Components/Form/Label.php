<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Label extends Component
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

    public function getBaseClass(): string
    {
        return 'mb-1 flex justify-between';
    }

    public function getLabelClass(bool $error = false): string
    {
        return Arr::toCssClasses([
            'block text-sm font-medium',
            'text-gray-700' => ! $error,
            'text-red-600' => $error,
        ]);
    }
}
