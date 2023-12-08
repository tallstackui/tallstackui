<?php

namespace TallStackUi\Actions;

class Banner extends AbstractInteraction
{
    protected bool $close = false;

    protected int $enter = 0;

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
        return $this->send([
            'title' => $title,
            'type' => 'error',
        ]);
    }

    public function info(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'type' => 'info',
        ]);
    }

    public function leave(int $seconds): self
    {
        $this->leave = $seconds;

        return $this;
    }

    public function success(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'type' => 'success',
        ]);
    }

    public function warning(string $title, ?string $description = null): AbstractInteraction
    {
        return $this->send([
            'title' => $title,
            'type' => 'warning',
        ]);
    }

    protected function data(): array
    {
        return [
            'close' => $this->close,
            'enter' => $this->enter,
            'leave' => $this->leave,
        ];
    }

    protected function event(): string
    {
        return 'navbar';
    }
}
