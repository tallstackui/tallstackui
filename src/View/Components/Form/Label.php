<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalize;

class Label extends Component implements Personalize
{
    public function __construct(
        public ?string $for = null,
        public ?string $label = null,
        public ?string $text = null,
        public bool $error = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return [
            'wrapper' => 'flex justify-between font-medium text-gray-600',
            'text' => 'block text-sm font-semibold',
            'error' => 'text-red-600',
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.label');
    }
}
