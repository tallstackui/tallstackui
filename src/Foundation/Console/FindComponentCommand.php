<?php

namespace TallStackUi\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\select;

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

        $component = select('Select a component to find', $components->values()->toArray(), scroll: 10);
        $component = $this->prefix($component);

        $process = new Process(['grep', '-rn', $component, resource_path('views')]);

        try {
            $process->mustRun();

            $this->format($process->getOutput());

            return self::SUCCESS;
        } catch (ProcessFailedException) {
            $component = $this->prefix($component, true);

            $this->components->error('The component ['.$component.'] was not found in use.');
        } catch (Exception $exception) {
            $this->components->error('Unexpected Error Occurred: '.$exception->getMessage());
        }

        return self::FAILURE;
    }

    private function format(string $output): void
    {
        if (empty($output)) {
            return;
        }

        $lines = collect(explode(PHP_EOL, $output))->filter();
        $total = count($lines);

        $this->components->info('🎉 Found '.count($lines).' occurrences.');

        foreach ($lines as $key => $line) {
            preg_match('/^(.*?):(\d+):(.*)$/', $line, $matches);

            if (blank($line) || count($matches) !== 4) {
                continue;
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
        }
    }

    private function prefix(string $component, bool $remove = false): string
    {
        if (! ($prefix = config('tallstackui.prefix'))) {
            return $component;
        }

        return match ($remove) {
            true => str($component)->after($prefix)->value(),
            default => $prefix.$component,
        };
    }
}
