<?php

namespace TallStackUi\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\text;

class SetupPrefixCommand extends Command
{
    public $description = 'Set up Component prefix.';

    public $signature = 'tallstackui:setup-prefix';

    public function handle(): int
    {
        $prefix = text('What prefix do you want to use?', required: true, hint: 'Type null to remove the current prefix, if set.');
        $null = $prefix === 'null';

        if ($null && blank(config('tallstackui.prefix'))) {
            $this->components->error('The prefix is already set to null.');

            return self::FAILURE;
        }

        if (($result = $this->setup($prefix)) !== true) {
            $this->components->error($result);

            return self::FAILURE;
        }

        $this->components->info($null ? 'The prefix was successfully removed.' : 'The prefix ['.$prefix.'] was successfully set up.');
        $this->components->info('Please, run <options=bold,underscore>php artisan optimize:clear</> to clear the cache.');

        return self::SUCCESS;
    }

    /**
     * Define the component prefix in the config file.
     */
    private function config(string $prefix): bool|string
    {
        if (! file_exists(config_path('tallstackui.php'))) {
            Process::run('php artisan vendor:publish --tag=tallstackui.config');
        }

        $formatted = "'$prefix'";

        try {
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

    /**
     * Get the content of the config file.
     */
    private function content(): string
    {
        return file_get_contents(config_path('tallstackui.php'));
    }

    /**
     * Define the component prefix in the .env file.
     */
    private function env(string $prefix): bool|string
    {
        if (! file_exists(base_path('.env'))) {
            $this->components->error('The .env file does not exist.');

            return false;
        }

        try {
            $env = file_get_contents(base_path('.env'));
            $prefix = $prefix === 'null' ? '' : "\"$prefix\"";

            $update = str_contains($env, 'TALLSTACKUI_PREFIX')
                ? preg_replace("/(TALLSTACKUI_PREFIX=)[^\n]*/", "TALLSTACKUI_PREFIX=$prefix", $env)
                : $env.PHP_EOL."TALLSTACKUI_PREFIX=$prefix";

            file_put_contents(base_path('.env'), $update);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Set up the prefix in the config file or in the .env file.
     */
    private function setup(string $prefix): bool|string
    {
        // When the configuration file is old and does not have the key
        // for the env variable, we configure it directly in the config file.
        if (file_exists(config_path('tallstackui.php')) && ! str_contains($this->content(), 'TALLSTACKUI_PREFIX')) {
            return $this->config($prefix);
        }

        return $this->env($prefix);
    }
}
