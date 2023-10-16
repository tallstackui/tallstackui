<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Personalizations\Support\Color;

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
            'title.icon.color' => $text,
            'text.title.wrapper.color' => $text,
            'text.icon.color' => $text,
            'icon.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('text', $alert->color === 'black' ? 'white' : $alert->color, $alert->color === 'black' ? null : 500)
                    ->get() => $alert->color !== 'white',
            ]),
        ];
    }

    private function background(): string
    {
        $colors = match ($this->alert->color) {
            'black' => 'neutral',
            default => $this->alert->color,
        };

        return TallStackUi::colors()
            ->when($this->alert->style === 'solid', function (Color $color) use ($colors) {
                $weight = match ($this->alert->color) {
                    'black' => 700,
                    'white' => null,
                    default => 300,
                };

                return $color->set('bg', $colors, $weight);
            })
            ->when($this->alert->style === 'translucent', function (Color $color) use ($colors) {
                $weight = match ($this->alert->color) {
                    'black' => 200,
                    default => 100,
                };

                return $color->set('bg', $colors, $weight);
            })
            ->get();
    }

    private function text(): string
    {
        $weight = $this->alert->color === 'white' ? 900 : ($this->alert->color === 'black' ? null : 900);

        return TallStackUi::colors()
            ->when($this->alert->style === 'solid', function (Color $color) use ($weight) {
                return (match ($this->alert->color) {
                    'black' => fn () => $color->set('text', 'white'),
                    'white' => fn () => $color->set('text', 'neutral', 900),
                    default => fn () => $color->set('text', $this->alert->color, $weight),
                })();
            })
            ->when($this->alert->style === 'translucent', function (Color $color) use ($weight) {
                return (match ($this->alert->color === 'black' || $this->alert->color === 'white') {
                    true => fn () => $color->set('text', 'neutral', 900),
                    default => fn () => $color->set('text', $this->alert->color, $weight),
                })();
            })
            ->get();
    }
}
