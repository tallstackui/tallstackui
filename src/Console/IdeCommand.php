<?php

namespace TallStackUi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Components\Wrapper\Input\Component as InputWrapper;
use TallStackUi\Components\Wrapper\Radio\Component as RadioWrapper;
use TallStackUi\Facades\TallStackUi;

use function Laravel\Prompts\confirm;

class IdeCommand extends Command
{
    private const IGNORES = [
        Floating::class,
        InputWrapper::class,
        RadioWrapper::class,
    ];

    protected $description = 'Generate IDE configuration file for TallStackUI components.';

    protected $signature = 'tallstackui:ide';

    public function handle(): int
    {
        if (app()->isProduction() && ! confirm('The application is in production. Do you want to continue?', default: false)) {
            return self::FAILURE;
        }

        $json = ['$schema' => 'https://laravel-ide.com/schema/laravel-ide-v2.json'];

        $list = [];

        $components = config('ts-ui.components');

        if (count($components) === 0) {
            $this->components->error('No TallStackUI components found based on the config file.');

            return self::FAILURE;
        }

        if (file_exists(base_path('ide.json'))) {
            $this->components->warn('The ide.json file already exists. The process will overwrite it.');
        }

        File::delete(base_path('ide.json'));

        foreach (
            collect($components)
                ->map(fn (string|array $class) => is_array($class) ? $class[0] : $class)
                ->filter(fn (string $class) => ! in_array($class, self::IGNORES)) as $name => $class
        ) {
            $list[] = [
                'name' => TallStackUi::prefix($name),
                'className' => $class,
            ];
        }

        $json['blade'] = [
            'components' => [
                'list' => $list,
            ],
        ];

        file_put_contents(base_path('ide.json'), json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->components->info('The ide.json file was successfully generated. Rebuild your Laravel Idea metadata.');

        return self::SUCCESS;
    }
}
