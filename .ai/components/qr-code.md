# TallStackUI: QrCode

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A dependency-free QR code rendered as inline SVG. The whole of ISO/IEC 18004
lives in `src/Support/QrCode/`: Reed-Solomon over GF(256), the version and
error correction tables, data placement, the eight masks and their penalty
rules. Nothing is fetched, nothing is shelled out to, and no encoding library
is involved. Alpine is attached only when the code offers copy or download.

## Basic Usage

```blade
<x-qr-code link="https://tallstackui.com" />
```

```blade
<x-qr-code link="https://tallstackui.com"
           color="blue"
           size="xl"
           watermark="bolt"
           copy
           download="svg" />
```

## Attributes

| Attribute | Type               | Default | Description                                                                      |
|-----------|--------------------|---------|----------------------------------------------------------------------------------|
| link      | string\|null       | null    | The URL to encode. Required unless `skeleton` is set                             |
| color     | string\|null       | null    | Module color. Absent, the modules follow the theme                               |
| size      | string\|null       | null    | One of: xs, sm, md, lg, xl, 2xl. Falls back to the config                        |
| watermark | string\|null       | null    | An icon name, or a caption of up to 8 characters. See [Watermark](#watermark)    |
| copy      | bool\|null         | null    | Renders a button that copies the code to the clipboard as a PNG                  |
| download  | bool\|string\|null | null    | Renders a download button. `true` and `'png'` produce a raster, `'svg'` a vector |
| skeleton  | bool\|int\|null    | null    | Renders a structural placeholder instead of a code. See [Skeleton](#skeleton)    |

The component throws when `link` is absent or is not a URL, when `size` or
`download` is outside its list, when a caption watermark is longer than eight
characters, and when the link exceeds what version forty holds.

## Configuration

```php
// config/tallstackui.php
'components' => [
    'qr-code' => [
        \TallStackUi\Components\QrCode\Component::class,
        [
            'size' => 'md',
            'pixels' => 1024,
        ],
    ],
],
```

`size` is only consulted when the attribute is absent. `pixels` is the width of
the image produced by copy and download, and has no effect on the page.

The error correction level is not configurable. It is `M` normally and `H` when
a watermark is present, because removing modules is exactly what the highest
level pays for.

## Behaviour

### Encoding

Byte mode only. The numeric and alphanumeric modes pack tighter, but a URL
rarely qualifies for either and the saving would not pay for the extra branch
in every step of the encoder. The smallest of the forty versions that fits the
payload is chosen automatically.

The grid is drawn as a **single SVG path** with consecutive dark modules merged
into one run. One node per module would put thousands of elements on the page
for a mid sized code, which is what makes a list of them stutter.

### Colors

Without `color`, the modules are `text-gray-900 dark:text-white`: dark on a
light page, light on a dark one. A named color is the same color under both
themes, because a brand is.

**The dark theme therefore inverts the symbol.** ISO/IEC 18004 specifies dark
modules on a light background. Modern readers — the iOS camera, Google Lens,
Apple Vision — read an inverted code without complaint, but the default reader
in ZXing does not, and neither do many industrial scanners. Pass an explicit
`color` where conformance matters more than the theme.

### Watermark

One attribute, two outcomes. A value that resolves to an existing icon draws
the icon; anything else is drawn as `<text>`. Both are SVG primitives, so both
survive the copy and the download — which is why arbitrary Blade is not
accepted here, as `<foreignObject>` is dropped during rasterization.

The modules underneath are **removed, not covered**: the component draws no
background, so anything painted over them would still show them through. The
region never reaches the timing patterns or either format information block,
and both kinds are capped at roughly six per cent of the symbol area — a
caption strip that grew with its text would take most of the width of a large
symbol and no reader would take that code. A long caption shrinks its font
rather than widening the strip.

### Copy and download

Copy always writes a PNG. Pasting a vector into a chat or a document does not
work anywhere it matters. Download takes `png` or `svg`.

The exported file is rendered without the page stylesheet, so the color the
classes resolved to is inlined into the clone before it is serialized. The
clipboard requires HTTPS, localhost or 127.0.0.1; elsewhere the button logs a
warning instead of copying.

There is **no background**, so the exported PNG is transparent. A dark code
pasted onto a dark surface disappears.

### Sizing and scannability

`size` sets the rendered box; the module count comes from the payload. A long
link inside a small box leaves very few pixels per module, and below roughly
three the code stops being readable from a one-times display:

| payload | version | xs   | sm   | md   | lg   | xl   | 2xl  |
|---------|---------|------|------|------|------|------|------|
| 23 B    | v2      | 2.91 | 3.88 | 4.85 | 5.82 | 6.79 | 7.76 |
| 120 B   | v7      | 1.81 | 2.42 | 3.02 | 3.62 | 4.23 | 4.83 |
| 330 B   | v13     | 1.25 | 1.66 | 2.08 | 2.49 | 2.91 | 3.32 |
| 800 B   | v23     | 0.82 | 1.09 | 1.37 | 1.64 | 1.91 | 2.19 |

The numbers are pixels per module at one times. A retina display doubles them.
Shorten the link or raise the size when a code has to be scanned off a screen
or printed small.

## Skeleton

`skeleton` renders a placeholder shaped like a code and stops requiring a
`link`, which is what a placeholder stands in for:

```blade
<x-qr-code :link="$resolved" :skeleton="$resolved === null" size="lg" />
```

It honours `size` and takes the same shared `animation` and `bar` blocks every
other skeleton uses. The shape is fixed, so it is a flag: an integer is ignored,
and one below `1` throws.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->qrCode()
    ->block('sizes.md', 'size-52');
```

### Available Blocks

| Block Name         | Purpose                                              |
|--------------------|------------------------------------------------------|
| wrapper            | Outermost container holding the code and the actions |
| code               | The SVG itself                                       |
| sizes.xs           | Rendered box for `size="xs"`                         |
| sizes.sm           | Rendered box for `size="sm"`                         |
| sizes.md           | Rendered box for `size="md"`                         |
| sizes.lg           | Rendered box for `size="lg"`                         |
| sizes.xl           | Rendered box for `size="xl"`                         |
| sizes.2xl          | Rendered box for `size="2xl"`                        |
| watermark.icon     | Class on the nested icon, kept free of sizing        |
| watermark.family   | `font-family` attribute of a caption watermark       |
| watermark.weight   | `font-weight` attribute of a caption watermark       |
| actions.wrapper    | Row holding the copy and download buttons            |
| actions.button     | A single action button                               |
| actions.icon       | Icon inside an action button                         |
| skeleton.animation | Placeholder animation                                |
| skeleton.bar       | Shared placeholder bar                               |
| skeleton.block     | Fill of a placeholder block                          |

`watermark.family` and `watermark.weight` hold attribute values rather than
classes, and `watermark.icon` deliberately carries no size: the exported file
has no stylesheet to resolve a class against, and a sizing class would override
the geometry attributes that place the nested icon.

### Color Customization

```bash
php artisan tallstackui:setup-color
```

Publishes `QrCodeColors`, whose `textColors()` maps every color name to a
class. The `default` key is the one used when no `color` is given, and is the
only one allowed to follow the theme.
