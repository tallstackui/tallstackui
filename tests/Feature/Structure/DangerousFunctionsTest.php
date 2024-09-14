<?php

use Illuminate\Support\Facades\File;

test('should not use dangerous functions in PHP files')
    ->expect(['dd', 'dump', 'exit', 'ray', 'var_dump'])
    ->not
    ->toBeUsed();

test('should not use dangerous functions in Blade files', function () {
    $files = collect(File::allFiles(__DIR__.'/../../../src/resources/views/'))
        ->map(fn (SplFileInfo $file) => ['name' => $file->getFilename(), 'content' => file_get_contents($file->getRealPath())])
        ->filter(fn (array $file) => str($file['content'])->contains(['@dd', '@dump', '@ray']))
        ->pluck('name')
        ->implode(', ');

    if (! empty($files)) {
        test()->fail("The following files contain dangerous functions: [{$files}]"); // @phpstan-ignore-line
    }

    expect($files)->toBeEmpty();
});
