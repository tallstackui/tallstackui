<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Input extends Component implements Customizable
{
    public function __construct(
        public ?string $computed = null,
        public ?string $label = null,
        public ?string $hint = null,
        public bool $error = false,
        public bool $validate = false,
        public bool $password = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.wrapper.input');
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
            'base' => 'relative mt-2 rounded-md shadow-sm',
        ];
    }
}
