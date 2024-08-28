<?php

namespace TallStackUi\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

use function Laravel\Prompts\table;

class PersonalizationScopesCommand extends Command
{
    public $description = 'Lists all the customization scope applied by the Service Provider and its components.';

    public $signature = 'tallstackui:scopes';

    public function handle(): int
    {
        /** @var Collection $scopes */
        $scopes = collect(app()->getBindings())
            ->filter(fn ($value, $key): bool => str_contains($key, '::scoped::'))
            ->map(fn ($value, $key): array => [
                'Scope Key' => str($key)->afterLast('::')->value(),
                'Component' => str(app()->call($value['concrete'])->component)->afterLast('\\')->value(),
            ]);

        if (($count = $scopes->count()) === 0) {
            $this->components->error('No personalization scoped found.');

            return self::SUCCESS;
        }

        $this->components->info('🎉 '.$count.' scopes defined');

        table(['Scope', 'Component'], $scopes->values());

        return self::SUCCESS;
    }
}
