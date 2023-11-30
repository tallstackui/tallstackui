<?php

namespace TallStackUi\View\Components\Form\Traits;

trait DefaultInputClasses
{
    private function error(...$rejects): string
    {
        $classes = 'text-red-600 ring-red-300 placeholder:text-red-600 focus-within:ring-red-500 focus-within:placeholder:text-red-600 focus:ring-red-500 focus-within:focus:ring-red-500 dark:ring-red-500 dark:ring-red-500 dark:focus-within:ring-red-500';

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
            'wrapper' => 'dark:bg-dark-800 focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md bg-white px-2 shadow-sm ring-1 ring-inset transition focus-within:ring-2 focus-within:ring-inset focus:ring-gray-300 sm:text-sm sm:leading-6',
            'base' => 'w-full border-0 bg-transparent p-1 py-1.5 ring-0 focus:ring-transparent sm:text-sm sm:leading-6',
            'slot' => 'm-1 flex select-none items-center whitespace-nowrap text-gray-500 transition sm:text-sm',
            'color' => 'dark:bg-dark-800 dark:ring-dark-600 dark:text-dark-300 dark:placeholder-dark-500 bg-white text-gray-600 ring-gray-300 placeholder:text-gray-400',
            'disabled' => 'bg-gray-50 text-gray-500 dark:bg-gray-600',
        ];
    }
}
 