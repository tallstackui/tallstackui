<?php

namespace TallStackUi\View\Components\Form\Traits;

trait DefaultInputClasses
{
    private function error(...$rejects): string
    {
        $classes = 'dark:bg-dark-800 text-red-600 ring-red-300 placeholder:text-red-600 focus:ring-2 focus:ring-inset focus:ring-red-500 dark:ring-red-500';

        if (! empty($rejects)) {
            foreach ($rejects as $reject) {
                $classes = str_replace($reject, '', $classes);
            }
        }

        return trim($classes);
    }

    private function input(): array
    {
        return [
            'base' => 'form-input block w-full overflow-hidden rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset transition duration-150 ease-in-out sm:text-sm sm:leading-6',
            'color' => 'focus:ring-primary-600 dark:bg-dark-800 dark:placeholder-dark-500 dark:text-dark-300 dark:border-dark-900 dark:ring-dark-600 dark:focus:ring-primary-600 dark:read-only:bg-dark-600 disabled:bg-dark-600 text-gray-600 ring-gray-300 placeholder:text-gray-400 read-only:bg-gray-50 read-only:text-gray-500 read-only:ring-gray-200 focus:ring-2 focus:ring-inset disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200',
        ];
    }
}
