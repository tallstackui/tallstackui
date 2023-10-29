<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Errors;

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

        $text = TallStackUi::tailwind()
            ->set('text', $colors['text'], 800)
            ->merge('dark:text', $colors['text'], 500)
            ->mergeWhen($errors->color === 'white', 'dark:text', 'white')
            ->get();

        return [
            'wrapper.second.color' => TallStackUi::tailwind()
                ->set('bg', $colors['bg'], 50)
                ->append('dark:bg-transparent')
                ->clean(false)
                ->merge('dark:border dark:border', $colors['bg'], 500)
                ->get(),
            'title.text.color' => $text,
            'title.wrapper.color' => TallStackUi::tailwind()
                ->set('border', $colors['bg'], 200)
                ->merge('dark:border', $colors['bg'], 500)
                ->get(),
            'body.list.color' => $text,
        ];
    }
}
