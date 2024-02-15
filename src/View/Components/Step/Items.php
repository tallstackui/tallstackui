<?php

namespace TallStackUi\View\Components\Step;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('step.items')]
class Items extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $step = null,
        public ?string $title = null, 
        public ?string $description = null, 
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.step.items');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }
}
