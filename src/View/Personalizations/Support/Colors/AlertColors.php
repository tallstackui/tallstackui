<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Personalizations\Support\Color;

class AlertColors
{
    public function __invoke(Alert $alert): array
    {
        $weight = $alert->color === 'black' || $alert->color === 'white' ? null : 900;
        $color = TallStackUi::colors()
            ->when($alert->style === 'solid', fn (Color $color) => $color->set('text', $alert->color === 'black' ? 'white' : ($alert->color === 'white' ? 'black' : $alert->color), $weight))
            ->unless($alert->style === 'solid', fn (Color $color) => $color->set('text', $alert->color === 'white' ? 'black' : $alert->color, $weight))
            ->get();

        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->when($alert->style === 'solid', fn (Color $color) => $color->set('bg', $alert->color, ! in_array($alert->color, ['white', 'black']) ? 300 : null))
                    ->unless($alert->style === 'solid', fn (Color $color) => $color->set('bg', $alert->color === 'black' ? 'neutral' : $alert->color, $alert->color === 'black' ? 200 : 100))
                    ->get(),
                'border border-gray-100' => $alert->color === 'white',
            ]),
            'title.base.color' => Arr::toCssClasses([$color => $alert->title !== null]),
            'title.icon.color' => $color,
            'text.title.wrapper.color' => $color,
            'text.icon.color' => $color,
            'icon.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('text', $alert->color === 'black' ? 'white' : $alert->color, $alert->color === 'black' ? null : 500)
                    ->get() => $alert->color !== 'white',
            ]),
        ];
    }
}
