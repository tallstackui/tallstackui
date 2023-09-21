<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TasteUi\View\Components\Form\Traits\DefaultInputBaseClass;

class Password extends Component
{
    use DefaultInputBaseClass;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.password', [
            'icon' => 'eye',
            'position' => 'right',
        ]);
    }

    public function getBaseClass(?bool $error = false): string
    {
        return $this->default($error);
    }

    public function getBaseIcon(): array
    {
        return [
            'size' => 'h-5 w-5',
            'color' => 'text-secondary-500',
            'style' => config('tasteui.icon') ?? 'solid',
            'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
        ];
    }
}
