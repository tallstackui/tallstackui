<?php

namespace TallStackUi\Console;

use Exception;
use Illuminate\Console\Command;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Components\Button\Circle\Component as Circle;
use TallStackUi\Components\Form\Checkbox\Component as Checkbox;

use function Laravel\Prompts\select;

class SetupColorCommand extends Command
{
    public $description = 'Publish stubs to personalize Component colors.';

    public $signature = 'tallstackui:setup-color';

    public function handle(): int
    {
        if (blank(config('ts-ui.color_classes_namespace'))) {
            $this->components->error('The namespace for the color classes is blank.');

            return self::FAILURE;
        }

        $reject = [
            Checkbox::class, // -> merged with Radio
            Circle::class, // -> merged with Progress
        ];

        $filtered = array_diff(__ts_filter_components_using_attribute(ColorsThroughOf::class), $reject);

        $components = [];

        foreach ($filtered as $class) {
            $name = substr(strrchr($class, '\\'), 1);
            $components[$name] = $name;
        }

        $component = select('Select the component to personalize the colors', $components, hint: 'Only colored components are listed.');

        return $this->publish($component);
    }

    private function publish(string $component): int
    {
        $collect = __ts_class_collection($component);

        if ($collect['file_exists'] === true) {
            $this->components->error('According to the namespace, the class file already exists.');

            return self::FAILURE;
        }

        try {
            $stub = file_get_contents($collect['stub']);

            // We start by replacing {{ namespace }} with the class
            // namespace based on the value coming from the configuration.
            $stub = str_replace('{{ namespace }}', $collect['namespace'], $stub);

            // To avoid: 'Failed to open stream: No such file or directory',
            // we make sure that the destination directory exists.
            if (! is_dir(dirname($path = $collect['app_path']))) {
                mkdir(dirname($path), 0755, true);
            }

            file_put_contents($path, $stub);

            $this->components->info("The color class <options=bold>[{$collect['file_raw']}]</> has been created successfully.");

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->components->error('Something went wrong: '.$e->getMessage());
        }

        return self::FAILURE;
    }
}
