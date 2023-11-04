<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\View\Components\Badge;

class BadgeColors
{
    public function __construct(protected Badge $component)
    {
        //
    }

    public function __invoke(): array
    {
        return [
            'wrapper.color' => Arr::toCssClasses([$this->background(), $this->text()]),
            'icon.color' => $this->icon(),
        ];
    }

    private function background(): string
    {
        return [
            'solid' => [
                'white' => 'border-black-200 bg-white',
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
                'white' => 'border-black-200 bg-transparent dark:text-white',
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
        ][$this->component->style][$this->component->color];
    }

    private function icon(): string
    {
        $icon = $this->text();

        return match (true) {
            $this->component->style === 'outline' => $icon.'dark:text-white',
            default => $icon,
        };
    }

    private function text(): string
    {
        return [
            'solid' => [
                'white' => 'text-black',
                'black' => 'text-white',
                'primary' => 'text-white',
                'secondary' => 'text-white',
                'slate' => 'text-white',
                'gray' => 'text-white',
                'zinc' => 'text-white',
                'neutral' => 'text-white',
                'stone' => 'text-white',
                'red' => 'text-white',
                'orange' => 'text-white',
                'amber' => 'text-white',
                'yellow' => 'text-white',
                'lime' => 'text-white',
                'green' => 'text-white',
                'emerald' => 'text-white',
                'teal' => 'text-white',
                'cyan' => 'text-white',
                'sky' => 'text-white',
                'blue' => 'text-white',
                'indigo' => 'text-white',
                'violet' => 'text-white',
                'purple' => 'text-white',
                'fuchsia' => 'text-white',
                'pink' => 'text-white',
                'rose' => 'text-white',
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
        ][$this->component->style][$this->component->color];
    }
}
