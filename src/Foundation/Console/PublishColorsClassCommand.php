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
     * The selected component.
     */
    private string $component;

    public function handle(): int
    {
        if (! config('tallstackui.color_classes_namespace')) {
            $this->components->error('The namespace for the color classes is not defined in the config. file.');

            return self::FAILURE;
        }

        $this->component = select('Select the component to personalize', self::COMPONENTS, hint: 'Only colored components are listed.', required: true);

        return $this->publish();
    }

    private function publish(): int
    {
        $collect = __ts_class_collection($this->component);

        if ($collect->get('file_exists') === true) {
            $this->components->error('According to the namespace, the class file already exists.');

            return self::FAILURE;
        }

        try {
            $stub = file_get_contents(__DIR__.'/../../Foundation/Colors/Stubs/'.$collect->get('file_raw').'.stub');

            // We start by replacing {{ namespace }} with the class
            // namespace based on the value coming from the configuration.
            $stub = str_replace('{{ namespace }}', $collect->get('namespace'), $stub);

            // To avoid: 'Failed to open stream: No such file or directory',
            // we make sure that the destination directory exists.
            if (! is_dir(dirname($path = $collect->get('app_path')))) {
                mkdir(dirname($path), 0755, true);
            }

            file_put_contents($path, $stub);

            $this->components->success("The color class <options=bold>[{$collect->get('file_raw')}]</> has been created successfully.");

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->components->error('Something went wrong: '.$e->getMessage());
        }

        return self::FAILURE;
    }
}
