<?php

namespace TallStackUi\View\Components\Form\Traits;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Facades\TallStackUi;

trait DetermineInputId
{
    // This method is used to determine which id will be
    // applied to the input element. The strategy here
    // is try to get the id from "id", or "wire:model"
    // or "name" attributes. If none of them are set.
    public function id(ComponentAttributeBag $attributes): ?string
    {
        $wire = TallStackUi::blade($attributes)->wire();

        return $attributes->get('id', $wire?->value() ?? $attributes->get('name'));
    }
}
