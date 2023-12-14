<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Form\Toggle;

class ToggleColors
{
    use OverrideColors;

    public function __construct(protected Toggle $component)
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
            'white' => 'peer-checked:bg-white peer-focus:ring-white group-focus:ring-white dark:ring-offset-dark-900',
            'black' => 'peer-checked:bg-black peer-focus:ring-black group-focus:ring-black dark:ring-offset-dark-900',
            'primary' => 'peer-checked:bg-primary-500 peer-focus:ring-primary-500 group-focus:ring-primary-500 dark:ring-offset-dark-900',
            'secondary' => 'peer-checked:bg-secondary-500 peer-focus:ring-secondary-500 group-focus:ring-secondary-500 dark:ring-offset-dark-900',
            'slate' => 'peer-checked:bg-slate-500 peer-focus:ring-slate-500 group-focus:ring-slate-500 dark:ring-offset-dark-900',
            'gray' => 'peer-checked:bg-gray-500 peer-focus:ring-gray-500 group-focus:ring-gray-500 dark:ring-offset-dark-900',
            'zinc' => 'peer-checked:bg-zinc-500 peer-focus:ring-zinc-500 group-focus:ring-zinc-500 dark:ring-offset-dark-900',
            'neutral' => 'peer-checked:bg-neutral-500 peer-focus:ring-neutral-500 group-focus:ring-neutral-500 dark:ring-offset-dark-900',
            'stone' => 'peer-checked:bg-stone-500 peer-focus:ring-stone-500 group-focus:ring-stone-500 dark:ring-offset-dark-900',
            'red' => 'peer-checked:bg-red-500 peer-focus:ring-red-500 group-focus:ring-red-500 dark:ring-offset-dark-900',
            'orange' => 'peer-checked:bg-orange-500 peer-focus:ring-orange-500 group-focus:ring-orange-500 dark:ring-offset-dark-900',
            'amber' => 'peer-checked:bg-amber-500 peer-focus:ring-amber-500 group-focus:ring-amber-500 dark:ring-offset-dark-900',
            'yellow' => 'peer-checked:bg-yellow-500 peer-focus:ring-yellow-500 group-focus:ring-yellow-500 dark:ring-offset-dark-900',
            'lime' => 'peer-checked:bg-lime-500 peer-focus:ring-lime-500 group-focus:ring-lime-500 dark:ring-offset-dark-900',
            'green' => 'peer-checked:bg-green-500 peer-focus:ring-green-500 group-focus:ring-green-500 dark:ring-offset-dark-900',
            'emerald' => 'peer-checked:bg-emerald-500 peer-focus:ring-emerald-500 group-focus:ring-emerald-500 dark:ring-offset-dark-900',
            'teal' => 'peer-checked:bg-teal-500 peer-focus:ring-teal-500 group-focus:ring-teal-500 dark:ring-offset-dark-900',
            'cyan' => 'peer-checked:bg-cyan-500 peer-focus:ring-cyan-500 group-focus:ring-cyan-500 dark:ring-offset-dark-900',
            'sky' => 'peer-checked:bg-sky-500 peer-focus:ring-sky-500 group-focus:ring-sky-500 dark:ring-offset-dark-900',
            'blue' => 'peer-checked:bg-blue-500 peer-focus:ring-blue-500 group-focus:ring-blue-500 dark:ring-offset-dark-900',
            'indigo' => 'peer-checked:bg-indigo-500 peer-focus:ring-indigo-500 group-focus:ring-indigo-500 dark:ring-offset-dark-900',
            'violet' => 'peer-checked:bg-violet-500 peer-focus:ring-violet-500 group-focus:ring-violet-500 dark:ring-offset-dark-900',
            'purple' => 'peer-checked:bg-purple-500 peer-focus:ring-purple-500 group-focus:ring-purple-500 dark:ring-offset-dark-900',
            'fuchsia' => 'peer-checked:bg-fuchsia-500 peer-focus:ring-fuchsia-500 group-focus:ring-fuchsia-500 dark:ring-offset-dark-900',
            'pink' => 'peer-checked:bg-pink-500 peer-focus:ring-pink-500 group-focus:ring-pink-500 dark:ring-offset-dark-900',
            'rose' => 'peer-checked:bg-rose-500 peer-focus:ring-rose-500 group-focus:ring-rose-500 dark:ring-offset-dark-900',
        ];
    }
}
