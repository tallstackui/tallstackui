<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalization;
use TallStackUi\Support\Personalizations\SoftPersonalization;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.textarea')]
class Textarea extends Component implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $id = null,
        public ?string $hint = null,
        public ?bool $resize = false,
        public ?bool $resizeAuto = false,
    ) {
        $this->id ??= uniqid();
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
