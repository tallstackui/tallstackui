<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\Support\ViewErrorBag;
use TallStackUi\Support\Runtime\AbstractRuntime;

class ErrorRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $property = $this->data('property');

        return [
            'message' => $property && $this->errors instanceof ViewErrorBag
                ? $this->errors->first($property)
                : null,
        ];
    }
}
