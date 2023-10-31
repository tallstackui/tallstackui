<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Checkbox extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(
        public ?string $label = null,
        public ?string $position = 'right',
        public ?string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public bool $checked = false,
    ) {
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
        $this->position = $this->position === 'right' ? 'right' : 'left';

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'class' => 'form-checkbox rounded ease-in-out duration-100 border-secondary-200',
                'sizes' => [
                    'sm' => 'w-4 h-4',
                    'md' => 'w-5 h-5',
                    'lg' => 'w-6 h-6',
                ],
            ],
            'error' => 'border border-red-300 text-red-600 focus:ring-red-600 focus:border-red-400',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.checkbox');
    }
}
