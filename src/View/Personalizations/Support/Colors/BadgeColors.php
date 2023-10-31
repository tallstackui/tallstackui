<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Badge;

class BadgeColors
{
    protected Badge $badge;

    public function __invoke(Badge $badge): array
    {
        $this->badge = $badge;

        return [
            'wrapper.color' => $this->background().' '.$this->text(),
            'icon.color' => Arr::toCssClasses([
                'text-white' => $badge->color !== 'white' && $badge->style === 'solid',
                TallStackUi::tailwind()
                    ->set('text', $badge->color, 500)
                    ->get() => $badge->style === 'outline',
            ]),
        ];
    }

    private function background(): string
    {
        $colors = match ($this->badge->color) {
            'white', 'black' => 'neutral',
            default => $this->badge->color,
        };

        $weight = match ($this->badge->color) {
            'black' => 700,
            'white' => null,
            default => 500,
        };

        return TallStackUi::tailwind()
            ->set('border', $colors, $weight)
            ->mergeWhen($this->badge->style === 'solid', 'bg', $colors, $weight)
            ->mergeWhen($this->badge->style === 'solid' && $this->badge->color === 'white', 'dark:bg', 'white')
            ->mergeWhen($this->badge->style === 'outline' && $this->badge->color === 'white', 'dark:text', 'white')
            ->get();
    }

    private function text(): string
    {
        $colors = match ($this->badge->color) {
            'white', 'black' => 'neutral',
            default => $this->badge->color,
        };

        $weight = match ($this->badge->color) {
            'white', 'black' => 700,
            default => 500,
        };

        return TallStackUi::tailwind()
            ->set('text', $colors, $weight)
            ->get();
    }
}
