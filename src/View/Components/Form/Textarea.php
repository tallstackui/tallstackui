<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\View\Components\Form\Traits\DefaultInputClasses;

class Textarea extends Component implements Customizable
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public int $rows = 8,
        public bool $resize = true,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.textarea');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return [
            'base' => Arr::toCssClasses([
                $this->tasteUiInputClasses(),
                'resize-none' => $this->resize,
            ]),
            'error' => 'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
        ];
    }
}
