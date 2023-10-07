<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Elements\Color;

class Alert extends Component implements Customizable
{
    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public string $color = 'primary',
        public bool $closeable = false,
        public bool $translucent = false,
        public string $style = 'solid',
    ) {
        $this->style = $this->translucent && $this->color !== 'white' ? 'translucent' : 'solid';
    }

    public function render(): View
    {
        return view('tallstack-ui::components.alert');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'base' => Arr::toCssClasses([
                'rounded-md p-4',
                TallStackUi::colors()
                    ->when(
                        $this->style === 'solid',
                        fn (Color $color) => $color->set('bg', $this->color, ! in_array($this->color, ['white', 'black']) ? 300 : null)
                    )
                    ->unless(
                        $this->style === 'solid',
                        fn (Color $color) => $color->set('bg', $this->color === 'black' ? 'neutral' : $this->color, $this->color === 'black' ? 200 : 100)
                    )
                    ->get(),
                TallStackUi::colors()
                    ->set('border', $this->color, $this->color === 'black' ? null : 500)
                    ->get() => $this->color !== 'white',
                'border' => $this->color === 'white',
            ]),
            'title' => [
                'base' => Arr::toCssClasses([
                    'text-lg font-semibold',
                    $this->tallStackUiTextColor() => $this->title !== null,
                ]),
                'wrapper' => 'flex items-center justify-between',
                'icon' => [
                    'wrapper' => 'ml-auto pl-3',
                    'classes' => Arr::toCssClasses(['w-5 h-5', $this->tallStackUiTextColor()]),
                ],
            ],
            'text' => [
                'wrapper' => 'flex items-center justify-between',
                'title' => [
                    'wrapper' => Arr::toCssClasses([
                        'text-sm',
                        'mt-2' => $this->title !== null,
                        $this->tallStackUiTextColor(),
                    ]),
                    'icon' => [
                        'wrapper' => 'flex items-center',
                        'classes' => Arr::toCssClasses(['w-5 h-5', $this->tallStackUiTextColor()]),
                    ],
                ],
            ],
        ]);
    }

    private function tallStackUiTextColor(): string
    {
        $weight = $this->color === 'black' || $this->color === 'white' ? null : 900;

        return TallStackUi::colors()
            ->when($this->style === 'solid',
                fn (Color $color) => $color->set('text', $this->color === 'black' ? 'white' : ($this->color === 'white' ? 'black' : $this->color), $weight)
            )
            ->unless($this->style === 'solid',
                fn (Color $color) => $color->set('text', $this->color === 'white' ? 'black' : $this->color, $weight)
            )
            ->get();
    }
}
