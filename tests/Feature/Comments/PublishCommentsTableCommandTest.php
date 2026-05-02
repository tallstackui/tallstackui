<?php

beforeEach(function () {
    collect(glob(database_path('migrations/*_create_tallstackui_comments_table.php')) ?: [])
        ->each(fn (string $file) => @unlink($file));
});

afterEach(function () {
    collect(glob(database_path('migrations/*_create_tallstackui_comments_table.php')) ?: [])
        ->each(fn (string $file) => @unlink($file));
});

test('can publish comments table migration', function () {
    $this->artisan('tallstackui:publish-comments-table')
        ->assertSuccessful();

    $files = glob(database_path('migrations/*_create_tallstackui_comments_table.php'));

    expect($files)->not->toBeEmpty()
        ->and(file_get_contents($files[0]))->toContain('Schema::create(\'tallstackui_comments\'');
});
