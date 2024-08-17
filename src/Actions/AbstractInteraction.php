<?php

namespace TallStackUi\Actions;

use Exception;
use Livewire\Component;

/**
 * @internal This class is not meant to be used directly.
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
     * Sets the interaction as error.
     */
    abstract public function error(string $title, ?string $description = null): self;

    /**
     * Sets the interaction as info.
     */
    abstract public function info(string $title, ?string $description = null): self;

    /**
     * Sets the interaction as question.
     */
    abstract public function question(string $title, ?string $description = null): self;

    /** @throws Exception */
    public function requireLivewire(string $what): void
    {
        if ($this->component && $this->component->getId()) {
            return;
        }

        // For situations where interactions are being used via Controllers.
        throw new Exception('This interaction ['.$what.'] using Interaction trait is not supported through Controllers.');
    }

    /**
     * Sets the interaction as success.
     */
    abstract public function success(string $title, ?string $description = null): self;

    /**
     * Sets the interaction as warning.
     */
    abstract public function warning(string $title, ?string $description = null): self;

    /**
     * Determine the event name.
     */
    abstract protected function event(): string;

    /**
     * Determine the messages displayed in the component.
     */
    abstract protected function messages(): array;
}
