# TallStackUI: Button

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A versatile button component supporting solid, light, outline, and flat styles with optional icons, loading states, tooltips, and link behavior.

## Basic Usage

```blade
<x-button text="Save" />
```

```blade
<x-button text="Delete" color="red" outline icon="trash" />
```

```blade
<x-button text="Processing..." loading="save" color="green" />
```

```blade
<x-button text="Visit Site" href="https://example.com" flat />
```

Submitting a form. The button renders `type="button"` by default, so it does
**not** submit the surrounding form until `submit` is set. Use the `submit`
prop — never write `type="submit"` by hand:

```blade
<x-button submit text="Save" />           {{-- correct --}}
<x-button type="submit" text="Save" />    {{-- wrong: use the submit prop --}}
```

To submit a form declared elsewhere in the DOM, keep `submit` and add the
native `form` attribute pointing at the form's `id`:

```blade
<form id="post-form" wire:submit="save"> ... </form>

<x-button submit form="post-form" text="Save" loading="save" />
```

Controlling the border radius. `round` on its own gives a pill; with a size it gives
that exact radius. `square` drops the radius altogether and wins over `round`:

```blade
<x-button text="Default" />           {{-- rounded-md --}}
<x-button text="Pill" round />        {{-- rounded-full --}}
<x-button text="Large" round="lg" />  {{-- rounded-lg --}}
<x-button text="Sharp" square />      {{-- no radius --}}
```

| Value          | Class          |
|----------------|----------------|
| (none)         | `rounded-md`   |
| `round`        | `rounded-full` |
| `round="xs"`   | `rounded-xs`   |
| `round="sm"`   | `rounded-sm`   |
| `round="md"`   | `rounded-md`   |
| `round="lg"`   | `rounded-lg`   |
| `round="xl"`   | `rounded-xl`   |
| `round="full"` | `rounded-full` |

Choosing the loading indicator. `spinner` renders one of the nine visual
[Spinner](../spinner.md) variants as the `wire:loading` indicator — `ring`,
`throbber`, `gradient`, `ping`, `dots`, `pulse`, `typing`, `bars` or `wave`.
Omitted, it falls back to `config('tallstackui.components.button.spinner')`
(shared with `<x-button.circle>`, `null` by default) and finally to the default
effect. The textual variants (`shimmer`, `caret`, `terminal`, `thinking`)
animate their own text and throw inside a button:

```blade
<x-button text="Save" loading="save" spinner="dots" />
<x-button text="Save" loading="save" spinner="shimmer" />   {{-- throws --}}
```

## Attributes

| Attribute | Type               | Default   | Description                                                                                                                            |
|-----------|--------------------|-----------|----------------------------------------------------------------------------------------------------------------------------------------|
| text      | string\|null       | null      | Button label text                                                                                                                      |
| icon      | string\|null       | null      | Heroicon name displayed alongside the text                                                                                             |
| position  | string\|null       | 'left'    | Icon position relative to text: 'left' or 'right'                                                                                      |
| xs        | bool               | null      | Extra-small size                                                                                                                       |
| sm        | bool               | null      | Small size                                                                                                                             |
| md        | bool               | null      | Medium size (default)                                                                                                                  |
| lg        | bool               | null      | Large size                                                                                                                             |
| color     | string\|null       | 'primary' | Color theme (e.g., primary, red, green, yellow)                                                                                        |
| square    | string\|null       | null      | Removes border radius for square corners. Wins over `round`                                                                            |
| round     | bool\|string\|null | false     | `true` gives a pill (`rounded-full`). A size (xs, sm, md, lg, xl, full) gives that exact radius. Defaults to `rounded-md` when omitted |
| block     | bool               | false     | Expands button to full width (`w-full`)                                                                                                |
| href      | string\|null       | null      | When set, renders as an anchor tag instead of a button                                                                                 |
| loading   | string\|null       | null      | Livewire action name to show a loading spinner during execution                                                                        |
| delay     | string\|null       | null      | Delay duration for the loading indicator (e.g., 'longest')                                                                             |
| spinner   | string\|null       | null      | Loading spinner variant: ring, throbber, gradient, ping, dots, pulse, typing, bars, wave. Falls back to the `button.spinner` config    |
| solid     | bool               | true      | Uses the solid color style variant (default)                                                                                           |
| outline   | bool               | false     | Uses the outline color style variant                                                                                                   |
| light     | bool               | false     | Uses the light color style variant                                                                                                     |
| flat      | bool               | false     | Uses the flat color style variant (no border)                                                                                          |
| submit    | bool               | false     | Renders `type="submit"` so the button submits its form. Always prefer this over passing `type="submit"` yourself                       |
| unfocus   | bool               | false     | No focus on mouse click (no ring/color); keyboard focus kept                                                                           |
| tooltip   | string\|null       | null      | Tooltip text shown on hover                                                                                                            |

The balloon accepts the same attributes as anywhere else — `data-position`,
`data-tooltip-delay`, `data-tooltip-color` and `data-tooltip-disabled`. See
[Tooltip](../tooltip.md#the-x-tooltip-directive).

```blade
<x-button text="Save" tooltip="Saves and closes" data-tooltip-delay="flash" />
```

## Slots

| Slot      | Description                                                           |
|-----------|-----------------------------------------------------------------------|
| (default) | Custom content, used when `text` is not provided                      |
| left      | Custom HTML rendered before the text (overrides left-positioned icon) |
| right     | Custom HTML rendered after the text (overrides right-positioned icon) |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->button()
    ->block('wrapper.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name          | Purpose                                                             |
|---------------------|---------------------------------------------------------------------|
| wrapper.class       | Base button styles (inline-flex, border, transition, cursor states) |
| wrapper.sizes.xs    | Extra-small text and padding                                        |
| wrapper.sizes.sm    | Small text and padding                                              |
| wrapper.sizes.md    | Medium text and padding                                             |
| wrapper.sizes.lg    | Large text and padding                                              |
| wrapper.block       | Full-width class applied by `block`                                 |
| border.radius.xs    | Radius applied by `round="xs"`                                      |
| border.radius.sm    | Radius applied by `round="sm"`                                      |
| border.radius.md    | Radius applied by `round="md"` and by default                       |
| border.radius.lg    | Radius applied by `round="lg"`                                      |
| border.radius.xl    | Radius applied by `round="xl"`                                      |
| border.radius.full  | Radius applied by `round` and by `round="full"`                     |
| wire.loading-cursor | Cursor applied while a `loading` action runs                        |
| icon.sizes.xs       | Extra-small icon dimensions                                         |
| icon.sizes.sm       | Small icon dimensions                                               |
| icon.sizes.md       | Medium icon dimensions                                              |
| icon.sizes.lg       | Large icon dimensions                                               |

The loading indicator mirrors the Spinner's blocks under a `spinner.` prefix,
sized to the button's icon box and isolated from the Spinner component's own
customization:

| Block Name                           | Purpose                                                      |
|--------------------------------------|--------------------------------------------------------------|
| spinner.delays.{0..4}                | Shared stagger applied by index to every multi-child variant |
| spinner.ring.base                    | Spinning border                                              |
| spinner.ring.sizes.{xs,sm,md,lg}     | Ring diameter and border width                               |
| spinner.throbber.base                | Rotating SVG                                                 |
| spinner.throbber.segment             | Each of the twelve segments                                  |
| spinner.throbber.sizes.{xs,sm,md,lg} | SVG size                                                     |
| spinner.gradient.base                | Rotating SVG                                                 |
| spinner.gradient.track               | Dimmed circle behind the arc                                 |
| spinner.gradient.head                | Highlighted arc                                              |
| spinner.gradient.sizes.{xs,sm,md,lg} | SVG size                                                     |
| spinner.ping.wrapper                 | Positioning context                                          |
| spinner.ping.echo                    | Expanding echo ring                                          |
| spinner.ping.core                    | Static inner ring                                            |
| spinner.ping.sizes.wrapper.*         | Ring diameter                                                |
| spinner.ping.sizes.border.*          | Ring border width                                            |
| spinner.dots.wrapper                 | Dot row                                                      |
| spinner.dots.dot                     | Each dot                                                     |
| spinner.dots.sizes.wrapper.*         | Gap between dots                                             |
| spinner.dots.sizes.dot.*             | Dot diameter                                                 |
| spinner.pulse.dot                    | The single dot                                               |
| spinner.pulse.sizes.{xs,sm,md,lg}    | Dot diameter                                                 |
| spinner.typing.wrapper               | Dot row                                                      |
| spinner.typing.dot                   | Each dot                                                     |
| spinner.typing.sizes.wrapper.*       | Gap between dots                                             |
| spinner.typing.sizes.dot.*           | Dot diameter                                                 |
| spinner.bars.wrapper                 | Bar row                                                      |
| spinner.bars.bar                     | Each bar                                                     |
| spinner.bars.sizes.wrapper.*         | Gap between bars                                             |
| spinner.bars.sizes.bar.*             | Bar height and width                                         |
| spinner.wave.wrapper                 | Bar row                                                      |
| spinner.wave.bar                     | Each bar                                                     |
| spinner.wave.sizes.wrapper.*         | Gap between bars                                             |
| spinner.wave.sizes.bar.*             | Bar height and width                                         |
