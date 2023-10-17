<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Textarea extends Component implements Personalize
{
    use DefaultInputClasses;
    use InteractWithProviders;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public int $rows = 3,
        public bool $autoResize = false,
        public bool $resize = true,
    ) {
        $this->configurations();
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
