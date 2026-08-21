<?php

namespace TallStackUi\Support\Runtime\Components;

use Closure;
use TallStackUi\Components\Chart\Component as Chart;
use TallStackUi\Support\Charts\Bars;
use TallStackUi\Support\Charts\Plot;
use TallStackUi\Support\Charts\Scale;
use TallStackUi\Support\Charts\Series;
use TallStackUi\Support\Charts\Slices;
use TallStackUi\Support\Charts\Spline;
use TallStackUi\Support\Colors\CompileColors;
use TallStackUi\Support\Colors\Components\ChartColors;
use TallStackUi\Support\Runtime\AbstractRuntime;

class ChartRuntime extends AbstractRuntime
{
    private const SHAPE = [4.0, 7.0, 5.0, 9.0, 6.0, 8.0, 5.5, 7.5];

    private string $curve = 'smooth';

    private bool $ends = false;

    private float $radius = Bars::RADIUS;

    private bool $slotted = false;

    public function runtime(): array
    {
        /** @var Chart $component */
        $component = $this->component;

        // Read off the component, not $this->data(): the snapshot predates
        // the config defaults CompileConfigurations writes onto the props.
        // The same goes for the colors, merged into the view data later.
        $series = Series::normalize($component->series);
        $type = $component->type ?? 'area';
        $radial = in_array($type, ['pie', 'donut'], true);

        $this->curve = $component->curve ?? 'smooth';
        $this->radius = Bars::RADII[$component->round ?? 'sm'];
        $this->ends = ($component->corners ?? 'all') === 'end';

        if ($this->skeletonized()) {
            return $this->placeholder($type, $radial);
        }

        $this->slotted = ! $radial && Series::slotted($series, $type);

        $palette = $this->palette($series, $radial);
        $ticks = $radial ? [] : $this->ticks($series, $type);
        $secondary = $radial ? [] : $this->ticks($series, $type, 'right');
        $slices = $radial ? $this->slices($series, $type, $palette) : [];
        $plots = $radial ? [] : $this->plots($series, $type, $palette);

        return [
            'radial' => $radial,
            // Stretched, a pie would be an ellipse.
            'aspect' => $radial ? 'xMidYMid meet' : 'none',
            'viewbox' => Plot::viewbox(),
            'bounds' => ['width' => Plot::WIDTH, 'height' => Plot::HEIGHT],
            'plots' => $plots,
            'slices' => $slices,
            'ticks' => $ticks,
            'secondary' => $secondary,
            'captions' => $radial ? [] : $this->captions($series),
            // Rendered invisibly in flow to give the axis column its width.
            'widest' => $this->widest($ticks),
            'widestSecondary' => $this->widest($secondary),
            'entries' => $radial
                ? array_map(static fn (array $slice): array => ['name' => $slice['label'], 'color' => $slice['color']], $slices)
                : array_map(static fn (array $plot): array => ['name' => $plot['name'], 'color' => $plot['color']], $plots),
            'interactive' => (bool) ($component->tooltip || $component->legend),
            'chrome' => [
                'legend' => (bool) $component->legend,
                'tooltip' => (bool) $component->tooltip,
                'markers' => (bool) $component->markers,
            ],
            'fitting' => $component->fit ?? 'thin',
            'interaction' => $this->interaction($series, $type, $palette, $plots, $slices),
        ];
    }

    private function affix(string|int|array|null $value, string $axis): string|int|null
    {
        return is_array($value) ? ($value[$axis] ?? null) : $value;
    }

    private function captions(array $series): array
    {
        /** @var Chart $component */
        $component = $this->component;
        $labels = $component->labels;

        if (blank($labels) || ($length = Series::length($series)) === 0) {
            return [];
        }

        // A curve ends on the edges; a bar owns a slot and is labelled in its middle.
        $slot = Plot::WIDTH / $length;
        $step = $this->slotted ? $slot : ($length > 1 ? Plot::WIDTH / ($length - 1) : 0.0);
        $offset = $this->slotted ? $slot / 2 : 0.0;

        $captions = [];

        foreach (array_slice((array) $labels, 0, $length) as $index => $label) {
            $captions[] = ['label' => (string) $label, 'x' => round($index * $step + $offset, 2)];
        }

        return $captions;
    }

    /**
     * One closed shape per run, so a gap stays open. An unstacked area drops
     * to the baseline; a stacked one closes on the curve below it.
     */
    private function close(array $points, Scale $scale, bool $bottom, array $offsets, array $indexes, int $length, string $curve): string
    {
        $below = $bottom
            ? []
            : Spline::points(array_fill(0, $length, 0.0), $scale, $indexes, $length, $offsets, $this->slotted);

        $shapes = [];

        foreach (Spline::runs($points) as $run) {
            if (($line = Spline::segment($run, $curve)) === '') {
                continue;
            }

            $first = $run[0][0];
            $last = $run[count($run) - 1][0];

            if ($bottom) {
                $baseline = $scale->zero();

                $shapes[] = $line.' L'.$last.','.$baseline.' L'.$first.','.$baseline.' Z';

                continue;
            }

            // The lower edge is the curve beneath, walked back.
            $edge = array_reverse(array_values(array_filter(
                $below,
                static fn (array $point): bool => $point[0] >= $first && $point[0] <= $last
            )));

            $tail = Spline::segment($edge, $curve, reversed: true);

            $shapes[] = $tail === '' ? $line.' Z' : $line.' L'.substr($tail, 1).' Z';
        }

        return implode(' ', $shapes);
    }

    private function edges(array $variants): array
    {
        $edges = [];

        foreach ($variants as $position => $variant) {
            $edges[$variant]['first'] ??= $position;
            $edges[$variant]['last'] = $position;
        }

        return $edges;
    }

    private function format(float $value, string $axis = 'left'): string
    {
        /** @var Chart $component */
        $component = $this->component;

        if ($component->formatter instanceof Closure) {
            return (string) ($component->formatter)($value, $axis);
        }

        $decimals = $this->affix($component->decimals, $axis) ?? (floor($value) === $value ? 0 : 2);

        return $this->affix($component->prefix, $axis)
            .number_format($value, (int) $decimals)
            .$this->affix($component->suffix, $axis);
    }

    private function interaction(array $series, string $type, array $palette, array $plots, array $slices): array
    {
        /** @var Chart $component */
        $component = $this->component;

        $scale = $this->scale($series, $type);

        return [
            'type' => $type,
            'slotted' => $this->slotted,
            'length' => Series::length($series),
            'labels' => array_map('strval', (array) ($component->labels ?? [])),
            'series' => array_map(fn (array $entry, int $index): array => [
                'name' => $entry['name'] ?? 'Series '.($index + 1),
                'color' => $palette[$index] ?? '',
                'data' => $entry['data'],
                'formatted' => array_map(fn (?float $value): ?string => $value === null ? null : $this->format($value, $entry['axis']), $entry['data']),
                'points' => $plots[$index]['points'] ?? [],
            ], $series, array_keys($series)),
            'slices' => array_map(static fn (array $slice): array => [
                'name' => $slice['label'],
                'color' => $slice['color'],
                'value' => $slice['formatted'],
                'percentage' => $slice['percentage'],
                'raw' => $slice['value'],
            ], $slices),
            // Hiding a slice redistributes the angles, so Alpine mirrors the arc geometry.
            'arc' => [
                'center' => Slices::CENTER,
                'radius' => Slices::RADIUS,
                'inner' => $type === 'donut' ? Slices::RADIUS * Slices::HOLE : 0.0,
            ],
            // Beziers are affine invariant, so hiding a series rescales the
            // rest through a transform on the group, not a recomputed path.
            'scale' => ['min' => $scale->min, 'max' => $scale->max, 'flat' => $scale->flat],
            'plot' => ['bottom' => Plot::bottom(), 'band' => Plot::band(), 'width' => Plot::WIDTH],
            'stacked' => (bool) $component->stacked,
            // Skipped where it would mislead: a labelled axis, an anchored
            // baseline, or two domains that one affine pair cannot carry.
            'rescale' => ! $scale->flat
                && ! (bool) $component->stacked
                && ! (bool) $component->grid
                && ! $this->secondary($series)
                && ! $this->slotted,
        ];
    }

    /** One text-* class per series; stroke and gradient stops paint from currentColor. */
    private function palette(array $series, bool $radial): array
    {
        /** @var Chart $component */
        $component = $this->component;

        $map = CompileColors::of($component)['palette'] ?? [];
        $count = $radial ? count($series[0]['data'] ?? []) : count($series);

        $names = $component->colors ?? [];

        if ($names === []) {
            $names = $count <= 1 && ! $radial
                ? [$component->color]
                : array_merge([$component->color], array_diff(ChartColors::SEQUENCE, [$component->color]));
        }

        $resolved = [];

        for ($index = 0; $index < $count; $index++) {
            $name = $names[$index % max(1, count($names))];

            $resolved[] = $map[$name] ?? $map[$component->color] ?? '';
        }

        return $resolved;
    }

    private function placeholder(string $type, bool $radial): array
    {
        $values = $this->shape($this->skeleton(6));

        $payload = [
            'radial' => $radial,
            'aspect' => $radial ? 'xMidYMid meet' : 'none',
            'viewbox' => Plot::viewbox(),
            'slices' => [],
            'bars' => [],
            'stroke' => '',
            'fill' => '',
        ];

        if ($radial) {
            return [...$payload, 'slices' => Slices::of($values, donut: $type === 'donut')];
        }

        $series = Series::normalize($values);
        $length = Series::length($series);
        $scale = Scale::of($values, zero: $type === 'bar');

        if ($type === 'bar') {
            return [...$payload, 'bars' => Bars::of($series, [$scale], $length, $this->radius, $this->ends)[0] ?? []];
        }

        $points = Spline::points($values, $scale, Spline::indexes($series, $length), $length);
        $line = Spline::path($points, $this->curve);

        return [
            ...$payload,
            'stroke' => $line,
            'fill' => $type === 'area' && $line !== ''
                ? $line.' L'.Plot::WIDTH.','.Plot::bottom().' L0,'.Plot::bottom().' Z'
                : '',
        ];
    }

    private function plots(array $series, string $type, array $palette): array
    {
        /** @var Chart $component */
        $component = $this->component;

        $length = Series::length($series);
        $scales = $this->scales($series, $type);
        $indexes = Spline::indexes($series, $length);
        $variants = $this->variants($series, $type);

        $stacked = (bool) $component->stacked;
        $offsets = $stacked ? Bars::offsets($series, $length, $variants) : [];
        $corners = $stacked ? Bars::corners($series, $length, $variants, $this->ends) : [];
        $edges = $this->edges($variants);

        $bars = $stacked
            ? []
            : Bars::of(array_filter($series, static fn (array $entry, int $position): bool => $variants[$position] === 'bar', ARRAY_FILTER_USE_BOTH), $scales, $length, $this->radius, $this->ends);

        $plots = [];

        foreach ($series as $position => $entry) {
            $scale = $scales[$position];
            $variant = $variants[$position];
            $curve = $entry['curve'] ?? $this->curve;

            $points = $variant === 'bar'
                ? []
                : Spline::points($entry['data'], $scale, $indexes, $length, $offsets[$position] ?? [], $this->slotted);

            $plots[] = [
                'name' => $entry['name'] ?? 'Series '.($position + 1),
                'color' => $palette[$position] ?? '',
                'gradient' => 'tsui-chart-'.uniqid(),
                'line' => Spline::path($points, $curve),
                'area' => $variant === 'area'
                    ? $this->close($points, $scale, ! $stacked || $position === $edges[$variant]['first'], $offsets[$position] ?? [], $indexes, $length, $curve)
                    : '',
                'points' => array_values(array_filter($points, static fn (?array $point): bool => $point !== null)),
                'bars' => match (true) {
                    $variant !== 'bar' => [],
                    $stacked => $this->stack($entry, $scale, $length, $offsets[$position] ?? [], $corners[$position] ?? []),
                    default => $bars[$position] ?? [],
                },
            ];
        }

        return $plots;
    }

    private function scale(array $series, string $type, string $axis = 'left'): Scale
    {
        /** @var Chart $component */
        $component = $this->component;

        $bound = Series::on($series, $axis);
        $values = Series::values($bound);

        if ($component->stacked) {
            $values = [...$values, ...Bars::totals($bound, Series::length($bound), $this->variants($bound, $type))];
        }

        // Bars must start at zero to stay proportional; a labelled axis needs round bounds.
        return Scale::of($values, nice: (bool) $component->grid, zero: $this->slotted || (bool) $component->stacked);
    }

    private function scales(array $series, string $type): array
    {
        $resolved = ['left' => $this->scale($series, $type), 'right' => $this->scale($series, $type, 'right')];

        return array_map(static fn (array $entry): Scale => $resolved[$entry['axis']], $series);
    }

    private function secondary(array $series): bool
    {
        return Series::on($series, 'right') !== [];
    }

    private function shape(int $count): array
    {
        $values = [];

        for ($index = 0; $index < $count; $index++) {
            $values[] = self::SHAPE[$index % count(self::SHAPE)];
        }

        return $values;
    }

    private function slices(array $series, string $type, array $palette): array
    {
        /** @var Chart $component */
        $component = $this->component;

        $values = array_map(static fn (?float $value): float => $value ?? 0.0, $series[0]['data'] ?? []);
        $labels = (array) ($component->labels ?? []);

        return array_map(fn (array $slice): array => [
            ...$slice,
            'color' => $palette[$slice['index']] ?? '',
            'label' => isset($labels[$slice['index']]) ? (string) $labels[$slice['index']] : 'Slice '.($slice['index'] + 1),
            'formatted' => $this->format($slice['value']),
        ], Slices::of($values, $type === 'donut'));
    }

    private function stack(array $entry, Scale $scale, int $length, array $offsets, array $corners): array
    {
        $slot = $length > 0 ? Plot::WIDTH / $length : 0.0;
        $band = $slot * (1 - Bars::GUTTER);
        $bars = [];

        for ($index = 0; $index < $length; $index++) {
            if (! isset($entry['data'][$index])) {
                continue;
            }

            $bottom = $scale->y($offsets[$index] ?? 0.0);
            $top = $scale->y(($offsets[$index] ?? 0.0) + $entry['data'][$index]);

            $bar = [
                'x' => round($index * $slot + ($slot - $band) / 2, 2),
                'y' => round(min($top, $bottom), 2),
                'width' => round($band, 2),
                'height' => round(max(abs($bottom - $top), 0.4), 2),
                'index' => $index,
            ];

            $bars[] = [...$bar, 'path' => Bars::path($bar, $corners[$index]['head'] ?? true, $corners[$index]['foot'] ?? true, $this->radius)];
        }

        return $bars;
    }

    /** Both axes pin to the same tick count, so one set of gridlines serves both. */
    private function ticks(array $series, string $type, string $axis = 'left'): array
    {
        /** @var Chart $component */
        $component = $this->component;

        if (! $component->grid || ($axis === 'right' && ! $this->secondary($series))) {
            return [];
        }

        $scale = $this->scale($series, $type, $axis);

        return array_map(fn (float $value): array => [
            'label' => $this->format($value, $axis),
            'y' => $scale->y($value),
        ], $scale->ticks());
    }

    private function variants(array $series, string $type): array
    {
        return array_map(static fn (array $entry): string => $entry['type'] ?? $type, $series);
    }

    private function widest(array $ticks): string
    {
        return array_reduce(
            array_column($ticks, 'label'),
            static fn (string $carry, string $label): string => mb_strlen($label) > mb_strlen($carry) ? $label : $carry,
            ''
        );
    }
}
