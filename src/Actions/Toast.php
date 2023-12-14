<?php

namespace TallStackUi\Actions;

use TallStackUi\Actions\Traits\InteractWithConfirmation;

class Toast extends AbstractInteraction
{
    use InteractWithConfirmation;

    protected ?bool $expand = null;

    protected ?int $timeout = 3;

    public function error(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => 'error',
        ]);
    }

    public function expandable(bool $expand = true): self
    {
        $this->expand = $expand;

        return $this;
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

    public function timeout(int $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }

    public function warning(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => 'warning',
        ]);
    }

    protected function data(): array
    {
        return [
            'expandable' => $this->expand ?? config('tallstackui.settings.toast.expandable', false),
            'timeout' => $this->timeout,
        ];
    }

    protected function event(): string
    {
        return 'toast';
    }
}
