<?php

namespace TallStackUi\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\suggest;
use function Laravel\Prompts\table;

class FindComponentCommand extends Command
{
    // List of components that should not be searched because
    // they are child components or non-visible components.
    private const IGNORES = [
        'dropdown.items',
        'floating',
        'progress.circle',
        'step.items',
        'tab.items',
        'wrapper.input',
        'wrapper.radio',
    ];

    public $description = 'TallStackUI find component';

    public $signature = 'tallstackui:find-component';

    public function handle(): int
    {
        $components = collect(config('tallstackui.components'))
            ->keys()
            ->filter(fn ($component) => ! in_array($component, self::IGNORES));

        $original = suggest('Select Component', $components->values()->toArray(), required: true);
        $prefix = config('tallstackui.prefix');
        $find = sprintf('<x-%s', $prefix ? $prefix.$original : $original);

        $process = new Process([
            'grep',
            '-rn',
            $find,
            resource_path('views'),
        ]);

        try {
            $process->mustRun();

            $this->output($process->getOutput(), $original);

            return self::SUCCESS;
        } catch (ProcessFailedException) {
            $this->components->error('The ['.$original.'] component is not in use.');
        } catch (Exception $exception) {
            $this->components->error('Unexpected Error: '.$exception->getMessage());
        }

        return self::FAILURE;
    }

    private function output(string $output, string $component): void
    {
        if (blank($output)) {
            return;
        }

        $rows = [];

        $this->components->info('🔍 Searching for ['.$component.'] component...');

        $lines = collect(explode(PHP_EOL, $output))
            // We need to keep this here to remove possible empty lines
            ->filter()
            // After that, need to ignore lines that contain
            // </x- because they are closing tags and not the
            // actual component, like examples of </x-modal> and </x-slide>
            ->filter(fn ($line) => ! str_contains($line, '</x-'));

        $total = $lines->count();

        $this->components->info('🎉 '.$total.' occurrences found');

        $lines->each(function (string $line) use (&$rows) {
            preg_match('/^(.*?):(\d+):(.*)$/', $line, $matches);

            if (blank($line) || count($matches) !== 4) {
                return false;
            }

            $path = str($matches[1])->afterLast(base_path().'/')->value();
            $number = $matches[2];

            $rows[] = [$path, $number];
        });

        table(['File', 'Line'], $rows);
    }
}
