<?php

namespace TallStackUi\View\Components\Form\Traits;

trait DefaultInputClasses
{
    private function tallStackUiInputClasses(): string
    {
        return 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 transition';
    }
}
