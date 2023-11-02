<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;

class Error extends Component implements Personalization
{
    public function __construct(public ?string $computed = null, public bool $error = false)
    {
        //
    }

    public function personalization(): array
    {
        return ['text' => 'mt-2 text-sm font-medium text-red-500'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.error');
    }
}
