<?php

namespace TasteUi\Actions;

use InvalidArgumentException;
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
        throw_if(
            (is_string($data) && ! $options) || (is_array($data) && ! isset($data['options'])),
            new InvalidArgumentException('You must provide options for the interaction')
        );

        $this->time ??= 3;
        $options['confirm']['text'] ??= __('taste-ui::messages.toast.button.confirm');
        $options['cancel']['text'] ??= __('taste-ui::messages.toast.button.cancel');

        $default = [
            'type' => 'question',
            'timeout' => $this->time,
            'confirm' => true,
        ];

        if (is_string($data)) {
            return $this->send([
                ...$default,
                'title' => $data,
                'description' => $description,
                'options' => $options,
            ]);
        }

        return $this->send([...$default, ...$data]);
    }

    public function send(array $options): self
    {
        $this->component->dispatch($this->event, ...$options);

        return $this;
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
}
