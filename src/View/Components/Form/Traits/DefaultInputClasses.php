<?php

namespace TallStackUi\View\Components\Form\Traits;

use Illuminate\Support\Arr;

trait DefaultInputClasses
{
    /**
     * Input, Password & Textarea Classes
     */
    private function inputClasses(): string
    {
        return Arr::toCssClasses([
            'block w-full border-0 py-1.5 text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition',
            'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6',
            'disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 overflow-hidden',
            'read-only:bg-gray-100 read-only:text-gray-500 read-only:ring-gray-200',
        ]);
    }
}
