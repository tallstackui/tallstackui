<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Errors;

// TODO: refactor
class ErrorsColors
{
    public function __construct(protected Errors $component)
    {
        //
    }

    public function __invoke(): array
    {
        $colors['text'] = match ($this->component->color === 'black') {
            true => 'white',
            false => $this->component->color,
        };

        $colors['bg'] = match ($this->component->color) {
            'white' => 'gray',
            default => $this->component->color,
        };

        $weight['general'] = match ($this->component->color) {
            'white', 'black' => null,
            default => 500,
        };

        $weight['bg'] = match ($this->component->color) {
            'black' => null,
            default => 50,
        };

        $text = TallStackUi::tailwind()
            ->set('text', $colors['text'], $weight['general'])
            ->merge('dark:text', $this->component->color, $weight['general'])
            ->mergeWhen($this->component->color === 'white', 'dark:text', 'white')
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
