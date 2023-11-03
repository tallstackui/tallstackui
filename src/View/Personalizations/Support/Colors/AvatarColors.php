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
        $border = match ($this->component->color) {
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
                    ->set('bg', $this->component->color, $weight)
                    ->merge('border', $border, $weight)
                    ->mergeWhen($this->component->color === 'white', 'dark:bg', 'white')
                    ->mergeWhen($this->component->color === 'white', 'dark:border', 'white')
                    ->get() => ! $this->component->model,
            ]),
        ];
    }
}
