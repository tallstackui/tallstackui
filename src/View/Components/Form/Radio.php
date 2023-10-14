<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalize;
use TallStackUi\Support\Personalizations\Traits\InternalColorPersonalizations;

class Radio extends Component implements Personalize
{
    use InternalColorPersonalizations;

    public function __construct(
        public ?string $id = null,
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
    }

    public function personalization(): array
    {
        return [
            'input' => Arr::toCssClasses([
                'form-radio rounded-full transition ease-in-out duration-100 border-secondary-300',
                'w-5 h-5' => $this->size === 'md',
                'w-6 h-6' => $this->size === 'lg',
            ]),
            'error' => 'border-red-300 focus:ring-red-600 focus:border-red-400 text-red-600',
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.radio');
    }
}
