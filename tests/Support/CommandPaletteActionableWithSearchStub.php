<?php

namespace Tests\Support;

use TallStackUi\Support\CommandPalette\Callback;
use TallStackUi\Support\CommandPalette\ItemSelected;

class CommandPaletteActionableWithSearchStub
{
    public function __invoke(ItemSelected $selected): Callback
    {
        return Callback::event('searched')->with(['term' => $selected->search]);
    }
}
