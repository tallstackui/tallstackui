<?php

namespace TallStackUi\View\Components\Form\Select\Traits;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\View\Components\Form\Select\Native;

trait Setup
{
    #[SkipDebug]
    public ?string $side = null;

    protected function setup(): void
    {
        $this->options = $this->options instanceof Collection
            ? $this->options->values()->toArray()
            : array_values($this->options);

        $this->select ??= 'label:label|value:value|description:description|image:image';

        if (! $this->select || ($this->options !== [] && ! is_array($this->options[0]))) {
            return;
        }

        $select = array_reduce(
            explode('|', $this->select),
            function ($result, $item) {
                $parts = explode(':', $item, 2);
                if (count($parts) === 2) {
                    $result[$parts[0]] = $parts[1];
                }

                return $result;
            },
            []
        );

        $label = $select['label'] ?? 'label';
        $value = $select['value'] ?? 'value';
        $image = $select['image'] ?? 'image';
        $description = $select['description'] ?? 'description';

        $component = $this instanceof Native ? 'select.native' : 'select.styled';

        $images = array_flip(['image', 'img', 'img_src']);
        $descriptions = array_flip(['description', 'note']);

        $this->options = collect($this->options)
            ->map(function (array $option) use (
                $label,
                $value,
                $image,
                $images,
                $component,
                $description,
                $descriptions
            ): array {
                if (! array_key_exists($label, $option)) {
                    throw new InvalidArgumentException("The $component key [$label] is missing in the options array.");
                }

                if (! array_key_exists($value, $option)) {
                    throw new InvalidArgumentException("The $component [$value] is missing in the options array.");
                }

                $this->grouped = is_array($option[$value]);

                $result = $option;

                $result[$label] = $option[$label];
                $result[$value] = $option[$value];

                if ($image !== 'image' || ! isset($result['image'])) {
                    $result[$image] = $option[$image] ?? current(array_intersect_key($option, $images)) ?: null;
                }

                if ($description !== 'description' || ! isset($result['description'])) {
                    $result[$description] = $option[$description] ?? current(array_intersect_key($option, $descriptions)) ?: null;
                }

                $result['id'] = substr(sha1($label.$value.$description.$image.uniqid()), 14, 12);
                $result['disabled'] = $option['disabled'] ?? false;

                return $result;
            })
            ->toArray();

        $this->selectable = [
            'label' => $label,
            'value' => $value,
            'description' => $description,
            'image' => $image,
        ];
    }
}
