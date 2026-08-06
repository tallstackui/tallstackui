<?php

namespace TallStackUi\Components\Traits;

use Illuminate\Support\Arr;

trait SelectionCustomization
{
    /** Blocks shared by both groups. `$shape` styles the control. */
    protected function selection(string $shape): array
    {
        return Arr::dot([
            'wrapper' => [
                'base' => 'w-full min-w-0',
                'legend' => 'dark:text-dark-400 mb-1 block text-sm font-semibold text-gray-600',
                'legend-error' => 'text-red-600 dark:text-red-500',
                'asterisk' => 'font-bold text-red-500 not-italic',
            ],
            'container' => [
                'list' => 'relative -space-y-px rounded-md',
                'card' => 'grid gap-3',
                'panel' => 'grid gap-3',
                'inline' => 'inline-flex -space-x-px rounded-md',
            ],
            'columns' => [
                1 => 'grid-cols-1',
                2 => 'grid-cols-1 sm:grid-cols-2',
                3 => 'grid-cols-1 sm:grid-cols-3',
                4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
            ],
            'item' => [
                'base' => 'dark:border-dark-700 group relative flex cursor-pointer border border-gray-200 has-focus-visible:z-10 has-focus-visible:ring-2 has-focus-visible:ring-primary-500 has-focus-visible:ring-offset-2 dark:has-focus-visible:ring-offset-dark-900',
                'list' => 'items-start gap-3 p-4 first:rounded-t-md last:rounded-b-md has-checked:z-10 sm:items-center',
                'card' => 'flex-col gap-2 rounded-lg p-4',
                'panel' => 'flex-col gap-2 rounded-lg p-4',
                'inline' => 'items-center justify-center gap-2 px-4 py-2 first:rounded-l-md last:rounded-r-md has-checked:z-10',
                'disabled' => 'cursor-not-allowed opacity-50',
                'error' => 'border-red-300 dark:border-red-500',
            ],
            'control' => [
                'base' => 'dark:border-dark-600/50 dark:bg-dark-800 border-1 shrink-0 border-gray-200 bg-white ring-0 ring-offset-0 focus:ring-0 focus:ring-offset-0',
                'shape' => $shape,
                'hidden' => 'sr-only',
                'sizes' => [
                    'xs' => 'h-3 w-3',
                    'sm' => 'h-4 w-4',
                    'md' => 'h-5 w-5',
                    'lg' => 'h-6 w-6',
                ],
                'spacing' => [
                    'left' => 'order-first',
                    'right' => 'order-last ml-auto',
                ],
            ],
            'check' => 'invisible absolute right-3 top-3 h-5 w-5 group-has-checked:visible',
            'content' => [
                'wrapper' => 'flex min-w-0 flex-1 flex-col',
                'header' => 'flex items-center gap-2',
                'label' => 'dark:text-dark-300 text-sm font-medium text-gray-900',
                'inline' => 'group-has-checked:text-white',
                'description' => 'dark:text-dark-400 mt-1 text-sm text-gray-500',
                'aside' => 'dark:text-dark-400 shrink-0 text-sm text-gray-500',
                'icon' => 'dark:text-dark-400 h-5 w-5 shrink-0 text-gray-500',
                'image' => 'h-8 w-8 shrink-0 rounded-full object-cover',
            ],
        ]);
    }
}
