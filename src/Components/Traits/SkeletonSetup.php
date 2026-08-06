<?php

namespace TallStackUi\Components\Traits;

trait SkeletonSetup
{
    /**
     * Blocks shared by every skeleton, merged into each component's own.
     */
    protected function blocks(): array
    {
        return [
            'animation' => 'animate-pulse',
            'bar' => 'dark:bg-dark-700 rounded bg-gray-200',
        ];
    }

    /**
     * Configuration validation, so it runs in skeleton mode like the rest.
     */
    protected function guard(): void
    {
        if (is_int($this->skeleton) && $this->skeleton < 1) {
            __ts_validation_exception($this, 'The [skeleton] must be greater than 0.');
        }
    }

    protected function skeletonized(): bool
    {
        return $this->skeleton !== null && $this->skeleton !== false;
    }
}
