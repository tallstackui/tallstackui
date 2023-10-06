<?php

namespace TallStackUi\View\Components\Button\Traits;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Elements\Color;
use TallStackUi\View\Components\Button\Index;

trait DefaultButtonColorClasses
{
    private array $variations = [];

    public function tasteUiButtonColorClasses(): string
    {
        $this->variations = match ($this->color) {
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

        $color = $this->tasteUiTextColorVariations(TallStackUi::colors());

        $this->tasteUiButtonSolidVariations($color)->get();
        $this->tasteUiButtonOutlineVariations($color)->get();

        return $color->get();
    }

    public function tasteUiIconColorClasses(): string
    {
        $color = $this->tasteUiTextColorVariations(TallStackUi::colors());

        if ($this instanceof Index) {
            $classes = [
                'w-2 h-2' => $this->size === 'xs' || $this->size === 'sm',
                'w-3 h-3' => $this->size === 'md',
                'w-5 h-5' => $this->size === 'lg',
            ];
        } else {
            $classes = ['w-4 h-4'];
        }

        return Arr::toCssClasses([...$classes, $color->get()]);
    }

    private function tasteUiTextColorVariations(Color $color): Color
    {
        $weight = $this->color === 'white' ? 950 : 50;

        if ($this->style === 'outline') {
            $weight = in_array($this->color, ['black', 'white']) ? 950 : 500;
        }

        return $color->set('text', $this->variations['text'], $weight);
    }

    private function tasteUiButtonSolidVariations(Color $color): Color
    {
        $variation = match ($this->color) {
            'white' => [
                'bg' => 50,
                'ring' => 200,
                'hover:bg' => 200,
                'hover:ring' => 200,
            ],
            'black' => [
                'bg' => 950,
                'ring' => 950,
                'hover:bg' => 700,
                'hover:ring' => 950,
            ],
            default => [
                'bg' => 500,
                'ring' => 500,
                'hover:bg' => 600,
                'hover:ring' => 600,
            ]
        };

        return $color->when($this->style === 'solid', function (Color $color) use ($variation) {
            return $color->set('ring', $this->variations['ring'], $variation['ring'])
                ->set('hover:bg', $this->variations['hover:bg'], $variation['hover:bg'])
                ->set('hover:ring', $this->variations['hover:ring'], $variation['hover:ring'])
                ->set('bg', $this->variations['bg'], $variation['bg']);
        });
    }

    private function tasteUiButtonOutlineVariations(Color $color): Color
    {
        $variation = match ($this->color) {
            'white' => [
                'border' => 200,
                'ring' => 200,
                'hover:bg' => 200,
                'hover:ring' => 200,
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

        return $color->when($this->style === 'outline', function (Color $color) use ($variation) {
            return $color->set('border', $this->variations['border'], $variation['border'])
                ->set('ring', $this->variations['ring'], $variation['ring'])
                ->set('hover:bg', $this->variations['hover:bg'], $variation['hover:bg'])
                ->set('hover:ring', $this->variations['hover:ring'], $variation['hover:ring'])
                ->append('border');
        });
    }
}
