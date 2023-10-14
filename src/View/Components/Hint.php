<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Hint extends Component implements Customizable
{
    public function __construct(
        public ?string $computed = null,
        public bool $error = false,
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
        return view('tallstack-ui::components.hint');
    }

    public function tallStackUiClasses(): array
    {
        return [
            'text' => 'mt-2 text-sm text-secondary-500',
        ];
    }
}
