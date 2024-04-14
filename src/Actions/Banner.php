<?php

namespace TallStackUi\Actions;

use TallStackUi\Actions\Traits\DispatchInteraction;

class Banner extends AbstractInteraction
{
    use DispatchInteraction;

    protected bool $close = false;

    protected array $data = [];

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

    public function error(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'error',
            'title' => $title,
        ];

        return $this;
    }

    public function info(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'info',
            'title' => $title,
        ];

        return $this;
    }

    public function leave(int $seconds): self
    {
        $this->leave = $seconds;

        return $this;
    }

    /** @deprecated This method is not supported with Banner. */
    public function question(string $title, ?string $description = null): self
    {
        return $this;
    }

    public function success(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'success',
            'title' => $title,
        ];

        return $this;
    }

    public function warning(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'warning',
            'title' => $title,
        ];

        return $this;
    }

    protected function additional(): array
    {
        return [
            'close' => $this->close,
            'enter' => $this->enter,
            'leave' => $this->leave,
        ];
    }

    protected function event(): string
    {
        return 'banner';
    }

    protected function messages(): array
    {
        return []; // not supported
    }
}
