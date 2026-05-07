<?php

namespace TallStackUi\Components\Timeline\Items;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\TimelineColors;
use TallStackUi\Support\Runtime\Components\TimelineItemsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('timeline.items')]
#[ColorsThroughOf(TimelineColors::class)]
#[PassThroughRuntime(TimelineItemsRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $date = null,
        public ?string $icon = null,
        public ?string $color = 'primary',
        #[SkipDebug]
        public ComponentSlot|string|null $marker = null,
        #[SkipDebug]
        public ?string $style = 'solid',
        #[SkipDebug]
        public ?bool $horizontal = false,
        #[SkipDebug]
        public ?bool $alternate = false,
        #[SkipDebug]
        public ?bool $compact = false,
        #[SkipDebug]
        public ?bool $reversed = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.timeline.items');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'flex-vertical' => 'relative flex gap-4 [&:last-child>[data-timeline-line]]:hidden',
                'flex-horizontal' => 'relative flex flex-col items-center gap-2 [&:first-child>[data-timeline-line-left]]:hidden [&:last-child>[data-timeline-line-right]]:hidden',
                'grid-vertical' => 'relative grid grid-cols-[1fr_auto_1fr] items-start gap-4 [&:last-child>[data-timeline-line]]:hidden',
                'grid-horizontal' => 'relative grid grid-rows-[1fr_auto_1fr] justify-items-center gap-2 [&:first-child>[data-timeline-line-left]]:hidden [&:last-child>[data-timeline-line-right]]:hidden',
            ],
            'content' => [
                'base' => 'flex-1',
                'flex-vertical' => 'text-start',
                'flex-horizontal' => 'text-center mt-2',
                'grid-vertical-normal' => 'col-start-3 row-start-1 text-start',
                'grid-vertical-reversed' => 'col-start-1 row-start-1 text-end',
                'grid-horizontal-normal' => 'row-start-3 col-start-1 text-center',
                'grid-horizontal-reversed' => 'row-start-1 col-start-1 text-center',
            ],
            'title' => 'text-sm font-semibold text-gray-900 dark:text-white',
            'description' => 'mt-1 text-sm text-gray-600 dark:text-gray-300',
            'date' => 'text-xs text-gray-500 dark:text-gray-400',
            'line' => [
                'flex-vertical' => 'absolute start-3 top-0 -bottom-6 w-0.5 -translate-x-1/2',
                'flex-vertical-compact' => 'absolute start-3 top-0 bottom-0 w-0.5 -translate-x-1/2',
                'flex-horizontal-left' => 'absolute top-3 -start-3 w-[calc(50%+12px)] h-0.5 -translate-y-1/2',
                'flex-horizontal-left-compact' => 'absolute top-3 start-0 w-1/2 h-0.5 -translate-y-1/2',
                'flex-horizontal-right' => 'absolute top-3 start-1/2 w-[calc(50%+12px)] h-0.5 -translate-y-1/2',
                'flex-horizontal-right-compact' => 'absolute top-3 start-1/2 w-1/2 h-0.5 -translate-y-1/2',
                'grid-vertical' => 'absolute top-0 -bottom-6 left-1/2 w-0.5 -translate-x-1/2',
                'grid-vertical-compact' => 'absolute top-0 bottom-0 left-1/2 w-0.5 -translate-x-1/2',
                'grid-horizontal-left' => 'absolute top-1/2 -start-3 w-[calc(50%+12px)] h-0.5 -translate-y-1/2',
                'grid-horizontal-left-compact' => 'absolute top-1/2 start-0 w-1/2 h-0.5 -translate-y-1/2',
                'grid-horizontal-right' => 'absolute top-1/2 start-1/2 w-[calc(50%+12px)] h-0.5 -translate-y-1/2',
                'grid-horizontal-right-compact' => 'absolute top-1/2 start-1/2 w-1/2 h-0.5 -translate-y-1/2',
            ],
            'marker' => [
                'wrapper' => 'relative z-10 flex h-6 w-6 shrink-0 items-center justify-center rounded-full',
                'grid-vertical' => 'col-start-2 row-start-1',
                'grid-horizontal' => 'row-start-2 col-start-1',
                'bullet' => '',
                'bullet-base' => 'h-2 w-2 rounded-full',
                'icon-size' => 'h-3.5 w-3.5',
                'icon-wrapper' => '',
                'custom' => '',
            ],
        ]);
    }
}
