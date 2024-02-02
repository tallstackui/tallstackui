<?php

namespace TallStackUi\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use ZipArchive;

use function Laravel\Prompts\spin;

#[AsCommand(name: 'tallstackui:setup-icons', description: 'Icon configuration for TallStackUI')]
class SetupIconsCommand extends Command
{
    protected ?Collection $metadata = null;

    public function handle(): int // OK
    {
        if (! extension_loaded('zip')) {
            $this->components->error('The zip extension is not installed. Please install it and try again.');

            return self::FAILURE;
        }

        $this->metadata = collect();

        if (($result = spin(fn () => $this->setup(), 'Setting up...')) !== true) {
            $this->components->error($result);

            return self::FAILURE;
        }

        if (($result = spin(fn () => $this->download(), 'Downloading...')) !== true) {
            $this->components->error($result);

            return self::FAILURE;
        }

        $this->components->info('Done!');

        return self::SUCCESS;
    }

    private function download(): string|bool
    {
        $response = Http::get(sprintf('https://github.com/tallstackui/icons/raw/main/%s/files.zip', $this->metadata->get('type')));

        if ($response->failed()) {
            return 'Failed to download the .zip file.';
        }

        $folderName = Str::random();
        $zipFileName = storage_path('app/'.$folderName.'.zip');

        file_put_contents($zipFileName, $response->body());

        $zip = new ZipArchive;

        if ($zip->open($zipFileName)) {
            $extractPath = storage_path('app/'.$folderName);
            $zip->extractTo($extractPath);
            $zip->close();

            $destinationPath = __DIR__.'/../../resources/views/components/icon/'.$this->metadata->get('type');
            File::copyDirectory($extractPath, $destinationPath);

            unlink($zipFileName);
            File::deleteDirectory($extractPath);

            return true;
        }

        return 'Failed to extract the .zip file.';
    }

    private function setup(): bool|string // OK
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

        $styles = [
            'heroicons' => ['solid', 'outline'],
            'phosphoricons' => ['thin', 'light', 'regular', 'bold', 'fill', 'duotone'],
        ];

        if (! in_array($type, ['heroicons', 'phosphoricons'])) {
            return 'Unsupported icon type. Please, review the configuration file.';
        }

        if (! in_array($style, $styles[$type])) {
            return 'Unsupported icon style. Please, review the configuration file.';
        }

        $this->metadata->put('type', $type);
        $this->metadata->put('style', $style);

        if (is_dir(__DIR__.'/../../resources/views/components/icon/'.$type)) {
            return 'The icons selected are already installed.';
        }

        // delete the folder if is different than the installed

        return true;
    }
}
