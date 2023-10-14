<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalize;
use TallStackUi\Support\Personalizations\Traits\InternalColorPersonalizations;

class Toggle extends Component implements Personalize
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
                'absolute mx-0.5 my-auto inset-y-0 left-0.5 rounded-full border-0',
                'appearance-none translate-x-0 transform transition ease-in-out duration-200 cursor-pointer shadow',
                'checked:bg-none peer focus:ring-0 focus:ring-offset-0 focus:outline-none bg-white checked:text-white',
                'w-3 h-3 checked:translate-x-2.5' => $this->size === 'sm',
                'w-3.5 h-3.5 checked:translate-x-4' => $this->size === 'md',
                'w-4 h-4 checked:translate-x-4' => $this->size === 'lg',
            ]),
            'wrapper' => Arr::toCssClasses([
                'block rounded-full cursor-pointer transition ease-in-out duration-100 peer-focus:ring-2',
                'peer-focus:ring-offset-2 group-focus:ring-2 group-focus:ring-offset-2 bg-secondary-200',
                'h-4 w-7' => $this->size === 'sm',
                'h-5 w-9' => $this->size === 'md',
                'h-6 w-10' => $this->size === 'lg',
            ]),
            'error' => 'bg-red-600 peer-checked:bg-red-600 peer-focus:ring-red-600 group-focus:ring-red-600',
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.toggle');
    }
}
