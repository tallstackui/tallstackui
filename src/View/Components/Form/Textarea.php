<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Support\Personalizations\Traits\InternalPersonalization;

class Textarea extends Component implements Customizable
{
    use InternalPersonalization;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public int $rows = 5,
        public bool $autoResize = false,
        public bool $resize = true,
        public bool $square = false,
    ) {
        $this->square = config('tallstackui.personalizations.input.square');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.textarea');
    }

    public function tallStackUiClasses(): array
    {
        return [
            'input' => $this->inputClasses(),
            'error' => 'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
        ];
    }
}
