<?php

namespace TallStackUi\Foundation\Support\Colors\Components;

use TallStackUi\Foundation\Support\Colors\Concerns\SetupColors;

class CardColors
{
    use SetupColors;

    public function colors(): array
    {
        $background = $this->get('background');

        $default = $this->personalization('header.text.color');

        if (($color = $this->component->color) === null) { // @phpstan-ignore-line
            return ['background' => $default];
        }

        $getter = $this->format($this->component->variation, $this->component->style, $color); // @phpstan-ignore-line

        $colors = ['background' => data_get($background, $getter) ?? data_get($this->background(), $getter)];

        // Whe variation is border, we need to preserve the default
        // text colors because the color only apply a border.
        if ($this->component->variation === 'border') { // @phpstan-ignore-line
            $colors['background'] = $default.' '.$colors['background'];
        }

        return $colors;
    }

    private function background(): array
    {
        return [
            'border' => [
                'solid' => [
                    'black' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-black/30',
                    'primary' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-primary-600',
                    'secondary' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-secondary-600',
                    'slate' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-slate-600',
                    'gray' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-gray-600',
                    'zinc' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-zinc-600',
                    'neutral' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-neutral-600',
                    'stone' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-stone-600',
                    'red' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-red-600',
                    'orange' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-orange-600',
                    'amber' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-amber-600',
                    'yellow' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-yellow-600',
                    'lime' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-lime-600',
                    'green' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-green-600',
                    'emerald' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-emerald-600',
                    'teal' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-teal-600',
                    'cyan' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-cyan-600',
                    'sky' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-sky-600',
                    'blue' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-blue-600',
                    'indigo' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-indigo-600',
                    'violet' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-violet-600',
                    'purple' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-purple-600',
                    'fuchsia' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-fuchsia-600',
                    'pink' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-pink-600',
                    'rose' => 'rounded-tl-lg rounded-tr-lg border-t-4 border-t-rose-600',
                ],
            ],
            'background' => [
                'solid' => [
                    'black' => 'text-white rounded-tl-lg rounded-tr-lg bg-black',
                    'primary' => 'text-primary-50 rounded-tl-lg rounded-tr-lg bg-primary-600',
                    'secondary' => 'text-secondary-50 rounded-tl-lg rounded-tr-lg bg-secondary-600',
                    'slate' => 'text-slate-50 rounded-tl-lg rounded-tr-lg bg-slate-600',
                    'gray' => 'text-gray-50 rounded-tl-lg rounded-tr-lg bg-gray-600',
                    'zinc' => 'text-zinc-50 rounded-tl-lg rounded-tr-lg bg-zinc-600',
                    'neutral' => 'text-neutral-50 rounded-tl-lg rounded-tr-lg bg-neutral-600',
                    'stone' => 'text-stone-50 rounded-tl-lg rounded-tr-lg bg-stone-600',
                    'red' => 'text-red-50 rounded-tl-lg rounded-tr-lg bg-red-600',
                    'orange' => 'text-orange-50 rounded-tl-lg rounded-tr-lg bg-orange-600',
                    'amber' => 'text-amber-50 rounded-tl-lg rounded-tr-lg bg-amber-600',
                    'yellow' => 'text-yellow-50 rounded-tl-lg rounded-tr-lg bg-yellow-600',
                    'lime' => 'text-lime-50 rounded-tl-lg rounded-tr-lg bg-lime-600',
                    'green' => 'text-green-50 rounded-tl-lg rounded-tr-lg bg-green-600',
                    'emerald' => 'text-emerald-50 rounded-tl-lg rounded-tr-lg bg-emerald-600',
                    'teal' => 'text-teal-50 rounded-tl-lg rounded-tr-lg bg-teal-600',
                    'cyan' => 'text-cyan-50 rounded-tl-lg rounded-tr-lg bg-cyan-600',
                    'sky' => 'text-sky-50 rounded-tl-lg rounded-tr-lg bg-sky-600',
                    'blue' => 'text-blue-50 rounded-tl-lg rounded-tr-lg bg-blue-600',
                    'indigo' => 'text-indigo-50 rounded-tl-lg rounded-tr-lg bg-indigo-600',
                    'violet' => 'text-violet-50 rounded-tl-lg rounded-tr-lg bg-violet-600',
                    'purple' => 'text-purple-50 rounded-tl-lg rounded-tr-lg bg-purple-600',
                    'fuchsia' => 'text-fuchsia-50 rounded-tl-lg rounded-tr-lg bg-fuchsia-600',
                    'pink' => 'text-pink-50 rounded-tl-lg rounded-tr-lg bg-pink-600',
                    'rose' => 'text-rose-50 rounded-tl-lg rounded-tr-lg bg-rose-600',
                ],
                'light' => [
                    'black' => 'text-black/80 rounded-tl-lg rounded-tr-lg bg-black/30',
                    'primary' => 'text-primary-600 rounded-tl-lg rounded-tr-lg bg-primary-50',
                    'secondary' => 'text-secondary-600 rounded-tl-lg rounded-tr-lg bg-secondary-50',
                    'slate' => 'text-slate-600 rounded-tl-lg rounded-tr-lg bg-slate-50',
                    'gray' => 'text-gray-600 rounded-tl-lg rounded-tr-lg bg-gray-50',
                    'zinc' => 'text-zinc-600 rounded-tl-lg rounded-tr-lg bg-zinc-50',
                    'neutral' => 'text-neutral-600 rounded-tl-lg rounded-tr-lg bg-neutral-50',
                    'stone' => 'text-stone-600 rounded-tl-lg rounded-tr-lg bg-stone-50',
                    'red' => 'text-red-600 rounded-tl-lg rounded-tr-lg bg-red-50',
                    'orange' => 'text-orange-600 rounded-tl-lg rounded-tr-lg bg-orange-50',
                    'amber' => 'text-amber-600 rounded-tl-lg rounded-tr-lg bg-amber-50',
                    'yellow' => 'text-yellow-600 rounded-tl-lg rounded-tr-lg bg-yellow-50',
                    'lime' => 'text-lime-600 rounded-tl-lg rounded-tr-lg bg-lime-50',
                    'green' => 'text-green-600 rounded-tl-lg rounded-tr-lg bg-green-50',
                    'emerald' => 'text-emerald-600 rounded-tl-lg rounded-tr-lg bg-emerald-50',
                    'teal' => 'text-teal-600 rounded-tl-lg rounded-tr-lg bg-teal-50',
                    'cyan' => 'text-cyan-600 rounded-tl-lg rounded-tr-lg bg-cyan-50',
                    'sky' => 'text-sky-600 rounded-tl-lg rounded-tr-lg bg-sky-50',
                    'blue' => 'text-blue-600 rounded-tl-lg rounded-tr-lg bg-blue-50',
                    'indigo' => 'text-indigo-600 rounded-tl-lg rounded-tr-lg bg-indigo-50',
                    'violet' => 'text-violet-600 rounded-tl-lg rounded-tr-lg bg-violet-50',
                    'purple' => 'text-purple-600 rounded-tl-lg rounded-tr-lg bg-purple-50',
                    'fuchsia' => 'text-fuchsia-600 rounded-tl-lg rounded-tr-lg bg-fuchsia-50',
                    'pink' => 'text-pink-600 rounded-tl-lg rounded-tr-lg bg-pink-50',
                    'rose' => 'text-rose-600 rounded-tl-lg rounded-tr-lg bg-rose-50',
                ],
            ],
        ];
    }
}
