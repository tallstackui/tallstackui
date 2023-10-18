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
            'wrapper.color' => Arr::toCssClasses([
                $this->background(),
                $this->text() => $badge->style === 'outline',
            ]),
            'icon.color' => Arr::toCssClasses([
                'text-white' => $badge->color !== 'white' && $badge->style === 'solid',
                TallStackUi::colors()
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

        return TallStackUi::colors()
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
            'white', 'black' => null,
            default => 500,
        };

        return TallStackUi::colors()
            ->set('text', $colors, $weight)
            ->get();
    }
}
