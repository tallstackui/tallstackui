<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Components\Carousel\Component as Carousel;
use TallStackUi\Support\Runtime\AbstractRuntime;

class CarouselRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var Carousel $component */
        $component = $this->component;

        $limit = $component->limit ?? __ts_get_component_configuration(Carousel::class, 'limit') ?? 6;

        return [
            'visible' => $limit,
            'remaining' => count($component->images) > $limit ? count($component->images) - $limit + 1 : 0,
        ];
    }
}
