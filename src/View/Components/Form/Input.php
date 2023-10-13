<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Support\Personalizations\Traits\InternalPersonalization;

class Input extends Component implements Customizable
{
    use InternalPersonalization;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public bool $square = false,
        public bool $round = false,
        public bool $validate = true,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';
        $this->square = config('tallstackui.personalizations.input.square');
        $this->round = config('tallstackui.personalizations.input.round');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.input');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'input' => $this->tallStackUiInputClasses(),
            'icon' => [
                'wrapper' => Arr::toCssClasses([
                    'pointer-events-none absolute inset-y-0 flex items-center text-secondary-500',
                    'left-0 pl-3' => $this->position === null || $this->position === 'left',
                    'right-0 pr-3' => $this->position === 'right',
                ]),
                'padding' => [
                    'left' => 'left-0 pl-3',
                    'right' => 'right-0 pr-3',
                ],
                'size' => 'h-5 w-5',
            ],
            'error' => 'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
        ]);
    }
}
