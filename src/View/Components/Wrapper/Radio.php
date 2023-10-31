<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;

class Radio extends Component implements Personalize
{
    public function __construct(
        public ?string $computed = null,
        public ?string $label = null,
        public ?string $position = 'left',
        public bool $error = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center',
            'label' => [
                'wrapper' => [
                    'text' => 'text-sm font-medium text-gray-700 dark:text-dark-400',
                    'error' => 'text-red-600 dark:text-red-500',
                ],
            ],
            'slot' => 'relative inline-flex cursor-pointer items-center',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.wrapper.radio');
    }
}
