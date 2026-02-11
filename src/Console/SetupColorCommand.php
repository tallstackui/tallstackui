<?php

namespace TallStackUi\Console;

use Exception;
use Illuminate\Console\Command;
use ReflectionClass;
use ReflectionException;
use TallStackUi\Attributes\ColorsThroughOf;

use function Laravel\Prompts\select;

class SetupColorCommand extends Command
{
    public $description = 'Publish stubs to customize Component colors.';

    public $signature = 'tallstackui:setup-color';

    /** @throws ReflectionException */
    public function handle(): int
    {
        if (blank(config('ts-ui.color_classes_namespace'))) {
            $this->components->error('The namespace for the color classes is blank.');

            return self::FAILURE;
        }

        $filtered = __ts_filter_components_using_attribute(ColorsThroughOf::class);

        $components = [];
        $map = [];

        foreach ($filtered as $class) {
            $display = str_replace('TallStackUi\\Components\\', '', $class);
            $display = preg_replace('/\\\\Component$/', '', $display);
            $display = str_replace('\\Main', '', $display);

            $attribute = (new ReflectionClass($class))->getAttributes(ColorsThroughOf::class);
            $key = str_replace('Colors', '', class_basename($attribute[0]->getArguments()[0]));

            $components[$display] = $display;
            $map[$display] = $key;
        }

        $component = select('Select the component to customize the colors', $components, hint: 'Only colored components are listed.');

        return $this->publish($map[$component]);
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
