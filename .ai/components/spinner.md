# TallStackUI: Spinner

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A purely visual loading indicator. Thirteen animated variants, four sizes, 29 colors and an optional label. It binds nothing to Livewire and holds no state, so it can be dropped anywhere: inside a card, next to a button, in an empty state or in a `#[Lazy]` placeholder.

Only the `thinking` variant needs Alpine, to cycle its braille glyphs.

## Basic Usage

```blade
<x-spinner />
```

```blade
<x-spinner lg bars color="red" />
```

```blade
<x-spinner wave text="Sending the file" />
```

```blade
<x-spinner thinking />
```

## Variants

One boolean flag per variant. Using two at the same time throws.

| Flag     | Appearance                                       | Animation                 |
|----------|--------------------------------------------------|---------------------------|
| ring     | Spinning border with a transparent top (default) | `animate-spin`            |
| throbber | Twelve SVG segments in ramping opacity           | `animate-spin`            |
| gradient | Two-tone SVG arc                                 | `animate-spin`            |
| ping     | Hollow ring with an expanding echo               | `animate-ping`            |
| dots     | Three bouncing dots                              | `animate-spinner-dots`    |
| pulse    | One scaling dot                                  | `animate-spinner-pulse`   |
| typing   | Three chat-style dots                            | `animate-spinner-typing`  |
| bars     | Three vertical bars                              | `animate-spinner-bars`    |
| wave     | Five vertical bars travelling as a wave          | `animate-spinner-wave`    |
| shimmer  | Gradient sweeping across the text                | `animate-spinner-shimmer` |
| caret    | Text followed by a blinking block                | `animate-spinner-caret`   |
| terminal | Prompt sign with a blinking block                | `animate-spinner-caret`   |
| thinking | Cycling braille glyphs with a translated label   | Alpine timer              |

`shimmer` and `caret` animate the text itself, so `text` or the default slot is required. `terminal` renders the prompt and the caret on its own.

## Attributes

| Attribute | Type               | Default   | Description                                                   |
|-----------|--------------------|-----------|---------------------------------------------------------------|
| ring      | bool               | null      | Spinning border variant, the default                          |
| throbber  | bool               | null      | Segmented SVG variant                                         |
| gradient  | bool               | null      | Two-tone SVG variant                                          |
| ping      | bool               | null      | Ring with an expanding echo                                   |
| dots      | bool               | null      | Three bouncing dots                                           |
| pulse     | bool               | null      | Single scaling dot                                            |
| typing    | bool               | null      | Three chat-style dots                                         |
| bars      | bool               | null      | Three vertical bars                                           |
| wave      | bool               | null      | Five vertical bars                                            |
| shimmer   | bool               | null      | Gradient sweeping across the text                             |
| caret     | bool               | null      | Text with a blinking caret                                    |
| terminal  | bool               | null      | Prompt sign with a blinking caret                             |
| thinking  | bool               | null      | Cycling braille glyphs                                        |
| xs        | bool               | null      | Extra-small size                                              |
| sm        | bool               | null      | Small size                                                    |
| md        | bool               | null      | Medium size (default)                                         |
| lg        | bool               | null      | Large size                                                    |
| color     | string\|null       | 'primary' | Color name, resolved to a single `text-*` class               |
| text      | string\|bool\|null | null      | Label next to the spinner. `false` hides the `thinking` label |
| interval  | int\|null          | 80        | Milliseconds between `thinking` frames                        |

Passing more than one size flag is allowed and resolves by precedence: `lg`, `md`, `sm`, `xs`.

## Slots

| Slot      | Description                                     |
|-----------|-------------------------------------------------|
| (default) | Label content, used when `text` is not provided |

## Color

Every variant paints itself through `currentColor`, so a single `text-*` class drives borders, dots, bars, SVG strokes and the shimmer gradient. That class lands on the root element:

```blade
<x-spinner bars color="emerald" />
```

Anything outside the palette can come straight from a utility:

```blade
<x-spinner class="text-[#ff5f1f]" />
```

## Label & Accessibility

The root carries `role="status"`. Without a label the component renders a `sr-only` fallback translated from `ts-ui::messages.spinner.loading`, so screen readers never announce an empty region.

```blade
<x-spinner />                                    {{-- glyph + sr-only fallback --}}
<x-spinner text="Sending the file" />            {{-- glyph + visible label --}}
<x-spinner>Sending <b>3</b> files</x-spinner>    {{-- glyph + markup label --}}
```

The `thinking` variant defaults its label to `ts-ui::messages.spinner.thinking`:

```blade
<x-spinner thinking />                     {{-- ⠋ Thinking... --}}
<x-spinner thinking text="Analyzing" />    {{-- ⠋ Analyzing --}}
<x-spinner thinking :text="false" />       {{-- ⠋ only --}}
```

## Global Settings

```php
'spinner' => [
    'type' => 'ring',
    'size' => 'md',
],
```

An unknown `type` or `size` throws when the component renders.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->spinner()
    ->block('bars.bar', 'your-tailwind-classes');
```

```php
TallStackUi::customize('spinner', scope: 'chat')
    ->block('typing.dot', 'size-2');
```

### Available Blocks

| Block Name                   | Purpose                                                      |
|------------------------------|--------------------------------------------------------------|
| wrapper                      | Root element, holds the layout and the resolved color        |
| text.base                    | Label next to the spinner                                    |
| text.sizes.{xs,sm,md,lg}     | Label font size                                              |
| delays.{0..4}                | Shared stagger applied by index to every multi-child variant |
| ring.base                    | Spinning border                                              |
| ring.sizes.{xs,sm,md,lg}     | Ring diameter and border width                               |
| throbber.base                | Rotating SVG                                                 |
| throbber.segment             | Each of the twelve segments                                  |
| throbber.sizes.{xs,sm,md,lg} | SVG size                                                     |
| gradient.base                | Rotating SVG                                                 |
| gradient.track               | Dimmed circle behind the arc                                 |
| gradient.head                | Highlighted arc                                              |
| gradient.sizes.{xs,sm,md,lg} | SVG size                                                     |
| ping.wrapper                 | Positioning context                                          |
| ping.echo                    | Expanding echo ring                                          |
| ping.core                    | Static inner ring                                            |
| ping.sizes.wrapper.*         | Ring diameter                                                |
| ping.sizes.border.*          | Ring border width                                            |
| dots.wrapper                 | Dot row                                                      |
| dots.dot                     | Each dot                                                     |
| dots.sizes.wrapper.*         | Gap between dots                                             |
| dots.sizes.dot.*             | Dot diameter                                                 |
| pulse.dot                    | The single dot                                               |
| pulse.sizes.{xs,sm,md,lg}    | Dot diameter                                                 |
| typing.wrapper               | Dot row                                                      |
| typing.dot                   | Each dot                                                     |
| typing.sizes.wrapper.*       | Gap between dots                                             |
| typing.sizes.dot.*           | Dot diameter                                                 |
| bars.wrapper                 | Bar row                                                      |
| bars.bar                     | Each bar                                                     |
| bars.sizes.wrapper.*         | Gap between bars                                             |
| bars.sizes.bar.*             | Bar height and width                                         |
| wave.wrapper                 | Bar row                                                      |
| wave.bar                     | Each bar                                                     |
| wave.sizes.wrapper.*         | Gap between bars                                             |
| wave.sizes.bar.*             | Bar height and width                                         |
| shimmer.base                 | Gradient clipped to the text                                 |
| shimmer.sizes.{xs,sm,md,lg}  | Text size                                                    |
| caret.wrapper                | Text and caret row                                           |
| caret.caret                  | Blinking block                                               |
| caret.sizes.text.*           | Text size                                                    |
| caret.sizes.caret.*          | Caret height and width                                       |
| terminal.wrapper             | Prompt, text and caret row                                   |
| terminal.prompt              | The prompt sign                                              |
| terminal.caret               | Blinking block                                               |
| terminal.sizes.text.*        | Text size                                                    |
| terminal.sizes.caret.*       | Caret height and width                                       |
| thinking.wrapper             | Glyph and label row                                          |
| thinking.glyph               | The cycling braille glyph                                    |
| thinking.label               | The label next to the glyph                                  |
| thinking.sizes.glyph.*       | Glyph size                                                   |
| thinking.sizes.text.*        | Label size                                                   |

## Color Personalization

```bash
php artisan tallstackui:setup-color
```

Publishes a `SpinnerColors` class with a single `textColors()` palette.
