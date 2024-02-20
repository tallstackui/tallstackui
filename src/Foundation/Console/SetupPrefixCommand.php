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
        if (! file_exists(config_path('tallstackui.php'))) {
            Process::run('php artisan vendor:publish --tag=tallstackui.config');
        }

        $prefix = text('What prefix do you want to use for the TallStackUI components?', required: true);
        $result = $this->setup($prefix);

        if ($result !== true) {
            $this->components->error($result);

            return self::FAILURE;
        }

        Process::run('php artisan view:clear');

        $this->components->info('The prefix ['.$prefix.'] was successfully set up.');

        return self::SUCCESS;
    }

    private function setup(string $prefix): bool|string
    {
        try {
            $prefix = $prefix === 'null' ? var_export(null, true) : "'$prefix'";

            $config = file_get_contents(config_path('tallstackui.php'));
            $update = preg_replace("/('prefix' => )[^,]+/", "\$1$prefix", $config);
            file_put_contents(config_path('tallstackui.php'), $update);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
