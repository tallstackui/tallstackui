<?php

namespace Tests\Support;

use TallStackUi\Support\CommandPalette\Callback;
use TallStackUi\Support\CommandPalette\ItemSelected;

class CommandPaletteActionableWithAdditionalStub
{
    public function __invoke(ItemSelected $selected): Callback
    {
        return Callback::event('role-check')->with(['role' => $selected->additional['role']]);
    }
}
