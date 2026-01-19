<?php

namespace TallStackUi\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;

use function Laravel\Prompts\suggest;

class SetupColorCommand extends Command
{
    public $description = 'Publish stubs to personalize Component colors.';

    public $signature = 'tallstackui:setup-color';

    public function handle(): int
    {
        if (blank(config('tallstackui.color_classes_namespace'))) {
            $this->components->error('The namespace for the color classes is blank.');

            return self::FAILURE;
        }

        $components = __ts_filter_components_using_attribute(ColorsThroughOf::class)
            // We remove the Circle component because its colors are personalized via Progress.
            ->reject(fn (string $component): bool => str($component)->contains('Circle'))
            ->mapWithKeys(function (string $component): array {
                $component = str($component)
                    ->remove('TallStackUi\\View\\Components\\')
                    ->afterLast('\\')
                    ->value();

                return [$component => $component];
            })
            ->all();

        $component = suggest('Select the component to personalize the colors', $components, required: true, hint: 'Only colored components are listed.');

        return $this->publish($component);
    }

    private function publish(string $component): int
    {
        $collect = __ts_class_collection($component);

        if ($collect->get('file_exists') === true) {
            $this->components->error('According to the namespace, the class file already exists.');

            return self::FAILURE;
        }

        try {
            $stub = file_get_contents(__DIR__.'/../../Foundation/Support/Colors/Stubs/'.$collect->get('file_raw').'.stub');

            // We start by replacing {{ namespace }} with the class
            // namespace based on the value coming from the configuration.
            $stub = str_replace('{{ namespace }}', $collect->get('namespace'), $stub);

            // To avoid: 'Failed to open stream: No such file or directory',
            // we make sure that the destination directory exists.
            if (! is_dir(dirname((string) $path = $collect->get('app_path')))) {
                mkdir(dirname((string) $path), 0755, true);
            }

            file_put_contents($path, $stub);

            $this->components->info("The color class <options=bold>[{$collect->get('file_raw')}]</> has been created successfully.");

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->components->error('Something went wrong: '.$e->getMessage());
        }

        return self::FAILURE;
    }
}
