<?php

namespace TallStackUi\Actions\Traits;

/**
 * @internal This trait is not meant to be used directly.
 */
trait DispatchInteraction
{
    public function send(): void
    {
        $data = $this->data;

        if (method_exists($this, 'additional')) {
            $data = array_merge($data, $this->additional());
        }

        $data['component'] = $this->component->getId();

        $this->component->dispatch(sprintf('tallstackui:%s', $this->event()), ...$data);
    }
}
