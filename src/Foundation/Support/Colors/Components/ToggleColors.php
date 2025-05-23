<?php

namespace TallStackUi\Foundation\Support\Colors\Components;

use TallStackUi\Foundation\Support\Colors\Concerns\SetupColors;

class ToggleColors
{
    use SetupColors;

    public function colors(): array
    {
        $getter = $this->component->color; // @phpstan-ignore-line

        return ['background' => data_get($this->get('background'), $getter) ?? data_get($this->background(), $getter)];
    }

    private function background(): array
    {
        return [
            'black' => 'peer-checked:bg-black',
            'primary' => 'peer-checked:bg-primary-500',
            'secondary' => 'peer-checked:bg-secondary-500',
            'slate' => 'peer-checked:bg-slate-500',
            'gray' => 'peer-checked:bg-gray-500',
            'zinc' => 'peer-checked:bg-zinc-500',
            'neutral' => 'peer-checked:bg-neutral-500',
            'stone' => 'peer-checked:bg-stone-500',
            'red' => 'peer-checked:bg-red-500',
            'orange' => 'peer-checked:bg-orange-500',
            'amber' => 'peer-checked:bg-amber-500',
            'yellow' => 'peer-checked:bg-yellow-500',
            'lime' => 'peer-checked:bg-lime-500',
            'green' => 'peer-checked:bg-green-500',
            'emerald' => 'peer-checked:bg-emerald-500',
            'teal' => 'peer-checked:bg-teal-500',
            'cyan' => 'peer-checked:bg-cyan-500',
            'sky' => 'peer-checked:bg-sky-500',
            'blue' => 'peer-checked:bg-blue-500',
            'indigo' => 'peer-checked:bg-indigo-500',
            'violet' => 'peer-checked:bg-violet-500',
            'purple' => 'peer-checked:bg-purple-500',
            'fuchsia' => 'peer-checked:bg-fuchsia-500',
            'pink' => 'peer-checked:bg-pink-500',
            'rose' => 'peer-checked:bg-rose-500',
        ];
    }
}
