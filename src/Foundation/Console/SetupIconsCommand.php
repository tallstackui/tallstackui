<?php

namespace TallStackUi\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use TallStackUi\Foundation\Support\Components\IconGuide;
use ZipArchive;

use function Laravel\Prompts\spin;

class SetupIconsCommand extends Command
{
    public $description = 'TallStackUI icon set up';

    public $signature = 'tallstackui:setup-icons {--force : Install icons even when the icons are already installed}';

    protected ?Collection $data = null;

    /**
     * @var bool Indicate that the returned message is not part of an error.
     */
    protected bool $error = true;

    public function handle(): int
    {
        $this->data = collect();

        if (($result = spin(fn () => $this->setup(), 'Setting up...')) !== true) {
            if (! $this->error) {
                $this->components->warn($result);

                return self::SUCCESS;
            }

            $this->components->error($result);

            return self::FAILURE;
        }

        if (($result = spin(fn () => $this->download(), 'Downloading...')) !== true) {
            $this->components->error($result);

            return self::FAILURE;
        }

        spin(fn () => Process::run('php artisan optimize:clear'), 'Cleaning up ...');

        $type = $this->data->get('type');

        $this->components->info('The icons ['.$type.'] are successfully installed.');

        return self::SUCCESS;
    }

    private function download(): string|bool
    {
        $response = Http::get(sprintf('https://github.com/tallstackui/icons/raw/main/%s/files.zip', $this->data->get('type')));

        if ($response->failed()) {
            return 'Failed to download the .zip file.';
        }

        $temp = Str::random();
        $file = storage_path('app/'.$temp.'.zip');
        file_put_contents($file, $response->body());

        $zip = new ZipArchive();

        if (! $zip->open($file)) {
            return 'Failed to extract the .zip file.';
        }

        $extract = storage_path('app/'.$temp);
        $zip->extractTo($extract);
        $zip->close();

        $this->flush($file, $extract);

        return true;
    }

    private function flush(string $file, string $extract): void
    {
        $path = __DIR__.'/../../resources/views/components/icon/';

        if ($this->option('force')) {
            File::deleteDirectory($path.$this->data->get('type'));
        }

        File::copyDirectory($extract, $path.$this->data->get('type'));
        File::deleteDirectory($extract);
        unlink($file);

        if (config('tallstackui.icons.flush', true) === true && ! $this->laravel->isProduction()) {
            return;
        }

        foreach (
            collect(array_keys(IconGuide::AVAILABLE))
                ->mapWithKeys(fn ($value, $key) => [$value => $value]) // @pest-ignore-type
                ->except($this->data->get('type'))
                ->toArray() as $type
        ) {
            // Flushing the other unused icons to
            // avoid the existence of unused files.
            File::deleteDirectory($path.$type);
        }
    }

    private function setup(): bool|string
    {
        sleep(1);

        if (! file_exists(config_path('tallstackui.php'))) {
            return 'The TallStackUI configuration file does not exist. Please, review the docs.';
        }

        $config = config('tallstackui');

        if (
            blank(data_get($config, 'icons')) ||
            blank($type = data_get($config, 'icons.type')) ||
            blank($style = data_get($config, 'icons.style'))
        ) {
            return 'Wrong configuration file. Please, review the docs.';
        }

        if (! in_array($type, array_keys(IconGuide::AVAILABLE))) {
            return 'Unsupported icon type. Please, review the configuration file.';
        }

        if (! in_array($style, IconGuide::AVAILABLE[$type])) {
            return 'Unsupported icon style. Please, review the configuration file.';
        }

        if (! $this->option('force') && is_dir(__DIR__.'/../../resources/views/components/icon/'.$type)) {
            $this->error = false;

            return 'The icons selected ['.$type.'] are already installed.';
        }

        $this->data->put('type', $type);
        $this->data->put('style', $style);

        return true;
    }
}
