<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;

class RadioColors
{
    use OverrideColors;

    public function __construct(protected Radio|Checkbox $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        // We just need to $this->format when we
        // have a style and color, otherwise we
        // can just use the color as the getter.
        $getter = $this->component->color;

        return ['background' => data_get($this->get('background'), $getter, data_get($this->background(), $getter))];
    }

    private function background(): array
    {
        return [
            'white' => 'text-white focus:ring-white dark:ring-offset-dark-900',
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
        ];
    }
}
