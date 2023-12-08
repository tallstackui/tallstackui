<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('wrapper.radio')]
class Radio extends Component implements Personalization
{
    public function __construct(
        public ?string $computed = null,
        public ?string $label = null,
        public ?string $id = null,
        public ?string $position = 'left',
        public ?string $align = 'middle',
        public bool $error = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex items-center',
                'second' => [
                    'start' => 'flex items-start',
                    'middle' => 'flex items-end',
                ],
            ],
            'label' => [
                'wrapper' => 'relative inline-flex cursor-pointer items-start',
                'text' => 'dark:text-dark-400 cursor-pointer items-center text-sm font-medium text-gray-700',
                'error' => 'text-red-600 dark:text-red-500',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.wrapper.radio');
    }
}
