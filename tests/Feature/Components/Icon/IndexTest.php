<?php

it('can render', function () {
    $this->blade('<x-icon icon="users" />')
        ->assertSee('<svg', false);
});

it('can render solid', function () {
    $this->blade('<x-icon icon="users" solid />')
        ->assertSee('<svg', false);
});

it('can render outline', function () {
    $this->blade('<x-icon icon="users" outline />')
        ->assertSee('<svg', false);
});

it('can render with error', function () {
    $this->blade('<x-icon icon="users" outline error />')
        ->assertSee('text-red-500');
});
