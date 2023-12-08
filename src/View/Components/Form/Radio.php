<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Illuminate\View\ComponentSlot;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

#[SoftPersonalization('form.radio')]
class Radio extends Component implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public string|null|ComponentSlot $label = null,
        public ?string $id = null,
        public ?string $position = 'right',
        public ?string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public bool $checked = false,
    ) {
        $this->id ??= uniqid();

        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
        $this->position = $this->position === 'right' ? 'right' : 'left';

        $this->colors();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'class' => 'form-radio border-secondary-200 rounded-full transition duration-100 ease-in-out',
                'sizes' => [
                    'sm' => 'h-4 w-4',
                    'md' => 'h-5 w-5',
                    'lg' => 'h-6 w-6',
                ],
            ],
            'error' => 'border-red-300 text-red-600 focus:border-red-400 focus:ring-red-600',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.radio');
    }
}
