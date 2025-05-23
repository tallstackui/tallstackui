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
        // TODO en only
        public ?string $locale = 'en-US',
        //        #[SkipDebug]
        //        public ?string $prefix = null,
        //        #[SkipDebug]
        //        public ?string $suffix = null,
    ) {
        // $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.currency');
    }

    public function personalization(): array
    {
        return [];
    }
}
