<?php

namespace TasteUi\Actions;

use Livewire\Component;

abstract class AbstractInteraction
{
    public function __construct(
        public Component $component,
        protected ?int $time = null,
    ) {
        //
    }

    abstract public function success(string $title, string $description = null): self;

    abstract public function error(string $title, string $description = null): self;

    abstract public function info(string $title, string $description = null): self;

    abstract public function warning(string $title, string $description = null): self;

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

    protected function base(string $title, string $description = null, string $type = null): self
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'timeout' => $this->time ?? 3,
        ]);
    }

    protected function send(array $options): self
    {
        $this->component->dispatch($this->event, ...$options);

        return $this;
    }
}
