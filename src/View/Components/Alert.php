<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use InvalidArgumentException;
use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;
use Throwable;

class Alert extends Component implements Customizable
{
    private bool $isBlack;

    private bool $isWhite;

    /**
     * @throws Throwable
     */
    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public string $color = 'primary',
        public bool $closeable = false,
        public bool $outline = false,
        public bool $translucent = false,
        public string $style = 'solid',
    ) {
        throw_if(
            $outline && $translucent,
            new InvalidArgumentException('You can not use "outline" and "translucent" attributes together.')
        );

        $this->style = $this->outline && $this->color !== 'white' ? 'outline' : $this->style;
        $this->style = $this->translucent && $this->color !== 'white' ? 'translucent' : $this->style;

        $this->isBlack = $this->color === 'black';
        $this->isWhite = $this->color === 'white';
    }

    public function render(): View
    {
        return view('taste-ui::components.alert');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return Arr::dot([
            'base' => Arr::toCssClasses([
                'rounded-md p-4',
                $this->handleBackgroundColor(),
                TasteUi::colors()
                    ->set('border', $this->color, $this->isBlack ? null : 900)
                    ->get() => $this->outline && ! $this->isWhite,
                'border' => $this->outline && ! $this->isWhite,
            ]),
            'title' => [
                'base' => Arr::toCssClasses(['text-lg font-semibold', $this->handleTextColor()]),
                'wrapper' => 'flex items-center justify-between',
                'icon' => [
                    'wrapper' => 'ml-auto pl-3',
                    'classes' => Arr::toCssClasses(['w-5 h-5', $this->handleTextColor()]),
                ],
            ],
            'text' => [
                'wrapper' => 'flex items-center justify-between',
                'title' => [
                    'wrapper' => Arr::toCssClasses([
                        'text-sm',
                        'mt-2' => $this->title !== null,
                        $this->handleTextColor(),
                    ]),
                    'icon' => [
                        'wrapper' => 'flex items-center',
                        'classes' => Arr::toCssClasses(['w-5 h-5', $this->handleTextColor()]),
                    ],
                ],
            ],
        ]);
    }

    private function handleBackgroundColor(): string
    {
        if ($this->style === 'solid') {
            $color = $this->color;
            $variation = ! in_array($color, ['white', 'black']) ? 300 : null;
        } else {
            $color = $this->color === 'black' ? 'neutral' : $this->color;
            $variation = $this->color === 'black' ? 200 : 100;
        }

        return TasteUi::colors()->set('bg', $color, $variation)->get();
    }

    private function handleTextColor(): string
    {
        if ($this->style === 'solid') {
            $color = $this->isBlack ? 'white' : ($this->isWhite ? 'black' : $this->color);
        } else {
            $color = $this->isWhite ? 'black' : $this->color;
        }

        $variation = $this->isBlack || $this->isWhite ? null : 900;

        return TasteUi::colors()->set('text', $color, $variation)->get();
    }
}
