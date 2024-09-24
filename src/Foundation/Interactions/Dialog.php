<?php

namespace TallStackUi\Foundation\Interactions;

use TallStackUi\Foundation\Interactions\Traits\DispatchInteraction;
use TallStackUi\Foundation\Interactions\Traits\InteractWithConfirmation;

class Dialog extends AbstractInteraction
{
    use DispatchInteraction;
    use InteractWithConfirmation;

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
    protected function event(): string
    {
        return 'dialog';
    }

    /**
     * {@inheritdoc}
     */
    protected function messages(): array
    {
        return [trans('tallstack-ui::messages.dialog.button.confirm'), trans('tallstack-ui::messages.dialog.button.cancel')];
    }
}
