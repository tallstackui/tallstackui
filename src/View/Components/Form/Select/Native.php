<?php

namespace TallStackUi\View\Components\Form\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\SelectNativeRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Select\Traits\Setup;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('select.native')]
#[PassThroughRuntime(SelectNativeRuntime::class)]
class Native extends TallStackUiComponent implements Personalization
{
    use DefaultInputClasses;
    use Setup;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?string $select = null,
        public ?array $selectable = [],
        public ?bool $invalidate = null,
        public ?bool $grouped = null,
    ) {
        $this->side = Cache::driver('array')->pull('tallstackui::form::input-selector::side');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.select.native');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'relative',
            'input' => [
                'wrapper' => 'focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex ring-1 focus-within:ring-2',
                'base' => 'dark:placeholder-dark-400 w-full border-0 bg-transparent py-1.5 ring-0 placeholder:text-gray-400 focus:outline-hidden focus:ring-transparent sm:text-sm sm:leading-6',
                'slot' => 'dark:text-dark-400 flex select-none items-center whitespace-nowrap text-gray-500 sm:text-sm',
                'color' => [
                    'base' => 'dark:ring-dark-600 dark:text-dark-300 text-gray-600 ring-gray-300',
                    'background' => 'dark:bg-dark-800 bg-white',
                    'disabled' => 'dark:bg-dark-600 bg-gray-100',
                ],
                'round' => [
                    'none' => 'rounded-md',
                    'left' => 'rounded-l-lg',
                    'right' => 'rounded-r-lg',
                ],
            ],
            'error' => $this->error('focus:ring-2'),
        ]);
    }
}
