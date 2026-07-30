<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

class SpinnerColors
{
    use SetupColors;

    public function colors(): array
    {
        $getter = $this->component->color; // @phpstan-ignore-line

        return ['text' => data_get($this->get('text'), $getter) ?? data_get($this->text(), $getter)];
    }

    private function text(): array
    {
        return [
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
            'mauve' => 'text-mauve-600',
            'olive' => 'text-olive-600',
            'mist' => 'text-mist-600',
            'taupe' => 'text-taupe-600',
        ];
    }
}
