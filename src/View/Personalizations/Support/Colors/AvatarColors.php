<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Avatar\Avatar;

class AvatarColors
{
    public function __invoke(Avatar $avatar): array
    {
        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('bg', $avatar->color, $avatar->color === 'black' ? null : 500)
                    ->merge('border', $avatar->color, $avatar->color === 'black' ? null : 500)
                    ->get() => ! $avatar->modelable,
            ]),
        ];
    }
}
