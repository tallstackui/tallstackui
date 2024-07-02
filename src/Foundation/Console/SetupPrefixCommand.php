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

    private function content(): string
    {
        return file_get_contents(config_path('tallstackui.php'));
    }

    private function env(string $prefix): bool|string
    {
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

    private function setup(string $prefix): bool|string
    {
        if (file_exists(config_path('tallstackui.php')) && ! str_contains($this->content(), 'TALLSTACKUI_PREFIX')) {
            return $this->config($prefix);
        }

        return $this->env($prefix);
    }
}
