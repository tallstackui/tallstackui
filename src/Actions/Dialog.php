<?php

namespace TallStackUi\Actions;

use TallStackUi\Actions\Traits\DispatchInteraction;
use TallStackUi\Actions\Traits\InteractWithConfirmation;

class Dialog extends AbstractInteraction
{
    use DispatchInteraction;
    use InteractWithConfirmation;

    protected array $data = [];

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

    protected function event(): string
    {
        return 'dialog';
    }

    protected function messages(): array
    {
        return [
            __('tallstack-ui::messages.dialog.button.confirm'),
            __('tallstack-ui::messages.dialog.button.cancel'),
        ];
    }
}
