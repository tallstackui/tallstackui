<?php

it('can render', function () {
    $this->blade('<x-avatar label="Lorem" />')
        ->assertSee('Lorem');
});

it('can render sm', function () {
    $this->blade('<x-avatar label="Lorem" sm />')
        ->assertSee('w-8 h-8');
});

it('can render md', function () {
    $this->blade('<x-avatar label="Lorem" md />')
        ->assertSee('w-12 h-12');
});

it('can render lg', function () {
    $this->blade('<x-avatar label="Lorem" lg />')
        ->assertSee('w-14 h-14');
});

it('can render square', function () {
    $this->blade('<x-avatar label="Lorem" lg square />')
        ->assertDontSee('rounded-full');
});

it('can render placeholder', function () {
    $this->blade('<x-avatar />')
        ->assertSee('svg');
});
