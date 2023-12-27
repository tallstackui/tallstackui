<?php

it('can render', function () {
    $component = <<<'HTML'
    @php
        $rows = [
            ['id' => 1, 'name' => 'Foo'],
            ['id' => 2, 'name' => 'Bar'],
        ];

        $headers = [
            ['index' => 'id', 'label' => '#'],
            ['index' => 'name', 'label' => 'Name'],
        ];
    @endphp
    
    <x-table :$headers :$rows />
HTML;

    expect($component)->render()
        ->toContain('Foo', 'Bar');
});

it('can render headerless', function () {
    $component = <<<'HTML'
    @php
        $rows = [
            ['id' => 1, 'name' => 'Foo'],
            ['id' => 2, 'name' => 'Bar'],
        ];

        $headers = [
            ['index' => 'id', 'label' => '#'],
            ['index' => 'name', 'label' => 'Name'],
        ];
    @endphp
    
    <x-table :$headers :$rows headerless />
HTML;

    expect($component)->render()
        ->toContain('Foo', 'Bar')
        ->not->toContain('#', 'Name');
});
