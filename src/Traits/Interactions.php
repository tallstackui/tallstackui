<?php

namespace TallStackUi\Traits;

use Livewire\Component;
use TallStackUi\Foundation\Interactions\Banner;
use TallStackUi\Foundation\Interactions\Dialog;
use TallStackUi\Foundation\Interactions\Toast;

trait Interactions
{
    public function banner(): Banner
    {
        /** @var Component|null $this */
        // TODO: what will happens if the parent class doesn't have Controllers in its name?
        return new Banner(str_contains(static::class, 'Controllers') ? null : $this);
    }

    public function dialog(): Dialog
    {
        /** @var Component|null $this */
        return new Dialog(str_contains(static::class, 'Controllers') ? null : $this);
    }

    public function toast(): Toast
    {
        /** @var Component|null $this */
        return new Toast(str_contains(static::class, 'Controllers') ? null : $this);
    }
}
