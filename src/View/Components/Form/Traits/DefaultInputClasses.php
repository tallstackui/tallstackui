<?php

namespace TallStackUi\View\Components\Form\Traits;

use Illuminate\Support\Arr;
use TallStackUi\View\Components\Form\Textarea;

trait DefaultInputClasses
{
    private function tallStackUiInputClasses(): string
    {
        $round = rescue(fn () => $this->round, report: false);
        $textarea = $this instanceof Textarea;

        return Arr::toCssClasses([
            'block w-full border-0 py-1.5 text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition',
            'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6',
            'disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200',
            'read-only:bg-gray-100 read-only:text-gray-500 read-only:ring-gray-200',
            'rounded-md' => ! $this->square && ! $round,
            'rounded-full' => $round && ! $textarea,
        ]);
    }
}
