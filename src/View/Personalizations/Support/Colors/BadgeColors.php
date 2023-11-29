<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Badge;
use TallStackUi\View\Personalizations\Support\Colors\Traits\OverrideColors;

class BadgeColors
{
    use OverrideColors;

    public function __construct(protected Badge $component)
    {
        $this->define();
    }

    public function __invoke(): array
    {
        [$background, $text, $icon] = $this->override('background', 'text', 'icon');

        return [
            'background' => $background[$this->component->style][$this->component->color],
            'text' => $text[$this->component->style][$this->component->color],
            'icon' => $icon[$this->component->style][$this->component->color],
        ];
    }

    private function background(): array
    {
        return [
            'solid' => [
                'white' => 'border-white bg-white',
                'black' => 'border-black bg-black',
                'primary' => 'border-primary-500 bg-primary-500',
                'secondary' => 'border-secondary-500 bg-secondary-500',
                'slate' => 'border-slate-500 bg-slate-500',
                'gray' => 'border-gray-500 bg-gray-500',
                'zinc' => 'border-zinc-500 bg-zinc-500',
                'neutral' => 'border-neutral-500 bg-neutral-500',
                'stone' => 'border-stone-500 bg-stone-500',
                'red' => 'border-red-500 bg-red-500',
                'orange' => 'border-orange-500 bg-orange-500',
                'amber' => 'border-amber-500 bg-amber-500',
                'yellow' => 'border-yellow-500 bg-yellow-500',
                'lime' => 'border-lime-500 bg-lime-500',
                'green' => 'border-green-500 bg-green-500',
                'emerald' => 'border-emerald-500 bg-emerald-500',
                'teal' => 'border-teal-500 bg-teal-500',
                'cyan' => 'border-cyan-500 bg-cyan-500',
                'sky' => 'border-sky-500 bg-sky-500',
                'blue' => 'border-blue-500 bg-blue-500',
                'indigo' => 'border-indigo-500 bg-indigo-500',
                'violet' => 'border-violet-500 bg-violet-500',
                'purple' => 'border-purple-500 bg-purple-500',
                'fuchsia' => 'border-fuchsia-500 bg-fuchsia-500',
                'pink' => 'border-pink-500 bg-pink-500',
                'rose' => 'border-rose-500 bg-rose-500',
            ],
            'outline' => [
                'white' => 'border-white text-white bg-transparent',
                'black' => 'border-black bg-transparent',
                'primary' => 'border-primary-500 bg-transparent',
                'secondary' => 'border-secondary-500 bg-transparent',
                'slate' => 'border-slate-500 bg-transparent',
                'gray' => 'border-gray-500 bg-transparent',
                'zinc' => 'border-zinc-500 bg-transparent',
                'neutral' => 'border-neutral-500 bg-transparent',
                'stone' => 'border-stone-500 bg-transparent',
                'red' => 'border-red-500 bg-transparent',
                'orange' => 'border-orange-500 bg-transparent',
                'amber' => 'border-amber-500 bg-transparent',
                'yellow' => 'border-yellow-500 bg-transparent',
                'lime' => 'border-lime-500 bg-transparent',
                'green' => 'border-green-500 bg-transparent',
                'emerald' => 'border-emerald-500 bg-transparent',
                'teal' => 'border-teal-500 bg-transparent',
                'cyan' => 'border-cyan-500 bg-transparent',
                'sky' => 'border-sky-500 bg-transparent',
                'blue' => 'border-blue-500 bg-transparent',
                'indigo' => 'border-indigo-500 bg-transparent',
                'violet' => 'border-violet-500 bg-transparent',
                'purple' => 'border-purple-500 bg-transparent',
                'fuchsia' => 'border-fuchsia-500 bg-transparent',
                'pink' => 'border-pink-500 bg-transparent',
                'rose' => 'border-rose-500 bg-transparent',
            ],
            'light' => [
                'white' => 'border-white text-white bg-white',
                'black' => 'border-black-300 bg-black-300',
                'primary' => 'border-primary-300 bg-primary-300',
                'secondary' => 'border-secondary-300 bg-secondary-300',
                'slate' => 'border-slate-300 bg-slate-300',
                'gray' => 'border-gray-300 bg-gray-300',
                'zinc' => 'border-zinc-300 bg-zinc-300',
                'neutral' => 'border-neutral-300 bg-neutral-300',
                'stone' => 'border-stone-300 bg-stone-300',
                'red' => 'border-red-300 bg-red-300',
                'orange' => 'border-orange-300 bg-orange-300',
                'amber' => 'border-amber-300 bg-amber-300',
                'yellow' => 'border-yellow-300 bg-yellow-300',
                'lime' => 'border-lime-300 bg-lime-300',
                'green' => 'border-green-300 bg-green-300',
                'emerald' => 'border-emerald-300 bg-emerald-300',
                'teal' => 'border-teal-300 bg-teal-300',
                'cyan' => 'border-cyan-300 bg-cyan-300',
                'sky' => 'border-sky-300 bg-sky-300',
                'blue' => 'border-blue-300 bg-blue-300',
                'indigo' => 'border-indigo-300 bg-indigo-300',
                'violet' => 'border-violet-300 bg-violet-300',
                'purple' => 'border-purple-300 bg-purple-300',
                'fuchsia' => 'border-fuchsia-300 bg-fuchsia-300',
                'pink' => 'border-pink-300 bg-pink-300',
                'rose' => 'border-rose-300 bg-rose-300',
            ],
        ];
    }

    private function icon(): array
    {
        return $this->text();
    }

    private function text(): array
    {
        return [
            'solid' => [
                'white' => 'text-white',
                'black' => 'text-black-50',
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
            'outline' => [
                'white' => 'text-black',
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
            ],
            'light' => [
                'white' => 'text-white',
                'black' => 'text-black-600',
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
