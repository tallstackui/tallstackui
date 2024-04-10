<?php

namespace TallStackUi\Actions\Traits;

use Exception;

/**
 * @internal This trait is not meant to be used directly.
 */
trait DispatchInteraction
{
    protected bool $flash = false;

    /**
     * Persist the interaction into session to be displayed after redirect.
     */
    public function flash(): self
    {
        $this->flash = true;

        return $this;
    }

    public function send(): void
    {
        $data = $this->data;

        if (method_exists($this, 'additional')) {
            $data = array_merge($data, $this->additional());
        }

        $data['component'] = $this->component?->getId() ?? null;

        $event = sprintf('tallstackui:%s', $this->event());

        if (($this->flash && isset($this->data['options'])) && ! $this->component) {
            throw new Exception('You can not use Interaction confirmations out of the Livewire context.');
        }

        if (! $this->flash && $this->component) {
            $this->component->dispatch($event, ...$data);

            return;
        }

        // For some unknown reason the `flash` doesn't work,
        // so we use `put` here and `pull` in the blade file.
        session()->put($event, $data);
    }
}
