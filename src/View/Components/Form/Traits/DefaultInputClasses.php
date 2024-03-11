<?php

namespace TallStackUi\View\Components\Form\Traits;

trait DefaultInputClasses
{
    private function error(...$excepts): string
    {
        $classes = '!text-red-600 ring-red-300 placeholder:text-red-600 focus-within:ring-red-500 focus-within:placeholder:text-red-600 focus:ring-red-500 focus-within:focus:ring-red-500 dark:ring-red-500 dark:focus-within:ring-red-500';

        if ($excepts !== []) {
            foreach ($excepts as $except) {
                $classes = str_replace($except, '', $classes);
            }
        }

        return trim($classes);
    }

    private function input(): array
    {
        return [
            'wrapper' => 'focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md ring-1 transition focus-within:ring-2',
            'base' => 'dark:placeholder-dark-400 w-full rounded-md border-0 bg-transparent py-1.5 ring-0 placeholder:text-gray-400 focus:outline-none focus:ring-transparent sm:text-sm sm:leading-6',
            'slot' => 'dark:text-dark-400 flex select-none items-center whitespace-nowrap text-gray-500 transition sm:text-sm',
            'color' => [
                'base' => 'dark:ring-dark-600 dark:text-dark-300 text-gray-600 ring-gray-300',
                'background' => 'dark:bg-dark-800 bg-white',
                'disabled' => 'dark:bg-dark-600 bg-gray-100',
            ],
        ];
    }
}
