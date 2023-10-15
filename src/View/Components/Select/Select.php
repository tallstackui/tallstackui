<?php

namespace TallStackUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TallStackUi\View\Components\Select\Traits\InteractsWithSelectOptions;
use TallStackUi\View\Personalizations\Contracts\Personalize;

class Select extends Component implements Personalize
{
    use InteractsWithSelectOptions;

    public function __construct(
        public ?string $id = null,
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
        return [
            'wrapper' => 'focus:ring-primary-600 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-700 ring-1 ring-inset ring-gray-300 focus:ring-2 sm:text-sm sm:leading-6',
            'error' => 'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.select.select');
    }
}
