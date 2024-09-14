<?php

namespace TallStackUi\Foundation\Interactions;

use Exception;
use TallStackUi\Foundation\Interactions\Traits\DispatchInteraction;

class Banner extends AbstractInteraction
{
    use DispatchInteraction;

    /**
     * Control the close button.
     */
    protected bool $close = false;

    /**
     * Control the enter effect seconds.
     */
    protected int $enter = 0;

    /**
     * Control the leave effect seconds.
     */
    protected ?int $leave = null;

    /**
     * Sets the close button.
     */
    public function close(): self
    {
        $this->close = true;

        return $this;
    }

    /**
     * Sets the enter effect.
     */
    public function enter(int $seconds): self
    {
        $this->enter = $seconds;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function error(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'error',
            'title' => $title,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function info(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'info',
            'title' => $title,
        ];

        return $this;
    }

    /**
     * Sets the leave effect.
     */
    public function leave(int $seconds): self
    {
        $this->leave = $seconds;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     *
     * @deprecated This method is not supported with Banner.
     */
    public function question(string $title, ?string $description = null): self
    {
        throw new Exception('The question method is not supported with Banner.');
    }

    /**
     * {@inheritdoc}
     */
    public function success(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'success',
            'title' => $title,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    protected function event(): string
    {
        return 'banner';
    }

    /**
     * {@inheritdoc}
     */
    protected function messages(): array
    {
        return []; // not supported
    }
}
