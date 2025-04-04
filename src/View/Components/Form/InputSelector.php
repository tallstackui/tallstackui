<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\InputSelectorRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.input.selector')]
#[PassThroughRuntime(InputSelectorRuntime::class)]
class InputSelector extends TallStackUiComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?bool $clearable = null,
        public ?bool $invalidate = null,
        #[SkipDebug]
        public ?string $position = 'left',
        #[SkipDebug]
        public ComponentSlot|string|null $prefix = null,
        #[SkipDebug]
        public ComponentSlot|string|null $suffix = null,
        #[SkipDebug]
        public ?bool $left = null,
        #[SkipDebug]
        public ?bool $right = null,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';

        Cache::driver('array')->put('__tsui::input-selector::side', $this->right ? 'right' : 'left');

        if ($this->left === null && $this->right === null) {
            $this->left = true;
        }
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.input-selector');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => [
                    'first' => 'flex w-full',
                    'second' => 'focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex ring-1 focus-within:ring-2',
                    'round' => [
                        'left' => 'rounded-r-md',
                        'right' => 'rounded-l-md',
                    ],
                ],
                'base' => 'dark:placeholder-dark-400 w-full border-0 bg-transparent py-1.5 ring-0 placeholder:text-gray-400 focus:outline-hidden focus:ring-transparent sm:text-sm sm:leading-6',
                'slot' => 'dark:text-dark-400 flex select-none items-center whitespace-nowrap text-gray-500 sm:text-sm',
                'color' => [
                    'base' => 'dark:ring-dark-600 dark:text-dark-300 text-gray-600 ring-gray-300',
                    'background' => 'dark:bg-dark-800 bg-white',
                    'disabled' => 'dark:bg-dark-600 bg-gray-100',
                ],
                'paddings' => [
                    'prefix' => 'pr-2 pl-0',
                    'suffix' => 'pl-2 pr-0',
                    'left' => 'pl-2',
                    'right' => 'pr-8',
                    'clearable' => 'pr-14!',
                ],
            ],
            'icon' => [
                'wrapper' => 'pointer-events-none absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-400',
                'paddings' => [
                    'left' => 'left-0 pl-2',
                    'right' => 'right-0 pr-2',
                ],
                'size' => 'h-5 w-5',
                'color' => 'text-gray-500 dark:text-dark-400',
            ],
            'clearable' => [
                'wrapper' => 'cursor-pointer absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-400',
                'padding' => 'right-0 pr-2',
                'size' => 'h-5 w-5',
                'color' => 'hover:text-red-500',
            ],
            'error' => $this->error(),
        ]);
    }

    protected function validate(): void
    {
        $this->validations();
    }
}
