<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Errors;
use TallStackUi\View\Personalizations\Support\Colors\Traits\OverrideColors;

class ErrorsColors
{
    use OverrideColors;

    public function __construct(protected Errors $component)
    {
        //
    }

    public function __invoke(): array
    {
        $override = $this->overrides();

        $background = $override['background'] ?? $this->background();
        $text = $override['text'] ?? $this->text();
        $border = $override['border'] ?? $this->border();

        return [
            'background' => $background[$this->component->color],
            'text' => $text[$this->component->color],
            'border' => $border[$this->component->color],
        ];
    }

    private function background(): array
    {
        return [
            'white' => 'bg-white dark:border dark:border-white dark:bg-transparent',
            'black' => 'bg-black dark:border dark:border-black dark:bg-transparent',
            'primary' => 'bg-primary-50 dark:border dark:border-primary-500 dark:bg-transparent',
            'secondary' => 'bg-secondary-50 dark:border dark:border-secondary-500 dark:bg-transparent',
            'slate' => 'bg-slate-50 dark:border dark:border-slate-500 dark:bg-transparent',
            'gray' => 'bg-gray-50 dark:border dark:border-gray-500 dark:bg-transparent',
            'zinc' => 'bg-zinc-50 dark:border dark:border-zinc-500 dark:bg-transparent',
            'neutral' => 'bg-neutral-50 dark:border dark:border-neutral-500 dark:bg-transparent',
            'stone' => 'bg-stone-50 dark:border dark:border-stone-500 dark:bg-transparent',
            'red' => 'bg-red-50 dark:border dark:border-red-500 dark:bg-transparent',
            'orange' => 'bg-orange-50 dark:border dark:border-orange-500 dark:bg-transparent',
            'amber' => 'bg-amber-50 dark:border dark:border-amber-500 dark:bg-transparent',
            'yellow' => 'bg-yellow-50 dark:border dark:border-yellow-500 dark:bg-transparent',
            'lime' => 'bg-lime-50 dark:border dark:border-lime-500 dark:bg-transparent',
            'green' => 'bg-green-50 dark:border dark:border-green-500 dark:bg-transparent',
            'emerald' => 'bg-emerald-50 dark:border dark:border-emerald-500 dark:bg-transparent',
            'teal' => 'bg-teal-50 dark:border dark:border-teal-500 dark:bg-transparent',
            'cyan' => 'bg-cyan-50 dark:border dark:border-cyan-500 dark:bg-transparent',
            'sky' => 'bg-sky-50 dark:border dark:border-sky-500 dark:bg-transparent',
            'blue' => 'bg-blue-50 dark:border dark:border-blue-500 dark:bg-transparent',
            'indigo' => 'bg-indigo-50 dark:border dark:border-indigo-500 dark:bg-transparent',
            'violet' => 'bg-violet-50 dark:border dark:border-violet-500 dark:bg-transparent',
            'purple' => 'bg-purple-50 dark:border dark:border-purple-500 dark:bg-transparent',
            'fuchsia' => 'bg-fuchsia-50 dark:border dark:border-fuchsia-500 dark:bg-transparent',
            'pink' => 'bg-pink-50 dark:border dark:border-pink-500 dark:bg-transparent',
            'rose' => 'bg-rose-50 dark:border dark:border-rose-500 dark:bg-transparent',
        ];
    }

    private function border(): array
    {
        return [
            'white' => 'border-b-white',
            'black' => 'border-b-white dark:border-b-black',
            'primary' => 'border-b-primary-200 dark:border-b dark:border-primary-500',
            'secondary' => 'border-b-secondary-200 dark:border-b dark:border-secondary-500',
            'slate' => 'border-b-slate-200 dark:border-b dark:border-slate-500',
            'gray' => 'border-b-gray-200 dark:border-b dark:border-gray-500',
            'zinc' => 'border-b-zinc-200 dark:border-b dark:border-zinc-500',
            'neutral' => 'border-b-neutral-200 dark:border-b dark:border-neutral-500',
            'stone' => 'border-b-stone-200 dark:border-b dark:border-stone-500',
            'red' => 'border-b-red-200 dark:border-b dark:border-red-500',
            'orange' => 'border-b-orange-200 dark:border-b dark:border-orange-500',
            'amber' => 'border-b-amber-200 dark:border-b dark:border-amber-500',
            'yellow' => 'border-b-yellow-200 dark:border-b dark:border-yellow-500',
            'lime' => 'border-b-lime-200 dark:border-b dark:border-lime-500',
            'green' => 'border-b-green-200 dark:border-b dark:border-green-500',
            'emerald' => 'border-b-emerald-200 dark:border-b dark:border-emerald-500',
            'teal' => 'border-b-teal-200 dark:border-b dark:border-teal-500',
            'cyan' => 'border-b-cyan-200 dark:border-b dark:border-cyan-500',
            'sky' => 'border-b-sky-200 dark:border-b dark:border-sky-500',
            'blue' => 'border-b-blue-200 dark:border-b dark:border-blue-500',
            'indigo' => 'border-b-indigo-200 dark:border-b dark:border-indigo-500',
            'violet' => 'border-b-violet-200 dark:border-b dark:border-violet-500',
            'purple' => 'border-b-purple-200 dark:border-b dark:border-purple-500',
            'fuchsia' => 'border-b-fuchsia-200 dark:border-b dark:border-fuchsia-500',
            'pink' => 'border-b-pink-200 dark:border-b dark:border-pink-500',
            'rose' => 'border-b-rose-200 dark:border-b dark:border-rose-500',
        ];
    }

    private function text(): array
    {
        return [
            'white' => 'text-white',
            'black' => 'text-white dark:text-black',
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
        ];
    }
}
