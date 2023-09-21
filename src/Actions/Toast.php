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
}
