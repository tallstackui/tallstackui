<?php

namespace TallStackUi\View\Components\Tabs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Index extends Component implements Customizable
{
    public function __construct(
        public array $options = [],
        public ?string $selected = null,
        private bool $square = false,
    ) {
        $this->square = config('tasteui.personalizations.tabs.square');
    }

    public function render(): View
    {
        return view('taste-ui::components.tabs.index');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => '-mb-px flex items-stretch overflow-auto',
            'item' => [
                'wrapper' => Arr::toCssClasses([
                    'inline-flex truncate px-5 py-2.5 text-gray-700 transition cursor-pointer',
                    'rounded-t-lg' => ! $this->square,
                ]),
                'selected' => 'bg-white text-primary font-semibold',
                'unselected' => 'opacity-50',
            ],
        ]);
    }
}
