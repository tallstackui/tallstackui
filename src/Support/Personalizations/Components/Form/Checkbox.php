<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\ShouldBePersonalized;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Checkbox implements Arrayable, ShouldBePersonalized
{
    use ShareablePersonalization;

    public const EDITABLES = ['main.base'];
}
