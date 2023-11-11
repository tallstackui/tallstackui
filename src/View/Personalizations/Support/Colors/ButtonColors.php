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
        return [
            'solid' => [
                'white' => 'text-black border-white focus:ring-offset-2 ring-white bg-white focus:bg-white hover:bg-white hover:ring-white dark:focus:ring-offset-dark-900 dark:focus:ring-white dark:hover:bg-white dark:hover:ring-white hover:border-transparent',
                'black' => 'text-white border-black focus:ring-offset-2 ring-black bg-black focus:bg-black hover:bg-black hover:ring-black dark:focus:ring-offset-dark-900 dark:focus:ring-black dark:hover:bg-black dark:hover:ring-black hover:border-transparent',
                'primary' => 'text-primary-50 border-primary-500 ring-primary-500 bg-primary-500 focus:bg-primary-600 hover:bg-primary-600 hover:ring-primary-600 dark:focus:ring-offset-dark-900 dark:focus:ring-primary-600 dark:hover:bg-primary-600 dark:hover:ring-primary-600 hover:border-transparent focus:ring-offset-2',
                'secondary' => 'text-secondary-50 border-secondary-500 ring-secondary-500 bg-secondary-500 focus:bg-secondary-600 hover:bg-secondary-600 hover:ring-secondary-600 dark:focus:ring-offset-dark-900 dark:focus:ring-secondary-600 dark:hover:bg-secondary-600 dark:hover:ring-secondary-600 hover:border-transparent focus:ring-offset-2',
                'slate' => 'text-slate-50 border-slate-500 ring-slate-500 bg-slate-500 focus:bg-slate-600 hover:bg-slate-600 hover:ring-slate-600 dark:focus:ring-offset-dark-900 dark:focus:ring-slate-600 dark:hover:bg-slate-600 dark:hover:ring-slate-600 hover:border-transparent focus:ring-offset-2',
                'gray' => 'text-gray-50 border-gray-500 ring-gray-500 bg-gray-500 focus:bg-gray-600 hover:bg-gray-600 hover:ring-gray-600 dark:focus:ring-offset-dark-900 dark:focus:ring-gray-600 dark:hover:bg-gray-600 dark:hover:ring-gray-600 hover:border-transparent focus:ring-offset-2',
                'zinc' => 'text-zinc-50 border-zinc-500 ring-zinc-500 bg-zinc-500 focus:bg-zinc-600 hover:bg-zinc-600 hover:ring-zinc-600 dark:focus:ring-offset-dark-900 dark:focus:ring-zinc-600 dark:hover:bg-zinc-600 dark:hover:ring-zinc-600 hover:border-transparent focus:ring-offset-2',
                'neutral' => 'text-neutral-50 border-neutral-500 ring-neutral-500 bg-neutral-500 focus:bg-neutral-600 hover:bg-neutral-600 hover:ring-neutral-600 dark:focus:ring-offset-dark-900 dark:focus:ring-neutral-600 dark:hover:bg-neutral-600 dark:hover:ring-neutral-600 hover:border-transparent focus:ring-offset-2',
                'stone' => 'text-stone-50 border-stone-500 ring-stone-500 bg-stone-500 focus:bg-stone-600 hover:bg-stone-600 hover:ring-stone-600 dark:focus:ring-offset-dark-900 dark:focus:ring-stone-600 dark:hover:bg-stone-600 dark:hover:ring-stone-600 hover:border-transparent focus:ring-offset-2',
                'red' => 'text-red-50 border-red-500 ring-red-500 bg-red-500 focus:bg-red-600 hover:bg-red-600 hover:ring-red-600 dark:focus:ring-offset-dark-900 dark:focus:ring-red-600 dark:hover:bg-red-600 dark:hover:ring-red-600 hover:border-transparent focus:ring-offset-2',
                'orange' => 'text-orange-50 border-orange-500 ring-orange-500 bg-orange-500 focus:bg-orange-600 hover:bg-orange-600 hover:ring-orange-600 dark:focus:ring-offset-dark-900 dark:focus:ring-orange-600 dark:hover:bg-orange-600 dark:hover:ring-orange-600 hover:border-transparent focus:ring-offset-2',
                'amber' => 'text-amber-50 border-amber-500 ring-amber-500 bg-amber-500 focus:bg-amber-600 hover:bg-amber-600 hover:ring-amber-600 dark:focus:ring-offset-dark-900 dark:focus:ring-amber-600 dark:hover:bg-amber-600 dark:hover:ring-amber-600 hover:border-transparent focus:ring-offset-2',
                'yellow' => 'text-yellow-50 border-yellow-500 ring-yellow-500 bg-yellow-500 focus:bg-yellow-600 hover:bg-yellow-600 hover:ring-yellow-600 dark:focus:ring-offset-dark-900 dark:focus:ring-yellow-600 dark:hover:bg-yellow-600 dark:hover:ring-yellow-600 hover:border-transparent focus:ring-offset-2',
                'lime' => 'text-lime-50 border-lime-500 ring-lime-500 bg-lime-500 focus:bg-lime-600 hover:bg-lime-600 hover:ring-lime-600 dark:focus:ring-offset-dark-900 dark:focus:ring-lime-600 dark:hover:bg-lime-600 dark:hover:ring-lime-600 hover:border-transparent focus:ring-offset-2',
                'green' => 'text-green-50 border-green-500 ring-green-500 bg-green-500 focus:bg-green-600 hover:bg-green-600 hover:ring-green-600 dark:focus:ring-offset-dark-900 dark:focus:ring-green-600 dark:hover:bg-green-600 dark:hover:ring-green-600 hover:border-transparent focus:ring-offset-2',
                'emerald' => 'text-emerald-50 border-emerald-500 ring-emerald-500 bg-emerald-500 focus:bg-emerald-600 hover:bg-emerald-600 hover:ring-emerald-600 dark:focus:ring-offset-dark-900 dark:focus:ring-emerald-600 dark:hover:bg-emerald-600 dark:hover:ring-emerald-600 hover:border-transparent focus:ring-offset-2',
                'teal' => 'text-teal-50 border-teal-500 ring-teal-500 bg-teal-500 focus:bg-teal-600 hover:bg-teal-600 hover:ring-teal-600 dark:focus:ring-offset-dark-900 dark:focus:ring-teal-600 dark:hover:bg-teal-600 dark:hover:ring-teal-600 hover:border-transparent focus:ring-offset-2',
                'cyan' => 'text-cyan-50 border-cyan-500 ring-cyan-500 bg-cyan-500 focus:bg-cyan-600 hover:bg-cyan-600 hover:ring-cyan-600 dark:focus:ring-offset-dark-900 dark:focus:ring-cyan-600 dark:hover:bg-cyan-600 dark:hover:ring-cyan-600 hover:border-transparent focus:ring-offset-2',
                'sky' => 'text-sky-50 border-sky-500 ring-sky-500 bg-sky-500 focus:bg-sky-600 hover:bg-sky-600 hover:ring-sky-600 dark:focus:ring-offset-dark-900 dark:focus:ring-sky-600 dark:hover:bg-sky-600 dark:hover:ring-sky-600 hover:border-transparent focus:ring-offset-2',
                'blue' => 'text-blue-50 border-blue-500 ring-blue-500 bg-blue-500 focus:bg-blue-600 hover:bg-blue-600 hover:ring-blue-600 dark:focus:ring-offset-dark-900 dark:focus:ring-blue-600 dark:hover:bg-blue-600 dark:hover:ring-blue-600 hover:border-transparent focus:ring-offset-2',
                'indigo' => 'text-indigo-50 border-indigo-500 ring-indigo-500 bg-indigo-500 focus:bg-indigo-600 hover:bg-indigo-600 hover:ring-indigo-600 dark:focus:ring-offset-dark-900 dark:focus:ring-indigo-600 dark:hover:bg-indigo-600 dark:hover:ring-indigo-600 hover:border-transparent focus:ring-offset-2',
                'violet' => 'text-violet-50 border-violet-500 ring-violet-500 bg-violet-500 focus:bg-violet-600 hover:bg-violet-600 hover:ring-violet-600 dark:focus:ring-offset-dark-900 dark:focus:ring-violet-600 dark:hover:bg-violet-600 dark:hover:ring-violet-600 hover:border-transparent focus:ring-offset-2',
                'purple' => 'text-purple-50 border-purple-500 ring-purple-500 bg-purple-500 focus:bg-purple-600 hover:bg-purple-600 hover:ring-purple-600 dark:focus:ring-offset-dark-900 dark:focus:ring-purple-600 dark:hover:bg-purple-600 dark:hover:ring-purple-600 hover:border-transparent focus:ring-offset-2',
                'fuchsia' => 'text-fuchsia-50 border-fuchsia-500 ring-fuchsia-500 bg-fuchsia-500 focus:bg-fuchsia-600 hover:bg-fuchsia-600 hover:ring-fuchsia-600 dark:focus:ring-offset-dark-900 dark:focus:ring-fuchsia-600 dark:hover:bg-fuchsia-600 dark:hover:ring-fuchsia-600 hover:border-transparent focus:ring-offset-2',
                'pink' => 'text-pink-50 border-pink-500 ring-pink-500 bg-pink-500 focus:bg-pink-600 hover:bg-pink-600 hover:ring-pink-600 dark:focus:ring-offset-dark-900 dark:focus:ring-pink-600 dark:hover:bg-pink-600 dark:hover:ring-pink-600 hover:border-transparent focus:ring-offset-2',
                'rose' => 'text-rose-50 border-rose-500 ring-rose-500 bg-rose-500 focus:bg-rose-600 hover:bg-rose-600 hover:ring-rose-600 dark:focus:ring-offset-dark-900 dark:focus:ring-rose-600 dark:hover:bg-rose-600 dark:hover:ring-rose-600 hover:border-transparent focus:ring-offset-2',
            ],
            'outline' => [
                'white' => 'text-white border-white hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-white hover:bg-white dark:hover:text-white dark:hover:bg-white focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-white focus:bg-white focus:ring-white dark:focus:text-white dark:focus:bg-white dark:focus:ring-white',
                'black' => 'text-black border-black hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-black hover:bg-black dark:hover:text-black dark:hover:bg-black focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-black focus:bg-black focus:ring-black dark:focus:text-black dark:focus:bg-black dark:focus:ring-black',
                'primary' => 'text-primary-600 border-primary-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-primary-700 hover:bg-primary-400 dark:hover:text-primary-500 dark:hover:bg-primary-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-primary-700 focus:bg-primary-400 focus:ring-primary-600 dark:focus:text-primary-500 dark:focus:bg-primary-600 dark:focus:ring-primary-700',
                'secondary' => 'text-secondary-600 border-secondary-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-secondary-700 hover:bg-secondary-400 dark:hover:text-secondary-500 dark:hover:bg-secondary-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-secondary-700 focus:bg-secondary-400 focus:ring-secondary-600 dark:focus:text-secondary-500 dark:focus:bg-secondary-600 dark:focus:ring-secondary-700',
                'slate' => 'text-slate-600 border-slate-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-slate-700 hover:bg-slate-400 dark:hover:text-slate-500 dark:hover:bg-slate-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-slate-700 focus:bg-slate-400 focus:ring-slate-600 dark:focus:text-slate-500 dark:focus:bg-slate-600 dark:focus:ring-slate-700',
                'gray' => 'text-gray-600 border-gray-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-gray-700 hover:bg-gray-400 dark:hover:text-gray-500 dark:hover:bg-gray-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-gray-700 focus:bg-gray-400 focus:ring-gray-600 dark:focus:text-gray-500 dark:focus:bg-gray-600 dark:focus:ring-gray-700',
                'zinc' => 'text-zinc-600 border-zinc-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-zinc-700 hover:bg-zinc-400 dark:hover:text-zinc-500 dark:hover:bg-zinc-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-zinc-700 focus:bg-zinc-400 focus:ring-zinc-600 dark:focus:text-zinc-500 dark:focus:bg-zinc-600 dark:focus:ring-zinc-700',
                'neutral' => 'text-neutral-600 border-neutral-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-neutral-700 hover:bg-neutral-400 dark:hover:text-neutral-500 dark:hover:bg-neutral-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-neutral-700 focus:bg-neutral-400 focus:ring-neutral-600 dark:focus:text-neutral-500 dark:focus:bg-neutral-600 dark:focus:ring-neutral-700',
                'stone' => 'text-stone-600 border-stone-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-stone-700 hover:bg-stone-400 dark:hover:text-stone-500 dark:hover:bg-stone-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-stone-700 focus:bg-stone-400 focus:ring-stone-600 dark:focus:text-stone-500 dark:focus:bg-stone-600 dark:focus:ring-stone-700',
                'red' => 'text-red-600 border-red-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-red-700 hover:bg-red-400 dark:hover:text-red-500 dark:hover:bg-red-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-red-700 focus:bg-red-400 focus:ring-red-600 dark:focus:text-red-500 dark:focus:bg-red-600 dark:focus:ring-red-700',
                'orange' => 'text-orange-600 border-orange-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-orange-700 hover:bg-orange-400 dark:hover:text-orange-500 dark:hover:bg-orange-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-orange-700 focus:bg-orange-400 focus:ring-orange-600 dark:focus:text-orange-500 dark:focus:bg-orange-600 dark:focus:ring-orange-700',
                'amber' => 'text-amber-600 border-amber-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-amber-700 hover:bg-amber-400 dark:hover:text-amber-500 dark:hover:bg-amber-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-amber-700 focus:bg-amber-400 focus:ring-amber-600 dark:focus:text-amber-500 dark:focus:bg-amber-600 dark:focus:ring-amber-700',
                'yellow' => 'text-yellow-600 border-yellow-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-yellow-700 hover:bg-yellow-400 dark:hover:text-yellow-500 dark:hover:bg-yellow-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-yellow-700 focus:bg-yellow-400 focus:ring-yellow-600 dark:focus:text-yellow-500 dark:focus:bg-yellow-600 dark:focus:ring-yellow-700',
                'lime' => 'text-lime-600 border-lime-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-lime-700 hover:bg-lime-400 dark:hover:text-lime-500 dark:hover:bg-lime-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-lime-700 focus:bg-lime-400 focus:ring-lime-600 dark:focus:text-lime-500 dark:focus:bg-lime-600 dark:focus:ring-lime-700',
                'green' => 'text-green-600 border-green-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-green-700 hover:bg-green-400 dark:hover:text-green-500 dark:hover:bg-green-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-green-700 focus:bg-green-400 focus:ring-green-600 dark:focus:text-green-500 dark:focus:bg-green-600 dark:focus:ring-green-700',
                'emerald' => 'text-emerald-600 border-emerald-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-emerald-700 hover:bg-emerald-400 dark:hover:text-emerald-500 dark:hover:bg-emerald-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-emerald-700 focus:bg-emerald-400 focus:ring-emerald-600 dark:focus:text-emerald-500 dark:focus:bg-emerald-600 dark:focus:ring-emerald-700',
                'teal' => 'text-teal-600 border-teal-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-teal-700 hover:bg-teal-400 dark:hover:text-teal-500 dark:hover:bg-teal-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-teal-700 focus:bg-teal-400 focus:ring-teal-600 dark:focus:text-teal-500 dark:focus:bg-teal-600 dark:focus:ring-teal-700',
                'cyan' => 'text-cyan-600 border-cyan-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-cyan-700 hover:bg-cyan-400 dark:hover:text-cyan-500 dark:hover:bg-cyan-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-cyan-700 focus:bg-cyan-400 focus:ring-cyan-600 dark:focus:text-cyan-500 dark:focus:bg-cyan-600 dark:focus:ring-cyan-700',
                'sky' => 'text-sky-600 border-sky-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-sky-700 hover:bg-sky-400 dark:hover:text-sky-500 dark:hover:bg-sky-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-sky-700 focus:bg-sky-400 focus:ring-sky-600 dark:focus:text-sky-500 dark:focus:bg-sky-600 dark:focus:ring-sky-700',
                'blue' => 'text-blue-600 border-blue-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-blue-700 hover:bg-blue-400 dark:hover:text-blue-500 dark:hover:bg-blue-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-blue-700 focus:bg-blue-400 focus:ring-blue-600 dark:focus:text-blue-500 dark:focus:bg-blue-600 dark:focus:ring-blue-700',
                'indigo' => 'text-indigo-600 border-indigo-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-indigo-700 hover:bg-indigo-400 dark:hover:text-indigo-500 dark:hover:bg-indigo-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-indigo-700 focus:bg-indigo-400 focus:ring-indigo-600 dark:focus:text-indigo-500 dark:focus:bg-indigo-600 dark:focus:ring-indigo-700',
                'violet' => 'text-violet-600 border-violet-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-violet-700 hover:bg-violet-400 dark:hover:text-violet-500 dark:hover:bg-violet-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-violet-700 focus:bg-violet-400 focus:ring-violet-600 dark:focus:text-violet-500 dark:focus:bg-violet-600 dark:focus:ring-violet-700',
                'purple' => 'text-purple-600 border-purple-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-purple-700 hover:bg-purple-400 dark:hover:text-purple-500 dark:hover:bg-purple-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-purple-700 focus:bg-purple-400 focus:ring-purple-600 dark:focus:text-purple-500 dark:focus:bg-purple-600 dark:focus:ring-purple-700',
                'fuchsia' => 'text-fuchsia-600 border-fuchsia-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-fuchsia-700 hover:bg-fuchsia-400 dark:hover:text-fuchsia-500 dark:hover:bg-fuchsia-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-fuchsia-700 focus:bg-fuchsia-400 focus:ring-fuchsia-600 dark:focus:text-fuchsia-500 dark:focus:bg-fuchsia-600 dark:focus:ring-fuchsia-700',
                'pink' => 'text-pink-600 border-pink-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-pink-700 hover:bg-pink-400 dark:hover:text-pink-500 dark:hover:bg-pink-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-pink-700 focus:bg-pink-400 focus:ring-pink-600 dark:focus:text-pink-500 dark:focus:bg-pink-600 dark:focus:ring-pink-700',
                'rose' => 'text-rose-600 border-rose-600 hover:bg-opacity-25 dark:hover:bg-opacity-10 hover:text-rose-700 hover:bg-rose-400 dark:hover:text-rose-500 dark:hover:bg-rose-600 focus:bg-opacity-25 dark:focus:border-transparent dark:focus:bg-opacity-10 focus:ring-offset-0 focus:text-rose-700 focus:bg-rose-400 focus:ring-rose-600 dark:focus:text-rose-500 dark:focus:bg-rose-600 dark:focus:ring-rose-700',
            ],
        ][$this->component->style][$this->component->color];
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
