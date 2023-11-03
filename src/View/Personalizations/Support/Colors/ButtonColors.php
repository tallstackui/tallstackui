<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;

class ButtonColors
{
    public function __construct(protected Button|Circle $component)
    {
        //
    }

    public function __invoke(): array
    {
        return [
            'wrapper.color' => $this->button(),
            'icon.color' => $this->icon(),
            'icon.loading.color' => $this->icon(),
        ];
    }

    private function button(): string
    {
        $color = [
            'solid' => [
                'white' => 'text-black bg-white ring-white hover:bg-white hover:ring-white',
                'black' => 'text-white bg-black ring-black hover:bg-black hover:ring-black',
                'primary' => 'text-primary-50 bg-primary-500 ring-primary-500 hover:bg-primary-600 hover:ring-primary-600',
                'secondary' => 'text-secondary-50 bg-secondary-500 ring-secondary-500 hover:bg-secondary-600 hover:ring-secondary-600',
                'slate' => 'text-slate-50 bg-slate-500 ring-slate-500 hover:bg-slate-600 hover:ring-slate-600',
                'gray' => 'text-gray-50 bg-gray-500 ring-gray-500 hover:bg-gray-600 hover:ring-gray-600',
                'zinc' => 'text-zinc-50 bg-zinc-500 ring-zinc-500 hover:bg-zinc-600 hover:ring-zinc-600',
                'neutral' => 'text-neutral-50 bg-neutral-500 ring-neutral-500 hover:bg-neutral-600 hover:ring-neutral-600',
                'stone' => 'text-stone-50 bg-stone-500 ring-stone-500 hover:bg-stone-600 hover:ring-stone-600',
                'red' => 'text-red-50 bg-red-500 ring-red-500 hover:bg-red-600 hover:ring-red-600',
                'orange' => 'text-orange-50 bg-orange-500 ring-orange-500 hover:bg-orange-600 hover:ring-orange-600',
                'amber' => 'text-amber-50 bg-amber-500 ring-amber-500 hover:bg-amber-600 hover:ring-amber-600',
                'yellow' => 'text-yellow-50 bg-yellow-500 ring-yellow-500 hover:bg-yellow-600 hover:ring-yellow-600',
                'lime' => 'text-lime-50 bg-lime-500 ring-lime-500 hover:bg-lime-600 hover:ring-lime-600',
                'green' => 'text-green-50 bg-green-500 ring-green-500 hover:bg-green-600 hover:ring-green-600',
                'emerald' => 'text-emerald-50 bg-emerald-500 ring-emerald-500 hover:bg-emerald-600 hover:ring-emerald-600',
                'teal' => 'text-teal-50 bg-teal-500 ring-teal-500 hover:bg-teal-600 hover:ring-teal-600',
                'cyan' => 'text-cyan-50 bg-cyan-500 ring-cyan-500 hover:bg-cyan-600 hover:ring-cyan-600',
                'sky' => 'text-sky-50 bg-sky-500 ring-sky-500 hover:bg-sky-600 hover:ring-sky-600',
                'blue' => 'text-blue-50 bg-blue-500 ring-blue-500 hover:bg-blue-600 hover:ring-blue-600',
                'indigo' => 'text-indigo-50 bg-indigo-500 ring-indigo-500 hover:bg-indigo-600 hover:ring-indigo-600',
                'violet' => 'text-violet-50 bg-violet-500 ring-violet-500 hover:bg-violet-600 hover:ring-violet-600',
                'purple' => 'text-purple-50 bg-purple-500 ring-purple-500 hover:bg-purple-600 hover:ring-purple-600',
                'fuchsia' => 'text-fuchsia-50 bg-fuchsia-500 ring-fuchsia-500 hover:bg-fuchsia-600 hover:ring-fuchsia-600',
                'pink' => 'text-pink-50 bg-pink-500 ring-pink-500 hover:bg-pink-600 hover:ring-pink-600',
                'rose' => 'text-rose-50 bg-rose-500 ring-rose-500 hover:bg-rose-600 hover:ring-rose-600',
            ],
            'outline' => [
                'white' => 'text-white bg-transparent border border-white ring-white hover:bg-white hover:ring-white dark:hover:bg-slate-700',
                'black' => 'text-black bg-transparent border border-black ring-black hover:bg-black hover:ring-black dark:hover:bg-slate-700',
                'primary' => 'text-primary-500 bg-transparent border border-primary-500 ring-primary-500 hover:bg-primary-100 hover:ring-primary-600 dark:hover:bg-slate-700',
                'secondary' => 'text-secondary-500 bg-transparent border border-secondary-500 ring-secondary-500 hover:bg-secondary-100 hover:ring-secondary-600 dark:hover:bg-slate-700',
                'slate' => 'text-slate-500 bg-transparent border border-slate-500 ring-slate-500 hover:bg-slate-100 hover:ring-slate-600 dark:hover:bg-slate-700',
                'gray' => 'text-gray-500 bg-transparent border border-gray-500 ring-gray-500 hover:bg-gray-100 hover:ring-gray-600 dark:hover:bg-slate-700',
                'zinc' => 'text-zinc-500 bg-transparent border border-zinc-500 ring-zinc-500 hover:bg-zinc-100 hover:ring-zinc-600 dark:hover:bg-slate-700',
                'neutral' => 'text-neutral-500 bg-transparent border border-neutral-500 ring-neutral-500 hover:bg-neutral-100 hover:ring-neutral-600 dark:hover:bg-slate-700',
                'stone' => 'text-stone-500 bg-transparent border border-stone-500 ring-stone-500 hover:bg-stone-100 hover:ring-stone-600 dark:hover:bg-slate-700',
                'red' => 'text-red-500 bg-transparent border border-red-500 ring-red-500 hover:bg-red-100 hover:ring-red-600 dark:hover:bg-slate-700',
                'orange' => 'text-orange-500 bg-transparent border border-orange-500 ring-orange-500 hover:bg-orange-100 hover:ring-orange-600 dark:hover:bg-slate-700',
                'amber' => 'text-amber-500 bg-transparent border border-amber-500 ring-amber-500 hover:bg-amber-100 hover:ring-amber-600 dark:hover:bg-slate-700',
                'yellow' => 'text-yellow-500 bg-transparent border border-yellow-500 ring-yellow-500 hover:bg-yellow-100 hover:ring-yellow-600 dark:hover:bg-slate-700',
                'lime' => 'text-lime-500 bg-transparent border border-lime-500 ring-lime-500 hover:bg-lime-100 hover:ring-lime-600 dark:hover:bg-slate-700',
                'green' => 'text-green-500 bg-transparent border border-green-500 ring-green-500 hover:bg-green-100 hover:ring-green-600 dark:hover:bg-slate-700',
                'emerald' => 'text-emerald-500 bg-transparent border border-emerald-500 ring-emerald-500 hover:bg-emerald-100 hover:ring-emerald-600 dark:hover:bg-slate-700',
                'teal' => 'text-teal-500 bg-transparent border border-teal-500 ring-teal-500 hover:bg-teal-100 hover:ring-teal-600 dark:hover:bg-slate-700',
                'cyan' => 'text-cyan-500 bg-transparent border border-cyan-500 ring-cyan-500 hover:bg-cyan-100 hover:ring-cyan-600 dark:hover:bg-slate-700',
                'sky' => 'text-sky-500 bg-transparent border border-sky-500 ring-sky-500 hover:bg-sky-100 hover:ring-sky-600 dark:hover:bg-slate-700',
                'blue' => 'text-blue-500 bg-transparent border border-blue-500 ring-blue-500 hover:bg-blue-100 hover:ring-blue-600 dark:hover:bg-slate-700',
                'indigo' => 'text-indigo-500 bg-transparent border border-indigo-500 ring-indigo-500 hover:bg-indigo-100 hover:ring-indigo-600 dark:hover:bg-slate-700',
                'violet' => 'text-violet-500 bg-transparent border border-violet-500 ring-violet-500 hover:bg-violet-100 hover:ring-violet-600 dark:hover:bg-slate-700',
                'purple' => 'text-purple-500 bg-transparent border border-purple-500 ring-purple-500 hover:bg-purple-100 hover:ring-purple-600 dark:hover:bg-slate-700',
                'fuchsia' => 'text-fuchsia-500 bg-transparent border border-fuchsia-500 ring-fuchsia-500 hover:bg-fuchsia-100 hover:ring-fuchsia-600 dark:hover:bg-slate-700',
                'pink' => 'text-pink-500 bg-transparent border border-pink-500 ring-pink-500 hover:bg-pink-100 hover:ring-pink-600 dark:hover:bg-slate-700',
                'rose' => 'text-rose-500 bg-transparent border border-rose-500 ring-rose-500 hover:bg-rose-100 hover:ring-rose-600 dark:hover:bg-slate-700',
            ],
        ][$this->component->style][$this->component->color];

        return $color.' dark:ring-offset-dark-900';
    }

    private function icon(): string
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
            'outline' => [
                'white' => 'text-white',
                'black' => 'text-black',
                'primary' => 'text-primary-500',
                'secondary' => 'text-secondary-500',
                'slate' => 'text-slate-500',
                'gray' => 'text-gray-500',
                'zinc' => 'text-zinc-500',
                'neutral' => 'text-neutral-500',
                'stone' => 'text-stone-500',
                'red' => 'text-red-500',
                'orange' => 'text-orange-500',
                'amber' => 'text-amber-500',
                'yellow' => 'text-yellow-500',
                'lime' => 'text-lime-500',
                'green' => 'text-green-500',
                'emerald' => 'text-emerald-500',
                'teal' => 'text-teal-500',
                'cyan' => 'text-cyan-500',
                'sky' => 'text-sky-500',
                'blue' => 'text-blue-500',
                'indigo' => 'text-indigo-500',
                'violet' => 'text-violet-500',
                'purple' => 'text-purple-500',
                'fuchsia' => 'text-fuchsia-500',
                'pink' => 'text-pink-500',
                'rose' => 'text-rose-500',
            ],
        ][$this->component->style][$this->component->color];
    }
}
