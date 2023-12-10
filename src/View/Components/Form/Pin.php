<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;

class Pin extends Component implements Personalization
{
    public function __construct()
    {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.pin');
    }
}
