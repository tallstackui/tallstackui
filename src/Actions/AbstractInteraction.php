<?php

namespace TallStackUi\Actions;

use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;
use Livewire\Component;

/**
 * @internal This class is not meant to be used directly.
 */
abstract class AbstractInteraction
{
    use Conditionable;

    public function __construct(public Component $component)
    {
        //
    }

    abstract public function error(string $title, ?string $description = null): self;

    abstract public function info(string $title, ?string $description = null): self;

    public function send(array $data): self
    {
        throw_if(isset($data['component']), new InvalidArgumentException('You cannot set the component key.'));

        if (method_exists($this, 'data')) {
            $data = array_merge($data, $this->data());
        }

        $data['component'] = $this->component->getId();

        $this->component->dispatch(sprintf('tallstackui:%s', $this->event()), ...$data);

        return $this;
    }

    abstract public function success(string $title, ?string $description = null): self;

    abstract public function warning(string $title, ?string $description = null): self;

    abstract protected function event(): string;
}
