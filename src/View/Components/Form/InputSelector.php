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
        public ?bool $right = null,
        public ?bool $invalidate = null,
        public ?ComponentSlot $selector = null,
        #[SkipDebug]
        public ComponentSlot|string|null $prefix = null,
        #[SkipDebug]
        public ComponentSlot|string|null $suffix = null,
        #[SkipDebug]
        public ?string $side = null,
    ) {
        $this->side = $this->right ? 'right' : 'left';

        Cache::driver('array')->put('tallstackui::form::input-selector::side', $this->side);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.input-selector');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => 'flex grow items-stretch ring-inset focus-within:z-50',
                'base' => 'focus:ring-primary-600 dark:focus:ring-primary-600 dark:ring-dark-600 dark:text-dark-300 dark:bg-dark-800 block w-full border-0 bg-white py-1.5 text-gray-600 ring-1 ring-gray-300 placeholder:text-gray-400 focus:ring-2 sm:text-sm sm:leading-6',
                'slot' => 'dark:text-dark-400 flex select-none items-center whitespace-nowrap text-gray-500 sm:text-sm',
                'color' => [...$this->input()['color']],
                'round' => [
                    'right' => 'rounded-l-md',
                    'left' => 'rounded-r-md',
                ],
            ],
            'error' => $this->error(),
        ]);
    }
}
