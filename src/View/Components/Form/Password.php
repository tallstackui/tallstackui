<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalization;
use TallStackUi\Support\Personalizations\SoftPersonalization;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.password')]
class Password extends Component implements Personalization
{
    use DefaultInputClasses;

    public function __construct(public ?string $label = null, public ?string $id = null, public ?string $hint = null)
    {
        $this->id ??= uniqid();
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
