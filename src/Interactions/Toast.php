<?php

namespace TallStackUi\Interactions;

use TallStackUi\Components\Toast\Component;
use TallStackUi\Interactions\Traits\DispatchInteraction;
use TallStackUi\Interactions\Traits\InteractWithConfirmation;

class Toast extends AbstractInteraction
{
    use DispatchInteraction;
    use InteractWithConfirmation;

    /**
     * Control the expandable effect.
     */
    protected ?bool $expand = null;

    /**
     * Set the toast as persistent (without a timeout and progress bar).
     */
    protected ?bool $persistent = null;

    /**
     * Set the toast position dynamically.
     */
    protected ?string $position = null;

    /**
     * Determines if only this toast will be shown, flushing any previous toasts.
     */
    protected ?bool $sole = false;

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
     * Sets the toast as persistent (without a timeout and progress bar).
     */
    public function persistent(): self
    {
        $this->persistent = true;

        return $this;
    }

    /**
     * Sets the toast position dynamically.
     */
    public function position(string $position): self
    {
        if (! in_array($position, ['top-right', 'top-left', 'bottom-right', 'bottom-left'])) {
            __ts_validation_exception(Component::class, "Invalid position: {$position}. Allowed: top-right, top-left, bottom-right, bottom-left.");
        }

        $this->position = $position;

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
     * Determines if only this toast will be shown, flushing any previous toasts.
     */
    public function sole(bool $sole = true): self
    {
        $this->sole = $sole;

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
        $this->timeout = $seconds ?? (int) (__ts_get_component_configuration(Component::class, 'timeout') ?? 3);

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
        $configuration = __ts_get_component_configuration(Component::class);

        return [
            'expandable' => $this->expand ?? $configuration['expandable'] ?? false,
            'timeout' => $this->timeout,
            'persistent' => $this->persistent,
            'position' => $this->position ?? $configuration['position'] ?? 'top-right',
            'sole' => $this->sole,
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
        return [trans('ts-ui::messages.toast.button.confirm'), trans('ts-ui::messages.toast.button.cancel')];
    }
}
