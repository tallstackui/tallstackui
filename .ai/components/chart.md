# TallStackUI: Chart

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A dependency-free chart rendered as inline SVG. Every path, rectangle and arc is
computed server-side and shipped as markup, so there is no charting library and
nothing to hydrate. Alpine is attached only when the chart is interactive, and
its code ships in its own bundle rather than in the main one.

## Basic Usage

```blade
{{-- A flat list of numbers is a single unnamed series --}}
<x-chart :series="[10, 40, 25, 60, 30, 80]" />
```

```blade
{{-- Several named series share one scale, so they compare at a glance --}}
<x-chart :labels="['Jan', 'Fev', 'Mar', 'Abr']"
         :series="[
             ['name' => '2026', 'data' => [10, 40, 25, 60]],
             ['name' => '2025', 'data' => [8, 30, 33, 41]],
         ]"
         grid
         legend
         tooltip
         markers
         prefix="R$ " />
```

```blade
<x-chart :series="$revenue" :labels="$months" type="bar" grid prefix="R$ " height="240" />
<x-chart :series="$split" :labels="$sources" type="donut" legend tooltip />
```

```blade
{{-- Inside a card, paddingless lets the plot bleed to the edges --}}
<x-card paddingless>
    <x-slot:header>Balanço Mensal</x-slot:header>
    <x-chart :series="$revenue" :labels="$months" grid prefix="R$ " class="p-4" />
</x-card>
```

## Attributes

| Attribute | Type                    | Default   | Description                                                                                                                                                |
|-----------|-------------------------|-----------|------------------------------------------------------------------------------------------------------------------------------------------------------------|
| series    | array\|Collection\|null | null      | Values to plot, flat or grouped. Required                                                                                                                  |
| labels    | array\|Collection\|null | null      | Horizontal axis captions, or slice names on radial types                                                                                                   |
| type      | string\|null            | 'area'    | One of: area, line, bar, pie, donut                                                                                                                        |
| area      | bool\|null              | null      | Shorthand for `type="area"`. See [Type flags](#type-flags)                                                                                                 |
| line      | bool\|null              | null      | Shorthand for `type="line"`                                                                                                                                |
| bar       | bool\|null              | null      | Shorthand for `type="bar"`                                                                                                                                 |
| pie       | bool\|null              | null      | Shorthand for `type="pie"`                                                                                                                                 |
| donut     | bool\|null              | null      | Shorthand for `type="donut"`                                                                                                                               |
| stacked   | bool\|null              | null      | Stacks series instead of overlaying them. Area and bar only                                                                                                |
| color     | string\|null            | 'primary' | Base color, and the first of the cycled palette                                                                                                            |
| colors    | array\|Collection\|null | null      | Explicit color names, cycled across series or slices                                                                                                       |
| height    | int\|null               | null      | Minimum rendered height in pixels, falling back to the config                                                                                              |
| grid      | bool\|null              | null      | Horizontal gridlines plus a labelled vertical axis                                                                                                         |
| legend    | bool\|null              | null      | Series names with a color swatch. Clicking one toggles it                                                                                                  |
| tooltip   | bool\|null              | null      | Crosshair and a tooltip following the pointer                                                                                                              |
| markers   | bool\|null              | null      | A dot on every plotted point                                                                                                                               |
| fit       | string\|null            | 'thin'    | How the horizontal axis labels avoid overlapping on narrow plots: thin, rotate or stagger. See [Axis labels on narrow plots](#axis-labels-on-narrow-plots) |
| prefix    | string\|array\|null     | null      | Prepended to formatted values. Per axis when an array                                                                                                      |
| suffix    | string\|array\|null     | null      | Appended to formatted values. Per axis when an array                                                                                                       |
| decimals  | int\|array\|null        | null      | Decimal places. Defaults to 0 for whole numbers, 2 otherwise                                                                                               |
| formatter | Closure\|null           | null      | Formats every value, winning over the three above                                                                                                          |
| skeleton  | bool\|int\|null         | null      | Renders a structural placeholder instead of the plot. A bare flag draws 6 points; an integer sets the count. See [Skeleton](#skeleton)                     |

### Type flags

Every type also answers to a flag of its own name, so the common case loses the
attribute:

```blade
<x-chart :series="$revenue" line />
<x-chart :series="$split" :labels="$sources" donut legend />
```

`type` is untouched and keeps working, including alongside a flag that says the
same thing. Two flags at once throw, and so does a flag beside a `type` that
contradicts it.

The per-series `type` stays a string — a key inside `:series` has no attribute
to be a flag of:

```blade
<x-chart bar stacked :series="[
    ['name' => 'Novos', 'data' => $new],
    ['name' => 'Total', 'data' => $total, 'type' => 'line'],
]" />
```

### Formatting

`prefix`, `suffix` and `decimals` cover the common case. Anything beyond it —
a locale, a currency, a rule that changes with the data — takes a closure:

```blade
<x-chart :series="$revenue"
         grid
         :formatter="fn (float $value) => 'R$ '.number_format($value, 2, ',', '.')" />
```

```blade
{{-- Or through Laravel's own helper --}}
<x-chart :series="$revenue" grid :formatter="fn (float $value) => Number::currency($value, 'BRL', 'pt_BR')" />
```

The axis arrives as a second argument, so two axes can read differently:

```blade
<x-chart :formatter="fn (float $value, string $axis) => $axis === 'right'
             ? $value.' un'
             : Number::currency($value, 'BRL', 'pt_BR')"
         :series="[
             ['name' => 'Receita', 'data' => $revenue],
             ['name' => 'Pedidos', 'data' => $orders, 'axis' => 'right'],
         ]"
         grid />
```

Every displayed number is formatted server-side — the axis labels and the
tooltip payload alike — so the closure never has to cross over to JavaScript.
The one exception is the percentage a radial tooltip shows: hiding a slice
redistributes the circle, so it is recomputed in the browser and does not pass
through the formatter.

### Secondary axis

A series can bind itself to a second vertical axis when its magnitude would
flatten everything else against a shared scale:

```blade
<x-chart :labels="$months"
         :series="[
             ['name' => 'Receita', 'data' => [1200, 1900, 1500]],
             ['name' => 'Pedidos', 'data' => [8, 14, 11], 'axis' => 'right'],
         ]"
         grid
         :prefix="['left' => 'R$ ']"
         :suffix="['right' => ' un']" />
```

Both axes are pinned to the same tick count, so a single set of gridlines lines
up with either side and neither can be misread. Formatting is resolved per axis:
a scalar `prefix`, `suffix` or `decimals` applies to both, an array picks the
side.

Legend rescaling is disabled while a secondary axis exists, since one affine
pair cannot carry two domains.

### Combining Types

A series can be drawn as something other than the chart it lives in, which is
what puts a trend line over a stack of bars:

```blade
<x-chart :labels="$months"
         type="bar"
         stacked
         :series="[
             ['name' => 'Novos', 'data' => $new],
             ['name' => 'Recorrentes', 'data' => $returning],
             ['name' => 'Total', 'data' => $total, 'type' => 'line'],
         ]"
         grid
         legend
         tooltip />
```

`type` on a series accepts `area`, `line` and `bar` and falls back to the
chart's own. A radial chart takes no override, and no series can become one.

A single bar anywhere divides the horizontal axis into slots, so the curves,
the axis captions, the crosshair and the pointer all read from the middle of a
slot rather than from the edges. Left on the edges a curve would sit half a
slot out of line with the bars underneath it.

Stacking accumulates within each type — bars pile onto bars, areas onto areas —
and anything drawn over them keeps its own values. The total line above is
therefore a series you pass rather than something derived, and it appears in
the legend and the tooltip like any other.

It also accumulates within each sign, so a negative value hangs below the axis
on a running total of its own instead of pulling the positive stack down.

Legend rescaling is disabled while a bar is on the plot, whose baseline is
anchored.

### Rounded corners

Bars are drawn as paths rather than rectangles, because SVG rounds all four
corners of a `rect` at once. Inside a stack only the two ends of the column
round — an arc on both sides of a seam pulls the two segments apart and the
card shows through the gap. Everything else keeps the corner it had.

The axis is an end only while the column stops there. A stack that carries on
past zero meets it like any other seam, so its ends are the extremes of the
whole column rather than one pair on either side of the line.

The radius shrinks to fit whatever it is applied to, so the hairline a zero
value renders as never folds through itself. That hairline never counts as the
end of a column either, or it would take the rounding and leave the visible
segment above it square.

## Slots

| Slot   | Description                              |
|--------|------------------------------------------|
| header | Rendered above the plot, outside the SVG |
| footer | Rendered below the legend                |

The component deliberately ships no card of its own, so it can serve both as a
standalone chart inside `<x-card>` and as the background layer of `<x-stats>`.

## Configuration

```php
// config/tallstackui.php
'components' => [
    'chart' => [
        \TallStackUi\Components\Chart\Component::class,
        [
            'height' => 240,
            'grid' => false,
            'legend' => false,
            'tooltip' => false,
            'markers' => false,
            'fit' => 'thin',
        ],
    ],
],
```

Presentation only, and each one is only consulted when the matching attribute
is absent. `type` is deliberately absent: a dashboard mixes bars, lines and
pies, so the type stays a per-chart decision rather than an application-wide
one.

`grid` is the one exception: a radial type has no axis to label, so a global
`true` is dropped there rather than throwing. Passing `grid` explicitly on a
`pie` or a `donut` still throws — the difference is between an application-wide
preference and a call site asking for something impossible.

## Behaviour

### Interpolation

Monotone cubic (Fritsch-Carlson), not Catmull-Rom. The curve is guaranteed never
to leave the range of the data it passes through, so the drawn minimum and
maximum equal the series minimum and maximum exactly.

A plain Catmull-Rom spline overshoots by up to 7% of the plot height on the
common flat-then-jump series. On an area chart that is not cosmetic: the fill
closes on the baseline, so a curve dipping below it self-intersects and hangs a
lobe under the chart, besides drawing a value lower than any in the data.

The cost is that peaks read as smooth domes rather than sharp flicks, because a
zero derivative at a local extremum is what makes the guarantee hold.

### Scaling and text

The plot uses a fixed `viewBox` with `preserveAspectRatio="none"`, so it
stretches to fill its box. Cubic Beziers are affine invariant, so the rendered
curve is the exact image of the computed one, and the stroke carries
`vector-effect="non-scaling-stroke"` to keep its width constant and its round
caps circular under that non-uniform scaling.

Nothing textual or circular lives inside the SVG, because both would be
distorted by that same stretch. Axis labels and point markers are HTML
positioned over the plot, which also gives them Tailwind typography and dark
mode for free.

### Axis labels on narrow plots

Thirty captions fit across a desktop plot and pile onto each other on a phone.
The server cannot know how wide the plot will be, so whenever `labels` exist
the horizontal axis carries a small Alpine piece of its own — independent from
`tooltip` and `legend` — that measures the axis and its widest caption and
keeps the shown labels from touching. It re-measures on resize, after a
Livewire update and once web fonts have loaded. Hidden labels keep their box
through `axis.x.off` (`invisible`) rather than leaving the flow, which is what
keeps them measurable.

`fit` picks how the labels make room:

```blade
<x-chart :series="$sent" :labels="$days" fit="rotate" />
```

| Fit       | Behaviour                                                                                                  |
|-----------|------------------------------------------------------------------------------------------------------------|
| `thin`    | Shows every *n*-th label from the first one. The axis keeps its height. Default                            |
| `rotate`  | Slants every label by -45°, then thins only what still collides. The axis grows to hold the slanted labels |
| `stagger` | Alternates the labels over two rows, thinning each row on its own. Text stays upright; the axis doubles    |

The default comes from `fit` in the configuration, so a mobile-first
application can switch every chart at once.

### Color

Each series is wrapped in a `<g>` carrying its own `text-*` class, and both the
stroke and the gradient stops paint from `currentColor`. A gradient resolves
`currentColor` against its own ancestors, not against the element referencing it,
which is why the class sits on the group rather than on the path.

Each gradient id is unique per instance: a shared one would make every chart on
the page paint with the first chart's color, since `url(#id)` resolves to the
first match in the document.

Without `colors`, a single series uses `color` and several cycle through a
built-in sequence starting from it.

### Interaction

`tooltip` snaps a crosshair to the nearest index and lists every visible series
at that point. `legend` toggles a series, or a slice on a radial type, and the
last visible one cannot be switched off, since an empty plot has no domain left
to scale against and a pie would have no circle left to divide.

Toggling rescales the remaining series. Because rescaling a domain is an affine
map in y, and Beziers are affine invariant, this is applied as an SVG
`transform` on the group rather than a curve recomputed in JavaScript — the
interpolation exists in exactly one place, in PHP.

Rescaling is skipped when it would be misleading or meaningless: on a labelled
grid, where the axis values would stop matching the curve, on stacked or bar
charts, whose baselines are anchored, and whenever a secondary axis exists.

A pie is the exception. Removing a slice redistributes every remaining angle,
which is a genuine recomputation rather than a transform, so its arc geometry —
trigonometry, not interpolation — is mirrored in the Alpine layer. Slice
percentages in the tooltip follow the redistribution.

### Touch

Interaction runs on Pointer Events, so it works from mouse, touch and pen. On
touch, a tap opens the tooltip and a tap outside closes it. Dragging is
deliberately left to the page: capturing it would need `preventDefault`, which
locks scrolling inside a chart that often fills most of a small screen. That is
also why `pointerleave` only closes the tooltip for a mouse — on touch it fires
right after the tap and would blank it instantly.

### Livewire lazy loading

The markup arrives already drawn, so a chart inside a `#[Lazy]` component
appears complete the moment the placeholder is replaced. Hit-testing measures
the element on the pointer event and never on `init()`, which is the usual
failure mode for charting libraries mounted before their container has a size.

For the placeholder itself, see [Skeleton](#skeleton) below.

## Skeleton

Renders a structural placeholder in place of the plot, for the first paint
before any series exists. Meant for the `placeholder()` of a `#[Lazy]` component,
where a chart is usually the slowest thing on the page.

```blade
<x-chart skeleton />                                {{-- 6 points, area --}}
<x-chart skeleton="10" type="bar" :height="240" />
<x-chart skeleton="5" type="donut" />
```

The placeholder runs the same geometry as a real chart — `Series`, `Scale`,
`Bars`, `Spline`, `Slices` — over invented values, so it lands in the same
`viewBox` with the same proportions, and honours the resolved `height`. Three
shape families cover all five types:

| Family | Types          | Produced by |
|--------|----------------|-------------|
| Curve  | `area`, `line` | `Spline`    |
| Bars   | `bar`          | `Bars`      |
| Slices | `pie`, `donut` | `Slices`    |

Real geometry filled with invented numbers would read as *a chart showing wrong
data*, so three things keep it unambiguous: every fill and stroke is neutral grey
(`color` and the palette are ignored), the plot pulses, and **no axis labels,
legend, tooltip, markers, grid or crosshair are rendered**. The placeholder is a
shape, never a reading.

`series` is required everywhere else, but not here: it is the content, and a
placeholder stands in for content that does not exist yet. Every configuration
validation still runs — an unknown `type`, a `height` below `1`, `stacked` on a
radial type and `grid` on a radial type all still throw. Any `skeleton` integer
below `1` throws.

### Degenerate input

| Input                                        | Result                                                                 |
|----------------------------------------------|------------------------------------------------------------------------|
| Absent `series`                              | Throws `The [series] attribute is required.`, unless `skeleton` is set |
| Empty array                                  | The plot renders at full height with no path                           |
| Single value                                 | Spans the plot as a constant series, like `[7, 7, 7]`                  |
| All values identical                         | A flat line centred in the band, not on the baseline                   |
| Negative values                              | Handled natively; bars anchor on zero, and stack below it              |
| Negative values on a pie or donut            | Clamped to zero; a slice cannot sweep backwards                        |
| More than one series on a pie or donut       | Throws; a circle divides one set of values                             |
| Non-numeric, `NAN`, `INF`                    | Throws `The [series] must contain only numeric values.`                |
| Entry without `data`                         | Throws `Every entry of [series] must carry a [data] key.`              |
| Unknown `type`                               | Throws, naming the accepted values                                     |
| Unknown `type` on a series                   | Throws; only `area`, `line` and `bar` exist                            |
| `type` on a series of a pie or donut         | Throws                                                                 |
| Unknown `axis`                               | Throws; only `left` and `right` exist                                  |
| `stacked` on line or pie                     | Throws                                                                 |
| `stacked` with a secondary axis              | Throws; one running total cannot span two domains                      |
| `grid` on pie or donut                       | Throws                                                                 |
| Negative or non-integer `decimals`           | Throws                                                                 |
| Formatting array keyed other than left/right | Throws                                                                 |

### Long series

Above 120 points the series is bucketed, keeping each bucket's lowest and
highest value in the order they appeared, so peaks and the scale anchors always
survive. The chosen indexes are shared across every series, so multiple curves
stay aligned, and each point keeps the horizontal position of its original index.

### Customizations Carry Over

The `skeleton.*` blocks are only the bars. Everything structural is resolved
from this component's **own, existing blocks**, because the skeleton view calls
the same `classes()` as the normal one — customization is resolved on the
component, not on the view. Whatever you already changed applies to the
placeholder too, so the box keeps matching the box it stands in for. Scopes
work the same, including when they target the placeholder alone.

Chart reuses `wrapper`, `plot.wrapper`, `plot.svg`, `plot.slice` and the `axis.*.wrapper` set.

Blocks the placeholder does not render have nothing to act on there.
Customizing them is not an error; it simply has no effect while the skeleton
is on screen.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

Soft customization reaches how the SVG is painted, not how it is drawn. Fill,
stroke, width and opacity are Tailwind classes on the elements, so every
`plot.*` block works like any other. The shapes themselves are computed
server-side and exposed nowhere: curvature, corner radius, the donut hole and
the tick count are fixed.

### Customization

```php
TallStackUi::customize()
    ->chart()
    ->block('plot.line', 'fill-none stroke-current stroke-[3]');
```

Two blocks are worth a warning. `plot.line` paints from `stroke-current`, and
`currentColor` comes from the `<g>` the series is wrapped in, not from the
block — replacing it with a literal `stroke-red-500` paints every series red
and takes `color`, `colors` and the legend toggle down with it. And the stroke
carries `vector-effect="non-scaling-stroke"`, so `stroke-[3]` is three screen
pixels at any chart size rather than three viewBox units.

### Available Blocks

| Block Name           | Purpose                                                    |
|----------------------|------------------------------------------------------------|
| wrapper              | Outermost container holding the plot, legend and slots     |
| plot.wrapper         | The grid aligning the axes with the plot                   |
| plot.svg             | The SVG itself                                             |
| plot.line            | The curve stroke                                           |
| plot.area            | The filled area under a curve                              |
| plot.bar             | Bar shapes                                                 |
| plot.slice           | Pie and donut arcs                                         |
| plot.markers         | Container for the point markers                            |
| plot.marker          | A single point marker                                      |
| plot.grid            | Horizontal gridlines                                       |
| plot.crosshair       | The vertical line following the pointer                    |
| axis.y.wrapper       | Vertical axis column                                       |
| axis.y.label         | A vertical axis label                                      |
| axis.y.right.wrapper | Secondary axis column                                      |
| axis.y.right.label   | A secondary axis label                                     |
| axis.x.wrapper       | Horizontal axis row                                        |
| axis.x.label         | A horizontal axis label                                    |
| axis.x.off           | Applied to a horizontal axis label hidden to avoid overlap |
| legend.wrapper       | Legend container                                           |
| legend.item          | A legend entry                                             |
| legend.off           | Applied to a legend entry whose series is hidden           |
| legend.dot           | Legend color swatch                                        |
| legend.text          | Legend label                                               |
| tooltip.wrapper      | Tooltip container                                          |
| tooltip.title        | Tooltip heading, the axis label or the slice percentage    |
| tooltip.row          | One series row inside the tooltip                          |
| tooltip.dot          | Tooltip color swatch                                       |
| tooltip.name         | Series name inside the tooltip                             |
| tooltip.value        | Formatted value inside the tooltip                         |
| slots.header         | Header slot styles                                         |
| slots.footer         | Footer slot styles                                         |
| opacity.from         | Gradient stop opacity at the top of the area, as a number  |
| opacity.to           | Gradient stop opacity at the baseline, as a number         |
| skeleton.animation   | Pulse animation applied to the whole placeholder           |
| skeleton.bar         | Base look of the header and footer placeholder bars        |
| skeleton.fill        | Neutral fill of placeholder areas, bars and slices         |
| skeleton.stroke      | Neutral stroke of the placeholder curve                    |
| skeleton.header      | Header bar dimensions                                      |
| skeleton.footer      | Footer bar dimensions                                      |

`opacity.from` and `opacity.to` hold plain numbers rather than classes because
Tailwind has no `stop-opacity` utility.
