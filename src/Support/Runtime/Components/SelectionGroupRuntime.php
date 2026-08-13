<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use Illuminate\Support\Arr;
use TallStackUi\Support\Runtime\AbstractRuntime;

class SelectionGroupRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        return [
            ...$bind,
            ...$this->locks(),
            'reference' => $reference = $this->data('id') ?? $bind->get('id') ?? uniqid(),
            'legend' => $this->legend(),
            'name' => $this->name($reference),
            'selected' => $this->selected(),
            'shows' => $this->shows(),
        ];
    }

    /** The legend text and whether the asterisk follows it. */
    private function legend(): array
    {
        $label = $this->data('label');

        if (blank($label)) {
            return ['text' => null, 'asterisk' => false];
        }

        return [
            'text' => str($label)->before(' *')->value(),
            'asterisk' => $this->data('required') === true || str($label)->endsWith(' *'),
        ];
    }

    /** The name shared by every input, without which radios are not a group. */
    private function name(string $reference): string
    {
        $name = $this->data('attributes')->get('name');

        if (filled($name)) {
            return $name;
        }

        return $this->data('type') === 'checkbox' ? $reference.'[]' : $reference;
    }

    /** The selected values, as strings. Only used out of Livewire. */
    private function selected(): array
    {
        if ($this->wireable()) {
            return [];
        }

        $value = $this->data('attributes')->get('value');

        if (blank($value)) {
            return [];
        }

        return array_map(fn (mixed $item): string => (string) $item, Arr::wrap($value));
    }

    /** The parts each variant renders, so one item template serves all four. */
    private function shows(): array
    {
        $variant = $this->data('variant');

        $inline = $variant === 'inline';

        return [
            'control' => ! in_array($variant, ['panel', 'inline'], true),
            'check' => $variant === 'panel',
            'icon' => true,
            'image' => ! $inline,
            'description' => ! $inline,
            'aside' => ! $inline,
            'badge' => ! $inline,
        ];
    }
}
