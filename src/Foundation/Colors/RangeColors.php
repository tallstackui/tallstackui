<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Form\Range;

class RangeColors
{
    use OverrideColors;

    public function __construct(protected Range $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        // We just need to $this->format when we
        // have a style and color, otherwise we
        // can just use the color as the getter.
        $getter = $this->component->color;

        return ['thumb' => data_get($this->get('thumb'), $getter, data_get($this->thumb(), $getter))];
    }

    private function thumb(): array
    {
        return [
            'white' => '[&::-webkit-slider-thumb]:bg-white',
            'black' => '[&::-webkit-slider-thumb]:bg-black',
            'primary' => '[&::-webkit-slider-thumb]:bg-primary-500',
            'secondary' => '[&::-webkit-slider-thumb]:bg-secondary-500',
            'slate' => '[&::-webkit-slider-thumb]:bg-slate-500',
            'gray' => '[&::-webkit-slider-thumb]:bg-gray-500',
            'zinc' => '[&::-webkit-slider-thumb]:bg-zinc-500',
            'neutral' => '[&::-webkit-slider-thumb]:bg-neutral-500',
            'stone' => '[&::-webkit-slider-thumb]:bg-stone-500',
            'red' => '[&::-webkit-slider-thumb]:bg-red-500',
            'orange' => '[&::-webkit-slider-thumb]:bg-orange-500',
            'amber' => '[&::-webkit-slider-thumb]:bg-amber-500',
            'yellow' => '[&::-webkit-slider-thumb]:bg-yellow-500',
            'lime' => '[&::-webkit-slider-thumb]:bg-lime-500',
            'green' => '[&::-webkit-slider-thumb]:bg-green-500',
            'emerald' => '[&::-webkit-slider-thumb]:bg-emerald-500',
            'teal' => '[&::-webkit-slider-thumb]:bg-teal-500',
            'cyan' => '[&::-webkit-slider-thumb]:bg-cyan-500',
            'sky' => '[&::-webkit-slider-thumb]:bg-sky-500',
            'blue' => '[&::-webkit-slider-thumb]:bg-blue-500',
            'indigo' => '[&::-webkit-slider-thumb]:bg-indigo-500',
            'violet' => '[&::-webkit-slider-thumb]:bg-violet-500',
            'purple' => '[&::-webkit-slider-thumb]:bg-purple-500',
            'fuchsia' => '[&::-webkit-slider-thumb]:bg-fuchsia-500',
            'pink' => '[&::-webkit-slider-thumb]:bg-pink-500',
            'rose' => '[&::-webkit-slider-thumb]:bg-rose-500',
        ];
    }
}
