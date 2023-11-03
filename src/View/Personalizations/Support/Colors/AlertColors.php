<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Alert;

// TODO: refactor
class AlertColors
{
    public function __construct(protected Alert $component)
    {
        //
    }

    public function __invoke(): array
    {
        $text = $this->text();

        return [
            'wrapper.color' => Arr::toCssClasses([$this->background(), 'border border-gray-100' => $this->component->color === 'white']),
            'title.base.color' => Arr::toCssClasses([$text => $this->component->title !== null]),
            'title.close.color' => $text,
            'text.title.wrapper.color' => $text,
            'text.close.color' => $text,
            'icon.color' => Arr::toCssClasses([
                TallStackUi::tailwind()
                    ->set('text', $this->component->color === 'black' ? 'white' : $this->component->color, $this->component->color === 'black' ? null : 500)
                    ->get() => $this->component->color !== 'white',
            ]),
        ];
    }

    private function background(): string
    {
        if ($this->component->style === 'solid') {
            $weight = match ($this->component->color) {
                'white', 'black' => null,
                default => 300,
            };
        } else {
            $weight = 100;
        }

        return TallStackUi::tailwind()
            ->set('bg', $this->component->color, $weight)
            ->get();
    }

    private function text(): string
    {
        $weight = (match ($this->component->color) {
            'black' => fn () => $this->component->style === 'solid' ? null : 700,
            default => fn () => 900,
        })();

        if ($this->component->style === 'solid') {
            $colors = match ($this->component->color) {
                'black' => 'white',
                'white' => 'neutral',
                default => $this->component->color,
            };
        } else {
            $colors = match ($this->component->color) {
                'black', 'white' => 'neutral',
                default => $this->component->color,
            };
        }

        return TallStackUi::tailwind()
            ->set('text', $colors, $weight)
            ->get();
    }
}
