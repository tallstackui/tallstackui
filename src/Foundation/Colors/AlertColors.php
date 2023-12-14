<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Alert;

class AlertColors
{
    use OverrideColors;

    public function __construct(protected Alert $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        [$background, $text] = $this->get('background', 'text');

        $getter = $this->format($this->component->style, $this->component->color);

        return [
            'background' => data_get($background, $getter, data_get($this->background(), $getter)),
            'text' => data_get($text, $getter, data_get($this->text(), $getter)),
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
            'outline' => [
                'white' => 'border border border-gray-100',
                'black' => 'border border-black',
                'primary' => 'border border-primary-600',
                'secondary' => 'border border-secondary-600',
                'slate' => 'border border-slate-600',
                'gray' => 'border border-gray-600',
                'zinc' => 'border border-zinc-600',
                'neutral' => 'border border-neutral-600',
                'stone' => 'border border-stone-600',
                'red' => 'border border-red-500',
                'orange' => 'border border-orange-600',
                'amber' => 'border border-amber-600',
                'yellow' => 'border border-yellow-600',
                'lime' => 'border border-lime-600',
                'green' => 'border border-green-600',
                'emerald' => 'border border-emerald-600',
                'teal' => 'border border-teal-600',
                'cyan' => 'border border-cyan-600',
                'sky' => 'border border-sky-600',
                'blue' => 'border border-blue-600',
                'indigo' => 'border border-indigo-600',
                'violet' => 'border border-violet-600',
                'purple' => 'border border-purple-600',
                'fuchsia' => 'border border-fuchsia-600',
                'pink' => 'border border-pink-600',
                'rose' => 'border border-rose-600',
            ],
            'light' => [
                'white' => 'bg-white shadow',
                'black' => 'bg-black-50 shadow',
                'primary' => 'bg-primary-50 shadow',
                'secondary' => 'bg-secondary-50 shadow',
                'slate' => 'bg-slate-50 shadow',
                'gray' => 'bg-gray-50 shadow',
                'zinc' => 'bg-zinc-50 shadow',
                'neutral' => 'bg-neutral-50 shadow',
                'stone' => 'bg-stone-50 shadow',
                'red' => 'bg-red-50 shadow',
                'orange' => 'bg-orange-50 shadow',
                'amber' => 'bg-amber-50 shadow',
                'yellow' => 'bg-yellow-50 shadow',
                'lime' => 'bg-lime-50 shadow',
                'green' => 'bg-green-50 shadow',
                'emerald' => 'bg-emerald-50 shadow',
                'teal' => 'bg-teal-50 shadow',
                'cyan' => 'bg-cyan-50 shadow',
                'sky' => 'bg-sky-50 shadow',
                'blue' => 'bg-blue-50 shadow',
                'indigo' => 'bg-indigo-50 shadow',
                'violet' => 'bg-violet-50 shadow',
                'purple' => 'bg-purple-50 shadow',
                'fuchsia' => 'bg-fuchsia-50 shadow',
                'pink' => 'bg-pink-50 shadow',
                'rose' => 'bg-rose-50 shadow',
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
