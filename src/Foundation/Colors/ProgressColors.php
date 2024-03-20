<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Progress\Circle;
use TallStackUi\View\Components\Progress\Progress;

class ProgressColors
{
    use OverrideColors;

    public function __construct(protected Progress|Circle $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        $getter = $this->format($this->component->style, $this->component->color);

        return ['background' => data_get($this->get('background'), $getter, data_get($this->background(), $getter))];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'white' => 'bg-white',
                'black' => 'bg-black',
                'primary' => 'bg-primary-600',
                'secondary' => 'bg-secondary-600',
                'slate' => 'bg-slate-600',
                'gray' => 'bg-gray-600',
                'zinc' => 'bg-zinc-600',
                'neutral' => 'bg-neutral-600',
                'stone' => 'bg-stone-600',
                'red' => 'bg-red-600',
                'orange' => 'bg-orange-600',
                'amber' => 'bg-amber-600',
                'yellow' => 'bg-yellow-600',
                'lime' => 'bg-lime-600',
                'green' => 'bg-green-600',
                'emerald' => 'bg-emerald-600',
                'teal' => 'bg-teal-600',
                'cyan' => 'bg-cyan-600',
                'sky' => 'bg-sky-600',
                'blue' => 'bg-blue-600',
                'indigo' => 'bg-indigo-600',
                'violet' => 'bg-violet-600',
                'purple' => 'bg-purple-600',
                'fuchsia' => 'bg-fuchsia-600',
                'pink' => 'bg-pink-600',
                'rose' => 'bg-rose-600',
            ],
            'light' => [
                'white' => 'bg-white',
                'black' => 'bg-black',
                'primary' => 'bg-primary-400',
                'secondary' => 'bg-secondary-400',
                'slate' => 'bg-slate-400',
                'gray' => 'bg-gray-400',
                'zinc' => 'bg-zinc-400',
                'neutral' => 'bg-neutral-400',
                'stone' => 'bg-stone-400',
                'red' => 'bg-red-400',
                'orange' => 'bg-orange-400',
                'amber' => 'bg-amber-400',
                'yellow' => 'bg-yellow-400',
                'lime' => 'bg-lime-400',
                'green' => 'bg-green-400',
                'emerald' => 'bg-emerald-400',
                'teal' => 'bg-teal-400',
                'cyan' => 'bg-cyan-400',
                'sky' => 'bg-sky-400',
                'blue' => 'bg-blue-400',
                'indigo' => 'bg-indigo-400',
                'violet' => 'bg-violet-400',
                'purple' => 'bg-purple-400',
                'fuchsia' => 'bg-fuchsia-400',
                'pink' => 'bg-pink-400',
                'rose' => 'bg-rose-400',
            ],
        ];
    }
}
