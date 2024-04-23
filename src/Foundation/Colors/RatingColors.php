<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Rating;

class RatingColors
{
    use OverrideColors;

    public function __construct(protected Rating $component)
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
            'white' => 'text-white',
            'black' => 'text-black',
            'primary' => 'text-primary-500',
            'secondary' => 'text-secondary-500',
            'slate' => 'text-slate-500',
            'gray' => 'text-gray-500',
            'zinc' => 'text-zinc-500',
            'neutral' => 'text-neutral-500',
            'stone' => 'text-stone-500',
            'red' => 'text-red-500',
            'orange' => 'text-orange-500',
            'amber' => 'text-amber-500',
            'yellow' => 'text-yellow-500',
            'lime' => 'text-lime-500',
            'green' => 'text-green-500',
            'emerald' => 'text-emerald-500',
            'teal' => 'text-teal-500',
            'cyan' => 'text-cyan-500',
            'sky' => 'text-sky-500',
            'blue' => 'text-blue-500',
            'indigo' => 'text-indigo-500',
            'violet' => 'text-violet-500',
            'purple' => 'text-purple-500',
            'fuchsia' => 'text-fuchsia-500',
            'pink' => 'text-pink-500',
            'rose' => 'text-rose-500',
        ];
    }
}
