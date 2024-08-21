<?php

use TallStackUi\Foundation\Actions\Dialog;
use TallStackUi\Foundation\Actions\Toast;
use TallStackUi\Foundation\Actions\Traits\InteractWithConfirmation;

test('can only be used in Dialog and Toast')
    ->expect(InteractWithConfirmation::class)
    ->toOnlyBeUsedIn([
        Dialog::class,
        Toast::class,
    ]);
