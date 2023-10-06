<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Radio extends Component implements Customizable
{
    public function __construct(
        public ?string $id = null,
        public ?string $computed = null,
        public ?string $label = null,
        public ?string $position = 'left',
        public bool $error = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('tallstack-ui::components.wrapper.radio');
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
            'wrapper' => 'flex items-center',
            'label' => [
                'span' => 'text-sm',
                'base' => [
                    'normal' => 'font-medium text-gray-700',
                    'error' => 'text-red-600',
                ],
            ],
            'slot' => 'relative inline-flex cursor-pointer items-center',
        ]);
    }
}
