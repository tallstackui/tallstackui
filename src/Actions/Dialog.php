<?php

namespace TallStackUi\Actions;

use TallStackUi\Actions\Traits\InteractWithConfirmation;

class Dialog extends AbstractInteraction
{
    use InteractWithConfirmation;

    public function error(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => 'error',
        ]);
    }

    public function info(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => 'info',
        ]);
    }

    public function success(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => 'success',
        ]);
    }

    public function warning(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => 'warning',
        ]);
    }

    protected function event(): string
    {
        return 'dialog';
    }
}
