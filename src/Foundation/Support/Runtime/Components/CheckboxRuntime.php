<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Illuminate\Support\ViewErrorBag;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Toggle;

class CheckboxRuntime
{
    public function runtime(array $data, Checkbox|Radio|Toggle $component, bool $livewire, ?ViewErrorBag $errors = null): array
    {
        //
    }
}
