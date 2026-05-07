<?php

namespace TallStackUi\Components\Environment;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\EnvironmentColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('environment')]
#[ColorsThroughOf(EnvironmentColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $square = false,
        public bool|string|null $round = false,
        public ?bool $withoutBranch = null,
        #[SkipDebug]
        public ?string $branch = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $rounded = null,
        #[SkipDebug]
        public ComponentSlot|string|null $left = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
    ) {
        $this->branch = $this->branch();
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->sm ? 'sm' : 'xs'));
        $this->rounded = $this->round === true ? 'full' : (is_string($this->round) ? $this->round : 'md');
    }

    public function blade(): View
    {
        return view('ts-ui::components.environment.main');
    }

    public function customization(): array
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
            'border.radius' => [
                'xs' => 'rounded-xs',
                'sm' => 'rounded-sm',
                'md' => 'rounded-md',
                'lg' => 'rounded-lg',
                'xl' => 'rounded-xl',
                'full' => 'rounded-full',
            ],
            'icon' => [
                'fork-size' => 'w-4 h-4',
            ],
        ]);
    }

    protected function validate(): void
    {
        if (! is_string($this->round)) {
            return;
        }

        $sizes = ['xs', 'sm', 'md', 'lg', 'xl'];

        if (! in_array($this->round, $sizes, true)) {
            __ts_validation_exception($this, 'The [round] must be true or one of: ['.implode(', ', $sizes).'].');
        }
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
