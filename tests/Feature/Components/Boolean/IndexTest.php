<?php

it('can render')
    ->expect('<x-boolean :boolean="true" />')
    ->render()
    ->toContain('<svg');

it('can render different icon when true')->todo();

it('can render different icon when false')->todo();

it('can render different color when true')->todo();

it('can render different color when false')->todo();
