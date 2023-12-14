<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Banner;

class BannerColors
{
    use OverrideColors;

    public function __construct(protected Banner $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        // If the banner color is an array then the colors will
        // be set directly in the component as hexadecimal.
        if (is_array($this->component->color)) {
            return [];
        }

        [$background, $text] = $this->get('background', 'text');

        $getter = $this->format($this->component->style, $this->component->color);

        return [
            'background' => data_get($background, $getter, data_get($this->background(), $getter)),
            'text' => data_get($text, $getter, data_get($this->text(), $getter)),
        ];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'white' => 'bg-white border border-gray-100',
                'black' => 'bg-black',
                'primary' => 'bg-primary-600 dark:bg-opacity-70',
                'secondary' => 'bg-secondary-600 dark:bg-opacity-70',
                'slate' => 'bg-slate-600 dark:bg-opacity-70',
                'gray' => 'bg-gray-600 dark:bg-opacity-70',
                'zinc' => 'bg-zinc-600 dark:bg-opacity-70',
                'neutral' => 'bg-neutral-600 dark:bg-opacity-70',
                'stone' => 'bg-stone-600 dark:bg-opacity-70',
                'red' => 'bg-red-600 dark:bg-opacity-70',
                'orange' => 'bg-orange-600 dark:bg-opacity-70',
                'amber' => 'bg-amber-600 dark:bg-opacity-70',
                'yellow' => 'bg-yellow-600 dark:bg-opacity-70',
                'lime' => 'bg-lime-600 dark:bg-opacity-70',
                'green' => 'bg-green-600 dark:bg-opacity-70',
                'emerald' => 'bg-emerald-600 dark:bg-opacity-70',
                'teal' => 'bg-teal-600 dark:bg-opacity-70',
                'cyan' => 'bg-cyan-600 dark:bg-opacity-70',
                'sky' => 'bg-sky-600 dark:bg-opacity-70',
                'blue' => 'bg-blue-600 dark:bg-opacity-70',
                'indigo' => 'bg-indigo-600 dark:bg-opacity-70',
                'violet' => 'bg-violet-600 dark:bg-opacity-70',
                'purple' => 'bg-purple-600 dark:bg-opacity-70',
                'fuchsia' => 'bg-fuchsia-600 dark:bg-opacity-70',
                'pink' => 'bg-pink-600 dark:bg-opacity-70',
                'rose' => 'bg-rose-600 dark:bg-opacity-70',
            ],
            'light' => [
                'white' => 'bg-white border border-gray-100',
                'black' => 'bg-black-300',
                'primary' => 'bg-primary-300 dark:bg-primary-600 dark:bg-opacity-30',
                'secondary' => 'bg-secondary-300 dark:bg-secondary-600 dark:bg-opacity-30',
                'slate' => 'bg-slate-300 dark:bg-slate-600 dark:bg-opacity-30',
                'gray' => 'bg-gray-300 dark:bg-gray-600 dark:bg-opacity-30',
                'zinc' => 'bg-zinc-300 dark:bg-zinc-600 dark:bg-opacity-30',
                'neutral' => 'bg-neutral-300 dark:bg-neutral-600 dark:bg-opacity-30',
                'stone' => 'bg-stone-300 dark:bg-stone-600 dark:bg-opacity-30',
                'red' => 'bg-red-300 dark:bg-red-600 dark:bg-opacity-30',
                'orange' => 'bg-orange-300 dark:bg-orange-600 dark:bg-opacity-30',
                'amber' => 'bg-amber-300 dark:bg-amber-600 dark:bg-opacity-30',
                'yellow' => 'bg-yellow-300 dark:bg-yellow-600 dark:bg-opacity-30',
                'lime' => 'bg-lime-300 dark:bg-lime-600 dark:bg-opacity-30',
                'green' => 'bg-green-300 dark:bg-green-600 dark:bg-opacity-30',
                'emerald' => 'bg-emerald-300 dark:bg-emerald-600 dark:bg-opacity-30',
                'teal' => 'bg-teal-300 dark:bg-teal-600 dark:bg-opacity-30',
                'cyan' => 'bg-cyan-300 dark:bg-cyan-600 dark:bg-opacity-30',
                'sky' => 'bg-sky-300 dark:bg-sky-600 dark:bg-opacity-30',
                'blue' => 'bg-blue-300 dark:bg-blue-600 dark:bg-opacity-30',
                'indigo' => 'bg-indigo-300 dark:bg-indigo-600 dark:bg-opacity-30',
                'violet' => 'bg-violet-300 dark:bg-violet-600 dark:bg-opacity-30',
                'purple' => 'bg-purple-300 dark:bg-purple-600 dark:bg-opacity-30',
                'fuchsia' => 'bg-fuchsia-300 dark:bg-fuchsia-600 dark:bg-opacity-30',
                'pink' => 'bg-pink-300 dark:bg-pink-600 dark:bg-opacity-30',
                'rose' => 'bg-rose-300 dark:bg-rose-600 dark:bg-opacity-30',
            ],
        ];
    }

    private function text(): array
    {
        return [
            'solid' => [
                'white' => 'text-black',
                'black' => 'text-white',
                'primary' => 'text-primary-50',
                'secondary' => 'text-secondary-50',
                'slate' => 'text-slate-50',
                'gray' => 'text-gray-50',
                'zinc' => 'text-zinc-50',
                'neutral' => 'text-neutral-50',
                'stone' => 'text-stone-50',
                'red' => 'text-red-50',
                'orange' => 'text-orange-50',
                'amber' => 'text-amber-50',
                'yellow' => 'text-yellow-50',
                'lime' => 'text-lime-50',
                'green' => 'text-green-50',
                'emerald' => 'text-emerald-50',
                'teal' => 'text-teal-50',
                'cyan' => 'text-cyan-50',
                'sky' => 'text-sky-50',
                'blue' => 'text-blue-50',
                'indigo' => 'text-indigo-50',
                'violet' => 'text-violet-50',
                'purple' => 'text-purple-50',
                'fuchsia' => 'text-fuchsia-50',
                'pink' => 'text-pink-50',
                'rose' => 'text-rose-50',
            ],
            'light' => [
                'white' => 'text-black',
                'black' => 'text-black',
                'primary' => 'text-primary-600 dark:text-primary-300',
                'secondary' => 'text-secondary-600 dark:text-secondary-300',
                'slate' => 'text-slate-600 dark:text-slate-300',
                'gray' => 'text-gray-600 dark:text-gray-300',
                'zinc' => 'text-zinc-600 dark:text-zinc-300',
                'neutral' => 'text-neutral-600 dark:text-neutral-300',
                'stone' => 'text-stone-600 dark:text-stone-300',
                'red' => 'text-red-600 dark:text-red-300',
                'orange' => 'text-orange-600 dark:text-orange-300',
                'amber' => 'text-amber-600 dark:text-amber-300',
                'yellow' => 'text-yellow-600 dark:text-yellow-300',
                'lime' => 'text-lime-600 dark:text-lime-300',
                'green' => 'text-green-600 dark:text-green-300',
                'emerald' => 'text-emerald-600 dark:text-emerald-300',
                'teal' => 'text-teal-600 dark:text-teal-300',
                'cyan' => 'text-cyan-600 dark:text-cyan-300',
                'sky' => 'text-sky-600 dark:text-sky-300',
                'blue' => 'text-blue-600 dark:text-blue-300',
                'indigo' => 'text-indigo-600 dark:text-indigo-300',
                'violet' => 'text-violet-600 dark:text-violet-300',
                'purple' => 'text-purple-600 dark:text-purple-300',
                'fuchsia' => 'text-fuchsia-600 dark:text-fuchsia-300',
                'pink' => 'text-pink-600 dark:text-pink-300',
                'rose' => 'text-rose-600 dark:text-rose-300',
            ],
        ];
    }
}
