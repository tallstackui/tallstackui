<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Tab extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(public ?string $selected = null)
    {
        $this->configurations();
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
        return view('tallstack-ui::components.tab.tab');
    }
}
