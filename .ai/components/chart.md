# TallStackUI: Chart

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

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

| Attribute | Type                    | Default   | Description                                                   |
|-----------|-------------------------|-----------|---------------------------------------------------------------|
| series    | array\|Collection\|null | null      | Values to plot, flat or grouped. Required                     |
| labels    | array\|Collection\|null | null      | Horizontal axis captions, or slice names on radial types      |
| type      | string\|null            | 'area'    | One of: area, line, bar, pie, donut                           |
| stacked   | bool\|null              | null      | Stacks series instead of overlaying them. Area and bar only   |
| color     | string\|null            | 'primary' | Base color, and the first of the cycled palette               |
| colors    | array\|Collection\|null | null      | Explicit color names, cycled across series or slices          |
| height    | int\|null               | null      | Minimum rendered height in pixels, falling back to the config |
| grid      | bool\|null              | null      | Horizontal gridlines plus a labelled vertical axis            |
| legend    | bool\|null              | null      | Series names with a color swatch. Clicking one toggles it     |
| tooltip   | bool\|null              | null      | Crosshair and a tooltip following the pointer                 |
| markers   | bool\|null              | null      | A dot on every plotted point                                  |
| prefix    | string\|array\|null     | null      | Prepended to formatted values. Per axis when an array         |
| suffix    | string\|array\|null     | null      | Appended to formatted values. Per axis when an array          |
| decimals  | int\|array\|null        | null      | Decimal places. Defaults to 0 for whole numbers, 2 otherwise  |
| formatter | Closure\|null           | null      | Formats every value, winning over the three above             |

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
            'height' => 64,
            'grid' => false,
            'legend' => false,
            'tooltip' => false,
            'markers' => false,
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

### Degenerate input

| Input                                        | Result                                                    |
|----------------------------------------------|-----------------------------------------------------------|
| Absent `series`                              | Throws `The [series] attribute is required.`              |
| Empty array                                  | The plot renders at full height with no path              |
| Single value                                 | Spans the plot as a constant series, like `[7, 7, 7]`     |
| All values identical                         | A flat line centred in the band, not on the baseline      |
| Negative values                              | Handled natively; bars anchor on zero                     |
| Non-numeric, `NAN`, `INF`                    | Throws `The [series] must contain only numeric values.`   |
| Entry without `data`                         | Throws `Every entry of [series] must carry a [data] key.` |
| Unknown `type`                               | Throws, naming the accepted values                        |
| Unknown `axis`                               | Throws; only `left` and `right` exist                     |
| `stacked` on line or pie                     | Throws                                                    |
| `stacked` with a secondary axis              | Throws; one running total cannot span two domains         |
| `grid` on pie or donut                       | Throws                                                    |
| Negative or non-integer `decimals`           | Throws                                                    |
| Formatting array keyed other than left/right | Throws                                                    |

### Long series

Above 120 points the series is bucketed, keeping each bucket's lowest and
highest value in the order they appeared, so peaks and the scale anchors always
survive. The chosen indexes are shared across every series, so multiple curves
stay aligned, and each point keeps the horizontal position of its original index.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->chart()
    ->block('plot.line', 'fill-none stroke-current stroke-[3]');
```

### Available Blocks

| Block Name           | Purpose                                                   |
|----------------------|-----------------------------------------------------------|
| wrapper              | Outermost container holding the plot, legend and slots    |
| plot.wrapper         | The grid aligning the axes with the plot                  |
| plot.svg             | The SVG itself                                            |
| plot.line            | The curve stroke                                          |
| plot.area            | The filled area under a curve                             |
| plot.bar             | Bar rectangles                                            |
| plot.slice           | Pie and donut arcs                                        |
| plot.markers         | Container for the point markers                           |
| plot.marker          | A single point marker                                     |
| plot.grid            | Horizontal gridlines                                      |
| plot.crosshair       | The vertical line following the pointer                   |
| axis.y.wrapper       | Vertical axis column                                      |
| axis.y.label         | A vertical axis label                                     |
| axis.y.right.wrapper | Secondary axis column                                     |
| axis.y.right.label   | A secondary axis label                                    |
| axis.x.wrapper       | Horizontal axis row                                       |
| axis.x.label         | A horizontal axis label                                   |
| legend.wrapper       | Legend container                                          |
| legend.item          | A legend entry                                            |
| legend.off           | Applied to a legend entry whose series is hidden          |
| legend.dot           | Legend color swatch                                       |
| legend.text          | Legend label                                              |
| tooltip.wrapper      | Tooltip container                                         |
| tooltip.title        | Tooltip heading, the axis label or the slice percentage   |
| tooltip.row          | One series row inside the tooltip                         |
| tooltip.dot          | Tooltip color swatch                                      |
| tooltip.name         | Series name inside the tooltip                            |
| tooltip.value        | Formatted value inside the tooltip                        |
| slots.header         | Header slot styles                                        |
| slots.footer         | Footer slot styles                                        |
| opacity.from         | Gradient stop opacity at the top of the area, as a number |
| opacity.to           | Gradient stop opacity at the baseline, as a number        |

`opacity.from` and `opacity.to` hold plain numbers rather than classes because
Tailwind has no `stop-opacity` utility.
