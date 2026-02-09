<?php

namespace TallStackUi\Components\Boolean;

use Closure;
use Illuminate\Contracts\View\View;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\BooleanColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('boolean')]
#[ColorsThroughOf(BooleanColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public bool|Closure $boolean = false,
        public ?string $iconWhenTrue = null,
        public ?string $iconWhenFalse = null,
        public ?string $colorWhenTrue = null,
        public ?string $colorWhenFalse = null,
    ) {
        $this->boolean = value($this->boolean);

        $this->iconWhenTrue ??= 'check-circle';
        $this->iconWhenFalse ??= 'check-circle';
        $this->colorWhenTrue ??= 'primary';
        $this->colorWhenFalse ??= 'gray';
    }

    public function blade(): View
    {
        return view('ts-ui::components.boolean.main');
    }

    public function customization(): array
    {
        return ['icon' => 'w-5 h-5'];
    }
}
