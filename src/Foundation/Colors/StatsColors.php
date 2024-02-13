<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Stats;

class StatsColors
{
    use OverrideColors;

    public function __construct(protected Stats $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        // We just need to $this->format when we
        // have a style and color, otherwise we
        // can just use the color as the getter.
        $getter = $this->format($this->component->style, $this->component->color);

        return ['background' => data_get($this->get('background'), $getter, data_get($this->background(), $getter))];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'white' => 'bg-white text-white',
                'black' => 'bg-black text-white',
                'primary' => 'bg-primary-500 text-white',
                'secondary' => 'bg-secondary-500 text-white',
                'slate' => 'bg-slate-500 text-white',
                'gray' => 'bg-gray-500 text-white',
                'zinc' => 'bg-zinc-500 text-white',
                'neutral' => 'bg-neutral-500 text-white',
                'stone' => 'bg-stone-500 text-white',
                'red' => 'bg-red-500 text-white',
                'orange' => 'bg-orange-500 text-white',
                'amber' => 'bg-amber-500 text-white',
                'yellow' => 'bg-yellow-500 text-white',
                'lime' => 'bg-lime-500 text-white',
                'green' => 'bg-green-500 text-white',
                'emerald' => 'bg-emerald-500 text-white',
                'teal' => 'bg-teal-500 text-white',
                'cyan' => 'bg-cyan-500 text-white',
                'sky' => 'bg-sky-500 text-white',
                'blue' => 'bg-blue-500 text-white',
                'indigo' => 'bg-indigo-500 text-white',
                'violet' => 'bg-violet-500 text-white',
                'purple' => 'bg-purple-500 text-white',
                'fuchsia' => 'bg-fuchsia-500 text-white',
                'pink' => 'bg-pink-500 text-white',
                'rose' => 'bg-rose-500 text-white',
            ],
            'light' => [
                'white' => 'bg-white text-black',
                'black' => 'bg-black text-black-600',
                'primary' => 'bg-primary-300 text-primary-600',
                'secondary' => 'bg-secondary-300 text-secondary-600',
                'slate' => 'bg-slate-300 text-slate-600',
                'gray' => 'bg-gray-300 text-gray-600',
                'zinc' => 'bg-zinc-300 text-zinc-600',
                'neutral' => 'bg-neutral-300 text-neutral-600',
                'stone' => 'bg-stone-300 text-stone-600',
                'red' => 'bg-red-300 text-red-600',
                'orange' => 'bg-orange-300 text-orange-600',
                'amber' => 'bg-amber-300 text-amber-600',
                'yellow' => 'bg-yellow-300 text-yellow-600',
                'lime' => 'bg-lime-300 text-lime-600',
                'green' => 'bg-green-300 text-green-600',
                'emerald' => 'bg-emerald-300 text-emerald-600',
                'teal' => 'bg-teal-300 text-teal-600',
                'cyan' => 'bg-cyan-300 text-cyan-600',
                'sky' => 'bg-sky-300 text-sky-600',
                'blue' => 'bg-blue-300 text-blue-600',
                'indigo' => 'bg-indigo-300 text-indigo-600',
                'violet' => 'bg-violet-300 text-violet-600',
                'purple' => 'bg-purple-300 text-purple-600',
                'fuchsia' => 'bg-fuchsia-300 text-fuchsia-600',
                'pink' => 'bg-pink-300 text-pink-600',
                'rose' => 'bg-rose-300 text-rose-600',
            ],
        ];
    }
}
