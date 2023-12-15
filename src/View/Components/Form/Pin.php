<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.pin')]
class Pin extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?int $length = 4,
        public ?array $prefix = [],
        public ?bool $clear = null,
        public ?bool $prefixed = null,
        public ?bool $invalidate = null,
        public ?bool $numbers = null,
        public ?bool $letters = null,
        public ?string $mask = null,
    ) {
        $this->mask = $this->numbers ? '9' : ($this->letters ? 'a' : null);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.pin');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'soft-scrollbar flex items-center overflow-x-auto py-0.5',
            'input' => [
                'base' => 'mr-2 block w-[38px] rounded-md text-center text-lg font-medium shadow-sm ring-1 transition disabled:pointer-events-none disabled:opacity-50',
                'color' => [
                    'base' => 'dark:border-dark-600 dark:text-dark-300 focus:ring-primary-600 focus-within:focus:ring-primary-600 border-gray-300 text-gray-600 ring-transparent focus-within:ring-2 focus:border-0 focus:ring-2',
                    'background' => 'dark:bg-dark-800 bg-white',
                    'error' => 'border-0 border-red-500 text-red-600 ring-red-300 placeholder:text-red-600 focus-within:ring-red-500 focus-within:placeholder:text-red-600 focus:ring-2 focus:ring-red-500 focus-within:focus:ring-red-500 dark:ring-red-500 dark:focus-within:ring-red-500',
                ],
            ],
            'prefix' => 'dark:border-dark-600 focus:ring-primary-600 focus-within:focus:ring-primary-600 dark:bg-dark-800 dark:text-dark-300 mr-3 block w-[40px] rounded-md border border-gray-300 text-center text-lg font-medium text-gray-600 ring-0 ring-inset transition disabled:pointer-events-none disabled:opacity-50',
            'button' => 'h-6 w-6 text-red-500',
        ]);
    }
}
