<?php

namespace TallStackUi\View\Components\Tabs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;

class Tab extends Component implements Personalize
{
    public function __construct(
        public ?string $selected = null,
        public bool $square = false,
    ) {
        $this->square = config('tallstackui.personalizations.tabs.square');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => '-mb-px flex items-stretch overflow-auto',
            'item' => [
                'wrapper' => 'inline-flex justify-center truncate px-5 py-2.5 text-gray-700 transition cursor-pointer',
                'selected' => 'bg-white text-primary font-semibold',
                'unselected' => 'opacity-50',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tabs.index');
    }
}
