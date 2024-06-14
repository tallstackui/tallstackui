<?php

namespace TallStackUi\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\suggest;

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

        $original = suggest('Select Component', $components->values()->toArray(), scroll: 10);

        $process = new Process(['grep', '-rn', $this->prefix($original), resource_path('views')]);

        try {
            $process->mustRun();

            $this->format($process->getOutput(), $original);

            return self::SUCCESS;
        } catch (ProcessFailedException) {
            $this->components->error('The component ['.$original.'] was not found in use.');
        } catch (Exception $exception) {
            $this->components->error('Unexpected Error Occurred: '.$exception->getMessage());
        }

        return self::FAILURE;
    }

    private function format(string $output, string $component): void
    {
        if (blank($output)) {
            return;
        }

        $lines = collect(explode(PHP_EOL, $output))
            ->filter()
            // We need to ignore lines that contain </ because
            // they are closing tags and not the actual component,
            // like examples of </x-modal> and </x-slide>
            ->filter(fn ($line) => ! str_contains($line, '</'));

        $total = $lines->count();

        $this->components->info('🎉 Found '.$total.' usage occurrences of the ['.$component.'] component.');

        $lines->each(function (string $line, int $key) use ($total) {
            preg_match('/^(.*?):(\d+):(.*)$/', $line, $matches);

            if (blank($line) || count($matches) !== 4) {
                return false;
            }

            $path = str($matches[1])->afterLast(base_path().'/')->value();
            $number = $matches[2];

            $this->line("File: <fg=green>{$path}</>");
            $this->line("Line: <fg=yellow>{$number}</>");

            // While we are not at the end line we add ---
            // otherwise we just add a new line as a whitespace
            if (($key + 1) < $total) {
                $this->line(str_repeat('-', 3));
            } else {
                $this->newLine();
            }
        });
    }

    private function prefix(string $component): string
    {
        if (! ($prefix = config('tallstackui.prefix'))) {
            return $component;
        }

        return $prefix.$component;
    }
}
