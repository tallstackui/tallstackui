<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

class RangeColors
{
    use SetupColors;

    public function colors(): array
    {
        $getter = $this->component->color; // @phpstan-ignore-line

        [$thumb, $progress] = $this->get('thumb', 'progress');

        return [
            'thumb' => data_get($thumb, $getter) ?? data_get($this->thumb(), $getter),
            'progress' => data_get($progress, $getter) ?? data_get($this->progress(), $getter),
        ];
    }

    private function progress(): array
    {
        return [
            'black' => 'bg-black',
            'primary' => 'bg-primary-500',
            'secondary' => 'bg-secondary-500',
            'slate' => 'bg-slate-500',
            'gray' => 'bg-gray-500',
            'zinc' => 'bg-zinc-500',
            'neutral' => 'bg-neutral-500',
            'stone' => 'bg-stone-500',
            'red' => 'bg-red-500',
            'orange' => 'bg-orange-500',
            'amber' => 'bg-amber-500',
            'yellow' => 'bg-yellow-500',
            'lime' => 'bg-lime-500',
            'green' => 'bg-green-500',
            'emerald' => 'bg-emerald-500',
            'teal' => 'bg-teal-500',
            'cyan' => 'bg-cyan-500',
            'sky' => 'bg-sky-500',
            'blue' => 'bg-blue-500',
            'indigo' => 'bg-indigo-500',
            'violet' => 'bg-violet-500',
            'purple' => 'bg-purple-500',
            'fuchsia' => 'bg-fuchsia-500',
            'pink' => 'bg-pink-500',
            'rose' => 'bg-rose-500',
            'mauve' => 'bg-mauve-500',
            'olive' => 'bg-olive-500',
            'mist' => 'bg-mist-500',
            'taupe' => 'bg-taupe-500',
        ];
    }

    private function thumb(): array
    {
        return [
            'black' => '[&::-webkit-slider-thumb]:bg-black [&::-moz-range-thumb]:bg-black',
            'primary' => '[&::-webkit-slider-thumb]:bg-primary-500 [&::-moz-range-thumb]:bg-primary-500',
            'secondary' => '[&::-webkit-slider-thumb]:bg-secondary-500 [&::-moz-range-thumb]:bg-secondary-500',
            'slate' => '[&::-webkit-slider-thumb]:bg-slate-500 [&::-moz-range-thumb]:bg-slate-500',
            'gray' => '[&::-webkit-slider-thumb]:bg-gray-500 [&::-moz-range-thumb]:bg-gray-500',
            'zinc' => '[&::-webkit-slider-thumb]:bg-zinc-500 [&::-moz-range-thumb]:bg-zinc-500',
            'neutral' => '[&::-webkit-slider-thumb]:bg-neutral-500 [&::-moz-range-thumb]:bg-neutral-500',
            'stone' => '[&::-webkit-slider-thumb]:bg-stone-500 [&::-moz-range-thumb]:bg-stone-500',
            'red' => '[&::-webkit-slider-thumb]:bg-red-500 [&::-moz-range-thumb]:bg-red-500',
            'orange' => '[&::-webkit-slider-thumb]:bg-orange-500 [&::-moz-range-thumb]:bg-orange-500',
            'amber' => '[&::-webkit-slider-thumb]:bg-amber-500 [&::-moz-range-thumb]:bg-amber-500',
            'yellow' => '[&::-webkit-slider-thumb]:bg-yellow-500 [&::-moz-range-thumb]:bg-yellow-500',
            'lime' => '[&::-webkit-slider-thumb]:bg-lime-500 [&::-moz-range-thumb]:bg-lime-500',
            'green' => '[&::-webkit-slider-thumb]:bg-green-500 [&::-moz-range-thumb]:bg-green-500',
            'emerald' => '[&::-webkit-slider-thumb]:bg-emerald-500 [&::-moz-range-thumb]:bg-emerald-500',
            'teal' => '[&::-webkit-slider-thumb]:bg-teal-500 [&::-moz-range-thumb]:bg-teal-500',
            'cyan' => '[&::-webkit-slider-thumb]:bg-cyan-500 [&::-moz-range-thumb]:bg-cyan-500',
            'sky' => '[&::-webkit-slider-thumb]:bg-sky-500 [&::-moz-range-thumb]:bg-sky-500',
            'blue' => '[&::-webkit-slider-thumb]:bg-blue-500 [&::-moz-range-thumb]:bg-blue-500',
            'indigo' => '[&::-webkit-slider-thumb]:bg-indigo-500 [&::-moz-range-thumb]:bg-indigo-500',
            'violet' => '[&::-webkit-slider-thumb]:bg-violet-500 [&::-moz-range-thumb]:bg-violet-500',
            'purple' => '[&::-webkit-slider-thumb]:bg-purple-500 [&::-moz-range-thumb]:bg-purple-500',
            'fuchsia' => '[&::-webkit-slider-thumb]:bg-fuchsia-500 [&::-moz-range-thumb]:bg-fuchsia-500',
            'pink' => '[&::-webkit-slider-thumb]:bg-pink-500 [&::-moz-range-thumb]:bg-pink-500',
            'rose' => '[&::-webkit-slider-thumb]:bg-rose-500 [&::-moz-range-thumb]:bg-rose-500',
            'mauve' => '[&::-webkit-slider-thumb]:bg-mauve-500 [&::-moz-range-thumb]:bg-mauve-500',
            'olive' => '[&::-webkit-slider-thumb]:bg-olive-500 [&::-moz-range-thumb]:bg-olive-500',
            'mist' => '[&::-webkit-slider-thumb]:bg-mist-500 [&::-moz-range-thumb]:bg-mist-500',
            'taupe' => '[&::-webkit-slider-thumb]:bg-taupe-500 [&::-moz-range-thumb]:bg-taupe-500',
        ];
    }
}
