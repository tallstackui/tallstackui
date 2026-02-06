<?php

namespace TallStackUi\Components\Wrapper\Radio;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('wrapper.radio')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $property = null,
        public ?string $label = null,
        public ?string $id = null,
        public ?string $position = 'left',
        public ?string $alignment = 'middle',
        public ?bool $invalidate = null,
        public ?bool $error = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view()->file(__DIR__.'/view.blade.php');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex items-center',
                'second' => [
                    'start' => 'flex items-start',
                    'middle' => 'flex items-center',
                ],
            ],
            'label' => [
                'wrapper' => 'relative inline-flex cursor-pointer items-start',
                'text' => 'dark:text-dark-400 cursor-pointer items-center text-sm font-medium text-gray-700',
                'error' => 'text-red-600 dark:text-red-500',
            ],
        ]);
    }
}
