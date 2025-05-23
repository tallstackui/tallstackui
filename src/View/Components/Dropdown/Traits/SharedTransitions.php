<?php

namespace TallStackUi\View\Components\Dropdown\Traits;

trait SharedTransitions
{
    public function transitions(): string
    {
        $side = str_contains((string) $this->position, 'right') || str_contains((string) $this->position, 'left');
        $orientation = str_contains((string) $this->position, 'bottom') || str_contains((string) $this->position, 'right');

        $start = match (true) {
            $side && $orientation => '-translate-x-2',
            $side => 'translate-x-2',
            $orientation => '-translate-y-2',
            default => 'translate-y-2',
        };

        $end = $side ? 'translate-x-0' : 'translate-y-0';

        $content = <<<HTML
            x-transition:enter="transition duration-100 ease-out"
            x-transition:enter-start="opacity-0 $start"
            x-transition:enter-end="opacity-100 $end"
            x-transition:leave="transition duration-100 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        HTML;

        return str($content)->squish()->trim()->value();
    }
}
