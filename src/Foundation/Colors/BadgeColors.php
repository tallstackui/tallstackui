<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Badge;

class BadgeColors
{
    use OverrideColors;

    public function __construct(protected Badge $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        [$background, $text, $icon] = $this->get('background', 'text', 'icon');

        $getter = $this->format($this->component->style, $this->component->color);

        return [
            'background' => data_get($background, $getter, data_get($this->background(), $getter)),
            'text' => data_get($text, $getter, data_get($this->text(), $getter)),
            'icon' => data_get($icon, $getter, data_get($this->icon(), $getter)),
        ];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'white' => 'border-white bg-white',
                'black' => 'border-black bg-black dark:border-transparent',
                'primary' => 'border-primary-500 bg-primary-500 dark:bg-primary-700 dark:bg-opacity-80 dark:border-transparent',
                'secondary' => 'border-secondary-500 bg-secondary-500 dark:bg-secondary-700 dark:bg-opacity-80 dark:border-transparent',
                'slate' => 'border-slate-500 bg-slate-500 dark:bg-slate-700 dark:bg-opacity-80 dark:border-transparent',
                'gray' => 'border-gray-500 bg-gray-500 dark:bg-gray-700 dark:bg-opacity-80 dark:border-transparent',
                'zinc' => 'border-zinc-500 bg-zinc-500 dark:bg-zinc-700 dark:bg-opacity-80 dark:border-transparent',
                'neutral' => 'border-neutral-500 bg-neutral-500 dark:bg-neutral-700 dark:bg-opacity-80 dark:border-transparent',
                'stone' => 'border-stone-500 bg-stone-500 dark:bg-stone-700 dark:bg-opacity-80 dark:border-transparent',
                'red' => 'border-red-500 bg-red-500 dark:bg-red-700 dark:bg-opacity-80 dark:border-transparent',
                'orange' => 'border-orange-500 bg-orange-500 dark:bg-orange-700 dark:bg-opacity-80 dark:border-transparent',
                'amber' => 'border-amber-500 bg-amber-500 dark:bg-amber-700 dark:bg-opacity-80 dark:border-transparent',
                'yellow' => 'border-yellow-500 bg-yellow-500 dark:bg-yellow-700 dark:bg-opacity-80 dark:border-transparent',
                'lime' => 'border-lime-500 bg-lime-500 dark:bg-lime-700 dark:bg-opacity-80 dark:border-transparent',
                'green' => 'border-green-500 bg-green-500 dark:bg-green-700 dark:bg-opacity-80 dark:border-transparent',
                'emerald' => 'border-emerald-500 bg-emerald-500 dark:bg-emerald-700 dark:bg-opacity-80 dark:border-transparent',
                'teal' => 'border-teal-500 bg-teal-500 dark:bg-teal-700 dark:bg-opacity-80 dark:border-transparent',
                'cyan' => 'border-cyan-500 bg-cyan-500 dark:bg-cyan-700 dark:bg-opacity-80 dark:border-transparent',
                'sky' => 'border-sky-500 bg-sky-500 dark:bg-sky-700 dark:bg-opacity-80 dark:border-transparent',
                'blue' => 'border-blue-500 bg-blue-500 dark:bg-blue-700 dark:bg-opacity-80 dark:border-transparent',
                'indigo' => 'border-indigo-500 bg-indigo-500 dark:bg-indigo-700 dark:bg-opacity-80 dark:border-transparent',
                'violet' => 'border-violet-500 bg-violet-500 dark:bg-violet-700 dark:bg-opacity-80 dark:border-transparent',
                'purple' => 'border-purple-500 bg-purple-500 dark:bg-purple-700 dark:bg-opacity-80 dark:border-transparent',
                'fuchsia' => 'border-fuchsia-500 bg-fuchsia-500 dark:bg-fuchsia-700 dark:bg-opacity-80 dark:border-transparent',
                'pink' => 'border-pink-500 bg-pink-500 dark:bg-pink-700 dark:bg-opacity-80 dark:border-transparent',
                'rose' => 'border-rose-500 bg-rose-500 dark:bg-rose-700 dark:bg-opacity-80 dark:border-transparent',
            ],
            'outline' => [
                'white' => 'border-white text-white bg-transparent',
                'black' => 'border-black bg-transparent',
                'primary' => 'border-primary-600 bg-transparent',
                'secondary' => 'border-secondary-600 bg-transparent',
                'slate' => 'border-slate-600 bg-transparent',
                'gray' => 'border-gray-600 bg-transparent',
                'zinc' => 'border-zinc-600 bg-transparent',
                'neutral' => 'border-neutral-600 bg-transparent',
                'stone' => 'border-stone-600 bg-transparent',
                'red' => 'border-red-600 bg-transparent',
                'orange' => 'border-orange-600 bg-transparent',
                'amber' => 'border-amber-600 bg-transparent',
                'yellow' => 'border-yellow-600 bg-transparent',
                'lime' => 'border-lime-600 bg-transparent',
                'green' => 'border-green-600 bg-transparent',
                'emerald' => 'border-emerald-600 bg-transparent',
                'teal' => 'border-teal-600 bg-transparent',
                'cyan' => 'border-cyan-600 bg-transparent',
                'sky' => 'border-sky-600 bg-transparent',
                'blue' => 'border-blue-600 bg-transparent',
                'indigo' => 'border-indigo-600 bg-transparent',
                'violet' => 'border-violet-600 bg-transparent',
                'purple' => 'border-purple-600 bg-transparent',
                'fuchsia' => 'border-fuchsia-600 bg-transparent',
                'pink' => 'border-pink-600 bg-transparent',
                'rose' => 'border-rose-600 bg-transparent',
            ],
            'light' => [
                'white' => 'border-white text-black bg-white',
                'black' => 'border-black-300 bg-black-300 dark:bg-black-700 dark:bg-opacity-30 dark:border-transparent',
                'primary' => 'border-primary-300 bg-primary-300 dark:bg-primary-700 dark:bg-opacity-30 dark:border-transparent',
                'secondary' => 'border-secondary-300 bg-secondary-300 dark:bg-secondary-700 dark:bg-opacity-30 dark:border-transparent',
                'slate' => 'border-slate-300 bg-slate-300 dark:bg-slate-700 dark:bg-opacity-30 dark:border-transparent',
                'gray' => 'border-gray-300 bg-gray-300 dark:bg-gray-700 dark:bg-opacity-30 dark:border-transparent',
                'zinc' => 'border-zinc-300 bg-zinc-300 dark:bg-zinc-700 dark:bg-opacity-30 dark:border-transparent',
                'neutral' => 'border-neutral-300 bg-neutral-300 dark:bg-neutral-700 dark:bg-opacity-30 dark:border-transparent',
                'stone' => 'border-stone-300 bg-stone-300 dark:bg-stone-700 dark:bg-opacity-30 dark:border-transparent',
                'red' => 'border-red-300 bg-red-300 dark:bg-red-700 dark:bg-opacity-30 dark:border-transparent',
                'orange' => 'border-orange-300 bg-orange-300 dark:bg-orange-700 dark:bg-opacity-30 dark:border-transparent',
                'amber' => 'border-amber-300 bg-amber-300 dark:bg-amber-700 dark:bg-opacity-30 dark:border-transparent',
                'yellow' => 'border-yellow-300 bg-yellow-300 dark:bg-yellow-700 dark:bg-opacity-30 dark:border-transparent',
                'lime' => 'border-lime-300 bg-lime-300 dark:bg-lime-700 dark:bg-opacity-30 dark:border-transparent',
                'green' => 'border-green-300 bg-green-300 dark:bg-green-700 dark:bg-opacity-30 dark:border-transparent',
                'emerald' => 'border-emerald-300 bg-emerald-300 dark:bg-emerald-700 dark:bg-opacity-30 dark:border-transparent',
                'teal' => 'border-teal-300 bg-teal-300 dark:bg-teal-700 dark:bg-opacity-30 dark:border-transparent',
                'cyan' => 'border-cyan-300 bg-cyan-300 dark:bg-cyan-700 dark:bg-opacity-30 dark:border-transparent',
                'sky' => 'border-sky-300 bg-sky-300 dark:bg-sky-700 dark:bg-opacity-30 dark:border-transparent',
                'blue' => 'border-blue-300 bg-blue-300 dark:bg-blue-700 dark:bg-opacity-30 dark:border-transparent',
                'indigo' => 'border-indigo-300 bg-indigo-300 dark:bg-indigo-700 dark:bg-opacity-30 dark:border-transparent',
                'violet' => 'border-violet-300 bg-violet-300 dark:bg-violet-700 dark:bg-opacity-30 dark:border-transparent',
                'purple' => 'border-purple-300 bg-purple-300 dark:bg-purple-700 dark:bg-opacity-30 dark:border-transparent',
                'fuchsia' => 'border-fuchsia-300 bg-fuchsia-300 dark:bg-fuchsia-700 dark:bg-opacity-30 dark:border-transparent',
                'pink' => 'border-pink-300 bg-pink-300 dark:bg-pink-700 dark:bg-opacity-30 dark:border-transparent',
                'rose' => 'border-rose-300 bg-rose-300 dark:bg-rose-700 dark:bg-opacity-30 dark:border-transparent',
            ],
        ];
    }

    private function icon(): array
    {
        return $this->text();
    }

    private function text(): array
    {
        return [
            'solid' => [
                'white' => 'text-black',
                'black' => 'text-black-50',
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
            'outline' => [
                'white' => 'text-black',
                'black' => 'text-black',
                'primary' => 'text-primary-600',
                'secondary' => 'text-secondary-600',
                'slate' => 'text-slate-600',
                'gray' => 'text-gray-600',
                'zinc' => 'text-zinc-600',
                'neutral' => 'text-neutral-600',
                'stone' => 'text-stone-600',
                'red' => 'text-red-600',
                'orange' => 'text-orange-600',
                'amber' => 'text-amber-600',
                'yellow' => 'text-yellow-600',
                'lime' => 'text-lime-600',
                'green' => 'text-green-600',
                'emerald' => 'text-emerald-600',
                'teal' => 'text-teal-600',
                'cyan' => 'text-cyan-600',
                'sky' => 'text-sky-600',
                'blue' => 'text-blue-600',
                'indigo' => 'text-indigo-600',
                'violet' => 'text-violet-600',
                'purple' => 'text-purple-600',
                'fuchsia' => 'text-fuchsia-600',
                'pink' => 'text-pink-600',
                'rose' => 'text-rose-600',
            ],
            'light' => [
                'white' => 'text-black',
                'black' => 'text-black-600 dark:text-black-400',
                'primary' => 'text-primary-600 dark:text-primary-400',
                'secondary' => 'text-secondary-600 dark:text-secondary-400',
                'slate' => 'text-slate-600 dark:text-slate-400',
                'gray' => 'text-gray-600 dark:text-gray-400',
                'zinc' => 'text-zinc-600 dark:text-zinc-400',
                'neutral' => 'text-neutral-600 dark:text-neutral-400',
                'stone' => 'text-stone-600 dark:text-stone-400',
                'red' => 'text-red-600 dark:text-red-400',
                'orange' => 'text-orange-600 dark:text-orange-400',
                'amber' => 'text-amber-600 dark:text-amber-400',
                'yellow' => 'text-yellow-600 dark:text-yellow-400',
                'lime' => 'text-lime-600 dark:text-lime-400',
                'green' => 'text-green-600 dark:text-green-400',
                'emerald' => 'text-emerald-600 dark:text-emerald-400',
                'teal' => 'text-teal-600 dark:text-teal-400',
                'cyan' => 'text-cyan-600 dark:text-cyan-400',
                'sky' => 'text-sky-600 dark:text-sky-400',
                'blue' => 'text-blue-600 dark:text-blue-400',
                'indigo' => 'text-indigo-600 dark:text-indigo-400',
                'violet' => 'text-violet-600 dark:text-violet-400',
                'purple' => 'text-purple-600 dark:text-purple-400',
                'fuchsia' => 'text-fuchsia-600 dark:text-fuchsia-400',
                'pink' => 'text-pink-600 dark:text-pink-400',
                'rose' => 'text-rose-600 dark:text-rose-400',
            ],
        ];
    }
}
