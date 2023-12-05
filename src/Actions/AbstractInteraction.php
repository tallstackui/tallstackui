<?php

namespace TallStackUi\Actions;

use InvalidArgumentException;
use Livewire\Component;

/**
 * @internal This trait is not meant to be used directly.
 */
abstract class AbstractInteraction
{
    protected string $event = '';

    public function __construct(
        public Component $component,
        protected ?int $timeout = null,
    ) {
        //
    }

    public function confirm(string|array $data, string $description = null, array $options = null): self
    {
        throw_if(
            (is_string($data) && ! $options) || (is_array($data) && ! isset($data['options'])),
            new InvalidArgumentException('You must provide options for the interaction')
        );

        $this->timeout ??= 3;
        $options['confirm']['text'] ??= __('tallstack-ui::messages.toast.button.confirm');
        $options['cancel']['text'] ??= __('tallstack-ui::messages.toast.button.cancel');

        $default = [
            'type' => 'question',
            'timeout' => $this->timeout,
            'confirm' => true,
            'expandable' => $this->expand ?? false,
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

    abstract public function error(string $title, string $description = null): self;

    abstract public function info(string $title, string $description = null): self;

    public function send(array $options): self
    {
        $options['component'] = $this->component->getId();

        $this->component->dispatch($this->event, ...$options);

        return $this;
    }

    abstract public function success(string $title, string $description = null): self;

    abstract public function warning(string $title, string $description = null): self;

    protected function base(string $title, string $description = null, string $type = null, ...$params): self
    {
        return $this->send([
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'timeout' => $this->timeout ?? 3,
            'expandable' => $this->expand ?? false,
            ...$params,
        ]);
    }
}
