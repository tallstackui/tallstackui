<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Input extends Component implements Personalize
{
    use DefaultInputClasses;
    use InteractWithProviders;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public bool $validate = true,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';

        $this->configurations();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'class' => [...$this->input()],
                'paddings' => [
                    'left' => 'pl-10',
                    'right' => 'pr-10',
                ],
            ],
            'icon' => [
                'wrapper' => 'pointer-events-none absolute inset-y-0 flex items-center text-secondary-500',
                'paddings' => [
                    'left' => 'left-0 pl-3',
                    'right' => 'right-0 pr-3',
                ],
                'size' => 'h-5 w-5',
            ],
            'error' => 'dark:bg-dark-800 text-red-600 ring-red-300 placeholder:text-red-600 focus:ring-2 focus:ring-inset focus:ring-red-500 dark:ring-red-500',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.input');
    }
}
