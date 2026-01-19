<?php

namespace TallStackUi\Foundation\Interactions;

use Exception;
use Livewire\Component;

/**
 * @internal
 */
abstract class AbstractInteraction
{
    /**
     * The Event data.
     */
    protected array $data = [];

    public function __construct(public ?Component $component = null)
    {
        //
    }

    /**
     * Sets the interaction as an error.
     */
    abstract public function error(string $title, ?string $description = null): self;

    /**
     * Sets the interaction as info.
     */
    abstract public function info(string $title, ?string $description = null): self;

    /**
     * Sets the interaction as a question.
     */
    abstract public function question(string $title, ?string $description = null): self;

    /**
     * Sets the interaction as a success.
     */
    abstract public function success(string $title, ?string $description = null): self;

    /**
     * Sets the interaction as a warning.
     */
    abstract public function warning(string $title, ?string $description = null): self;

    /**
     * Thrown an exception if the component is not being used inside Livewire context.
     *
     * @throws Exception
     */
    public function wireable(string $what): void
    {
        if ($this->component && $this->component->getId()) {
            return;
        }

        // For situations where interactions are being used via Controllers.
        throw new Exception('This interaction ['.$what.'] using Interaction trait is not supported through Controllers.');
    }

    /**
     * Determine the event name.
     */
    abstract protected function event(): string;

    /**
     * Determine the messages displayed in the component.
     */
    abstract protected function messages(): array;
}
