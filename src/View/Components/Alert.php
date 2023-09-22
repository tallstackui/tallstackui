<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Support\Elements\Color;

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

    /**
     * Default class for the main div.
     */
    public function baseClass(): string
    {
        return Arr::toCssClasses([
            'rounded-md p-4',
            Color::get('bg', $this->color, '400') => ! $this->translucent,
            Color::get('bg', $this->color, '100') => $this->translucent,
        ]);
    }

    /**
     * Classes to the title element.
     */
    public function titleElement(): array
    {
        $color = Color::get('text', $this->color, '800');

        return [
            'base' => Arr::toCssClasses([
                'text-lg font-semibold',
                $color,
            ]),
            'wrapper' => 'flex items-center justify-between',
            'icon' => [
                'wrapper' => 'ml-auto pl-3',
                'style' => config('tasteui.icon') ?? 'solid',
                'class' => Arr::toCssClasses(['w-5 h-5', $color]),
            ],
        ];
    }

    /**
     * Classes to the text element.
     */
    public function textElement(): array
    {
        $color = Color::get('text', $this->color, '800');

        return [
            'wrapper' => 'flex items-center justify-between',
            'title' => [
                'wrapper' => Arr::toCssClasses([
                    'text-sm',
                    'mt-2' => $this->title !== null,
                    $color,
                ]),
                'icon' => [
                    'wrapper' => 'flex items-center',
                    'style' => config('tasteui.icon') ?? 'solid',
                    'class' => Arr::toCssClasses(['w-5 h-5', $color]),
                ],
            ],
        ];
    }
}
