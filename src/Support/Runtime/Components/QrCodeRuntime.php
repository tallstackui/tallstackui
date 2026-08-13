<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Components\QrCode\Component as QrCode;
use TallStackUi\Support\QrCode\Encoder;
use TallStackUi\Support\QrCode\Path;
use TallStackUi\Support\QrCode\Watermark;
use TallStackUi\Support\Runtime\AbstractRuntime;

class QrCodeRuntime extends AbstractRuntime
{
    private const SHAPE = [
        [0, 0, 4, 4], [11, 0, 4, 4], [0, 11, 4, 4],
        [5, 0, 1, 1], [7, 0, 2, 1], [6, 1, 1, 2], [9, 1, 1, 1], [5, 2, 2, 1], [8, 2, 1, 2], [5, 3, 1, 1], [7, 3, 1, 1],
        [0, 5, 1, 1], [2, 5, 2, 1], [1, 6, 1, 2], [3, 7, 1, 1], [0, 8, 2, 1], [2, 9, 1, 1],
        [5, 5, 2, 2], [8, 5, 1, 1], [10, 5, 2, 1], [13, 5, 1, 2], [5, 7, 1, 2], [7, 7, 3, 1],
        [11, 7, 1, 1], [9, 8, 2, 2], [12, 8, 2, 1], [6, 9, 2, 1], [14, 9, 1, 1],
        [5, 11, 1, 2], [7, 11, 2, 1], [10, 11, 1, 1], [12, 11, 2, 2], [6, 13, 2, 2],
        [9, 12, 1, 2], [11, 13, 1, 1], [13, 14, 1, 1], [5, 14, 1, 1],
    ];

    public function runtime(): array
    {
        /** @var QrCode $component */
        $component = $this->component;

        if ($this->skeletonized()) {
            return ['scale' => $component->size, 'blocks' => self::SHAPE, 'viewbox' => '0 0 15 15'];
        }

        $matrix = Encoder::make($component->link, $component->level());
        $mark = $this->watermark($matrix->version);
        $format = $this->format();

        return [
            'scale' => $component->size,
            'viewbox' => Path::viewbox($matrix),
            'path' => Path::of($matrix, $mark['knockout'] ?? []),
            'mark' => $mark,
            'format' => $format,
            'actions' => (bool) $component->copy || $format !== null,
            'export' => [
                'format' => $format,
                // Read from the config rather than $this->data(): the snapshot predates what CompileConfigurations resolves.
                'pixels' => __ts_get_component_configuration(QrCode::class, 'pixels') ?? 1024,
            ],
        ];
    }

    private function format(): ?string
    {
        /** @var QrCode $component */
        $component = $this->component;

        return match (true) {
            is_string($component->download) => $component->download,
            (bool) $component->download => 'png',
            default => null,
        };
    }

    private function watermark(int $version): ?array
    {
        /** @var QrCode $component */
        $component = $this->component;

        if (blank($component->watermark)) {
            return null;
        }

        if ($component->iconic()) {
            return ['type' => 'icon', 'value' => $component->watermark, ...Watermark::icon($version)];
        }

        return ['type' => 'text', 'value' => $component->watermark, ...Watermark::text($version, mb_strlen($component->watermark))];
    }
}
