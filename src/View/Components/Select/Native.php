<?php

namespace TallStackUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Select\Traits\InteractsWithSelectOptions;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('select.native')]
class Native extends Component implements Personalization
{
    use DefaultInputClasses;
    use InteractsWithSelectOptions;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?string $select = null,
        public ?array $selectable = [],
    ) {
        $this->options();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'class' => 'mt-1 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 ring-1 ring-inset transition duration-150 ease-in-out focus:ring-2 sm:text-sm sm:leading-6',
                'color' => 'focus:ring-primary-600 dark:bg-dark-800 dark:placeholder-dark-500 dark:text-dark-300 dark:border-dark-900 dark:ring-dark-600 dark:focus:ring-primary-600 text-gray-700 ring-gray-300',
            ],
            'error' => $this->error('focus:ring-2'),
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.select.native');
    }
}
