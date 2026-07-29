<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

class ChartColors
{
    use SetupColors;

    /** Colors a chart can cycle through when a series does not name one. */
    public const SEQUENCE = ['primary', 'emerald', 'amber', 'rose', 'sky', 'violet', 'teal', 'orange'];

    public function colors(): array
    {
        $palette = array_merge($this->text(), (array) $this->get('text'));
        $getter = $this->component->color; // @phpstan-ignore-line

        return [
            'text' => data_get($palette, $getter) ?? data_get($this->text(), $getter),
            // A chart with several series resolves one class per series.
            'palette' => $palette,
        ];
    }

    private function text(): array
    {
        return [
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
            'mauve' => 'text-mauve-500',
            'olive' => 'text-olive-500',
            'mist' => 'text-mist-500',
            'taupe' => 'text-taupe-500',
        ];
    }
}
