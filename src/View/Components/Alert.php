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
        public ?string $icon = null,
        public string $color = 'primary',
        public bool $closeable = false,
        public bool $translucent = false,
        public bool $pulse = false,
        public string $style = 'solid',
    ) {
        $this->style = $this->translucent && $this->color !== 'white' ? 'translucent' : 'solid';
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.alert');
    }

    public function tallStackUiClasses(): array
    {
        $color = $this->tallStackUiTextColor();

        return Arr::dot([
            'wrapper' => Arr::toCssClasses([
                'rounded-md p-4',
                'animate-pulse' => $this->pulse,
            ]),
            'title' => [
                'base' => 'text-lg font-semibold',
                'wrapper' => 'flex items-center justify-between',
                'icon' => [
                    'wrapper' => 'ml-auto pl-3',
                    'size' => 'w-5 h-5',
                ],
            ],
            'text' => [
                'wrapper' => 'flex items-center justify-between',
                'title' => [
                    'wrapper' => Arr::toCssClasses([
                        'text-sm',
                        'inline-flex' => $this->title === null && $this->icon !== null,
                        'mt-2' => $this->title !== null,
                    ]),
                    'icon' => [
                        'wrapper' => 'flex items-center',
                        'size' => 'w-5 h-5',
                    ],
                ],
            ],
            'icon' => [
                'wrapper' => 'mr-2',
                'size' => 'w-5 h-5',
            ],
            /* Internal Usage Only */
            'internal' => [
                'wrapper.color' => Arr::toCssClasses([
                    TallStackUi::colors()
                        ->when($this->style === 'solid', fn (Color $color) => $color->set('bg', $this->color, ! in_array($this->color, ['white', 'black']) ? 300 : null))
                        ->unless($this->style === 'solid', fn (Color $color) => $color->set('bg', $this->color === 'black' ? 'neutral' : $this->color, $this->color === 'black' ? 200 : 100))
                        ->get(),
                    'border' => $this->color === 'white',
                ]),
                'title.base.color' => Arr::toCssClasses([$color => $this->title !== null]),
                'title.icon.color' => $color,
                'text.title.wrapper.color' => $color,
                'text.title.icon.color' => $color,
                'icon.color' => Arr::toCssClasses([
                    TallStackUi::colors()
                        ->set('text', $this->color === 'black' ? 'white' : $this->color, $this->color === 'black' ? null : 500)
                        ->get() => $this->color !== 'white',
                ]),
            ]
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
