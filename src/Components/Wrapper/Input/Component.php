<?php

namespace TallStackUi\Components\Wrapper\Input;

use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('wrapper.input')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $property = null,
        public ComponentSlot|string|null $label = null,
        public ?string $id = null,
        public ?string $hint = null,
        public ?bool $invalidate = null,
        public ?bool $error = false,
        public ?bool $clearable = null,
    ) {
        $this->invalidate ??= config('ts-ui.invalidate_global') ?? false;
    }

    public function blade(): View
    {
        return view('ts-ui::components.wrapper.input');
    }

    public function customization(): array
    {
        return ['wrapper' => 'relative rounded-md'];
    }
}
