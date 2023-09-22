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

    public function baseClass(bool $error = false): string
    {
        return Arr::toCssClasses([
            'mb-1 flex justify-between',
            'text-gray-700' => ! $error,
            'text-red-600' => $error,
        ]);
    }

    public function labelClass(): string
    {
        return 'block text-sm font-medium';
    }
}
