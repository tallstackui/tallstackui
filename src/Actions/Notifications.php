<?php

namespace TasteUi\Actions;

use Livewire\Component;

class Notifications
{
    public function __construct(
        public Component $component,
        protected ?int $time = null,
    ) {
        //
    }

    public function time(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function success(string $title, string $description = null): self
    {
        return $this->base($title, $description, 'success');
    }

    public function error(string $title, string $description = null): self
    {
        return $this->base($title, $description, 'error');
    }

    public function info(string $title, string $description = null): self
    {
        return $this->base($title, $description, 'info');
    }

    public function warning(string $title, string $description = null): self
    {
        return $this->base($title, $description, 'warning');
    }

    public function confirm(string|array $data, string $description = null, array $options = null): self
    {
        $options['icon'] ??= 'question';

        $this->time ??= 3000;

        if (is_string($data)) {
            return $this->send([
                'title' => $data,
                'description' => $description,
                'type' => 'question',
                'confirm' => true,
                'timeout' => $this->time,
                'options' => $options,
            ]);
        }

        return $this->send([
            'type' => 'question',
            'timeout' => $this->time,
            'confirm' => true,
            ...$data,
        ]);
    }

    public function send(array $options): self
    {
        $this->component->dispatch('tasteui:notification', ...$options);

        return $this;
    }

    private function base(string $title, string $description = null, string $type = null): self
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'timeout' => $this->time ?? 3000,
        ]);
    }
}
