<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Alert;
use TallStackUi\View\Personalizations\Support\Colors\Traits\OverrideColors;

class AlertColors
{
    use OverrideColors;

    public function __construct(protected Alert $component)
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
                'primary' => 'bg-primary-300',
                'secondary' => 'bg-secondary-300',
                'slate' => 'bg-slate-300',
                'gray' => 'bg-gray-300',
                'zinc' => 'bg-zinc-300',
                'neutral' => 'bg-neutral-300',
                'stone' => 'bg-stone-300',
                'red' => 'bg-red-300',
                'orange' => 'bg-orange-300',
                'amber' => 'bg-amber-300',
                'yellow' => 'bg-yellow-300',
                'lime' => 'bg-lime-300',
                'green' => 'bg-green-300',
                'emerald' => 'bg-emerald-300',
                'teal' => 'bg-teal-300',
                'cyan' => 'bg-cyan-300',
                'sky' => 'bg-sky-300',
                'blue' => 'bg-blue-300',
                'indigo' => 'bg-indigo-300',
                'violet' => 'bg-violet-300',
                'purple' => 'bg-purple-300',
                'fuchsia' => 'bg-fuchsia-300',
                'pink' => 'bg-pink-300',
                'rose' => 'bg-rose-300',
            ],
            'light' => [
                'white' => 'bg-white border border-gray-100',
                'black' => 'bg-black-100',
                'primary' => 'bg-primary-100',
                'secondary' => 'bg-secondary-100',
                'slate' => 'bg-slate-100',
                'gray' => 'bg-gray-100',
                'zinc' => 'bg-zinc-100',
                'neutral' => 'bg-neutral-100',
                'stone' => 'bg-stone-100',
                'red' => 'bg-red-100',
                'orange' => 'bg-orange-100',
                'amber' => 'bg-amber-100',
                'yellow' => 'bg-yellow-100',
                'lime' => 'bg-lime-100',
                'green' => 'bg-green-100',
                'emerald' => 'bg-emerald-100',
                'teal' => 'bg-teal-100',
                'cyan' => 'bg-cyan-100',
                'sky' => 'bg-sky-100',
                'blue' => 'bg-blue-100',
                'indigo' => 'bg-indigo-100',
                'violet' => 'bg-violet-100',
                'purple' => 'bg-purple-100',
                'fuchsia' => 'bg-fuchsia-100',
                'pink' => 'bg-pink-100',
                'rose' => 'bg-rose-100',
            ],
        ];
    }

    private function text(): array
    {
        return [
            'solid' => [
                'white' => 'text-black',
                'black' => 'text-white',
                'primary' => 'text-primary-900',
                'secondary' => 'text-secondary-900',
                'slate' => 'text-slate-900',
                'gray' => 'text-gray-900',
                'zinc' => 'text-zinc-900',
                'neutral' => 'text-neutral-900',
                'stone' => 'text-stone-900',
                'red' => 'text-red-900',
                'orange' => 'text-orange-900',
                'amber' => 'text-amber-900',
                'yellow' => 'text-yellow-900',
                'lime' => 'text-lime-900',
                'green' => 'text-green-900',
                'emerald' => 'text-emerald-900',
                'teal' => 'text-teal-900',
                'cyan' => 'text-cyan-900',
                'sky' => 'text-sky-900',
                'blue' => 'text-blue-900',
                'indigo' => 'text-indigo-900',
                'violet' => 'text-violet-900',
                'purple' => 'text-purple-900',
                'fuchsia' => 'text-fuchsia-900',
                'pink' => 'text-pink-900',
                'rose' => 'text-rose-900',
            ],
            'light' => [
                'white' => 'text-black',
                'black' => 'text-black',
                'primary' => 'text-primary-900',
                'secondary' => 'text-secondary-900',
                'slate' => 'text-slate-900',
                'gray' => 'text-gray-900',
                'zinc' => 'text-zinc-900',
                'neutral' => 'text-neutral-900',
                'stone' => 'text-stone-900',
                'red' => 'text-red-900',
                'orange' => 'text-orange-900',
                'amber' => 'text-amber-900',
                'yellow' => 'text-yellow-900',
                'lime' => 'text-lime-900',
                'green' => 'text-green-900',
                'emerald' => 'text-emerald-900',
                'teal' => 'text-teal-900',
                'cyan' => 'text-cyan-900',
                'sky' => 'text-sky-900',
                'blue' => 'text-blue-900',
                'indigo' => 'text-indigo-900',
                'violet' => 'text-violet-900',
                'purple' => 'text-purple-900',
                'fuchsia' => 'text-fuchsia-900',
                'pink' => 'text-pink-900',
                'rose' => 'text-rose-900',
            ],
        ];
    }
}
