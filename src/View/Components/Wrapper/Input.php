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
        public ?string $alpine = null,
        public bool $error = false,
        public bool $validate = false,
        public bool $password = false,
    ) {
        //
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.wrapper.input');
    }

    public function tallStackUiClasses(): array
    {
        return [
            'wrapper' => 'relative rounded-md shadow-sm',
        ];
    }
}
