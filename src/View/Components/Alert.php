<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;

class Alert extends Component implements Customizable
{
    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public string $color = 'primary',
        public bool $closeable = false,
        public bool $translucent = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.alert');
    }

    public function customization(bool $error = false): array
    {
        return [
            ...$this->tasteUiMainClasses(),
        ];
    }

    public function tasteUiMainClasses(): array
    {
        $color = TasteUi::colors()
            ->set('text', $this->color, 800)
            ->get();

        return Arr::dot([
            'base' => Arr::toCssClasses([
                'rounded-md p-4',
                TasteUi::colors()
                    ->set('bg', $this->color, 400)
                    ->get() => ! $this->translucent,
                TasteUi::colors()
                    ->set('bg', $this->color, 100)
                    ->get() => $this->translucent,
            ]),
            'title' => [
                'base' => Arr::toCssClasses(['text-lg font-semibold', $color]),
                'wrapper' => 'flex items-center justify-between',
                'icon' => [
                    'wrapper' => 'ml-auto pl-3',
                    'classes' => Arr::toCssClasses(['w-5 h-5', $color]),
                ],
            ],
            'text' => [
                'wrapper' => 'flex items-center justify-between',
                'title' => [
                    'wrapper' => Arr::toCssClasses(['text-sm', 'mt-2' => $this->title !== null, $color]),
                    'icon' => [
                        'wrapper' => 'flex items-center',
                        'classes' => Arr::toCssClasses(['w-5 h-5', $color]),
                    ],
                ],
            ],
        ]);
    }
}
