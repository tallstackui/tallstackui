<?php

namespace TallStackUi\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\text;

class SetupPrefixCommand extends Command
{
    public $description = 'TallStackUI prefix set up';

    public $signature = 'tallstackui:setup-prefix';

    public function handle(): int
    {
        $prefix = text('What prefix do you want to use for the TallStackUI components?', required: true, hint: 'Type null to remove the current prefix, if set.');

        if ($prefix === 'null' && config('tallstackui.prefix') === null) {
            $this->components->error('The prefix is already set to null.');

            return self::FAILURE;
        }

        if (! file_exists(config_path('tallstackui.php'))) {
            Process::run('php artisan vendor:publish --tag=tallstackui.config');
        }

        if (($result = $this->setup($prefix)) !== true) {
            $this->components->error($result);

            return self::FAILURE;
        }

        Process::run('php artisan view:clear');

        $this->components->info('The prefix ['.$prefix.'] was successfully set up.');

        return self::SUCCESS;
    }

    private function content(): string
    {
        return file_get_contents(config_path('tallstackui.php'));
    }

    private function setup(string $prefix): bool|string
    {
        // This is an adaptation to accept the setup
        // of the prefix by using the .env file.
        return str_contains($this->content(), 'TALLSTACKUI_PREFIX')
            ? $this->setUpUsingEnv($prefix)
            : $this->setUpUsingConfig($prefix);
    }

    private function setUpUsingConfig(string $prefix): bool|string
    {
        try {
            $formatted = "'$prefix'";

            $update = preg_replace(
                "/('prefix' => )[^,]+/",
                $prefix === 'null' ? "'prefix' => null" : "\$1$formatted",
                $this->content()
            );

            file_put_contents(config_path('tallstackui.php'), $update);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function setUpUsingEnv(string $prefix): bool|string
    {
        try {
            $env = file_get_contents(base_path('.env'));
            $prefix = $prefix === 'null' ? '' : "\"$prefix\"";

            $update = str_contains($env, 'TALLSTACKUI_PREFIX')
                ? preg_replace("/(TALLSTACKUI_PREFIX=)[^\n]*/", "TALLSTACKUI_PREFIX=$prefix", $env)
                : $env.PHP_EOL."TALLSTACKUI_PREFIX=$prefix";

            file_put_contents(base_path('.env'), $update);

            Process::run('php artisan config:clear');

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
