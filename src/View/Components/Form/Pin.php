<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\View\Components\BaseComponent;

class Pin extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?int $length = 4,
        public ?array $prefix = [],
        public ?bool $clear = null,
        public ?bool $prefixed = null,
        public ?bool $invalidate = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.pin');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'soft-scrollbar flex items-center overflow-x-auto py-0.5',
        ]);
    }
}
