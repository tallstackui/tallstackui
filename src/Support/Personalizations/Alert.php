<?php

namespace TasteUi\Support\Personalizations;

class Alert
{
    public function __construct(
        public ?string $main = null,
        public ?string $footer = null,
    ) {
    }

    public function main(): ?string
    {
        return $this->main;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }
}
