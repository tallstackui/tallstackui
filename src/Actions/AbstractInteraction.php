<?php

namespace TallStackUi\Actions;

use Livewire\Component;

/**
 * @internal This class is not meant to be used directly.
 */
abstract class AbstractInteraction
{
    public function __construct(public Component $component)
    {
        //
    }

    abstract public function error(string $title, ?string $description = null): self;

    abstract public function info(string $title, ?string $description = null): self;

    abstract public function question(string $title, ?string $description = null): self;

    abstract public function success(string $title, ?string $description = null): self;

    abstract public function warning(string $title, ?string $description = null): self;

    abstract protected function event(): string;

    abstract protected function messages(): array;
}
