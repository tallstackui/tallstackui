<?php

namespace TasteUi\Traits;

use Livewire\Component;
use TasteUi\Actions\Toast;

trait Interactions
{
    public function toast(array $options = null): Toast
    {
        /** @var Component $this */
        $notification = new Toast($this);

        if ($options) {
            return $notification->send($options);
        }

        return $notification;
    }
}
