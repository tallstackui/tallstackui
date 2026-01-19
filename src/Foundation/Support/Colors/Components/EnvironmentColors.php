<?php

namespace TallStackUi\Foundation\Support\Colors\Components;

use Illuminate\Support\Str;
use TallStackUi\Foundation\Support\Colors\Concerns\SetupColors;

class EnvironmentColors
{
    use SetupColors;

    public function colors(): array
    {
        [$background, $text] = $this->get('background', 'text');

        return ['background' => $background ?? $this->background(), 'text' => $text ?? $this->text()];
    }

    private function background(): string
    {
        return match (Str::lower(app()->environment())) {
            'local' => 'border-green-500 bg-green-500 dark:bg-green-700 dark:bg-opacity-80 dark:border-transparent',
            'staging' => 'border-yellow-500 bg-yellow-500 dark:bg-yellow-700 dark:bg-opacity-80 dark:border-transparent',
            'sandbox' => 'border-orange-500 bg-orange-500 dark:bg-orange-700 dark:bg-opacity-80 dark:border-transparent',
            'production' => 'border-red-500 bg-red-500 dark:bg-red-700 dark:bg-opacity-80 dark:border-transparent',
            default => 'border-primary-500 bg-primary-500 dark:bg-primary-700 dark:bg-opacity-80 dark:border-transparent',
        };
    }

    private function text(): string
    {
        return match (Str::lower(app()->environment())) {
            'local' => 'text-green-50',
            'staging' => 'text-yellow-50',
            'sandbox' => 'text-orange-50',
            'production' => 'text-red-50',
            default => 'text-primary-50',
        };
    }
}
