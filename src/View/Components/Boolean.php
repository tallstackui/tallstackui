<?php

namespace TallStackUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('boolean')]
class Boolean extends BaseComponent implements Personalization
{
    public function __construct(
        public bool|Closure $boolean = false,
        public ?string $iconWhenTrue = null,
        public ?string $iconWhenFalse = null,
        public ?string $colorWhenTrue = null,
        public ?string $colorWhenFalse = null,
    ) {
        $this->boolean = is_bool($this->boolean) ? $this->boolean : ($this->boolean)();

        $this->iconWhenTrue ??= 'check-circle';
        $this->iconWhenFalse ??= 'check-circle';
        $this->colorWhenTrue ??= 'green';
        $this->colorWhenFalse ??= 'gray';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.boolean');
    }

    public function personalization(): array
    {
        return [];
    }
}
