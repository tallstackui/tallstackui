<?php

namespace TallStackUi\Actions;

use Exception;
use Livewire\Component;

/**
 * @internal This class is not meant to be used directly.
 */
abstract class AbstractInteraction
{
    public function __construct(public ?Component $component = null)
    {
        //
    }

    abstract public function error(string $title, ?string $description = null): self;

    abstract public function info(string $title, ?string $description = null): self;

    abstract public function question(string $title, ?string $description = null): self;

    abstract public function success(string $title, ?string $description = null): self;

    /** @throws Exception */
    public function unsupported(string $what): void
    {
        if ($this->component && $this->component->getId()) {
            return;
        }

        throw new Exception('This interaction ['.$what.'] using Interaction trait is not supported through Controllers.');
    }

    abstract public function warning(string $title, ?string $description = null): self;

    abstract protected function event(): string;

    abstract protected function messages(): array;
}
