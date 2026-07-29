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
    /** Invented values, so the placeholder keeps the proportions of a real plot. */
    private const SHAPE = [4.0, 7.0, 5.0, 9.0, 6.0, 8.0, 5.5, 7.5];

    public function runtime(): array
    {
        /** @var Chart $component */
        $component = $this->component;

        // Read off the component, not $this->data(): the snapshot predates
        // the config defaults CompileConfigurations writes onto the props.
        $series = Series::normalize($component->series);
        $type = $component->type ?? 'area';
        $radial = in_array($type, ['pie', 'donut'], true);

        if ($this->skeletonized()) {
            return $this->placeholder($type, $radial);
        }

        $palette = $this->palette($series, $radial);
        $ticks = $radial ? [] : $this->ticks($series, $type);
        $secondary = $radial ? [] : $this->ticks($series, $type, 'right');
        $slices = $radial ? $this->slices($series, $type, $palette) : [];
        $plots = $radial ? [] : $this->plots($series, $type, $palette);

        return [
            // Named [variant] and not [type]: Laravel applies the component
            // data after our own, so a runtime key sharing a prop name would
            // be silently overwritten by the raw, possibly null, prop.
            'variant' => $type,
            'radial' => $radial,
            // A pie drawn into a stretched viewBox would be an ellipse, so it
            // is the one type that has to keep its aspect ratio.
            'aspect' => $radial ? 'xMidYMid meet' : 'none',
            'viewbox' => Plot::viewbox(),
            'bounds' => ['width' => Plot::WIDTH, 'height' => Plot::HEIGHT],
            'plots' => $plots,
            'slices' => $slices,
            'ticks' => $ticks,
            'secondary' => $secondary,
            'captions' => $radial ? [] : $this->captions($series, $type),
            // Rendered invisibly in flow, which is what gives each axis
            // column its width without measuring anything.
            'widest' => $this->widest($ticks),
            'widestSecondary' => $this->widest($secondary),
            'entries' => $radial
                ? array_map(static fn (array $slice): array => ['name' => $slice['label'], 'color' => $slice['color']], $slices)
                : array_map(static fn (array $plot): array => ['name' => $plot['name'], 'color' => $plot['color']], $plots),
            // Read through the runtime, not straight off the props: the view
            // data was captured before CompileConfigurations wrote the config
            // defaults onto them, so a global flag would never reach the
            // markup. The keys are renamed for the same reason.
            'interactive' => (bool) ($component->tooltip || $component->legend),
            'chrome' => [
                'legend' => (bool) $component->legend,
                'tooltip' => (bool) $component->tooltip,
                'markers' => (bool) $component->markers,
            ],
            'interaction' => $this->interaction($series, $type, $palette, $plots, $slices),
        ];
    }

    /** A scalar applies to both axes; an array picks the side. */
    private function affix(string|int|array|null $value, string $axis): string|int|null
    {
        return is_array($value) ? ($value[$axis] ?? null) : $value;
    }

    private function captions(array $series, string $type): array
    {
        /** @var Chart $component */
        $component = $this->component;
        $labels = $component->labels;

        if (blank($labels) || ($length = Series::length($series)) === 0) {
            return [];
        }

        // A curve puts its first and last point on the edges, so labels sit on
        // the same edges. A bar owns a slot, so its label belongs in the middle
        // of that slot instead.
        $slot = Plot::WIDTH / $length;
        $step = $type === 'bar' ? $slot : ($length > 1 ? Plot::WIDTH / ($length - 1) : 0.0);
        $offset = $type === 'bar' ? $slot / 2 : 0.0;

        $captions = [];

        foreach (array_slice((array) $labels, 0, $length) as $index => $label) {
            $captions[] = ['label' => (string) $label, 'x' => round($index * $step + $offset, 2)];
        }

        return $captions;
    }

    /**
     * Close a curve into a fillable shape. An unstacked area drops to the
     * baseline; a stacked one closes on the curve below it, so the bands read
     * as separate layers instead of overlapping washes.
     */
    private function close(string $line, array $points, Scale $scale, bool $stacked, array $offsets, int $position, array $indexes, int $length): string
    {
        if ($line === '' || $points === []) {
            return '';
        }

        $last = $points[count($points) - 1][0];
        $first = $points[0][0];

        if (! $stacked || $position === 0) {
            $baseline = $scale->zero();

            return $line.' L'.$last.','.$baseline.' L'.$first.','.$baseline.' Z';
        }

        $below = Spline::points(
            array_fill(0, $length, 0.0),
            $scale,
            array_reverse($indexes),
            $length,
            $offsets[$position]
        );

        return $line.' L'.implode(' L', array_map(static fn (array $point): string => $point[0].','.$point[1], $below)).' Z';
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

    /**
     * The curve never crosses over to Alpine: only the values and the scale
     * that produced them do.
     */
    private function interaction(array $series, string $type, array $palette, array $plots, array $slices): array
    {
        /** @var Chart $component */
        $component = $this->component;

        $scale = $this->scale($series, $type);

        return [
            'type' => $type,
            'length' => Series::length($series),
            'labels' => array_map('strval', (array) ($component->labels ?? [])),
            'series' => array_map(fn (array $entry, int $index): array => [
                'name' => $entry['name'] ?? 'Series '.($index + 1),
                'color' => $palette[$index] ?? '',
                'data' => $entry['data'],
                'formatted' => array_map(fn (float $value): string => $this->format($value, $entry['axis']), $entry['data']),
                'points' => $plots[$index]['points'] ?? [],
            ], $series, array_keys($series)),
            'slices' => array_map(static fn (array $slice): array => [
                'name' => $slice['label'],
                'color' => $slice['color'],
                'value' => $slice['formatted'],
                'percentage' => $slice['percentage'],
                'raw' => $slice['value'],
            ], $slices),
            // Hiding a slice redistributes the remaining angles, which is a
            // genuine recomputation rather than the affine transform a curve
            // gets away with. The geometry is cheap enough to mirror.
            'arc' => [
                'center' => Slices::CENTER,
                'radius' => Slices::RADIUS,
                'inner' => $type === 'donut' ? Slices::RADIUS * Slices::HOLE : 0.0,
            ],
            // Rescaling a domain is an affine map in y, and Beziers are affine
            // invariant, so toggling a series off is a transform on the group
            // rather than a recomputed path.
            'scale' => ['min' => $scale->min, 'max' => $scale->max, 'flat' => $scale->flat],
            'plot' => ['bottom' => Plot::bottom(), 'band' => Plot::band(), 'width' => Plot::WIDTH],
            'stacked' => (bool) $component->stacked,
            // Two axes mean two domains, so a single affine pair could not
            // rescale both, and the labelled rows would stop matching anyway.
            'rescale' => ! $scale->flat
                && ! (bool) $component->stacked
                && ! (bool) $component->grid
                && ! $this->secondary($series)
                && $type !== 'bar',
        ];
    }

    /**
     * One Tailwind text-* class per series, so each group can paint its stroke
     * and its gradient stops from a single currentColor.
     */
    private function palette(array $series, bool $radial): array
    {
        /** @var Chart $component */
        $component = $this->component;

        // Resolved here rather than read from $this->data(): the compiled
        // colors are merged into the view data after CompileRuntime already
        // received its snapshot, so they are not visible from in here.
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
            'variant' => $type,
            'radial' => $radial,
            'aspect' => $radial ? 'xMidYMid meet' : 'none',
            'viewbox' => Plot::viewbox(),
            'slices' => [],
            'bars' => [],
            'line' => '',
            'area' => '',
        ];

        if ($radial) {
            return [...$payload, 'slices' => Slices::of($values, donut: $type === 'donut')];
        }

        $series = Series::normalize($values);
        $length = Series::length($series);
        $scale = Scale::of($values, zero: $type === 'bar');

        if ($type === 'bar') {
            return [...$payload, 'bars' => Bars::of($series, [$scale], $length)[0] ?? []];
        }

        $points = Spline::points($values, $scale, Spline::indexes($series, $length), $length);
        $line = Spline::path($points);

        return [
            ...$payload,
            'line' => $line,
            'area' => $type === 'area' && $line !== ''
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
        $stacked = (bool) $component->stacked;
        $offsets = $stacked ? Bars::offsets($series, $length) : [];
        $bars = $type === 'bar' && ! $stacked ? Bars::of($series, $scales, $length) : [];

        $plots = [];

        foreach ($series as $position => $entry) {
            $scale = $scales[$position];

            $points = $type === 'bar'
                ? []
                : Spline::points($entry['data'], $scale, $indexes, $length, $offsets[$position] ?? []);

            $line = Spline::path($points);

            $plots[] = [
                'name' => $entry['name'] ?? 'Series '.($position + 1),
                'color' => $palette[$position] ?? '',
                'gradient' => 'tsui-chart-'.uniqid(),
                'line' => $line,
                'area' => $type === 'area' ? $this->close($line, $points, $scale, $stacked, $offsets, $position, $indexes, $length) : '',
                'points' => $points,
                'bars' => $stacked
                    ? $this->stack($entry, $scale, $length, $offsets[$position] ?? [])
                    : ($bars[$position] ?? []),
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
            $length = Series::length($bound);
            $totals = array_fill(0, max(1, $length), 0.0);

            foreach ($bound as $entry) {
                foreach ($totals as $index => $total) {
                    $totals[$index] = $total + ($entry['data'][$index] ?? 0.0);
                }
            }

            $values = [...$values, ...$totals];
        }

        // Bars have to start at zero or their length stops being proportional
        // to their value, and a labelled axis needs round bounds to read well.
        return Scale::of($values, nice: (bool) $component->grid, zero: $type === 'bar' || (bool) $component->stacked);
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

        $values = $series[0]['data'] ?? [];
        $labels = (array) ($component->labels ?? []);

        return array_map(fn (array $slice): array => [
            ...$slice,
            'color' => $palette[$slice['index']] ?? '',
            'label' => isset($labels[$slice['index']]) ? (string) $labels[$slice['index']] : 'Slice '.($slice['index'] + 1),
            'formatted' => $this->format($slice['value']),
        ], Slices::of($values, $type === 'donut'));
    }

    private function stack(array $entry, Scale $scale, int $length, array $offsets): array
    {
        /** @var Chart $component */
        $component = $this->component;

        if ($component->type !== 'bar') {
            return [];
        }

        $slot = $length > 0 ? Plot::WIDTH / $length : 0.0;
        $band = $slot * (1 - Bars::GUTTER);
        $bars = [];

        for ($index = 0; $index < $length; $index++) {
            if (! array_key_exists($index, $entry['data'])) {
                continue;
            }

            $bottom = $scale->y($offsets[$index] ?? 0.0);
            $top = $scale->y(($offsets[$index] ?? 0.0) + $entry['data'][$index]);

            $bars[] = [
                'x' => round($index * $slot + ($slot - $band) / 2, 2),
                'y' => round(min($top, $bottom), 2),
                'width' => round($band, 2),
                'height' => round(max(abs($bottom - $top), 0.4), 2),
                'index' => $index,
            ];
        }

        return $bars;
    }

    /**
     * Both axes are pinned to the same tick count, so one set of gridlines
     * lines up with either side.
     */
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

    private function widest(array $ticks): string
    {
        return array_reduce(
            array_column($ticks, 'label'),
            static fn (string $carry, string $label): string => mb_strlen($label) > mb_strlen($carry) ? $label : $carry,
            ''
        );
    }
}
