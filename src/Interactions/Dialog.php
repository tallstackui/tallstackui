<?php

namespace TallStackUi\Interactions;

use TallStackUi\Components\Dialog\Component;
use TallStackUi\Interactions\Traits\DispatchInteraction;
use TallStackUi\Interactions\Traits\InteractWithConfirmation;

class Dialog extends AbstractInteraction
{
    use DispatchInteraction;
    use InteractWithConfirmation;

    /**
     * Set the dialog as persistent (prevent close on outside click).
     */
    protected ?bool $persistent = null;

    public function error(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'error',
            'title' => $title,
            'description' => $description,
        ];

        $this->static();

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

        $this->static();

        return $this;
    }

    /**
     * Sets the dialog as persistent (prevent close on outside click).
     */
    public function persistent(): self
    {
        $this->persistent = true;

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

        $this->static();

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

        $this->static();

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

        $this->static();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function additional(): array
    {
        return [
            'persistent' => $this->persistent ?? __ts_get_component_configuration(Component::class, 'persistent'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function event(): string
    {
        return 'dialog';
    }

    /**
     * {@inheritdoc}
     */
    protected function messages(): array
    {
        return [trans('ts-ui::messages.dialog.button.confirm'), trans('ts-ui::messages.dialog.button.cancel')];
    }
}
