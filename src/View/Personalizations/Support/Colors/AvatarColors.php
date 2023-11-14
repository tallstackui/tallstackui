<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Personalizations\Support\Colors\Traits\OverrideColors;

class AvatarColors
{
    use OverrideColors;

    public function __construct(protected Avatar $component)
    {
        //
    }

    public function __invoke(): array
    {
        $overrides = $this->overrides();
        $background = $overrides['background'] ?? $this->background();

        return ['background' => $background[$this->component->color]];
    }

    private function background(): array
    {
        return [
            'white' => 'bg-white dark:bg-white dark:border-white',
            'black' => 'bg-black border-black',
            'primary' => 'bg-primary-500 border-primary-500',
            'secondary' => 'bg-secondary-500 border-secondary-500',
            'slate' => 'bg-slate-500 border-slate-500',
            'gray' => 'bg-gray-500 border-gray-500',
            'zinc' => 'bg-zinc-500 border-zinc-500',
            'neutral' => 'bg-neutral-500 border-neutral-500',
            'stone' => 'bg-stone-500 border-stone-500',
            'red' => 'bg-red-500 border-red-500',
            'orange' => 'bg-orange-500 border-orange-500',
            'amber' => 'bg-amber-500 border-amber-500',
            'yellow' => 'bg-yellow-500 border-yellow-500',
            'lime' => 'bg-lime-500 border-lime-500',
            'green' => 'bg-green-500 border-green-500',
            'emerald' => 'bg-emerald-500 border-emerald-500',
            'teal' => 'bg-teal-500 border-teal-500',
            'cyan' => 'bg-cyan-500 border-cyan-500',
            'sky' => 'bg-sky-500 border-sky-500',
            'blue' => 'bg-blue-500 border-blue-500',
            'indigo' => 'bg-indigo-500 border-indigo-500',
            'violet' => 'bg-violet-500 border-violet-500',
            'purple' => 'bg-purple-500 border-purple-500',
            'fuchsia' => 'bg-fuchsia-500 border-fuchsia-500',
            'pink' => 'bg-pink-500 border-pink-500',
            'rose' => 'bg-rose-500 border-rose-500',
        ];
    }
}
