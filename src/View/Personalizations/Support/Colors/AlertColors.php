<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Alert;

class AlertColors
{
    protected Alert $alert;

    public function __invoke(Alert $alert): array
    {
        $this->alert = $alert;
        $text = $this->text();

        return [
            'wrapper.color' => Arr::toCssClasses([$this->background(), 'border border-gray-100' => $this->alert->color === 'white']),
            'title.base.color' => Arr::toCssClasses([$text => $alert->title !== null]),
            'title.close.color' => $text,
            'text.title.wrapper.color' => $text,
            'text.close.color' => $text,
            'icon.color' => Arr::toCssClasses([
                TallStackUi::tailwind()
                    ->set('text', $alert->color === 'black' ? 'white' : $alert->color, $alert->color === 'black' ? null : 500)
                    ->get() => $alert->color !== 'white',
            ]),
        ];
    }

    private function background(): string
    {
        if ($this->alert->style === 'solid') {
            $weight = match ($this->alert->color) {
                'white', 'black' => null,
                default => 300,
            };
        } else {
            $weight = 100;
        }

        return TallStackUi::tailwind()
            ->set('bg', $this->alert->color, $weight)
            ->get();
    }

    private function text(): string
    {
        $weight = (match ($this->alert->color) {
            'black' => fn () => $this->alert->style === 'solid' ? null : 700,
            default => fn () => 900,
        })();

        if ($this->alert->style === 'solid') {
            $colors = match ($this->alert->color) {
                'black' => 'white',
                'white' => 'neutral',
                default => $this->alert->color,
            };
        } else {
            $colors = match ($this->alert->color) {
                'black', 'white' => 'neutral',
                default => $this->alert->color,
            };
        }

        return TallStackUi::tailwind()
            ->set('text', $colors, $weight)
            ->get();
    }
}
