<?php

namespace TasteUi\Traits;

use Livewire\Component;
use TasteUi\Actions\Dialog;
use TasteUi\Actions\Toast;

trait Interactions
{
    public function toast(array $options = null): Toast
    {
        /** @var Component $this */
        $toast = new Toast($this);

        if ($options) {
            return $toast->send($options);
        }

        return $toast;
    }

    public function dialog(array $options = null): Dialog
    {
        /** @var Component $this */
        $dialog = new Dialog($this);

        if ($options) {
            return $dialog->send($options);
        }

        return $dialog;
    }
}
