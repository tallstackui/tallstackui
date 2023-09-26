<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Facades\TasteUi;

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

    // main
    public function tasteUiMainClasses(): string
    {
        return Arr::toCssClasses([
            'rounded-md p-4',
            TasteUi::colors()
                ->set('bg', $this->color, 400)
                ->get() => ! $this->translucent,
            TasteUi::colors()
                ->set('bg', $this->color, 100)
                ->get() => $this->translucent,
        ]);
    }

    // title.base
    public function tasteUiTitleBaseClasses(): string
    {
        return Arr::toCssClasses([
            'text-lg font-semibold',
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
        ]);
    }

    // title.wrapper
    public function tasteUiTitleWrapperClasses(): string
    {
        return 'flex items-center justify-between';
    }

    // title.icon.wrapper
    public function tasteUiTitleIconBaseClasses(): string
    {
        return Arr::toCssClasses([
            'w-5 h-5',
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
        ]);
    }

    // title.icon.classes
    public function tasteUiTitleIconWrapperClasses(): string
    {
        return 'ml-auto pl-3';
    }

    // text.wrapper
    public function tasteUiTextWrapperClasses(): string
    {
        return 'flex items-center justify-between';
    }

    // text.title.wrapper
    public function tasteUiTextTitleWrapperClasses(): string
    {
        return Arr::toCssClasses([
            'text-sm',
            'mt-2' => $this->title !== null,
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
        ]);
    }

    // text.icon.classes
    public function tasteUiTextIconBaseClasses(): string
    {
        return Arr::toCssClasses([
            'w-5 h-5',
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
        ]);
    }

    // text.icon.wrapper
    public function tasteUiTextIconWrapperClasses(): string
    {
        return Arr::toCssClasses([
            'text-sm',
            'mt-2' => $this->title !== null,
            TasteUi::colors()
                ->set('text', $this->color, 800)
                ->get(),
        ]);
    }

    public function tasteUiTitleClasses(): array
    {
        $color = TasteUi::colors()
            ->set('text', $this->color, 800)
            ->get();

        return [
            'base' => Arr::toCssClasses(['text-lg font-semibold', $color]),
            'wrapper' => 'flex items-center justify-between',
            'icon' => [
                'wrapper' => 'ml-auto pl-3',
                'class' => Arr::toCssClasses(['w-5 h-5', $color]),
            ],
        ];
    }

    public function tasteUiTextClasses(): array
    {
        $color = TasteUi::colors()
            ->set('text', $this->color, 800)
            ->get();

        return [
            'wrapper' => 'flex items-center justify-between',
            'title' => [
                'wrapper' => Arr::toCssClasses(['text-sm', 'mt-2' => $this->title !== null, $color]),
                'icon' => [
                    'wrapper' => 'flex items-center',
                    'class' => Arr::toCssClasses(['w-5 h-5', $color]),
                ],
            ],
        ];
    }
}
