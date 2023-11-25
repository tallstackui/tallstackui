<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Errors;
use TallStackUi\View\Personalizations\Support\Colors\Traits\OverrideColors;

class ErrorsColors
{
    use OverrideColors;

    public function __construct(protected Errors $component)
    {
        $this->define();
    }

    public function __invoke(): array
    {
        [$background, $text, $border] = $this->override('background', 'text', 'border');

        return [
            'background' => $background[$this->component->color],
            'text' => $text[$this->component->color],
            'border' => $border[$this->component->color],
        ];
    }

    private function background(): array
    {
        return [
            'white' => 'bg-white',
            'black' => 'bg-black',
            'primary' => 'bg-primary-50',
            'secondary' => 'bg-secondary-50',
            'slate' => 'bg-slate-50',
            'gray' => 'bg-gray-50',
            'zinc' => 'bg-zinc-50',
            'neutral' => 'bg-neutral-50',
            'stone' => 'bg-stone-50',
            'red' => 'bg-red-50',
            'orange' => 'bg-orange-50',
            'amber' => 'bg-amber-50',
            'yellow' => 'bg-yellow-50',
            'lime' => 'bg-lime-50',
            'green' => 'bg-green-50',
            'emerald' => 'bg-emerald-50',
            'teal' => 'bg-teal-50',
            'cyan' => 'bg-cyan-50',
            'sky' => 'bg-sky-50',
            'blue' => 'bg-blue-50',
            'indigo' => 'bg-indigo-50',
            'violet' => 'bg-violet-50',
            'purple' => 'bg-purple-50',
            'fuchsia' => 'bg-fuchsia-50',
            'pink' => 'bg-pink-50',
            'rose' => 'bg-rose-50',
        ];
    }

    private function border(): array
    {
        return [
            'white' => 'border-b-black',
            'black' => 'border-b-white',
            'primary' => 'border-b-primary-200',
            'secondary' => 'border-b-secondary-200',
            'slate' => 'border-b-slate-200',
            'gray' => 'border-b-gray-200',
            'zinc' => 'border-b-zinc-200',
            'neutral' => 'border-b-neutral-200',
            'stone' => 'border-b-stone-200',
            'red' => 'border-b-red-200',
            'orange' => 'border-b-orange-200',
            'amber' => 'border-b-amber-200',
            'yellow' => 'border-b-yellow-200',
            'lime' => 'border-b-lime-200',
            'green' => 'border-b-green-200',
            'emerald' => 'border-b-emerald-200',
            'teal' => 'border-b-teal-200',
            'cyan' => 'border-b-cyan-200',
            'sky' => 'border-b-sky-200',
            'blue' => 'border-b-blue-200',
            'indigo' => 'border-b-indigo-200',
            'violet' => 'border-b-violet-200',
            'purple' => 'border-b-purple-200',
            'fuchsia' => 'border-b-fuchsia-200',
            'pink' => 'border-b-pink-200',
            'rose' => 'border-b-rose-200',
        ];
    }

    private function text(): array
    {
        return [
            'white' => 'text-black',
            'black' => 'text-white',
            'primary' => 'text-primary-700',
            'secondary' => 'text-secondary-700',
            'slate' => 'text-slate-700',
            'gray' => 'text-gray-700',
            'zinc' => 'text-zinc-700',
            'neutral' => 'text-neutral-700',
            'stone' => 'text-stone-700',
            'red' => 'text-red-700',
            'orange' => 'text-orange-700',
            'amber' => 'text-amber-700',
            'yellow' => 'text-yellow-700',
            'lime' => 'text-lime-700',
            'green' => 'text-green-700',
            'emerald' => 'text-emerald-700',
            'teal' => 'text-teal-700',
            'cyan' => 'text-cyan-700',
            'sky' => 'text-sky-700',
            'blue' => 'text-blue-700',
            'indigo' => 'text-indigo-700',
            'violet' => 'text-violet-700',
            'purple' => 'text-purple-700',
            'fuchsia' => 'text-fuchsia-700',
            'pink' => 'text-pink-700',
            'rose' => 'text-rose-700',
        ];
    }
}
