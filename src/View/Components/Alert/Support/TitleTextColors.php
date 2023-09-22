<?php

namespace TasteUi\View\Components\Alert\Support;

class TitleTextColors
{
    public static function option(string $selected = null): string
    {
        return [
            'primary' => 'text-primary-800',
            'secondary' => 'text-secondary-800',
            'positive' => 'text-positive-800',
            'negative' => 'text-negative-800',
            'warning' => 'text-warning-800',
            'info' => 'text-info-800',
            'dark' => 'text-gray-700',
            'white' => 'text-black',
            'black' => 'text-white',
            'slate' => 'text-slate-800',
            'gray' => 'text-gray-800',
            'zinc' => 'text-zinc-800',
            'neutral' => 'text-neutral-800',
            'stone' => 'text-stone-800',
            'red' => 'text-red-800',
            'orange' => 'text-orange-800',
            'amber' => 'text-amber-800',
            'lime' => 'text-lime-800',
            'green' => 'text-green-800',
            'emerald' => 'text-emerald-800',
            'teal' => 'text-teal-800',
            'cyan' => 'text-cyan-800',
            'sky' => 'text-sky-800',
            'blue' => 'text-blue-800',
            'indigo' => 'text-indigo-800',
            'violet' => 'text-violet-800',
            'purple' => 'text-purple-800',
            'fuchsia' => 'text-fuchsia-800',
            'pink' => 'text-pink-800',
            'rose' => 'text-rose-800',
        ][$selected ?? 'primary'];
    }
}
