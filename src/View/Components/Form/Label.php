<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.label')]
class Label extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?bool $error = false,
        public ?bool $invalidate = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.label');
    }

    public function personalization(): array
    {
        return [
            'text' => 'block text-sm font-semibold text-gray-600 dark:text-dark-400',
            'asterisk' => 'font-bold not-italic text-red-500',
            'error' => 'text-red-600 dark:text-red-500',
        ];
    }
}
