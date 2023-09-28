<?php

namespace TasteUi\Actions;

class Toast extends AbstractInteraction
{
    protected string $event = 'tasteui:toast';

    public function time(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function success(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'success');
    }

    public function error(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'error');
    }

    public function info(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'info');
    }

    public function warning(string $title, string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'warning');
    }
}
