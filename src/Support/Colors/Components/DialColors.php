<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

class DialColors
{
    use SetupColors;

    public function colors(): array
    {
        [$background, $icon] = $this->get('background', 'icon');

        $getter = $this->format($this->component->style, $this->component->color); // @phpstan-ignore-line

        return [
            'background' => data_get($background, $getter) ?? data_get($this->background(), $getter),
            'icon' => data_get($icon, $getter) ?? data_get($this->icon(), $getter),
        ];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'black' => 'text-white ring-black bg-black focus:bg-black hover:bg-black/80 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-black dark:bg-black dark:hover:bg-black/80 dark:hover:ring-black',
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
                'mauve' => 'text-mauve-50 ring-mauve-500 bg-mauve-500 focus:bg-mauve-600 hover:bg-mauve-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-mauve-600 dark:bg-mauve-700 dark:hover:bg-mauve-600 dark:hover:ring-mauve-600',
                'olive' => 'text-olive-50 ring-olive-500 bg-olive-500 focus:bg-olive-600 hover:bg-olive-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-olive-600 dark:bg-olive-700 dark:hover:bg-olive-600 dark:hover:ring-olive-600',
                'mist' => 'text-mist-50 ring-mist-500 bg-mist-500 focus:bg-mist-600 hover:bg-mist-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-mist-600 dark:bg-mist-700 dark:hover:bg-mist-600 dark:hover:ring-mist-600',
                'taupe' => 'text-taupe-50 ring-taupe-500 bg-taupe-500 focus:bg-taupe-600 hover:bg-taupe-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-taupe-600 dark:bg-taupe-700 dark:hover:bg-taupe-600 dark:hover:ring-taupe-600',
            ],
        ];
    }

    private function icon(): array
    {
        return [
            'solid' => [
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
                'mauve' => 'text-mauve-50',
                'olive' => 'text-olive-50',
                'mist' => 'text-mist-50',
                'taupe' => 'text-taupe-50',
            ],
        ];
    }
}
