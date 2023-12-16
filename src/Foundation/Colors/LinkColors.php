<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Link;

class LinkColors
{
    use OverrideColors;

    public function __construct(protected Link $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        $text = $this->get('text');

        // We just need to $this->format when we
        // have a style and color, otherwise we
        // can just use the color as the getter.
        $getter = $this->component->color;

        // For :color="null"
        if (! $getter) {
            return ['text' => ''];
        }

        return ['text' => data_get($text, $getter, data_get($this->text(), $getter))];
    }

    private function text(): array
    {
        return [
            'white' => 'text-white',
            'black' => 'text-black',
            'primary' => 'text-primary-500 dark:text-primary-300',
            'secondary' => 'text-secondary-500 dark:text-secondary-300',
            'slate' => 'text-slate-500 dark:text-slate-300',
            'gray' => 'text-gray-500 dark:text-gray-300',
            'zinc' => 'text-zinc-500 dark:text-zinc-300',
            'neutral' => 'text-neutral-500 dark:text-neutral-300',
            'stone' => 'text-stone-500 dark:text-stone-300',
            'red' => 'text-red-500 dark:text-red-300',
            'orange' => 'text-orange-500 dark:text-orange-300',
            'amber' => 'text-amber-500 dark:text-amber-300',
            'yellow' => 'text-yellow-500 dark:text-yellow-300',
            'lime' => 'text-lime-500 dark:text-lime-300',
            'green' => 'text-green-500 dark:text-green-300',
            'emerald' => 'text-emerald-500 dark:text-emerald-300',
            'teal' => 'text-teal-500 dark:text-teal-300',
            'cyan' => 'text-cyan-500 dark:text-cyan-300',
            'sky' => 'text-sky-500 dark:text-sky-300',
            'blue' => 'text-blue-500 dark:text-blue-300',
            'indigo' => 'text-indigo-500 dark:text-indigo-300',
            'violet' => 'text-violet-500 dark:text-violet-300',
            'purple' => 'text-purple-500 dark:text-purple-300',
            'fuchsia' => 'text-fuchsia-500 dark:text-fuchsia-300',
            'pink' => 'text-pink-500 dark:text-pink-300',
            'rose' => 'text-rose-500 dark:text-rose-300',
        ];
    }
}
