<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Personalizations\Support\Color;

class ErrorsColors
{
    public function __invoke(Errors $errors): array
    {
        $colors['text'] = match ($errors->color === 'black') {
            true => 'neutral',
            false => $errors->color,
        };

        $colors['bg'] = match ($errors->color) {
            'white' => 'gray',
            'black' => 'neutral',
            default => $errors->color,
        };

        $text = TallStackUi::colors()
            ->set('text', $colors['text'], 800)
            ->merge('dark:text', $colors['text'], 500)
            ->mergeWhen($errors->color === 'white', 'dark:text', 'white')
            ->get();

        return [
            'wrapper.second.color' => TallStackUi::colors()
                ->set('bg', $colors['bg'], 50)
                ->merge('dark:bg', 'transparent')
                ->clean(false)
                ->merge('dark:border dark:border', $colors['bg'], 500)
                ->get(),
            'title.text.color' => $text,
            'title.wrapper.color' => TallStackUi::colors()
                ->set('border', $colors['bg'], 200)
                ->merge('dark:border', $colors['bg'], 500)
                ->get(),
            'body.list.color' => $text,
        ];
    }
}
