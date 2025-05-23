<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Support\Runtime\Components\CurrencyRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[PassThroughRuntime(CurrencyRuntime::class)]
class Currency extends TallStackUiComponent
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $clearable = null,
        public ?bool $invalidate = null,
        public ?string $locale = 'en-US',
        public ?int $decimals = 2,
        public ?int $precision = 4,
        public ?bool $indicators = null,
        #[SkipDebug]
        public ?array $symbols = [],
    ) {
        $this->symbols = trans('tallstack-ui::messages.currency');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.currency');
    }
}
