<?php

namespace TasteUi\Traits;

use Livewire\Component;
use TasteUi\Actions\Notifications as Action;

trait Notifications
{
    public function notify(array $options = null): Action
    {
        /** @var Component $this */
        $notification = new Action($this);

        if ($options) {
            return $notification->send($options);
        }

        return $notification;
    }
}
