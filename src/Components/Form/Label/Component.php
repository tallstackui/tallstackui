<?php

namespace TallStackUi\Components\Form\Label;

use Illuminate\Contracts\View\View;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\LabelRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.label')]
#[PassThroughRuntime(LabelRuntime::class)]
class Component extends TallStackUiComponent implements Customization
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
        return view('ts-ui::components.form.label');
    }

    public function customization(): array
    {
        return [
            'text' => 'dark:text-dark-400 mb-1 block text-sm font-semibold text-gray-600',
            'asterisk' => 'font-bold text-red-500 not-italic',
            'error' => 'text-red-600 dark:text-red-500',
        ];
    }
}
