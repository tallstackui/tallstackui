<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Form\Traits\DetermineInputId;

#[SoftPersonalization('form.textarea')]
class Textarea extends BaseComponent implements Personalization
{
    use DefaultInputClasses;
    use DetermineInputId;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $resize = false,
        public ?bool $resizeAuto = false,
        public ?bool $invalidate = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.textarea');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'error' => $this->error(),
        ]);
    }
}
