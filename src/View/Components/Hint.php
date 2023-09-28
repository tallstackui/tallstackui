<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Hint extends Component implements Customizable
{
    public function __construct(
        public ?string $computed = null,
        public bool $error = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.hint');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiMainClasses(),
        ];
    }

    public function tasteUiMainClasses(): array
    {
        return [
            'base' => 'mt-2 text-sm text-secondary-500',
        ];
    }
}
