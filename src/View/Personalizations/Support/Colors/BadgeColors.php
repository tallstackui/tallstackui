<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Badge;

class BadgeColors
{
    public function __invoke(Badge $badge): array
    {
        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('border', $badge->color, $badge->color === 'black' ? null : 500)
                    ->mergeWhen($badge->style === 'solid', 'bg', $badge->color, $badge->color === 'black' ? null : 500)
                    ->get(),
                TallStackUi::colors()
                    ->set('text', $badge->color === 'white' ? 'black' : $badge->color, $badge->color === 'white' ? null : 500)
                    ->get() => $badge->style === 'outline',
            ]),
            'icon.color' => Arr::toCssClasses([
                'text-white' => $badge->color !== 'white' && $badge->style === 'solid',
                TallStackUi::colors()
                    ->set('text', $badge->color, 500)
                    ->get() => $badge->style === 'outline',
            ]),
        ];
    }
}
