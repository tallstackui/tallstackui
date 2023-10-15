<?php

namespace TallStackUi\View\Components\Form\Traits;

trait DefaultInputClasses
{
    /**
     * Input, Password & Textarea Classes
     */
    private function inputClasses(): string
    {
        return 'focus:ring-primary-600 block w-full overflow-hidden border-0 py-1.5 text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition placeholder:text-gray-400 read-only:bg-gray-100 read-only:text-gray-500 read-only:ring-gray-200 focus:ring-2 focus:ring-inset disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6';
    }
}
