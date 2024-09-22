<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

class Environment extends TallStackUiComponent implements Personalization
{
    public function __construct(public ?bool $currentBranch = null, #[SkipDebug] public ?string $branch = null)
    {
        $this->branch = $this->branch();
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.environment');
    }

    public function branch(): ?string
    {
        if (! $this->currentBranch) {
            return null;
        }

        if (($branch = rescue(fn () => File::get(base_path('.git/HEAD')), report: false)) === null) {
            return null;
        }

        $string = str($branch);

        if ($string->contains('ref: refs/heads/')) {
            return $string->replace('ref: refs/heads/', '')->trim();
        }

        return null;
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'outline-none inline-flex items-center border px-2 py-0.5 font-bold',
                'sizes' => [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-md',
                    'lg' => 'text-lg',
                ],
            ],
            'icon' => 'h-3 w-3',
        ]);
    }
}
