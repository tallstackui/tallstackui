<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Badge;

class BadgeColors
{
    public function __construct(protected Badge $component)
    {
        //
    }

    public function __invoke(): array
    {
        return [
            'wrapper.color' => $this->background().' '.$this->text(),
            'icon.color' => Arr::toCssClasses([
                'text-white' => $this->component->color !== 'white' && $this->component->style === 'solid',
                TallStackUi::tailwind()
                    ->set('text', $this->component->color, 500)
                    ->get() => $this->component->style === 'outline',
            ]),
        ];
    }

    private function background(): string
    {
        $colors = match ($this->component->color) {
            'white' => 'neutral',
            default => $this->component->color,
        };

        $weight = match ($this->component->color) {
            'white', 'black' => null,
            default => 500,
        };

        return TallStackUi::tailwind()
            ->set('border', $colors, $weight)
            ->mergeWhen($this->component->style === 'solid', 'bg', $colors, $weight)
            ->mergeWhen($this->component->style === 'solid' && $this->component->color === 'white', 'dark:bg', 'white')
            ->mergeWhen($this->component->style === 'outline' && $this->component->color === 'white', 'dark:text', 'white')
            ->get();
    }

    private function text(): string
    {
        $style = $this->component->style;
        $color = $this->component->color;

        $colors = match (true) {
            $style === 'solid' && $color !== 'white' => 'white',
            ($style === 'solid' && $color === 'white') || ($style === 'outline' && $color === 'white') => 'neutral',
            default => $color,
        };

        $weight = match (true) {
            $color === 'white' || ($style === 'outline' && $color === 'black') => null,
            ($style === 'outline' && $color !== 'white') => 500,
            default => null,
        };

        return TallStackUi::tailwind()->set('text', $colors, $weight)->get();
    }
}
