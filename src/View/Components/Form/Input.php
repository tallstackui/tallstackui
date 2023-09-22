<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\View\Components\Form\Traits\DefaultInputBaseClass;

class Input extends Component
{
    use DefaultInputBaseClass;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.input');
    }

    public function getBaseClass(bool $error = false): string
    {
        return Arr::toCssClasses([
            $this->defaultInputBaseClass($error),
            'pl-10' => $this->icon && ($this->position === null || $this->position === 'left'),
        ]);
    }

    public function getBaseIcon(): array
    {
        return [
            'size' => 'h-5 w-5',
            'style' => config('tasteui.icon') ?? 'solid',
            'base' => Arr::toCssClasses([
                'pointer-events-none absolute inset-y-0 flex items-center text-secondary-500',
                'left-0 pl-3' => $this->position === null || $this->position === 'left',
                'right-0 pr-3' => $this->position === 'right',
            ]),
        ];
    }
}
