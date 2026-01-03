<?php

namespace TallStackUi\Customization\Presets;

use function Livewire\invade;

class RoundedPreset
{
    public function __construct(public array $applicable)
    {
        //
    }

    public function components(): array
    {
        $regex = '/\brounded-?\w*\b/';

        return collect($this->applicable)
            ->mapWithKeys(function (string $configuration) use ($regex) {
                $customization = rescue(fn () => invade(app($configuration))->customization(), null, false);

                if (! $customization) {
                    return [];
                }

                $filtered = array_filter((array) $customization, fn ($value) => preg_match($regex, $value));

                return $filtered ? [$configuration => $filtered] : [];
            })
            ->filter()
            ->toArray();
    }
}
