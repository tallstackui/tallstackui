<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Password extends Component implements Personalize
{
    use DefaultInputClasses;
    use InteractWithProviders;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
    ) {
        $this->configurations();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5 text-gray-400',
            ],
            'error' => $this->error(),
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.password');
    }
}
