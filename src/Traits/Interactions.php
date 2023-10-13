<?php

namespace TallStackUi\Traits;

use Livewire\Component;
use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;

trait Interactions
{
    public function dialog(array $options = null): Dialog
    {
        /** @var Component $this */
        $dialog = new Dialog($this);

        if ($options) {
            return $dialog->send($options);
        }

        return $dialog;
    }

    public function toast(array $options = null): Toast
    {
        /** @var Component $this */
        $toast = new Toast($this);

        if ($options) {
            return $toast->send($options);
        }

        return $toast;
    }
}
