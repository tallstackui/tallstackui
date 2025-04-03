<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\InputSelectorRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.input.selector')]
#[PassThroughRuntime(InputSelectorRuntime::class)]
class InputSelector extends TallStackUiComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $right = null,
        public ?bool $invalidate = null,
        public ?ComponentSlot $selector = null,
        #[SkipDebug]
        public ComponentSlot|string|null $prefix = null,
        #[SkipDebug]
        public ComponentSlot|string|null $suffix = null,
        #[SkipDebug]
        public ?string $side = null,
    ) {
        $this->side = $this->right ? 'right' : 'left';

        Cache::driver('array')->put('tallstackui::form::input-selector::side', $this->side);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.input-selector');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }
}
