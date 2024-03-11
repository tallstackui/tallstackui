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
                'white' => 'bg-white text-black',
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
                'white' => 'text-white',
                'black' => 'text-black',
                'primary' => 'text-primary-400',
                'secondary' => 'text-secondary-400',
                'slate' => 'text-slate-400',
                'gray' => 'text-gray-400',
                'zinc' => 'text-zinc-400',
                'neutral' => 'text-neutral-400',
                'stone' => 'text-stone-400',
                'red' => 'text-red-400',
                'orange' => 'text-orange-400',
                'amber' => 'text-amber-400',
                'yellow' => 'text-yellow-400',
                'lime' => 'text-lime-400',
                'green' => 'text-green-400',
                'emerald' => 'text-emerald-400',
                'teal' => 'text-teal-400',
                'cyan' => 'text-cyan-400',
                'sky' => 'text-sky-400',
                'blue' => 'text-blue-400',
                'indigo' => 'text-indigo-400',
                'violet' => 'text-violet-400',
                'purple' => 'text-purple-400',
                'fuchsia' => 'text-fuchsia-400',
                'pink' => 'text-pink-400',
                'rose' => 'text-rose-400',
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
        ];
    }
}
