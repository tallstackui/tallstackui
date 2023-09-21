<?php

namespace TasteUi\Actions;

class Toast extends AbstractInteraction
{
    protected string $event = 'tasteui:toast';

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

        $this->time ??= 3;

        $base = [
            'type' => 'question',
            'timeout' => $this->time,
            'confirm' => true,
        ];

        if (is_string($data)) {
            return $this->send([
                ...$base,
                'title' => $data,
                'description' => $description,
                'options' => $options,
            ]);
        }

        return $this->send([
            ...$base,
            ...$data,
        ]);
    }
}
