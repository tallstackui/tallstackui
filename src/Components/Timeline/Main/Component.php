<?php

namespace TallStackUi\Components\Timeline\Main;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\TimelineColors;
use TallStackUi\Support\Runtime\Components\TimelineRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('timeline')]
#[ColorsThroughOf(TimelineColors::class)]
#[PassThroughRuntime(TimelineRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public array|Collection|null $items = null,
        public ?bool $horizontal = false,
        public ?bool $alternate = false,
        public ?bool $compact = false,
        public ?string $color = 'primary',
        public ?string $style = 'solid',
    ) {
        if ($this->items instanceof Collection) {
            $this->items = $this->items->toArray();
        }
    }

    public function blade(): View
    {
        return view('ts-ui::components.timeline.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'vertical' => 'flex flex-col gap-6',
                'vertical-compact' => 'flex flex-col',
                'horizontal' => 'flex flex-row gap-6 overflow-auto',
                'horizontal-compact' => 'flex flex-row overflow-auto',
            ],
        ]);
    }

    protected function validate(): void
    {
        if (! in_array($this->style, ['solid', 'light', 'outline'], true)) {
            __ts_validation_exception($this, 'The [style] prop must be one of: solid, light, outline.');
        }
    }
}
