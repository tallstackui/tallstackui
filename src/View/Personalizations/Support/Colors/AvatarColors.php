<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Avatar;

class AvatarColors
{
    public function __construct(protected Avatar $component)
    {
        //
    }

    public function __invoke(): array
    {
        $colors = match ($this->component->color) {
            'white' => 'neutral',
            default => $this->component->color,
        };

        $weight = match ($this->component->color) {
            'white', 'black' => null,
            default => 500,
        };

        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::tailwind()
                    ->set('bg', $colors, $weight)
                    ->merge('border', $colors, $weight)
                    ->mergeWhen($this->component->color === 'white', 'dark:bg', 'white')
                    ->mergeWhen($this->component->color === 'white', 'dark:border', 'white')
                    ->get() => ! $this->component->model,
            ]),
        ];
    }
}
