<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Color;
use TallStackUi\View\Components\Errors;

class ErrorsColors
{
    public function __invoke(Errors $errors): array
    {
        $text = TallStackUi::colors()
            ->when($errors->color === 'black', fn (Color $color) => $color->set('text', 'neutral', 800))
            ->unless($errors->color === 'black', fn (Color $color) => $color->set('text', $errors->color, 800))
            ->get();

        return [
            'wrapper.second.color' => TallStackUi::colors()
                ->when($errors->color === 'white', fn (Color $color) => $color->set('bg', 'gray', 50))
                ->unless($errors->color === 'white', fn (Color $color) => $color->set('bg', $errors->color === 'black' ? 'neutral' : $errors->color, 50))
                ->get(),
            'title.text.color' => $text,
            'title.wrapper.color' => TallStackUi::colors()
                ->set('border', $errors->color, 200)
                ->get(),
            'body.list.color' => $text,
        ];
    }
}
