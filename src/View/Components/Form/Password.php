<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\View\Components\Form\Traits\DefaultInputBaseClass;

class Password extends Component
{
    use DefaultInputBaseClass;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = 'eye',
        public ?string $position = 'right',
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.password');
    }

    public function getBaseClass(?bool $error = false): string
    {
        return $this->default($error);
    }

    public function getBaseIcon(?bool $error = false): array
    {
        return [
            'size'    => 'h-5 w-5',
            'style'   => config('tasteui.icon') ?? 'solid',
            'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
            'color'   => Arr::toCssClasses(['text-gray-400' => !$error]),
        ];
    }
}
