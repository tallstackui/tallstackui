<?php

namespace Tests\Support;

use TallStackUi\Support\CommandPalette\Callback;
use TallStackUi\Support\CommandPalette\ItemSelected;

class CommandPaletteActionableWithNavigateStub
{
    public function __invoke(ItemSelected $selected): Callback
    {
        return Callback::redirect('/test')->navigate();
    }
}
