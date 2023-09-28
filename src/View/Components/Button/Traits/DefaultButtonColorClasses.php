<?php

namespace TasteUi\View\Components\Button\Traits;

use TasteUi\Facades\TasteUi;
use TasteUi\Support\Elements\Color;

trait DefaultButtonColorClasses
{
    private array $map = [];

    private function map(): void
    {
        $this->map = match ($this->color) {
            'white', 'black' => [
                'text' => 'neutral',
                'ring' => 'neutral',
                'hover:bg' => 'neutral',
                'hover:ring' => 'neutral',
                'border' => 'neutral',
                'bg' => 'neutral',
            ],
            default => [
                'text' => $this->color,
                'ring' => $this->color,
                'hover:bg' => $this->color,
                'hover:ring' => $this->color,
                'border' => $this->color,
                'bg' => $this->color,
            ]
        };
    }

    public function tasteUiButtonColorClasses(): string
    {
        $this->map();

        $color = TasteUi::colors();
        $color = $this->tasteUiTextColorVariations($color);

        if ($this->style === 'solid') {
            $color = $this->tasteUiSolidVariations($color);
        } else {
            $color = $this->tasteUiOutlineVariations($color);
        }

        return $color->get();
    }

    private function tasteUiTextColorVariations(Color $color): Color
    {
        if (empty($this->map)) {
            $this->map();
        }

        $weight = $this->color === 'white' ? 950 : 50;

        if ($this->style === 'outline') {
            $weight = in_array($this->color, ['black', 'white']) ? 950 : 500;
        }

        return $color->set('text', $this->map['text'], $weight);
    }

    private function tasteUiSolidVariations(Color $color): Color
    {
        $map = match ($this->color) {
            'white' => [
                'ring' => 200,
                'hover:bg' => 200,
                'hover:ring' => 200,
                'bg' => 50,
            ],
            'black' => [
                'ring' => 950,
                'hover:bg' => 700,
                'hover:ring' => 950,
                'bg' => 950,
            ],
            default => [
                'ring' => 500,
                'hover:bg' => 600,
                'hover:ring' => 600,
                'bg' => 500,
            ]
        };

        return $color->set('ring', $this->map['ring'], $map['ring'])
            ->set('hover:bg', $this->map['hover:bg'], $map['hover:bg'])
            ->set('hover:ring', $this->map['hover:ring'], $map['hover:ring'])
            ->set('bg', $this->map['bg'], $map['bg']);
    }

    private function tasteUiOutlineVariations(Color $color): Color
    {
        $map = match ($this->color) {
            'white' => [
                'ring' => 200,
                'hover:bg' => 200,
                'hover:ring' => 200,
                'border' => 200,
            ],
            'black' => [
                'border' => 950,
                'ring' => 950,
                'hover:bg' => 200,
                'hover:ring' => 950,
            ],
            default => [
                'border' => 500,
                'ring' => 500,
                'hover:bg' => 50,
                'hover:ring' => 600,
            ]
        };

        return $color->set('border', $this->map['border'], $map['border'])
            ->set('ring', $this->map['ring'], $map['ring'])
            ->set('hover:bg', $this->map['hover:bg'], $map['hover:bg'])
            ->set('hover:ring', $this->map['hover:ring'], $map['hover:ring'])
            ->append('border');
    }
}
