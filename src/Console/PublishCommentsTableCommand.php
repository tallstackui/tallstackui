<?php

namespace TallStackUi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use TallStackUi\Components\Comments\Component as CommentsComponent;

class PublishCommentsTableCommand extends Command
{
    public $description = 'Publish the comments table migration.';

    public $signature = 'tallstackui:publish-comments-table';

    public function handle(): int
    {
        $table = __ts_get_component_configuration(CommentsComponent::class, 'table') ?? 'tallstackui_comments';

        if (!is_dir(database_path('migrations'))) {
            mkdir(database_path('migrations'), 0755, true);
        }

        if ($this->migrationExists($table)) {
            $this->components->error("A migration for the [{$table}] table already exists.");

            return self::FAILURE;
        }

        $stub = file_get_contents(__DIR__ . '/stubs/create_comments_table.php.stub');
        $stub = str_replace('{{ table }}', $table, $stub);

        $file = database_path('migrations/' . date('Y_m_d_His') . '_create_' . Str::snake($table) . '_table.php');

        file_put_contents($file, $stub);

        $this->components->info("The comments table migration was published to [{$file}].");

        return self::SUCCESS;
    }

    protected function migrationExists(string $table): bool
    {
        return collect(glob(database_path('migrations/*_create_*_table.php')) ?: [])
            ->contains(fn(string $path): bool => str_contains($path, 'create_' . Str::snake($table) . '_table.php'));
    }
}
