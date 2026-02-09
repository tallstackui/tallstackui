<?php

namespace TallStackUi\Components\Tooltip;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Exceptions\InvalidSelectedPositionException;
use TallStackUi\Support\Colors\Components\TooltipColors;
use TallStackUi\Support\Concerns\BuildRawIcon;
use TallStackUi\Support\Icons\IconGuideMap;
use TallStackUi\Support\Runtime\Components\TooltipRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('tooltip')]
#[ColorsThroughOf(TooltipColors::class)]
#[PassThroughRuntime(TooltipRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use BuildRawIcon;

    /** @throws Exception */
    public function __construct(
        public ?string $text = null,
        public ?string $icon = 'question-mark-circle',
        public string $color = 'primary',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $position = 'top',
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public bool $internal = true,
    ) {
        $this->icon = IconGuideMap::internal($this->icon);
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->xs ? 'xs' : 'sm'));
    }

    public function blade(): View
    {
        return view('ts-ui::components.tooltip.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex',
            'sizes' => [
                'xs' => 'h-4 w-4',
                'sm' => 'h-5 w-5',
                'md' => 'h-6 w-6',
                'lg' => 'h-7 w-7',
            ],
        ]);
    }

    /** @throws InvalidSelectedPositionException */
    protected function validate(): void
    {
        InvalidSelectedPositionException::validate(static::class, $this->position);
    }
}
