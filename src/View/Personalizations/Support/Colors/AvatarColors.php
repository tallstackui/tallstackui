<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Avatar;

class AvatarColors
{
    public function __invoke(Avatar $avatar): array
    {
        $colors = match ($avatar->color) {
            'white', 'black' => 'neutral',
            default => $avatar->color,
        };

        $weight = match ($avatar->color) {
            'black' => 700,
            'white' => null,
            default => 500,
        };

        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('bg', $colors, $weight)
                    ->merge('border', $colors, $weight)
                    ->mergeWhen($avatar->color === 'white', 'dark:bg', 'white')
                    ->mergeWhen($avatar->color === 'white', 'dark:border', 'white')
                    ->get() => ! $avatar->model,
            ]),
        ];
    }
}
