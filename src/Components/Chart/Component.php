<?php

namespace TallStackUi\Components\Chart;

use Closure;
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
use TallStackUi\Support\Charts\Series;
use TallStackUi\Support\Colors\Components\ChartColors;
use TallStackUi\Support\Runtime\Components\ChartRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('chart')]
#[ColorsThroughOf(ChartColors::class)]
#[PassThroughRuntime(ChartRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use SkeletonSetup;

    public const TYPES = ['area', 'line', 'bar', 'pie', 'donut'];

    public function __construct(
        public Collection|array|null $series = null,
        public Collection|array|null $labels = null,
        public ?string $type = null,
        public ?bool $stacked = null,
        public ?string $color = 'primary',
        public Collection|array|null $colors = null,
        public ?int $height = null,
        public ?bool $grid = null,
        public ?bool $legend = null,
        public ?bool $tooltip = null,
        public ?bool $markers = null,
        public string|array|null $prefix = null,
        public string|array|null $suffix = null,
        public int|array|null $decimals = null,
        public bool|int|null $skeleton = null,
        #[SkipDebug]
        public ?Closure $formatter = null,
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null,
    ) {
        $this->labels = $this->labels === null ? null : collect($this->labels)->values()->toArray();
        $this->colors = $this->colors === null ? null : collect($this->colors)->values()->toArray();
    }

    public function blade(): View
    {
        return view($this->skeletonized() ? 'ts-ui::components.chart.skeleton' : 'ts-ui::components.chart.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex w-full flex-col gap-2',
            'plot' => [
                'wrapper' => 'grid w-full grow grid-cols-[auto_1fr_auto] grid-rows-[1fr_auto]',
                'svg' => 'absolute inset-0 block size-full',
                'line' => 'fill-none stroke-current stroke-2',
                'area' => 'stroke-none',
                'bar' => 'fill-current',
                'slice' => 'stroke-white dark:stroke-dark-700 stroke-[0.5]',
                'markers' => 'pointer-events-none absolute inset-0',
                'marker' => 'dark:ring-dark-700 absolute size-2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-current ring-2 ring-white',
                'grid' => 'stroke-gray-200 dark:stroke-dark-600 stroke-[0.4]',
                'crosshair' => 'stroke-gray-300 dark:stroke-dark-500 stroke-[0.5]',
            ],
            'axis' => [
                'y' => [
                    'wrapper' => 'relative shrink-0 pr-2 empty:pr-0',
                    'label' => 'dark:text-dark-400 absolute right-2 -translate-y-1/2 whitespace-nowrap text-[0.65rem] leading-none text-gray-500',
                    'right' => [
                        'wrapper' => 'relative shrink-0 pl-2 empty:pl-0',
                        'label' => 'dark:text-dark-400 absolute left-2 -translate-y-1/2 whitespace-nowrap text-[0.65rem] leading-none text-gray-500',
                    ],
                ],
                'x' => [
                    'wrapper' => 'relative h-4 w-full empty:h-0',
                    'label' => 'dark:text-dark-400 absolute -translate-x-1/2 whitespace-nowrap text-[0.65rem] leading-none text-gray-500',
                ],
            ],
            'legend' => [
                'wrapper' => 'flex flex-wrap items-center justify-center gap-x-4 gap-y-1',
                'item' => 'flex cursor-pointer items-center gap-1.5 text-xs transition',
                'off' => 'opacity-40',
                'dot' => 'size-2.5 shrink-0 rounded-sm bg-current',
                'text' => 'dark:text-dark-300 text-gray-600',
            ],
            'tooltip' => [
                'wrapper' => 'dark:bg-dark-800 dark:ring-dark-600 pointer-events-none absolute z-10 rounded-md bg-white px-2.5 py-1.5 shadow-lg ring-1 ring-gray-200',
                'title' => 'dark:text-dark-200 mb-1 text-xs font-medium text-gray-700',
                'row' => 'flex items-center gap-1.5 text-xs whitespace-nowrap',
                'dot' => 'size-2 shrink-0 rounded-sm bg-current',
                'name' => 'dark:text-dark-400 text-gray-500',
                'value' => 'dark:text-dark-200 ml-auto pl-2 font-medium text-gray-700',
            ],
            'slots' => [
                'header' => 'dark:text-dark-300 text-sm font-medium text-gray-700',
                'footer' => 'dark:text-dark-400 text-xs text-gray-500',
            ],
            'opacity' => [
                'from' => '0.35',
                'to' => '0',
            ],
            'skeleton' => [
                ...$this->blocks(),
                'fill' => 'fill-gray-200 dark:fill-dark-600',
                'stroke' => 'fill-none stroke-gray-200 stroke-2 dark:stroke-dark-600',
                'header' => 'h-4 w-32',
                'footer' => 'h-3 w-24',
            ],
        ]);
    }

    protected function validate(): void
    {
        $this->guard();

        // The series is the content, and a placeholder stands in for
        // content that does not exist yet.
        if (! $this->skeletonized() && ($violation = Series::violation($this->series))) {
            __ts_validation_exception($this, $violation);
        }

        if ($this->type !== null && ! in_array($this->type, self::TYPES, true)) {
            __ts_validation_exception($this, 'The [type] must be one of: '.implode(', ', self::TYPES).'.');
        }

        if ($this->height !== null && $this->height < 1) {
            __ts_validation_exception($this, 'The [height] must be greater than 0.');
        }

        foreach (['prefix' => $this->prefix, 'suffix' => $this->suffix, 'decimals' => $this->decimals] as $name => $value) {
            if (is_array($value) && array_diff(array_keys($value), Series::AXES) !== []) {
                __ts_validation_exception($this, 'The ['.$name.'] must only use the keys: '.implode(', ', Series::AXES).'.');
            }
        }

        foreach (is_array($this->decimals) ? $this->decimals : [$this->decimals] as $decimals) {
            if ($decimals !== null && (! is_int($decimals) || $decimals < 0)) {
                __ts_validation_exception($this, 'The [decimals] must be an integer greater than or equal to 0.');
            }
        }

        if ($this->stacked && in_array($this->type, ['pie', 'donut', 'line'], true)) {
            __ts_validation_exception($this, 'The [stacked] cannot be used with the ['.$this->type.'] type.');
        }

        if ($this->grid && in_array($this->type, ['pie', 'donut'], true)) {
            __ts_validation_exception($this, 'The [grid] cannot be used with the ['.$this->type.'] type.');
        }

        if ($this->stacked && Series::on(Series::normalize($this->series), 'right') !== []) {
            __ts_validation_exception($this, 'The [stacked] cannot be used together with a secondary axis.');
        }
    }
}
