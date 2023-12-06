<?php

namespace TallStackUi\Actions;

class Banner extends AbstractInteraction
{
    protected bool $close = false;

    protected int $enter = 0;

    protected string $event = 'tallstackui:navbar';

    protected ?int $leave = null;

    public function close(): self
    {
        $this->close = true;

        return $this;
    }

    public function enter(int $seconds): self
    {
        $this->enter = $seconds;

        return $this;
    }

    public function error(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'error', ...$this->params());
    }

    public function info(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'info', ...$this->params());
    }

    public function leave(int $seconds): self
    {
        $this->leave = $seconds;

        return $this;
    }

    public function success(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'success', ...$this->params());
    }

    public function warning(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->base($title, $description, 'warning', ...$this->params());
    }

    private function params(): array
    {
        return [
            'close' => $this->close,
            'enter' => $this->enter,
            'leave' => $this->leave,
        ];
    }
}
