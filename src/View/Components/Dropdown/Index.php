<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Index extends Component implements Customizable
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $header = null,
        public ?string $action = null,
        public ?bool $animate = false,
        public ?bool $right = false,
    ) {
        $this->animate = $this->text ? true : $this->animate;
    }

    public function render(): View
    {
        return view('tallstack-ui::components.dropdown.index');
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
            'wrapper' => [
                'first' => 'flex items-start justify-center',
                'second' => 'relative inline-block text-left',
                'third' => Arr::toCssClasses([
                    'absolute z-10 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none',
                    'right-0 origin-top-right' => ! $this->right,
                    'left-0 origin-top-left' => $this->right,
                ]),
            ],
            'action' => [
                'text' => 'text-sm text-gray-700',
                'wrapper' => 'inline-flex w-full gap-x-1.5',
                'icon' => 'h-5 w-5 cursor-pointer text-gray-400 transition',
            ],
        ]);
    }
}
