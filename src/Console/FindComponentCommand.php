<?php

namespace TallStackUi\Console;

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

    public $description = 'Find Components occurrences usage through all Blade files.';

    public $signature = 'tallstackui:find-component';

    public function handle(): int
    {
        $components = collect(config('ts-ui.components'))
            ->keys()
            ->filter(fn (string $component) => ! in_array($component, self::IGNORES));

        $original = suggest('Select Component', $components->values()->toArray(), required: true);
        $prefix = config('ts-ui.prefix');
        $find = sprintf('<x-%s', $prefix ? $prefix.$original : $original);

        $windows = windows_os();
        $pattern = str_replace('.', '\\.', $find).'([^a-zA-Z0-9-]|$)';

        $command = $windows
            ? ['findstr', '/S', '/N', '/I', $find, resource_path('views').'\*.blade.php']
            : ['grep', '-rnE', $pattern, resource_path('views')];

        $process = new Process($command);

        try {
            $process->mustRun();

            $this->output($process->getOutput(), $original, $find, $windows);

            return self::SUCCESS;
        } catch (ProcessFailedException) {
            $this->components->error('The ['.$original.'] component is not in use.');
        } catch (Exception $exception) {
            $this->components->error('Unexpected Error: '.$exception->getMessage());
        }

        return self::FAILURE;
    }

    private function output(string $output, string $component, string $find, bool $window): void
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
            ->filter(fn (string $line) => ! str_contains($line, '</x-'))
            ->filter(function (string $line) use ($find): bool {
                $pos = stripos($line, $find);

                if ($pos === false) {
                    return false;
                }

                $after = $pos + strlen($find);

                if ($after >= strlen($line)) {
                    return true;
                }

                $next = $line[$after];

                return ! ctype_alnum($next) && $next !== '-';
            });

        $total = $lines->count();

        $this->components->info('🎉 '.$total.' occurrences found');

        $lines->each(function (string $line) use (&$rows, $window): bool {
            if ($window) {
                preg_match('/^(.*\\\)([^\\\]+)\.blade\.php:(\d+):(.*)$/', $line, $matches);
            } else {
                preg_match('/^(.*?):(\d+):(.*)$/', $line, $matches);
            }

            if (blank($line) || count($matches) < 3) {
                return false;
            }

            $path = $window
                ? 'resources/views/'.$matches[2].'.blade.php'
                : str($matches[1])->afterLast(base_path().'/')->value();

            $number = $window
                ? $matches[3]
                : $matches[2];

            $rows[] = [$path, $number];

            return true;
        });

        table(['File', 'Line'], $rows);
    }
}
