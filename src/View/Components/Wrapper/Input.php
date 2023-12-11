<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;

#[SoftPersonalization('wrapper.input')]
class Input extends Component implements Personalization
{
    public function __construct(
        public ?string $computed = null,
        public ?string $label = null,
        public ?string $id = null,
        public ?string $hint = null,
        public bool $error = false,
        public bool $validate = false,
        public bool $password = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return ['wrapper' => 'relative mt-1 rounded-md shadow-sm'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.wrapper.input');
    }
}
