<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('form.password')]
class Password extends Component implements Personalization
{
    use DefaultInputClasses;

    public function __construct(public ?string $label = null, public ?string $hint = null)
    {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input(), 'pr-10'],
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
