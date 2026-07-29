<?php

namespace TallStackUi\Components\Stats;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\SkeletonSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\StatsColors;
use TallStackUi\Support\Runtime\Components\StatsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('stats')]
#[ColorsThroughOf(StatsColors::class)]
#[PassThroughRuntime(StatsRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use SkeletonSetup;

    public function __construct(
        public string|int|null $number = null,
        public ?string $title = null,
        public ComponentSlot|string|null $icon = null,
        public ?string $color = 'primary',
        public ?string $href = null,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?bool $outline = false,
        public ?bool $animated = false,
        public ?int $duration = 1,
        public ?bool $increase = false,
        public ?bool $decrease = false,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
        public bool|int|null $skeleton = null,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null,
        #[SkipDebug]
        public array|Collection|ComponentSlot|null $chart = null,
    ) {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');
        $this->duration = max(0, (int) $this->duration);

        if ($this->chart instanceof Collection) {
            $this->chart = $this->chart->values()->toArray();
        }
    }

    public function blade(): View
    {
        return view($this->skeletonized() ? 'ts-ui::components.stats.skeleton' : 'ts-ui::components.stats.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'dark:bg-dark-700 flex w-full flex-col rounded-lg bg-white shadow-md',
                'first-clickable' => 'cursor-pointer',
                'first-chart' => 'relative isolate',
                'second' => 'mx-4 flex h-full items-center justify-center gap-4',
                'second-no-header' => 'mt-4',
                'second-no-footer' => 'mb-4',
                'third' => 'flex h-12 w-12 items-center justify-center rounded-lg',
            ],
            'slots' => [
                'header' => [
                    'wrapper' => 'mx-2',
                    'text' => 'dark:text-dark-300 p-2 text-xs text-gray-600',
                ],
                'footer' => [
                    'wrapper' => 'mx-2',
                    'text' => 'dark:text-dark-300 p-2 text-xs text-gray-600',
                ],
                'right' => [
                    'increase' => [
                        'icon' => 'arrow-trending-up',
                        'class' => 'w-6 h-6 text-green-500',
                    ],
                    'decrease' => [
                        'icon' => 'arrow-trending-down',
                        'class' => 'w-6 h-6 text-red-500',
                    ],
                ],
            ],
            'chart' => [
                'wrapper' => 'pointer-events-none absolute inset-0 -z-10 overflow-hidden rounded-[inherit] opacity-20 dark:opacity-30',
                'element' => 'h-full w-full',
            ],
            'icon' => 'h-8 w-8',
            'title' => 'dark:text-dark-300 text-sm text-gray-600',
            'number' => 'dark:text-dark-300 text-2xl font-bold leading-none *:m-0',
            'skeleton' => [
                ...$this->blocks(),
                'icon' => 'size-12 rounded-lg',
                'title' => 'h-3 w-24',
                'number' => 'h-6 w-16',
                'header' => 'h-3 w-20',
                'footer' => 'h-3 w-28',
            ],
        ]);
    }

    protected function validate(): void
    {
        $this->guard();

        if (is_int($this->skeleton)) {
            __ts_validation_exception($this, 'The [skeleton] must be a flag: there is nothing to count.');
        }

        if ($this->increase && $this->decrease) {
            __ts_validation_exception($this, 'The [increase] and [decrease] cannot be used together.');
        }
    }
}
