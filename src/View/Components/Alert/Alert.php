<?php

namespace TasteUi\View\Components\Alert;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\View\Components\Alert\Support\BackgroundColors;
use TasteUi\View\Components\Alert\Support\TextColors;
use TasteUi\View\Components\Alert\Support\TitleTextColors;

class Alert extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public string $color = 'primary',
        public ?string $class = null,
        public bool $closeable = false,
        public bool $translucent = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.alert');
    }

    public function getBaseClass(): string
    {
        return Arr::toCssClasses([
            'rounded-md',
            'p-4',
            BackgroundColors::translucent($this->color) => $this->translucent,
            BackgroundColors::base($this->color) => ! $this->translucent,
        ]);
    }

    public function getBaseTitle(): array
    {
        return [
            'wrapper' => 'flex justify-between',
            'title' => Arr::toCssClasses([
                'text-lg font-semibold',
                TitleTextColors::option($this->color),
            ]),
            'icon' => [
                'wrapper' => 'ml-auto pl-3',
                'style' => config('tasteui.icon') ?? 'solid',
                'class' => Arr::toCssClasses([
                    'w-5 h-5',
                    TextColors::option($this->color),
                ]),
            ],
        ];
    }

    public function getBaseText(): array
    {
        return [
            'wrapper' => 'flex items-center justify-between',
            'title' => [
                'wrapper' => Arr::toCssClasses([
                    'mt-2 text-sm',
                    TitleTextColors::option($this->color),
                ]),
                'icon' => [
                    'wrapper' => 'flex items-center',
                    'style' => config('tasteui.icon') ?? 'solid',
                    'class' => Arr::toCssClasses([
                        'w-5 h-5',
                        TextColors::option($this->color),
                    ]),
                ],
            ],
        ];
    }
}
