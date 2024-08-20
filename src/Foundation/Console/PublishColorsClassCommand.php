<?php

namespace TallStackUi\Foundation\Console;

use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\select;

class PublishColorsClassCommand extends Command
{
    private const COMPONENTS = [
        'Alert',
        'Avatar',
        'Badge',
        'Banner',
        'Boolean',
        'Button',
        'Dialog',
        'Errors',
        'Link',
        'Progress',
        'Radio',
        'Range',
        'Rating',
        'Stats',
        'Toast',
        'Toggle',
        'Tooltip',
    ];

    public $description = 'TallStackUI color personalization';

    public $signature = 'tallstackui:personalize-colors';

    /**
     * The class name.
     */
    private string $class;

    /**
     * The full class name, with the namespace.
     */
    private string $full;

    /**
     * The namespace of the color classes.
     */
    private string $namespace;

    public function handle(): int
    {
        $namespace = config('tallstackui.color_classes_namespace');

        if (! $namespace) {
            $this->components->error('The namespace for the color classes is not defined in the config. file.');

            return self::FAILURE;
        }

        $component = select('Select the component to personalize', self::COMPONENTS, required: true);

        $this->namespace = $namespace;
        $this->class = $component.'Colors';
        $this->full = $namespace.'\\'.$this->class;

        return $this->publish();
    }

    private function publish(): int
    {
        if (class_exists($this->full)) {
            $this->components->error('According to the namespace, the class file already exists.');

            return self::FAILURE;
        }

        try {
            $stub = file_get_contents(__DIR__.'/../../Foundation/Colors/Stubs/'.$this->class.'.stub');

            // We start by replacing {{ namespace }} with the class
            // namespace based on the value coming from the configuration.
            $stub = str_replace('{{ namespace }}', $this->namespace, $stub);

            // We remove 'App\\' from the namespace so that we can refer
            // to the app/ structure, and then we obtain the file path.
            $namespace = str_replace('\\', '/', str($this->namespace)->remove('App\\')->value());

            $path = app_path($namespace.'/'.$this->class.'.php');

            // To avoid: 'Failed to open stream: No such file or directory',
            // we make sure that the destination directory exists.
            if (! is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            file_put_contents($path, $stub);

            $this->components->success("The color class <options=bold>[{$this->class}]</> has been created successfully.");

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->components->error('Something went wrong: '.$e->getMessage());
        }

        return self::FAILURE;
    }
}
