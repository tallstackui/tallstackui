<?php

namespace TasteUi\View\Components\Alert\Support;

class BackgroundColors
{
    public static function translucent(string $selected = null): string
    {
        return [
            'primary' => 'bg-primary-50',
            'secondary' => 'bg-secondary-50',
            'positive' => 'bg-positive-50',
            'negative' => 'bg-negative-50',
            'warning' => 'bg-warning-50',
            'info' => 'bg-info-50',
            'dark' => 'bg-gray-700',
            'white' => 'bg-white',
            'black' => 'bg-black',
            'slate' => 'bg-slate-50',
            'gray' => 'bg-gray-50',
            'zinc' => 'bg-zinc-50',
            'neutral' => 'bg-neutral-50',
            'stone' => 'bg-stone-50',
            'red' => 'bg-red-50',
            'orange' => 'bg-orange-50',
            'amber' => 'bg-amber-50',
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
        ][$selected ?? 'primary'];
    }

    public static function base(string $selected = null): string
    {
        return [
            'primary' => 'bg-primary-200',
            'secondary' => 'bg-secondary-200',
            'positive' => 'bg-positive-200',
            'negative' => 'bg-negative-200',
            'warning' => 'bg-warning-200',
            'info' => 'bg-info-200',
            'dark' => 'bg-gray-700',
            'white' => 'bg-white',
            'black' => 'bg-black',
            'slate' => 'bg-slate-200',
            'gray' => 'bg-gray-200',
            'zinc' => 'bg-zinc-200',
            'neutral' => 'bg-neutral-200',
            'stone' => 'bg-stone-200',
            'red' => 'bg-red-200',
            'orange' => 'bg-orange-200',
            'amber' => 'bg-amber-200',
            'lime' => 'bg-lime-200',
            'green' => 'bg-green-200',
            'emerald' => 'bg-emerald-200',
            'teal' => 'bg-teal-200',
            'cyan' => 'bg-cyan-200',
            'sky' => 'bg-sky-200',
            'blue' => 'bg-blue-200',
            'indigo' => 'bg-indigo-200',
            'violet' => 'bg-violet-200',
            'purple' => 'bg-purple-200',
            'fuchsia' => 'bg-fuchsia-200',
            'pink' => 'bg-pink-200',
            'rose' => 'bg-rose-200',
        ][$selected ?? 'primary'];
    }
}
