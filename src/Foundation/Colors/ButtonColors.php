<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;

class ButtonColors
{
    use OverrideColors;

    public function __construct(protected Button|Circle $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        [$background, $icon] = $this->get('background', 'icon');

        $getter = $this->format($this->component->style, $this->component->color);

        return [
            'background' => data_get($background, $getter, data_get($this->background(), $getter)),
            'icon' => data_get($icon, $getter, data_get($this->icon(), $getter)),
        ];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'white' => 'text-black border-white focus:ring-offset-2 ring-white bg-white focus:bg-white hover:bg-white hover:ring-white border-transparent dark:focus:ring-offset-dark-900 dark:focus:ring-white dark:hover:bg-white dark:hover:ring-white',
                'black' => 'text-white border-black focus:ring-offset-2 ring-black bg-black focus:bg-black hover:bg-black hover:ring-black border-transparent dark:focus:ring-offset-dark-900 dark:focus:ring-black dark:hover:bg-black dark:hover:ring-black',
                'primary' => 'text-primary-50 ring-primary-500 bg-primary-500 focus:bg-primary-600 hover:bg-primary-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-primary-600 dark:bg-primary-700 dark:hover:bg-primary-600 dark:hover:ring-primary-600',
                'secondary' => 'text-secondary-50 ring-secondary-500 bg-secondary-500 focus:bg-secondary-600 hover:bg-secondary-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-secondary-600 dark:bg-secondary-700 dark:hover:bg-secondary-600 dark:hover:ring-secondary-600',
                'slate' => 'text-slate-50 ring-slate-500 bg-slate-500 focus:bg-slate-600 hover:bg-slate-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-slate-600 dark:bg-slate-700 dark:hover:bg-slate-600 dark:hover:ring-slate-600',
                'gray' => 'text-gray-50 ring-gray-500 bg-gray-500 focus:bg-gray-600 hover:bg-gray-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:hover:ring-gray-600',
                'zinc' => 'text-zinc-50 ring-zinc-500 bg-zinc-500 focus:bg-zinc-600 hover:bg-zinc-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-zinc-600 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:hover:ring-zinc-600',
                'neutral' => 'text-neutral-50 ring-neutral-500 bg-neutral-500 focus:bg-neutral-600 hover:bg-neutral-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-neutral-600 dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:hover:ring-neutral-600',
                'stone' => 'text-stone-50 ring-stone-500 bg-stone-500 focus:bg-stone-600 hover:bg-stone-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-stone-600 dark:bg-stone-700 dark:hover:bg-stone-600 dark:hover:ring-stone-600',
                'red' => 'text-red-50 ring-red-500 bg-red-500 focus:bg-red-600 hover:bg-red-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-red-600 dark:bg-red-700 dark:hover:bg-red-600 dark:hover:ring-red-600',
                'orange' => 'text-orange-50 ring-orange-500 bg-orange-500 focus:bg-orange-600 hover:bg-orange-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-orange-600 dark:bg-orange-700 dark:hover:bg-orange-600 dark:hover:ring-orange-600',
                'amber' => 'text-amber-50 ring-amber-500 bg-amber-500 focus:bg-amber-600 hover:bg-amber-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-amber-600 dark:bg-amber-700 dark:hover:bg-amber-600 dark:hover:ring-amber-600',
                'yellow' => 'text-yellow-50 ring-yellow-500 bg-yellow-500 focus:bg-yellow-600 hover:bg-yellow-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-yellow-600 dark:bg-yellow-700 dark:hover:bg-yellow-600 dark:hover:ring-yellow-600',
                'lime' => 'text-lime-50 ring-lime-500 bg-lime-500 focus:bg-lime-600 hover:bg-lime-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-lime-600 dark:bg-lime-700 dark:hover:bg-lime-600 dark:hover:ring-lime-600',
                'green' => 'text-green-50 ring-green-500 bg-green-500 focus:bg-green-600 hover:bg-green-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-green-600 dark:bg-green-700 dark:hover:bg-green-600 dark:hover:ring-green-600',
                'emerald' => 'text-emerald-50 ring-emerald-500 bg-emerald-500 focus:bg-emerald-600 hover:bg-emerald-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-emerald-600 dark:bg-emerald-700 dark:hover:bg-emerald-600 dark:hover:ring-emerald-600',
                'teal' => 'text-teal-50 ring-teal-500 bg-teal-500 focus:bg-teal-600 hover:bg-teal-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-teal-600 dark:bg-teal-700 dark:hover:bg-teal-600 dark:hover:ring-teal-600',
                'cyan' => 'text-cyan-50 ring-cyan-500 bg-cyan-500 focus:bg-cyan-600 hover:bg-cyan-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-cyan-600 dark:bg-cyan-700 dark:hover:bg-cyan-600 dark:hover:ring-cyan-600',
                'sky' => 'text-sky-50 ring-sky-500 bg-sky-500 focus:bg-sky-600 hover:bg-sky-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-sky-600 dark:bg-sky-700 dark:hover:bg-sky-600 dark:hover:ring-sky-600',
                'blue' => 'text-blue-50 ring-blue-500 bg-blue-500 focus:bg-blue-600 hover:bg-blue-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-blue-600 dark:bg-blue-700 dark:hover:bg-blue-600 dark:hover:ring-blue-600',
                'indigo' => 'text-indigo-50 ring-indigo-500 bg-indigo-500 focus:bg-indigo-600 hover:bg-indigo-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-indigo-600 dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:hover:ring-indigo-600',
                'violet' => 'text-violet-50 ring-violet-500 bg-violet-500 focus:bg-violet-600 hover:bg-violet-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600 dark:hover:ring-violet-600',
                'purple' => 'text-purple-50 ring-purple-500 bg-purple-500 focus:bg-purple-600 hover:bg-purple-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-purple-600 dark:bg-purple-700 dark:hover:bg-purple-600 dark:hover:ring-purple-600',
                'fuchsia' => 'text-fuchsia-50 ring-fuchsia-500 bg-fuchsia-500 focus:bg-fuchsia-600 hover:bg-fuchsia-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-fuchsia-600 dark:bg-fuchsia-700 dark:hover:bg-fuchsia-600 dark:hover:ring-fuchsia-600',
                'pink' => 'text-pink-50 ring-pink-500 bg-pink-500 focus:bg-pink-600 hover:bg-pink-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-pink-600 dark:bg-pink-700 dark:hover:bg-pink-600 dark:hover:ring-pink-600',
                'rose' => 'text-rose-50 ring-rose-500 bg-rose-500 focus:bg-rose-600 hover:bg-rose-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-rose-600 dark:bg-rose-700 dark:hover:bg-rose-600 dark:hover:ring-rose-600',
            ],
            'outline' => [
                'white' => 'text-white border-white hover:bg-opacity-25 hover:text-white hover:bg-white focus:bg-opacity-25 focus:ring-offset-0 focus:text-white focus:bg-white focus:ring-white dark:hover:bg-opacity-10 dark:hover:text-white dark:hover:bg-white dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-white dark:focus:bg-white dark:focus:ring-white',
                'black' => 'text-black border-black hover:bg-opacity-25 hover:text-black hover:bg-black focus:bg-opacity-25 focus:ring-offset-0 focus:text-black focus:bg-black focus:ring-black dark:hover:bg-opacity-10 dark:hover:text-black dark:hover:bg-black dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-black dark:focus:bg-black dark:focus:ring-black',
                'primary' => 'text-primary-600 border-primary-600 hover:bg-opacity-25 hover:bg-primary-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-primary-700 focus:bg-primary-400 focus:ring-primary-600 dark:hover:bg-opacity-10 hover:text-primary-700 dark:hover:text-primary-500 dark:hover:bg-primary-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-primary-500 dark:focus:bg-primary-600 dark:focus:ring-primary-700',
                'secondary' => 'text-secondary-600 border-secondary-600 hover:bg-opacity-25 hover:bg-secondary-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-secondary-700 focus:bg-secondary-400 focus:ring-secondary-600 dark:hover:bg-opacity-10 hover:text-secondary-700 dark:hover:text-secondary-500 dark:hover:bg-secondary-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-secondary-500 dark:focus:bg-secondary-600 dark:focus:ring-secondary-700',
                'slate' => 'text-slate-600 border-slate-600 hover:bg-opacity-25 hover:bg-slate-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-slate-700 focus:bg-slate-400 focus:ring-slate-600 dark:hover:bg-opacity-10 hover:text-slate-700 dark:hover:text-slate-500 dark:hover:bg-slate-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-slate-500 dark:focus:bg-slate-600 dark:focus:ring-slate-700',
                'gray' => 'text-gray-600 border-gray-600 hover:bg-opacity-25 hover:bg-gray-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-gray-700 focus:bg-gray-400 focus:ring-gray-600 dark:hover:bg-opacity-10 hover:text-gray-700 dark:hover:text-gray-500 dark:hover:bg-gray-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-gray-500 dark:focus:bg-gray-600 dark:focus:ring-gray-700',
                'zinc' => 'text-zinc-600 border-zinc-600 hover:bg-opacity-25 hover:bg-zinc-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-zinc-700 focus:bg-zinc-400 focus:ring-zinc-600 dark:hover:bg-opacity-10 hover:text-zinc-700 dark:hover:text-zinc-500 dark:hover:bg-zinc-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-zinc-500 dark:focus:bg-zinc-600 dark:focus:ring-zinc-700',
                'neutral' => 'text-neutral-600 border-neutral-600 hover:bg-opacity-25 hover:bg-neutral-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-neutral-700 focus:bg-neutral-400 focus:ring-neutral-600 dark:hover:bg-opacity-10 hover:text-neutral-700 dark:hover:text-neutral-500 dark:hover:bg-neutral-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-neutral-500 dark:focus:bg-neutral-600 dark:focus:ring-neutral-700',
                'stone' => 'text-stone-600 border-stone-600 hover:bg-opacity-25 hover:bg-stone-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-stone-700 focus:bg-stone-400 focus:ring-stone-600 dark:hover:bg-opacity-10 hover:text-stone-700 dark:hover:text-stone-500 dark:hover:bg-stone-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-stone-500 dark:focus:bg-stone-600 dark:focus:ring-stone-700',
                'red' => 'text-red-600 border-red-600 hover:bg-opacity-25 hover:bg-red-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-red-700 focus:bg-red-400 focus:ring-red-600 dark:hover:bg-opacity-10 hover:text-red-700 dark:hover:text-red-500 dark:hover:bg-red-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-red-500 dark:focus:bg-red-600 dark:focus:ring-red-700',
                'orange' => 'text-orange-600 border-orange-600 hover:bg-opacity-25 hover:bg-orange-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-orange-700 focus:bg-orange-400 focus:ring-orange-600 dark:hover:bg-opacity-10 hover:text-orange-700 dark:hover:text-orange-500 dark:hover:bg-orange-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-orange-500 dark:focus:bg-orange-600 dark:focus:ring-orange-700',
                'amber' => 'text-amber-600 border-amber-600 hover:bg-opacity-25 hover:bg-amber-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-amber-700 focus:bg-amber-400 focus:ring-amber-600 dark:hover:bg-opacity-10 hover:text-amber-700 dark:hover:text-amber-500 dark:hover:bg-amber-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-amber-500 dark:focus:bg-amber-600 dark:focus:ring-amber-700',
                'yellow' => 'text-yellow-600 border-yellow-600 hover:bg-opacity-25 hover:bg-yellow-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-yellow-700 focus:bg-yellow-400 focus:ring-yellow-600 dark:hover:bg-opacity-10 hover:text-yellow-700 dark:hover:text-yellow-500 dark:hover:bg-yellow-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-yellow-500 dark:focus:bg-yellow-600 dark:focus:ring-yellow-700',
                'lime' => 'text-lime-600 border-lime-600 hover:bg-opacity-25 hover:bg-lime-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-lime-700 focus:bg-lime-400 focus:ring-lime-600 dark:hover:bg-opacity-10 hover:text-lime-700 dark:hover:text-lime-500 dark:hover:bg-lime-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-lime-500 dark:focus:bg-lime-600 dark:focus:ring-lime-700',
                'green' => 'text-green-600 border-green-600 hover:bg-opacity-25 hover:bg-green-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-green-700 focus:bg-green-400 focus:ring-green-600 dark:hover:bg-opacity-10 hover:text-green-700 dark:hover:text-green-500 dark:hover:bg-green-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-green-500 dark:focus:bg-green-600 dark:focus:ring-green-700',
                'emerald' => 'text-emerald-600 border-emerald-600 hover:bg-opacity-25 hover:bg-emerald-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-emerald-700 focus:bg-emerald-400 focus:ring-emerald-600 dark:hover:bg-opacity-10 hover:text-emerald-700 dark:hover:text-emerald-500 dark:hover:bg-emerald-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-emerald-500 dark:focus:bg-emerald-600 dark:focus:ring-emerald-700',
                'teal' => 'text-teal-600 border-teal-600 hover:bg-opacity-25 hover:bg-teal-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-teal-700 focus:bg-teal-400 focus:ring-teal-600 dark:hover:bg-opacity-10 hover:text-teal-700 dark:hover:text-teal-500 dark:hover:bg-teal-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-teal-500 dark:focus:bg-teal-600 dark:focus:ring-teal-700',
                'cyan' => 'text-cyan-600 border-cyan-600 hover:bg-opacity-25 hover:bg-cyan-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-cyan-700 focus:bg-cyan-400 focus:ring-cyan-600 dark:hover:bg-opacity-10 hover:text-cyan-700 dark:hover:text-cyan-500 dark:hover:bg-cyan-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-cyan-500 dark:focus:bg-cyan-600 dark:focus:ring-cyan-700',
                'sky' => 'text-sky-600 border-sky-600 hover:bg-opacity-25 hover:bg-sky-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-sky-700 focus:bg-sky-400 focus:ring-sky-600 dark:hover:bg-opacity-10 hover:text-sky-700 dark:hover:text-sky-500 dark:hover:bg-sky-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-sky-500 dark:focus:bg-sky-600 dark:focus:ring-sky-700',
                'blue' => 'text-blue-600 border-blue-600 hover:bg-opacity-25 hover:bg-blue-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-blue-700 focus:bg-blue-400 focus:ring-blue-600 dark:hover:bg-opacity-10 hover:text-blue-700 dark:hover:text-blue-500 dark:hover:bg-blue-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-blue-500 dark:focus:bg-blue-600 dark:focus:ring-blue-700',
                'indigo' => 'text-indigo-600 border-indigo-600 hover:bg-opacity-25 hover:bg-indigo-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-indigo-700 focus:bg-indigo-400 focus:ring-indigo-600 dark:hover:bg-opacity-10 hover:text-indigo-700 dark:hover:text-indigo-500 dark:hover:bg-indigo-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-indigo-500 dark:focus:bg-indigo-600 dark:focus:ring-indigo-700',
                'violet' => 'text-violet-600 border-violet-600 hover:bg-opacity-25 hover:bg-violet-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-violet-700 focus:bg-violet-400 focus:ring-violet-600 dark:hover:bg-opacity-10 hover:text-violet-700 dark:hover:text-violet-500 dark:hover:bg-violet-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-violet-500 dark:focus:bg-violet-600 dark:focus:ring-violet-700',
                'purple' => 'text-purple-600 border-purple-600 hover:bg-opacity-25 hover:bg-purple-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-purple-700 focus:bg-purple-400 focus:ring-purple-600 dark:hover:bg-opacity-10 hover:text-purple-700 dark:hover:text-purple-500 dark:hover:bg-purple-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-purple-500 dark:focus:bg-purple-600 dark:focus:ring-purple-700',
                'fuchsia' => 'text-fuchsia-600 border-fuchsia-600 hover:bg-opacity-25 hover:bg-fuchsia-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-fuchsia-700 focus:bg-fuchsia-400 focus:ring-fuchsia-600 dark:hover:bg-opacity-10 hover:text-fuchsia-700 dark:hover:text-fuchsia-500 dark:hover:bg-fuchsia-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-fuchsia-500 dark:focus:bg-fuchsia-600 dark:focus:ring-fuchsia-700',
                'pink' => 'text-pink-600 border-pink-600 hover:bg-opacity-25 hover:bg-pink-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-pink-700 focus:bg-pink-400 focus:ring-pink-600 dark:hover:bg-opacity-10 hover:text-pink-700 dark:hover:text-pink-500 dark:hover:bg-pink-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-pink-500 dark:focus:bg-pink-600 dark:focus:ring-pink-700',
                'rose' => 'text-rose-600 border-rose-600 hover:bg-opacity-25 hover:bg-rose-400 focus:bg-opacity-25 focus:ring-offset-0 focus:text-rose-700 focus:bg-rose-400 focus:ring-rose-600 dark:hover:bg-opacity-10 hover:text-rose-700 dark:hover:text-rose-500 dark:hover:bg-rose-600 dark:focus:border-transparent dark:focus:bg-opacity-10 dark:focus:text-rose-500 dark:focus:bg-rose-600 dark:focus:ring-rose-700',
            ],
            'light' => [
                'white' => 'text-black focus:ring-offset-2 ring-white bg-white focus:bg-white hover:bg-white hover:ring-white dark:focus:ring-offset-dark-900 dark:focus:ring-white dark:hover:bg-white dark:hover:ring-white border-transparent',
                'black' => 'text-black-600 ring-black-400 bg-black-300 focus:bg-black-400 hover:bg-black-400 border-transparent focus:ring-offset-2 dark:focus:text-black-400 dark:focus:ring-offset-dark-900 dark:focus:ring-black-500 dark:bg-opacity-30 dark:bg-black-600 dark:hover:bg-black-500 dark:hover:bg-opacity-30 dark:text-black-400 dark:hover:ring-black-600',
                'primary' => 'text-primary-600 ring-primary-400 bg-primary-300 focus:bg-primary-400 hover:bg-primary-400 border-transparent focus:ring-offset-2 dark:focus:text-primary-400 dark:focus:ring-offset-dark-900 dark:focus:ring-primary-500 dark:bg-opacity-30 dark:bg-primary-600 dark:hover:bg-primary-500 dark:hover:bg-opacity-30 dark:text-primary-400 dark:hover:ring-primary-600',
                'secondary' => 'text-secondary-600 ring-secondary-400 bg-secondary-300 focus:bg-secondary-400 hover:bg-secondary-400 border-transparent focus:ring-offset-2 dark:focus:text-secondary-400 dark:focus:ring-offset-dark-900 dark:focus:ring-secondary-500 dark:bg-opacity-30 dark:bg-secondary-600 dark:hover:bg-secondary-500 dark:hover:bg-opacity-30 dark:text-secondary-400 dark:hover:ring-secondary-600',
                'slate' => 'text-slate-600 ring-slate-400 bg-slate-300 focus:bg-slate-400 hover:bg-slate-400 border-transparent focus:ring-offset-2 dark:focus:text-slate-400 dark:focus:ring-offset-dark-900 dark:focus:ring-slate-500 dark:bg-opacity-30 dark:bg-slate-600 dark:hover:bg-slate-500 dark:hover:bg-opacity-30 dark:text-slate-400 dark:hover:ring-slate-600',
                'gray' => 'text-gray-600 ring-gray-400 bg-gray-300 focus:bg-gray-400 hover:bg-gray-400 border-transparent focus:ring-offset-2 dark:focus:text-gray-400 dark:focus:ring-offset-dark-900 dark:focus:ring-gray-500 dark:bg-opacity-30 dark:bg-gray-600 dark:hover:bg-gray-500 dark:hover:bg-opacity-30 dark:text-gray-400 dark:hover:ring-gray-600',
                'zinc' => 'text-zinc-600 ring-zinc-400 bg-zinc-300 focus:bg-zinc-400 hover:bg-zinc-400 border-transparent focus:ring-offset-2 dark:focus:text-zinc-400 dark:focus:ring-offset-dark-900 dark:focus:ring-zinc-500 dark:bg-opacity-30 dark:bg-zinc-600 dark:hover:bg-zinc-500 dark:hover:bg-opacity-30 dark:text-zinc-400 dark:hover:ring-zinc-600',
                'neutral' => 'text-neutral-600 ring-neutral-400 bg-neutral-300 focus:bg-neutral-400 hover:bg-neutral-400 border-transparent focus:ring-offset-2 dark:focus:text-neutral-400 dark:focus:ring-offset-dark-900 dark:focus:ring-neutral-500 dark:bg-opacity-30 dark:bg-neutral-600 dark:hover:bg-neutral-500 dark:hover:bg-opacity-30 dark:text-neutral-400 dark:hover:ring-neutral-600',
                'stone' => 'text-stone-600 ring-stone-400 bg-stone-300 focus:bg-stone-400 hover:bg-stone-400 border-transparent focus:ring-offset-2 dark:focus:text-stone-400 dark:focus:ring-offset-dark-900 dark:focus:ring-stone-500 dark:bg-opacity-30 dark:bg-stone-600 dark:hover:bg-stone-500 dark:hover:bg-opacity-30 dark:text-stone-400 dark:hover:ring-stone-600',
                'red' => 'text-red-600 ring-red-400 bg-red-300 focus:bg-red-400 hover:bg-red-400 border-transparent focus:ring-offset-2 dark:focus:text-red-400 dark:focus:ring-offset-dark-900 dark:focus:ring-red-500 dark:bg-opacity-30 dark:bg-red-600 dark:hover:bg-red-500 dark:hover:bg-opacity-30 dark:text-red-400 dark:hover:ring-red-600',
                'orange' => 'text-orange-600 ring-orange-400 bg-orange-300 focus:bg-orange-400 hover:bg-orange-400 border-transparent focus:ring-offset-2 dark:focus:text-orange-400 dark:focus:ring-offset-dark-900 dark:focus:ring-orange-500 dark:bg-opacity-30 dark:bg-orange-600 dark:hover:bg-orange-500 dark:hover:bg-opacity-30 dark:text-orange-400 dark:hover:ring-orange-600',
                'amber' => 'text-amber-600 ring-amber-400 bg-amber-300 focus:bg-amber-400 hover:bg-amber-400 border-transparent focus:ring-offset-2 dark:focus:text-amber-400 dark:focus:ring-offset-dark-900 dark:focus:ring-amber-500 dark:bg-opacity-30 dark:bg-amber-600 dark:hover:bg-amber-500 dark:hover:bg-opacity-30 dark:text-amber-400 dark:hover:ring-amber-600',
                'yellow' => 'text-yellow-600 ring-yellow-400 bg-yellow-300 focus:bg-yellow-400 hover:bg-yellow-400 border-transparent focus:ring-offset-2 dark:focus:text-yellow-400 dark:focus:ring-offset-dark-900 dark:focus:ring-yellow-500 dark:bg-opacity-30 dark:bg-yellow-600 dark:hover:bg-yellow-500 dark:hover:bg-opacity-30 dark:text-yellow-400 dark:hover:ring-yellow-600',
                'lime' => 'text-lime-600 ring-lime-400 bg-lime-300 focus:bg-lime-400 hover:bg-lime-400 border-transparent focus:ring-offset-2 dark:focus:text-lime-400 dark:focus:ring-offset-dark-900 dark:focus:ring-lime-500 dark:bg-opacity-30 dark:bg-lime-600 dark:hover:bg-lime-500 dark:hover:bg-opacity-30 dark:text-lime-400 dark:hover:ring-lime-600',
                'green' => 'text-green-600 ring-green-400 bg-green-300 focus:bg-green-400 hover:bg-green-400 border-transparent focus:ring-offset-2 dark:focus:text-green-400 dark:focus:ring-offset-dark-900 dark:focus:ring-green-500 dark:bg-opacity-30 dark:bg-green-600 dark:hover:bg-green-500 dark:hover:bg-opacity-30 dark:text-green-400 dark:hover:ring-green-600',
                'emerald' => 'text-emerald-600 ring-emerald-400 bg-emerald-300 focus:bg-emerald-400 hover:bg-emerald-400 border-transparent focus:ring-offset-2 dark:focus:text-emerald-400 dark:focus:ring-offset-dark-900 dark:focus:ring-emerald-500 dark:bg-opacity-30 dark:bg-emerald-600 dark:hover:bg-emerald-500 dark:hover:bg-opacity-30 dark:text-emerald-400 dark:hover:ring-emerald-600',
                'teal' => 'text-teal-600 ring-teal-400 bg-teal-300 focus:bg-teal-400 hover:bg-teal-400 border-transparent focus:ring-offset-2 dark:focus:text-teal-400 dark:focus:ring-offset-dark-900 dark:focus:ring-teal-500 dark:bg-opacity-30 dark:bg-teal-600 dark:hover:bg-teal-500 dark:hover:bg-opacity-30 dark:text-teal-400 dark:hover:ring-teal-600',
                'cyan' => 'text-cyan-600 ring-cyan-400 bg-cyan-300 focus:bg-cyan-400 hover:bg-cyan-400 border-transparent focus:ring-offset-2 dark:focus:text-cyan-400 dark:focus:ring-offset-dark-900 dark:focus:ring-cyan-500 dark:bg-opacity-30 dark:bg-cyan-600 dark:hover:bg-cyan-500 dark:hover:bg-opacity-30 dark:text-cyan-400 dark:hover:ring-cyan-600',
                'sky' => 'text-sky-600 ring-sky-400 bg-sky-300 focus:bg-sky-400 hover:bg-sky-400 border-transparent focus:ring-offset-2 dark:focus:text-sky-400 dark:focus:ring-offset-dark-900 dark:focus:ring-sky-500 dark:bg-opacity-30 dark:bg-sky-600 dark:hover:bg-sky-500 dark:hover:bg-opacity-30 dark:text-sky-400 dark:hover:ring-sky-600',
                'blue' => 'text-blue-600 ring-blue-400 bg-blue-300 focus:bg-blue-400 hover:bg-blue-400 border-transparent focus:ring-offset-2 dark:focus:text-blue-400 dark:focus:ring-offset-dark-900 dark:focus:ring-blue-500 dark:bg-opacity-30 dark:bg-blue-600 dark:hover:bg-blue-500 dark:hover:bg-opacity-30 dark:text-blue-400 dark:hover:ring-blue-600',
                'indigo' => 'text-indigo-600 ring-indigo-400 bg-indigo-300 focus:bg-indigo-400 hover:bg-indigo-400 border-transparent focus:ring-offset-2 dark:focus:text-indigo-400 dark:focus:ring-offset-dark-900 dark:focus:ring-indigo-500 dark:bg-opacity-30 dark:bg-indigo-600 dark:hover:bg-indigo-500 dark:hover:bg-opacity-30 dark:text-indigo-400 dark:hover:ring-indigo-600',
                'violet' => 'text-violet-600 ring-violet-400 bg-violet-300 focus:bg-violet-400 hover:bg-violet-400 border-transparent focus:ring-offset-2 dark:focus:text-violet-400 dark:focus:ring-offset-dark-900 dark:focus:ring-violet-500 dark:bg-opacity-30 dark:bg-violet-600 dark:hover:bg-violet-500 dark:hover:bg-opacity-30 dark:text-violet-400 dark:hover:ring-violet-600',
                'purple' => 'text-purple-600 ring-purple-400 bg-purple-300 focus:bg-purple-400 hover:bg-purple-400 border-transparent focus:ring-offset-2 dark:focus:text-purple-400 dark:focus:ring-offset-dark-900 dark:focus:ring-purple-500 dark:bg-opacity-30 dark:bg-purple-600 dark:hover:bg-purple-500 dark:hover:bg-opacity-30 dark:text-purple-400 dark:hover:ring-purple-600',
                'fuchsia' => 'text-fuchsia-600 ring-fuchsia-400 bg-fuchsia-300 focus:bg-fuchsia-400 hover:bg-fuchsia-400 border-transparent focus:ring-offset-2 dark:focus:text-fuchsia-400 dark:focus:ring-offset-dark-900 dark:focus:ring-fuchsia-500 dark:bg-opacity-30 dark:bg-fuchsia-600 dark:hover:bg-fuchsia-500 dark:hover:bg-opacity-30 dark:text-fuchsia-400 dark:hover:ring-fuchsia-600',
                'pink' => 'text-pink-600 ring-pink-400 bg-pink-300 focus:bg-pink-400 hover:bg-pink-400 border-transparent focus:ring-offset-2 dark:focus:text-pink-400 dark:focus:ring-offset-dark-900 dark:focus:ring-pink-500 dark:bg-opacity-30 dark:bg-pink-600 dark:hover:bg-pink-500 dark:hover:bg-opacity-30 dark:text-pink-400 dark:hover:ring-pink-600',
                'rose' => 'text-rose-600 ring-rose-400 bg-rose-300 focus:bg-rose-400 hover:bg-rose-400 border-transparent focus:ring-offset-2 dark:focus:text-rose-400 dark:focus:ring-offset-dark-900 dark:focus:ring-rose-500 dark:bg-opacity-30 dark:bg-rose-600 dark:hover:bg-rose-500 dark:hover:bg-opacity-30 dark:text-rose-400 dark:hover:ring-rose-600',
            ],
        ];
    }

    private function icon(): array
    {
        return [
            'solid' => [
                'white' => 'text-white',
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
                'black' => 'text-black-600',
                'primary' => 'text-primary-600 dark:text-primary-500',
                'secondary' => 'text-secondary-600 dark:text-secondary-500',
                'slate' => 'text-slate-600 dark:text-slate-500',
                'gray' => 'text-gray-600 dark:text-gray-500',
                'zinc' => 'text-zinc-600 dark:text-zinc-500',
                'neutral' => 'text-neutral-600 dark:text-neutral-500',
                'stone' => 'text-stone-600 dark:text-stone-500',
                'red' => 'text-red-600 dark:text-red-500',
                'orange' => 'text-orange-600 dark:text-orange-500',
                'amber' => 'text-amber-600 dark:text-amber-500',
                'yellow' => 'text-yellow-600 dark:text-yellow-500',
                'lime' => 'text-lime-600 dark:text-lime-500',
                'green' => 'text-green-600 dark:text-green-500',
                'emerald' => 'text-emerald-600 dark:text-emerald-500',
                'teal' => 'text-teal-600 dark:text-teal-500',
                'cyan' => 'text-cyan-600 dark:text-cyan-500',
                'sky' => 'text-sky-600 dark:text-sky-500',
                'blue' => 'text-blue-600 dark:text-blue-500',
                'indigo' => 'text-indigo-600 dark:text-indigo-500',
                'violet' => 'text-violet-600 dark:text-violet-500',
                'purple' => 'text-purple-600 dark:text-purple-500',
                'fuchsia' => 'text-fuchsia-600 dark:text-fuchsia-500',
                'pink' => 'text-pink-600 dark:text-pink-500',
                'rose' => 'text-rose-600 dark:text-rose-500',
            ],
        ];
    }
}
