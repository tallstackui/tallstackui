<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Banner;
use TallStackUi\View\Personalizations\Support\Colors\Traits\OverrideColors;

class BannerColors
{
    use OverrideColors;

    public function __construct(protected Banner $component)
    {
        $this->define();
    }

    public function __invoke(): array
    {
        [$background, $text] = $this->override('background', 'text');

        return [
            'background' => $background[$this->component->style][$this->component->color],
            'text' => $text[$this->component->style][$this->component->color],
        ];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'white' => 'bg-white border border-gray-100',
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
                'white' => 'bg-white border border-gray-100 shadow',
                'black' => 'bg-black-300 shadow',
                'primary' => 'bg-primary-300 shadow',
                'secondary' => 'bg-secondary-300 shadow',
                'slate' => 'bg-slate-300 shadow',
                'gray' => 'bg-gray-300 shadow',
                'zinc' => 'bg-zinc-300 shadow',
                'neutral' => 'bg-neutral-300 shadow',
                'stone' => 'bg-stone-300 shadow',
                'red' => 'bg-red-300 shadow',
                'orange' => 'bg-orange-300 shadow',
                'amber' => 'bg-amber-300 shadow',
                'yellow' => 'bg-yellow-300 shadow',
                'lime' => 'bg-lime-300 shadow',
                'green' => 'bg-green-300 shadow',
                'emerald' => 'bg-emerald-300 shadow',
                'teal' => 'bg-teal-300 shadow',
                'cyan' => 'bg-cyan-300 shadow',
                'sky' => 'bg-sky-300 shadow',
                'blue' => 'bg-blue-300 shadow',
                'indigo' => 'bg-indigo-300 shadow',
                'violet' => 'bg-violet-300 shadow',
                'purple' => 'bg-purple-300 shadow',
                'fuchsia' => 'bg-fuchsia-300 shadow',
                'pink' => 'bg-pink-300 shadow',
                'rose' => 'bg-rose-300 shadow',
            ],
        ];
    }

    private function text(): array
    {
        return [
            'solid' => [
                'white' => 'text-black',
                'black' => 'text-white',
                'primary' => 'text-primary-50',
                'secondary' => 'text-secondary-50',
                'slate' => 'text-slate-50',
                'gray' => 'text-gray-50',
                'zinc' => 'text-zinc-50',
                'neutral' => 'text-neutral-50',
                'stone' => 'text-stone-50',
                'red' => 'text-red-50',
                'orange' => 'text-orange-50',
                'amber' => 'text-amber-50',
                'yellow' => 'text-yellow-50',
                'lime' => 'text-lime-50',
                'green' => 'text-green-50',
                'emerald' => 'text-emerald-50',
                'teal' => 'text-teal-50',
                'cyan' => 'text-cyan-50',
                'sky' => 'text-sky-50',
                'blue' => 'text-blue-50',
                'indigo' => 'text-indigo-50',
                'violet' => 'text-violet-50',
                'purple' => 'text-purple-50',
                'fuchsia' => 'text-fuchsia-50',
                'pink' => 'text-pink-50',
                'rose' => 'text-rose-50',
            ],
            'light' => [
                'white' => 'text-black',
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
