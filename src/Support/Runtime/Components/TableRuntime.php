<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class TableRuntime extends AbstractRuntime
{
    /** A headerless skeleton, or one whose headers only arrive in mount(), still needs a width. */
    private const FALLBACK_COLUMNS = 4;

    /** @throws Exception */
    public function runtime(): array
    {
        $headers = $this->data('headers');

        return [
            ...$this->bind()->only('entangle'),
            'lines' => $this->skeleton(5),
            'columns' => count($headers) ?: self::FALLBACK_COLUMNS,
        ];
    }
}
