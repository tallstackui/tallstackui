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
        //
    }

    public function blade(): View
    {
        return view()->file(__DIR__.'/view.blade.php');
    }

    public function customization(): array
    {
        return ['wrapper' => 'relative rounded-md shadow-sm'];
    }
}
