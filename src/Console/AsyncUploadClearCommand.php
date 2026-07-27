<?php

namespace TallStackUi\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use TallStackUi\Components\Form\Upload\Async\Component;

class AsyncUploadClearCommand extends Command
{
    public $description = 'Delete orphan async-upload chunk sessions older than session_ttl.';

    public $signature = 'tallstackui:async-upload:clear';

    public function handle(): int
    {
        $configuration = __ts_get_component_configuration(Component::class);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($configuration['tmp_disk']);

        $directory = $configuration['tmp_directory'];

        if (! $disk->exists($directory)) {
            return self::SUCCESS;
        }

        $threshold = now()->subSeconds((int) $configuration['session_ttl'])->getTimestamp();
        $removed = 0;

        // A directory's mtime moves whenever a part lands inside it, so this
        // tracks "last chunk received" rather than "session opened".
        foreach ($disk->directories($directory) as $session) {
            if (filemtime($disk->path($session)) < $threshold) {
                $disk->deleteDirectory($session);
                $removed++;
            }
        }

        $this->info("Removed {$removed} orphan upload session(s).");

        return self::SUCCESS;
    }
}
