<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Errors;

class ErrorsColors
{
    use OverrideColors;

    public function __construct(protected Errors $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        [$background, $text, $border] = $this->get('background', 'text', 'border');

        // We just need to $this->format when we
        // have a style and color, otherwise we
        // can just use the color as the getter.
        $getter = $this->component->color;

        return [
            'background' => data_get($background, $getter, data_get($this->background(), $getter)),
            'text' => data_get($text, $getter, data_get($this->text(), $getter)),
            'border' => data_get($border, $getter, data_get($this->border(), $getter)),
        ];
    }

    private function background(): array
    {
        return [
            'white' => 'bg-white',
            'black' => 'bg-black',
            'primary' => 'bg-primary-50 dark:bg-primary-900/70',
            'secondary' => 'bg-secondary-50 dark:bg-secondary-900/70',
            'slate' => 'bg-slate-50 dark:bg-slate-900/70',
            'gray' => 'bg-gray-50 dark:bg-gray-900/70',
            'zinc' => 'bg-zinc-50 dark:bg-zinc-900/70',
            'neutral' => 'bg-neutral-50 dark:bg-neutral-900/70',
            'stone' => 'bg-stone-50 dark:bg-stone-900/70',
            'red' => 'bg-red-50 dark:bg-red-900/70',
            'orange' => 'bg-orange-50 dark:bg-orange-900/70',
            'amber' => 'bg-amber-50 dark:bg-amber-900/70',
            'yellow' => 'bg-yellow-50 dark:bg-yellow-900/70',
            'lime' => 'bg-lime-50 dark:bg-lime-900/70',
            'green' => 'bg-green-50 dark:bg-green-900/70',
            'emerald' => 'bg-emerald-50 dark:bg-emerald-900/70',
            'teal' => 'bg-teal-50 dark:bg-teal-900/70',
            'cyan' => 'bg-cyan-50 dark:bg-cyan-900/70',
            'sky' => 'bg-sky-50 dark:bg-sky-900/70',
            'blue' => 'bg-blue-50 dark:bg-blue-900/70',
            'indigo' => 'bg-indigo-50 dark:bg-indigo-900/70',
            'violet' => 'bg-violet-50 dark:bg-violet-900/70',
            'purple' => 'bg-purple-50 dark:bg-purple-900/70',
            'fuchsia' => 'bg-fuchsia-50 dark:bg-fuchsia-900/70',
            'pink' => 'bg-pink-50 dark:bg-pink-900/70',
            'rose' => 'bg-rose-50 dark:bg-rose-900/70',
        ];
    }

    private function border(): array
    {
        return [
            'white' => 'border-b-black',
            'black' => 'border-b-white',
            'primary' => 'border-b-primary-200 dark:border-b-primary-900/70',
            'secondary' => 'border-b-secondary-200 dark:border-b-secondary-900/70',
            'slate' => 'border-b-slate-200 dark:border-b-slate-900/70',
            'gray' => 'border-b-gray-200 dark:border-b-gray-900/70',
            'zinc' => 'border-b-zinc-200 dark:border-b-zinc-900/70',
            'neutral' => 'border-b-neutral-200 dark:border-b-neutral-900/70',
            'stone' => 'border-b-stone-200 dark:border-b-stone-900/70',
            'red' => 'border-b-red-200 dark:border-b-red-900/70',
            'orange' => 'border-b-orange-200 dark:border-b-orange-900/70',
            'amber' => 'border-b-amber-200 dark:border-b-amber-900/70',
            'yellow' => 'border-b-yellow-200 dark:border-b-yellow-900/70',
            'lime' => 'border-b-lime-200 dark:border-b-lime-900/70',
            'green' => 'border-b-green-200 dark:border-b-green-900/70',
            'emerald' => 'border-b-emerald-200 dark:border-b-emerald-900/70',
            'teal' => 'border-b-teal-200 dark:border-b-teal-900/70',
            'cyan' => 'border-b-cyan-200 dark:border-b-cyan-900/70',
            'sky' => 'border-b-sky-200 dark:border-b-sky-900/70',
            'blue' => 'border-b-blue-200 dark:border-b-blue-900/70',
            'indigo' => 'border-b-indigo-200 dark:border-b-indigo-900/70',
            'violet' => 'border-b-violet-200 dark:border-b-violet-900/70',
            'purple' => 'border-b-purple-200 dark:border-b-purple-900/70',
            'fuchsia' => 'border-b-fuchsia-200 dark:border-b-fuchsia-900/70',
            'pink' => 'border-b-pink-200 dark:border-b-pink-900/70',
            'rose' => 'border-b-rose-200 dark:border-b-rose-900/70',
        ];
    }

    private function text(): array
    {
        return [
            'white' => 'text-black',
            'black' => 'text-white',
            'primary' => 'text-primary-700 dark:text-primary-300',
            'secondary' => 'text-secondary-700 dark:text-secondary-300',
            'slate' => 'text-slate-700 dark:text-slate-300',
            'gray' => 'text-gray-700 dark:text-gray-300',
            'zinc' => 'text-zinc-700 dark:text-zinc-300',
            'neutral' => 'text-neutral-700 dark:text-neutral-300',
            'stone' => 'text-stone-700 dark:text-stone-300',
            'red' => 'text-red-700 dark:text-red-300',
            'orange' => 'text-orange-700 dark:text-orange-300',
            'amber' => 'text-amber-700 dark:text-amber-300',
            'yellow' => 'text-yellow-700 dark:text-yellow-300',
            'lime' => 'text-lime-700 dark:text-lime-300',
            'green' => 'text-green-700 dark:text-green-300',
            'emerald' => 'text-emerald-700 dark:text-emerald-300',
            'teal' => 'text-teal-700 dark:text-teal-300',
            'cyan' => 'text-cyan-700 dark:text-cyan-300',
            'sky' => 'text-sky-700 dark:text-sky-300',
            'blue' => 'text-blue-700 dark:text-blue-300',
            'indigo' => 'text-indigo-700 dark:text-indigo-300',
            'violet' => 'text-violet-700 dark:text-violet-300',
            'purple' => 'text-purple-700 dark:text-purple-300',
            'fuchsia' => 'text-fuchsia-700 dark:text-fuchsia-300',
            'pink' => 'text-pink-700 dark:text-pink-300',
            'rose' => 'text-rose-700 dark:text-rose-300',
        ];
    }
}
