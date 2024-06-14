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

        $process = new Process(['grep', '-rn', $this->prefix($original), resource_path('views')]);

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

        if (($prefix = config('tallstackui.prefix'))) {
            $this->components->info('🔍 Searching for ['.$component.'] component with prefix ['.$prefix.']');
        }

        $lines = collect(explode(PHP_EOL, $output))
            ->filter()
            // We need to ignore lines that contain </ because
            // they are closing tags and not the actual component,
            // like examples of </x-modal> and </x-slide>
            ->filter(fn ($line) => ! str_contains($line, '</'));

        $total = $lines->count();

        $this->components->info('🎉 '.$total.' occurrences found.');

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

    private function prefix(string $component): string
    {
        if (! ($prefix = config('tallstackui.prefix'))) {
            return $component;
        }

        return $prefix.$component;
    }
}
