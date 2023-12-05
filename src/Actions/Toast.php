<?php

namespace TallStackUi\Actions;

class Toast extends AbstractInteraction
{
    protected string $event = 'tallstackui:toast';

    protected ?bool $expand = null;

    public function error(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'error');
    }

    public function expandable(bool $expand = true): AbstractInteraction
    {
        $this->expand = $expand;

        return $this;
    }

    public function info(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'info');
    }

    public function success(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'success');
    }

    public function timeout(int $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }

    public function warning(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'warning');
    }
}
