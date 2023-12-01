<?php

namespace TallStackUi\Traits;

use Livewire\Component;
use TallStackUi\Actions\Banner;
use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;

trait Interactions
{
    public function banner(): Banner
    {
        /** @var Component $this */
        return new Banner($this);
    }

    public function dialog(): Dialog
    {
        /** @var Component $this */
        return new Dialog($this);
    }

    public function toast(): Toast
    {
        /** @var Component $this */
        return new Toast($this);
    }
}
