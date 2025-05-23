<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Colors\Components\EnvironmentColors;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('environment')]
#[ColorsThroughOf(EnvironmentColors::class)]
class Environment extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $square = false,
        public ?bool $round = false,
        public ?bool $withoutBranch = null,
        #[SkipDebug]
        public ?string $branch = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ComponentSlot|string|null $left = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
    ) {
        $this->branch = $this->branch();
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->sm ? 'sm' : 'xs'));
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.environment');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'outline-hidden inline-flex items-center border px-2 py-0.5 font-bold',
                'sizes' => [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-md',
                    'lg' => 'text-lg',
                ],
            ],
        ]);
    }

    private function branch(): ?string
    {
        if ($this->withoutBranch === true) {
            return null;
        }

        $custom = rescue(fn () => app('tallstackui::environment::branch'), report: false);

        if ($custom !== null) {
            return is_callable($custom) ? app()->call($custom) : value($custom); // @phpstan-ignore-line
        }

        if (($branch = rescue(fn () => File::get(base_path('.git/HEAD')), report: false)) === null) {
            return null;
        }

        $string = str($branch);

        if (! $string->contains('ref: refs/heads/')) {
            return null;
        }

        return $string->replace('ref: refs/heads/', '')->trim()->value();
    }
}
