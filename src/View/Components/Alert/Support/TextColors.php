<?php

namespace TasteUi\View\Components\Alert\Support;

class TextColors
{
    public static function option(string $selected = null): string
    {
        return [
            'primary' => 'text-primary-600',
            'secondary' => 'text-secondary-600',
            'positive' => 'text-positive-600',
            'negative' => 'text-negative-600',
            'warning' => 'text-warning-600',
            'info' => 'text-info-600',
            'dark' => 'text-gray-700',
            'white' => 'text-black',
            'black' => 'text-white',
            'slate' => 'text-slate-600',
            'gray' => 'text-gray-600',
            'zinc' => 'text-zinc-600',
            'neutral' => 'text-neutral-600',
            'stone' => 'text-stone-600',
            'red' => 'text-red-600',
            'orange' => 'text-orange-600',
            'amber' => 'text-amber-600',
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
        ][$selected ?? 'primary'];
    }
}
