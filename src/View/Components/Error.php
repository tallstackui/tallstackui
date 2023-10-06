<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Error extends Component implements Customizable
{
    public function __construct(
        public ?string $computed = null,
        public bool $error = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('tallstack-ui::components.error');
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
            'base' => 'mt-2 text-sm text-red-500',
        ];
    }
}
