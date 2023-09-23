<?php

namespace TasteUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\View\Components\Form\Traits\DefaultInputBaseClass;

class Textarea extends Component
{
    use DefaultInputBaseClass;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public int $rows = 8,
        public ?bool $resize = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.form.textarea');
    }

    /**
     * TODO: it should implement the label personalizations, like Checkbox class.
     */
    public function baseClass(bool $error = false): string
    {
        return Arr::toCssClasses([
            $this->baseInputClass($error),
            'resize-none' => $this->resize === null || $this->resize === 'none',
        ]);
    }
}
