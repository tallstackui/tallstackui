<?php

namespace TallStackUi\Actions\Traits;

use TallStackUi\Actions\Toast;

/**
 * @internal This trait is not meant to be used directly.
 */
trait DispatchInteraction
{
    public function send(): void
    {
        //TODO: comment here
        if ($this instanceof Toast && count($this->data['options']['confirm']) == 1) {
            unset($this->data['options']['confirm']);
        }

        $data = $this->data;

        if (method_exists($this, 'additional')) {
            $data = array_merge($data, $this->additional());
        }

        $data['component'] = $this->component->getId();

        $this->component->dispatch(sprintf('tallstackui:%s', $this->event()), ...$data);
    }
}
