<?php

namespace TallStackUi\Foundation\Support\Interactions;

use TallStackUi\Foundation\Support\Interactions\Traits\DispatchInteraction;
use TallStackUi\Foundation\Support\Interactions\Traits\InteractWithConfirmation;

class Toast extends AbstractInteraction
{
    use DispatchInteraction;
    use InteractWithConfirmation;

    /**
     * Control the expandable effect.
     */
    protected ?bool $expand = null;

    /**
     * Control the timeout seconds.
     */
    protected ?int $timeout = 3;

    /**
     * {@inheritdoc}
     */
    public function error(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'error',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    /**
     * Sets the expandable effect.
     */
    public function expandable(bool $expand = true): self
    {
        $this->expand = $expand;

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
            'description' => $description,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function question(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'question',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function success(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'success',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    /**
     * Sets the timeout seconds.
     */
    public function timeout(?int $seconds = null): self
    {
        $this->timeout = $seconds ?? (int) config('tallstackui.settings.toast.timeout', 3);

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
            'description' => $description,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function additional(): array
    {
        return [
            'expandable' => $this->expand ?? config('tallstackui.settings.toast.expandable', false),
            'timeout' => $this->timeout,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function event(): string
    {
        return 'toast';
    }

    /**
     * {@inheritdoc}
     */
    protected function messages(): array
    {
        return [
            trans('tallstack-ui::messages.toast.button.confirm'),
            trans('tallstack-ui::messages.toast.button.cancel'),
        ];
    }
}
