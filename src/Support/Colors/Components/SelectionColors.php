<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

/** Colors of the `<x-radio.group />` and `<x-checkbox.group />` components. */
class SelectionColors
{
    use SetupColors;

    public function colors(): array
    {
        $getter = $this->component->color; // @phpstan-ignore-line

        [$background, $border, $control, $muted, $solid, $text] = $this->get('background', 'border', 'control', 'muted', 'solid', 'text');

        return [
            'background' => data_get($background, $getter) ?? data_get($this->background(), $getter),
            'border' => data_get($border, $getter) ?? data_get($this->border(), $getter),
            'control' => data_get($control, $getter) ?? data_get($this->control(), $getter),
            'muted' => data_get($muted, $getter) ?? data_get($this->muted(), $getter),
            'solid' => data_get($solid, $getter) ?? data_get($this->solid(), $getter),
            'text' => data_get($text, $getter) ?? data_get($this->text(), $getter),
        ];
    }

    private function background(): array
    {
        return [
            'black' => 'has-checked:bg-gray-100 dark:has-checked:bg-dark-800',
            'primary' => 'has-checked:bg-primary-50 dark:has-checked:bg-primary-900/20',
            'secondary' => 'has-checked:bg-secondary-50 dark:has-checked:bg-secondary-900/20',
            'slate' => 'has-checked:bg-slate-50 dark:has-checked:bg-slate-900/20',
            'gray' => 'has-checked:bg-gray-50 dark:has-checked:bg-gray-900/20',
            'zinc' => 'has-checked:bg-zinc-50 dark:has-checked:bg-zinc-900/20',
            'neutral' => 'has-checked:bg-neutral-50 dark:has-checked:bg-neutral-900/20',
            'stone' => 'has-checked:bg-stone-50 dark:has-checked:bg-stone-900/20',
            'red' => 'has-checked:bg-red-50 dark:has-checked:bg-red-900/20',
            'orange' => 'has-checked:bg-orange-50 dark:has-checked:bg-orange-900/20',
            'amber' => 'has-checked:bg-amber-50 dark:has-checked:bg-amber-900/20',
            'yellow' => 'has-checked:bg-yellow-50 dark:has-checked:bg-yellow-900/20',
            'lime' => 'has-checked:bg-lime-50 dark:has-checked:bg-lime-900/20',
            'green' => 'has-checked:bg-green-50 dark:has-checked:bg-green-900/20',
            'emerald' => 'has-checked:bg-emerald-50 dark:has-checked:bg-emerald-900/20',
            'teal' => 'has-checked:bg-teal-50 dark:has-checked:bg-teal-900/20',
            'cyan' => 'has-checked:bg-cyan-50 dark:has-checked:bg-cyan-900/20',
            'sky' => 'has-checked:bg-sky-50 dark:has-checked:bg-sky-900/20',
            'blue' => 'has-checked:bg-blue-50 dark:has-checked:bg-blue-900/20',
            'indigo' => 'has-checked:bg-indigo-50 dark:has-checked:bg-indigo-900/20',
            'violet' => 'has-checked:bg-violet-50 dark:has-checked:bg-violet-900/20',
            'purple' => 'has-checked:bg-purple-50 dark:has-checked:bg-purple-900/20',
            'fuchsia' => 'has-checked:bg-fuchsia-50 dark:has-checked:bg-fuchsia-900/20',
            'pink' => 'has-checked:bg-pink-50 dark:has-checked:bg-pink-900/20',
            'rose' => 'has-checked:bg-rose-50 dark:has-checked:bg-rose-900/20',
            'mauve' => 'has-checked:bg-mauve-50 dark:has-checked:bg-mauve-900/20',
            'olive' => 'has-checked:bg-olive-50 dark:has-checked:bg-olive-900/20',
            'mist' => 'has-checked:bg-mist-50 dark:has-checked:bg-mist-900/20',
            'taupe' => 'has-checked:bg-taupe-50 dark:has-checked:bg-taupe-900/20',
        ];
    }

    private function border(): array
    {
        return [
            'black' => 'has-checked:border-black dark:has-checked:border-white',
            'primary' => 'has-checked:border-primary-500 dark:has-checked:border-primary-400',
            'secondary' => 'has-checked:border-secondary-500 dark:has-checked:border-secondary-400',
            'slate' => 'has-checked:border-slate-500 dark:has-checked:border-slate-400',
            'gray' => 'has-checked:border-gray-500 dark:has-checked:border-gray-400',
            'zinc' => 'has-checked:border-zinc-500 dark:has-checked:border-zinc-400',
            'neutral' => 'has-checked:border-neutral-500 dark:has-checked:border-neutral-400',
            'stone' => 'has-checked:border-stone-500 dark:has-checked:border-stone-400',
            'red' => 'has-checked:border-red-500 dark:has-checked:border-red-400',
            'orange' => 'has-checked:border-orange-500 dark:has-checked:border-orange-400',
            'amber' => 'has-checked:border-amber-500 dark:has-checked:border-amber-400',
            'yellow' => 'has-checked:border-yellow-500 dark:has-checked:border-yellow-400',
            'lime' => 'has-checked:border-lime-500 dark:has-checked:border-lime-400',
            'green' => 'has-checked:border-green-500 dark:has-checked:border-green-400',
            'emerald' => 'has-checked:border-emerald-500 dark:has-checked:border-emerald-400',
            'teal' => 'has-checked:border-teal-500 dark:has-checked:border-teal-400',
            'cyan' => 'has-checked:border-cyan-500 dark:has-checked:border-cyan-400',
            'sky' => 'has-checked:border-sky-500 dark:has-checked:border-sky-400',
            'blue' => 'has-checked:border-blue-500 dark:has-checked:border-blue-400',
            'indigo' => 'has-checked:border-indigo-500 dark:has-checked:border-indigo-400',
            'violet' => 'has-checked:border-violet-500 dark:has-checked:border-violet-400',
            'purple' => 'has-checked:border-purple-500 dark:has-checked:border-purple-400',
            'fuchsia' => 'has-checked:border-fuchsia-500 dark:has-checked:border-fuchsia-400',
            'pink' => 'has-checked:border-pink-500 dark:has-checked:border-pink-400',
            'rose' => 'has-checked:border-rose-500 dark:has-checked:border-rose-400',
            'mauve' => 'has-checked:border-mauve-500 dark:has-checked:border-mauve-400',
            'olive' => 'has-checked:border-olive-500 dark:has-checked:border-olive-400',
            'mist' => 'has-checked:border-mist-500 dark:has-checked:border-mist-400',
            'taupe' => 'has-checked:border-taupe-500 dark:has-checked:border-taupe-400',
        ];
    }

    private function control(): array
    {
        return [
            'black' => 'text-black focus:ring-black dark:ring-offset-dark-900',
            'primary' => 'text-primary-500 focus:ring-primary-500 dark:ring-offset-dark-900',
            'secondary' => 'text-secondary-500 focus:ring-secondary-500 dark:ring-offset-dark-900',
            'slate' => 'text-slate-500 focus:ring-slate-500 dark:ring-offset-dark-900',
            'gray' => 'text-gray-500 focus:ring-gray-500 dark:ring-offset-dark-900',
            'zinc' => 'text-zinc-500 focus:ring-zinc-500 dark:ring-offset-dark-900',
            'neutral' => 'text-neutral-500 focus:ring-neutral-500 dark:ring-offset-dark-900',
            'stone' => 'text-stone-500 focus:ring-stone-500 dark:ring-offset-dark-900',
            'red' => 'text-red-500 focus:ring-red-500 dark:ring-offset-dark-900',
            'orange' => 'text-orange-500 focus:ring-orange-500 dark:ring-offset-dark-900',
            'amber' => 'text-amber-500 focus:ring-amber-500 dark:ring-offset-dark-900',
            'yellow' => 'text-yellow-500 focus:ring-yellow-500 dark:ring-offset-dark-900',
            'lime' => 'text-lime-500 focus:ring-lime-500 dark:ring-offset-dark-900',
            'green' => 'text-green-500 focus:ring-green-500 dark:ring-offset-dark-900',
            'emerald' => 'text-emerald-500 focus:ring-emerald-500 dark:ring-offset-dark-900',
            'teal' => 'text-teal-500 focus:ring-teal-500 dark:ring-offset-dark-900',
            'cyan' => 'text-cyan-500 focus:ring-cyan-500 dark:ring-offset-dark-900',
            'sky' => 'text-sky-500 focus:ring-sky-500 dark:ring-offset-dark-900',
            'blue' => 'text-blue-500 focus:ring-blue-500 dark:ring-offset-dark-900',
            'indigo' => 'text-indigo-500 focus:ring-indigo-500 dark:ring-offset-dark-900',
            'violet' => 'text-violet-500 focus:ring-violet-500 dark:ring-offset-dark-900',
            'purple' => 'text-purple-500 focus:ring-purple-500 dark:ring-offset-dark-900',
            'fuchsia' => 'text-fuchsia-500 focus:ring-fuchsia-500 dark:ring-offset-dark-900',
            'pink' => 'text-pink-500 focus:ring-pink-500 dark:ring-offset-dark-900',
            'rose' => 'text-rose-500 focus:ring-rose-500 dark:ring-offset-dark-900',
            'mauve' => 'text-mauve-500 focus:ring-mauve-500 dark:ring-offset-dark-900',
            'olive' => 'text-olive-500 focus:ring-olive-500 dark:ring-offset-dark-900',
            'mist' => 'text-mist-500 focus:ring-mist-500 dark:ring-offset-dark-900',
            'taupe' => 'text-taupe-500 focus:ring-taupe-500 dark:ring-offset-dark-900',
        ];
    }

    private function muted(): array
    {
        return [
            'black' => 'group-has-checked:text-gray-700 dark:group-has-checked:text-gray-300',
            'primary' => 'group-has-checked:text-primary-700 dark:group-has-checked:text-primary-300',
            'secondary' => 'group-has-checked:text-secondary-700 dark:group-has-checked:text-secondary-300',
            'slate' => 'group-has-checked:text-slate-700 dark:group-has-checked:text-slate-300',
            'gray' => 'group-has-checked:text-gray-700 dark:group-has-checked:text-gray-300',
            'zinc' => 'group-has-checked:text-zinc-700 dark:group-has-checked:text-zinc-300',
            'neutral' => 'group-has-checked:text-neutral-700 dark:group-has-checked:text-neutral-300',
            'stone' => 'group-has-checked:text-stone-700 dark:group-has-checked:text-stone-300',
            'red' => 'group-has-checked:text-red-700 dark:group-has-checked:text-red-300',
            'orange' => 'group-has-checked:text-orange-700 dark:group-has-checked:text-orange-300',
            'amber' => 'group-has-checked:text-amber-700 dark:group-has-checked:text-amber-300',
            'yellow' => 'group-has-checked:text-yellow-700 dark:group-has-checked:text-yellow-300',
            'lime' => 'group-has-checked:text-lime-700 dark:group-has-checked:text-lime-300',
            'green' => 'group-has-checked:text-green-700 dark:group-has-checked:text-green-300',
            'emerald' => 'group-has-checked:text-emerald-700 dark:group-has-checked:text-emerald-300',
            'teal' => 'group-has-checked:text-teal-700 dark:group-has-checked:text-teal-300',
            'cyan' => 'group-has-checked:text-cyan-700 dark:group-has-checked:text-cyan-300',
            'sky' => 'group-has-checked:text-sky-700 dark:group-has-checked:text-sky-300',
            'blue' => 'group-has-checked:text-blue-700 dark:group-has-checked:text-blue-300',
            'indigo' => 'group-has-checked:text-indigo-700 dark:group-has-checked:text-indigo-300',
            'violet' => 'group-has-checked:text-violet-700 dark:group-has-checked:text-violet-300',
            'purple' => 'group-has-checked:text-purple-700 dark:group-has-checked:text-purple-300',
            'fuchsia' => 'group-has-checked:text-fuchsia-700 dark:group-has-checked:text-fuchsia-300',
            'pink' => 'group-has-checked:text-pink-700 dark:group-has-checked:text-pink-300',
            'rose' => 'group-has-checked:text-rose-700 dark:group-has-checked:text-rose-300',
            'mauve' => 'group-has-checked:text-mauve-700 dark:group-has-checked:text-mauve-300',
            'olive' => 'group-has-checked:text-olive-700 dark:group-has-checked:text-olive-300',
            'mist' => 'group-has-checked:text-mist-700 dark:group-has-checked:text-mist-300',
            'taupe' => 'group-has-checked:text-taupe-700 dark:group-has-checked:text-taupe-300',
        ];
    }

    private function solid(): array
    {
        return [
            'black' => 'has-checked:bg-black',
            'primary' => 'has-checked:bg-primary-500',
            'secondary' => 'has-checked:bg-secondary-500',
            'slate' => 'has-checked:bg-slate-500',
            'gray' => 'has-checked:bg-gray-500',
            'zinc' => 'has-checked:bg-zinc-500',
            'neutral' => 'has-checked:bg-neutral-500',
            'stone' => 'has-checked:bg-stone-500',
            'red' => 'has-checked:bg-red-500',
            'orange' => 'has-checked:bg-orange-500',
            'amber' => 'has-checked:bg-amber-500',
            'yellow' => 'has-checked:bg-yellow-500',
            'lime' => 'has-checked:bg-lime-500',
            'green' => 'has-checked:bg-green-500',
            'emerald' => 'has-checked:bg-emerald-500',
            'teal' => 'has-checked:bg-teal-500',
            'cyan' => 'has-checked:bg-cyan-500',
            'sky' => 'has-checked:bg-sky-500',
            'blue' => 'has-checked:bg-blue-500',
            'indigo' => 'has-checked:bg-indigo-500',
            'violet' => 'has-checked:bg-violet-500',
            'purple' => 'has-checked:bg-purple-500',
            'fuchsia' => 'has-checked:bg-fuchsia-500',
            'pink' => 'has-checked:bg-pink-500',
            'rose' => 'has-checked:bg-rose-500',
            'mauve' => 'has-checked:bg-mauve-500',
            'olive' => 'has-checked:bg-olive-500',
            'mist' => 'has-checked:bg-mist-500',
            'taupe' => 'has-checked:bg-taupe-500',
        ];
    }

    private function text(): array
    {
        return [
            'black' => 'group-has-checked:text-black dark:group-has-checked:text-white',
            'primary' => 'group-has-checked:text-primary-900 dark:group-has-checked:text-primary-200',
            'secondary' => 'group-has-checked:text-secondary-900 dark:group-has-checked:text-secondary-200',
            'slate' => 'group-has-checked:text-slate-900 dark:group-has-checked:text-slate-200',
            'gray' => 'group-has-checked:text-gray-900 dark:group-has-checked:text-gray-200',
            'zinc' => 'group-has-checked:text-zinc-900 dark:group-has-checked:text-zinc-200',
            'neutral' => 'group-has-checked:text-neutral-900 dark:group-has-checked:text-neutral-200',
            'stone' => 'group-has-checked:text-stone-900 dark:group-has-checked:text-stone-200',
            'red' => 'group-has-checked:text-red-900 dark:group-has-checked:text-red-200',
            'orange' => 'group-has-checked:text-orange-900 dark:group-has-checked:text-orange-200',
            'amber' => 'group-has-checked:text-amber-900 dark:group-has-checked:text-amber-200',
            'yellow' => 'group-has-checked:text-yellow-900 dark:group-has-checked:text-yellow-200',
            'lime' => 'group-has-checked:text-lime-900 dark:group-has-checked:text-lime-200',
            'green' => 'group-has-checked:text-green-900 dark:group-has-checked:text-green-200',
            'emerald' => 'group-has-checked:text-emerald-900 dark:group-has-checked:text-emerald-200',
            'teal' => 'group-has-checked:text-teal-900 dark:group-has-checked:text-teal-200',
            'cyan' => 'group-has-checked:text-cyan-900 dark:group-has-checked:text-cyan-200',
            'sky' => 'group-has-checked:text-sky-900 dark:group-has-checked:text-sky-200',
            'blue' => 'group-has-checked:text-blue-900 dark:group-has-checked:text-blue-200',
            'indigo' => 'group-has-checked:text-indigo-900 dark:group-has-checked:text-indigo-200',
            'violet' => 'group-has-checked:text-violet-900 dark:group-has-checked:text-violet-200',
            'purple' => 'group-has-checked:text-purple-900 dark:group-has-checked:text-purple-200',
            'fuchsia' => 'group-has-checked:text-fuchsia-900 dark:group-has-checked:text-fuchsia-200',
            'pink' => 'group-has-checked:text-pink-900 dark:group-has-checked:text-pink-200',
            'rose' => 'group-has-checked:text-rose-900 dark:group-has-checked:text-rose-200',
            'mauve' => 'group-has-checked:text-mauve-900 dark:group-has-checked:text-mauve-200',
            'olive' => 'group-has-checked:text-olive-900 dark:group-has-checked:text-olive-200',
            'mist' => 'group-has-checked:text-mist-900 dark:group-has-checked:text-mist-200',
            'taupe' => 'group-has-checked:text-taupe-900 dark:group-has-checked:text-taupe-200',
        ];
    }
}
