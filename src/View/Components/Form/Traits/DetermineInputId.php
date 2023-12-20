<?php

namespace TallStackUi\View\Components\Form\Traits;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Facades\TallStackUi;

trait DetermineInputId
{
    public function id(ComponentAttributeBag $attributes): ?string
    {
        $wire = TallStackUi::blade()->wireable($attributes);

        return $attributes->get('id', $wire?->value() ?? $attributes->get('name'));
    }
}
