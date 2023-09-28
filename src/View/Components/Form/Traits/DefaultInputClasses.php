<?php

namespace TasteUi\View\Components\Form\Traits;

use Illuminate\Support\Arr;

trait DefaultInputClasses
{
    private function tasteUiInputClasses(bool $error = false): string
    {
        $validate = ! property_exists($this, 'validate') || $this->validate;

        return Arr::toCssClasses([
            'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300',
            'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 transition',
            'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500' => $error && $validate,
        ]);
    }
}
