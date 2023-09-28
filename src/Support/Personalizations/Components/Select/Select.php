<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Select implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = ['base'];
}
