<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

class Textarea extends Component implements Customizable
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public int $rows = 5,
        public bool $resize = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.textarea');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function tallStackUiClasses(): array
    {
        return [
            'base' => Arr::toCssClasses([
                $this->tallStackUiInputClasses(),
                'resize-none' => ! $this->resize,
            ]),
            'error' => 'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
        ];
    }
}
