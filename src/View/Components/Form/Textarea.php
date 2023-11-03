<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('form.textarea')]
class Textarea extends Component implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?int $rows = 3,
        public ?bool $resize = false,
        public ?bool $resizeAuto = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'error' => $this->error(),
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.textarea');
    }
}
