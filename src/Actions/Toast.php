<?php

namespace TallStackUi\Actions;

use TallStackUi\Actions\Traits\DispatchInteraction;
use TallStackUi\Actions\Traits\InteractWithConfirmation;

class Toast extends AbstractInteraction
{
    use DispatchInteraction;
    use InteractWithConfirmation;

    protected array $data = [];

    protected ?bool $expand = null;

    protected ?int $timeout = 3;

    public function error(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'error',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    public function expandable(bool $expand = true): self
    {
        $this->expand = $expand;

        return $this;
    }

    public function info(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'info',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    public function question(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'question',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    public function success(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'success',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    public function timeout(?int $seconds = null): self
    {
        $this->timeout = $seconds ?? (int) config('tallstackui.settings.toast.timeout', 3);

        return $this;
    }

    public function warning(string $title, ?string $description = null): self
    {
        $this->data = [
            'type' => 'warning',
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    protected function additional(): array
    {
        return [
            'expandable' => $this->expand ?? config('tallstackui.settings.toast.expandable', false),
            'timeout' => $this->timeout,
        ];
    }

    protected function event(): string
    {
        return 'toast';
    }

    protected function messages(): array
    {
        return [
            __('tallstack-ui::messages.toast.button.confirm'),
            __('tallstack-ui::messages.toast.button.cancel'),
        ];
    }
}
