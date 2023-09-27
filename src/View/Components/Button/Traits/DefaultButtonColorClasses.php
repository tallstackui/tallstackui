<?php

namespace TasteUi\View\Components\Button\Traits;

use TasteUi\Facades\TasteUi;
use TasteUi\Support\Elements\Color;

trait DefaultButtonColorClasses
{
    private array $colorMap;

    private function setColorMap(): void
    {
        $this->colorMap = match ($this->color) {
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
        $this->setColorMap();

        $colorClasses = TasteUi::colors();
        $colorClasses = $this->setSolidColors($colorClasses);
        $colorClasses = $this->setOutlineColors($colorClasses);

        return $colorClasses->get();
    }

    private function setTextColor(Color $color): Color
    {
        if (empty($this->colorMap)) {
            $this->setColorMap();
        }

        $weight = $this->color === 'white' ? 950 : 50;
        if ($this->style === 'outline') {
            $weight = in_array($this->color, ['black', 'white']) ? 950 : 500;
        }

        return $color->set('text', $this->colorMap['text'], $weight);
    }

    private function setSolidColors(Color $color): Color
    {
        $weightMap = match ($this->color) {
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

        return $color->when($this->style === 'solid', function (Color $color) use ($weightMap) {
            $textColor = $this->setTextColor($color);
            $color = $textColor;

            return $color->set('ring', $this->colorMap['ring'], $weightMap['ring'])
                ->set('hover:bg', $this->colorMap['hover:bg'], $weightMap['hover:bg'])
                ->set('hover:ring', $this->colorMap['hover:ring'], $weightMap['hover:ring'])
                ->set('bg', $this->colorMap['bg'], $weightMap['bg']);
        });
    }

    private function setOutlineColors(Color $color): Color
    {
        $weightMap = match ($this->color) {
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

        return $color->when($this->style === 'outline', function (Color $color) use ($weightMap) {
            $textColor = $this->setTextColor($color);
            $color = $textColor;

            return $color->set('border', $this->colorMap['border'], $weightMap['border'])
                ->set('ring', $this->colorMap['ring'], $weightMap['ring'])
                ->set('hover:bg', $this->colorMap['hover:bg'], $weightMap['hover:bg'])
                ->set('hover:ring', $this->colorMap['hover:ring'], $weightMap['hover:ring'])
                ->append('border');
        });
    }
}
