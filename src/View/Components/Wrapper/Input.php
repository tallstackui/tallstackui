<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('wrapper.input')]
class Input extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $property = null,
        public ?string $label = null,
        public ?string $id = null,
        public ?string $hint = null,
        public ?bool $invalidate = null,
        public ?bool $error = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.wrapper.input');
    }

    public function personalization(): array
    {
        return ['wrapper' => 'relative mt-1 rounded-md shadow-sm'];
    }
}
