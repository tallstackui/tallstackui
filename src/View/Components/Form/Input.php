<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('form.input')]
class Input extends Component implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $id = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public bool $validate = true,
    ) {
        $this->id = $this->id === null ? uniqid() : $this->id;
        $this->position = $this->position === 'left' ? 'left' : 'right';
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
                'wrapper' => 'pointer-events-none absolute inset-y-0 flex items-center text-secondary-500 dark:text-dark-400',
                'paddings' => [
                    'left' => 'left-0 pl-3',
                    'right' => 'right-0 pr-3',
                ],
                'size' => 'h-5 w-5',
            ],
            'error' => $this->error(),
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.input');
    }
}
