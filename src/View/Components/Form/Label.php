<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\LabelRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('form.label')]
#[PassThroughRuntime(LabelRuntime::class)]
class Label extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?bool $error = false,
        public ?bool $invalidate = null
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
            'text' => 'dark:text-dark-400 mb-1 block text-sm font-semibold text-gray-600',
            'asterisk' => 'font-bold text-red-500 not-italic',
            'error' => 'text-red-600 dark:text-red-500',
        ];
    }
}
