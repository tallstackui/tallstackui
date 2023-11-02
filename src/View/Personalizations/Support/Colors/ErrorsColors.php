<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Errors;

class ErrorsColors
{
    public function __invoke(Errors $errors): array
    {
        $colors['text'] = match ($errors->color === 'black') {
            true => 'white',
            false => $errors->color,
        };

        $colors['bg'] = match ($errors->color) {
            'white' => 'gray',
            default => $errors->color,
        };

        $weight['general'] = match ($errors->color) {
            'white', 'black' => null,
            default => 500,
        };

        $weight['bg'] = match ($errors->color) {
            'black' => null,
            default => 50,
        };

        $text = TallStackUi::tailwind()
            ->set('text', $colors['text'], $weight['general'])
            ->merge('dark:text', $errors->color, $weight['general'])
            ->mergeWhen($errors->color === 'white', 'dark:text', 'white')
            ->get();

        return [
            'wrapper.second.color' => TallStackUi::tailwind()
                ->set('bg', $colors['bg'], $weight['bg'])
                ->append('dark:bg-transparent')
                ->clean(false)
                ->merge('dark:border dark:border', $colors['bg'], $weight['general'])
                ->get(),
            'title.text.color' => $text,
            'title.wrapper.color' => TallStackUi::tailwind()
                ->set('border', $colors['bg'], 200)
                ->merge('dark:border', $colors['bg'], $weight['general'])
                ->get(),
            'body.list.color' => $text,
        ];
    }
}
