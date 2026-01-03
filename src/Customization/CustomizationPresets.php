<?php

namespace TallStackUi\Customization;

use TallStackUi\Customization\Presets\RoundedPreset;
use TallStackUi\View\Components\Form\Input;

class CustomizationPresets
{
    public array $on = [];

    public ?RoundedPreset $preset = null;

    public function empty(): bool
    {
        return empty($this->on) && $this->preset === null;
    }

    public function rounded(?array $on = null): void
    {
        $this->on = $on ?? [
            Input::class,
        ];

        $this->preset = new RoundedPreset($this->on);
    }

    public function shadowless(): void
    {
        //
    }

    public function transitionless(): void
    {
        //
    }
}
