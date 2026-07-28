<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\Support\Collection;
use TallStackUi\Components\Gallery\Component as Gallery;
use TallStackUi\Support\Runtime\AbstractRuntime;

class GalleryRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var Gallery $component */
        $component = $this->component;

        if (! $component->feature) {
            return [
                'cover' => null,
                'coverIndex' => 0,
                'tiles' => [],
                'remaining' => 0,
            ];
        }

        /** @var Collection<int, array> $images */
        $images = collect($component->images);

        $index = $images->search(fn (array $image): bool => ($image['cover'] ?? false) === true);
        $index = $index === false ? 0 : $index;

        $limit = $component->limit ?? 7;

        return [
            'cover' => $images->get($index),
            'coverIndex' => $index,
            // The original keys are preserved so each thumbnail addresses its
            // own position inside the untouched array the lightbox navigates.
            'tiles' => $images->reject(fn (array $image, int $key): bool => $key === $index)
                ->take($limit - 1)
                ->toArray(),
            'remaining' => max(0, $images->count() - $limit),
        ];
    }
}
