<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalize;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

class Password extends Component implements Personalize
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public bool $square = false,
        public bool $round = false,
    ) {
        $this->square = config('tallstackui.personalizations.input.square');
        $this->round = config('tallstackui.personalizations.input.round');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => $this->inputClasses(),
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5 text-gray-400',
            ],
            'error' => 'text-red-600 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.password');
    }
}
