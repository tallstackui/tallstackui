<?php

namespace TallStackUi\Actions\Traits;

/**
 * @internal This trait is not meant to be used directly.
 */
trait DispatchInteraction
{
    protected bool $flash = false;

    /**
     * Persist the interaction into session to be displayed after redirects.
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

        $data['component'] = $this->component->getId();

        $event = sprintf('tallstackui:%s', $this->event());

        $this->component->dispatch($event, ...$data);

        if (! $this->flash) {
            return;
        }

        // For some unknown reason the `flash` doesn't work,
        // so we use `put` here and `pull` in the blade file.
        session()->put($event, $data);
    }
}
