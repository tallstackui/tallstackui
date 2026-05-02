<?php

use Illuminate\Console\Command;
use TallStackUi\Console\FindComponentCommand;
use TallStackUi\Console\IdeCommand;
use TallStackUi\Console\PublishCommentsTableCommand;
use TallStackUi\Console\SetupColorCommand;
use TallStackUi\Console\SetupPrefixCommand;

afterEach(fn () => @unlink(base_path('ide.json')));

test('all commands extend illuminate command', function () {
    expect([
        IdeCommand::class,
        FindComponentCommand::class,
        SetupColorCommand::class,
        SetupPrefixCommand::class,
        PublishCommentsTableCommand::class,
    ])->toExtend(Command::class);
});

test('all commands have handle method', function (string $command) {
    expect($command)->toHaveMethod('handle');
})->with([
    IdeCommand::class,
    FindComponentCommand::class,
    SetupColorCommand::class,
    SetupPrefixCommand::class,
    PublishCommentsTableCommand::class,
]);

test('ide command has ignores constant', function () {
    $reflection = new ReflectionClass(IdeCommand::class);

    expect($reflection->getConstants())->toHaveKey('IGNORES')
        ->and($reflection->getConstant('IGNORES'))->toBeArray()->not->toBeEmpty();
});

test('find component command has ignores constant', function () {
    $reflection = new ReflectionClass(FindComponentCommand::class);

    expect($reflection->getConstants())->toHaveKey('IGNORES')
        ->and($reflection->getConstant('IGNORES'))->toBeArray()->not->toBeEmpty();
});

test('ide command generates ide.json file', function () {
    $this->artisan('tallstackui:ide')->assertSuccessful();

    expect(file_exists(base_path('ide.json')))->toBeTrue();

    $content = json_decode(file_get_contents(base_path('ide.json')), true);

    expect($content)
        ->toHaveKey('$schema')
        ->toHaveKey('blade.components.list')
        ->and($content['blade']['components']['list'])
        ->toBeArray()
        ->not->toBeEmpty();
});
