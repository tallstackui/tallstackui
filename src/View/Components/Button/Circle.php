<?php

namespace TasteUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;
use TasteUi\Support\Elements\Color;
use TasteUi\View\Components\Button\Traits\DefaultButtonCustomColorClasses;

class Circle extends Component implements Customizable
{
    use DefaultButtonCustomColorClasses;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $color = 'primary',
        private ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
    }

    public function render(): View
    {
        return view('taste-ui::components.buttons.circle');
    }

    public function customize(bool $error = false): array
    {
        return [
            'main' => $this->customMainClasses(),
            'icon' => $this->customIconClasses(),
        ];
    }

    public function customMainClasses(): string
    {
        //TODO: black and white buttons
        return Arr::toCssClasses([
            'outline-none inline-flex justify-center items-center group transition ease-in duration-150 w-9 h-9',
            'focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded-full',
            $this->customColorClasses(),
        ]);
    }

    public function customIconClasses(): string
    {
        return Arr::toCssClasses([
            'w-4 h-4',
            TasteUi::colors()
                ->when($this->style === 'solid', fn (Color $color) => $color->set('text', 'white'))
                ->when($this->style === 'outline', fn (Color $color) => $color->set('text', $this->color, 500))
                ->get(),
        ]);
    }
}
