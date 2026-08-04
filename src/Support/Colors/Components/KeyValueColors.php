<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

class KeyValueColors
{
    use SetupColors;

    public function colors(): array
    {
        [$header, $button] = $this->get('header', 'button');

        // An empty string hands the element back to its neutral customization
        // block, so a color and a neutral never land on the same element.
        if ($this->component->colorless === true) { // @phpstan-ignore-line
            return ['header' => '', 'button' => ''];
        }

        $getter = $this->component->color; // @phpstan-ignore-line

        // The header is a caption and stays neutral until a color is asked for.
        // The button is an action, so it carries the accent unasked.
        return [
            'header' => $getter ? (data_get($header, $getter) ?? data_get($this->header(), $getter) ?? '') : '',
            'button' => data_get($button, $getter ?? 'primary') ?? data_get($this->button(), $getter ?? 'primary') ?? '',
        ];
    }

    private function button(): array
    {
        return [
            'black' => 'text-black hover:bg-gray-100 dark:text-white dark:hover:bg-dark-600',
            'primary' => 'text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-950/30',
            'secondary' => 'text-secondary-600 hover:bg-secondary-50 dark:text-secondary-400 dark:hover:bg-secondary-950/30',
            'slate' => 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-950/30',
            'gray' => 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-950/30',
            'zinc' => 'text-zinc-600 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:bg-zinc-950/30',
            'neutral' => 'text-neutral-600 hover:bg-neutral-50 dark:text-neutral-400 dark:hover:bg-neutral-950/30',
            'stone' => 'text-stone-600 hover:bg-stone-50 dark:text-stone-400 dark:hover:bg-stone-950/30',
            'red' => 'text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30',
            'orange' => 'text-orange-600 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-950/30',
            'amber' => 'text-amber-600 hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-950/30',
            'yellow' => 'text-yellow-600 hover:bg-yellow-50 dark:text-yellow-400 dark:hover:bg-yellow-950/30',
            'lime' => 'text-lime-600 hover:bg-lime-50 dark:text-lime-400 dark:hover:bg-lime-950/30',
            'green' => 'text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-950/30',
            'emerald' => 'text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-950/30',
            'teal' => 'text-teal-600 hover:bg-teal-50 dark:text-teal-400 dark:hover:bg-teal-950/30',
            'cyan' => 'text-cyan-600 hover:bg-cyan-50 dark:text-cyan-400 dark:hover:bg-cyan-950/30',
            'sky' => 'text-sky-600 hover:bg-sky-50 dark:text-sky-400 dark:hover:bg-sky-950/30',
            'blue' => 'text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-950/30',
            'indigo' => 'text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/30',
            'violet' => 'text-violet-600 hover:bg-violet-50 dark:text-violet-400 dark:hover:bg-violet-950/30',
            'purple' => 'text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-950/30',
            'fuchsia' => 'text-fuchsia-600 hover:bg-fuchsia-50 dark:text-fuchsia-400 dark:hover:bg-fuchsia-950/30',
            'pink' => 'text-pink-600 hover:bg-pink-50 dark:text-pink-400 dark:hover:bg-pink-950/30',
            'rose' => 'text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30',
            'mauve' => 'text-mauve-600 hover:bg-mauve-50 dark:text-mauve-400 dark:hover:bg-mauve-950/30',
            'olive' => 'text-olive-600 hover:bg-olive-50 dark:text-olive-400 dark:hover:bg-olive-950/30',
            'mist' => 'text-mist-600 hover:bg-mist-50 dark:text-mist-400 dark:hover:bg-mist-950/30',
            'taupe' => 'text-taupe-600 hover:bg-taupe-50 dark:text-taupe-400 dark:hover:bg-taupe-950/30',
        ];
    }

    private function header(): array
    {
        return [
            'black' => 'text-black dark:text-white',
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
            'mauve' => 'text-mauve-600 dark:text-mauve-400',
            'olive' => 'text-olive-600 dark:text-olive-400',
            'mist' => 'text-mist-600 dark:text-mist-400',
            'taupe' => 'text-taupe-600 dark:text-taupe-400',
        ];
    }
}
