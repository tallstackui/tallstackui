# TallStackUI: Tooltip

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A tooltip icon component that displays informational text on hover, focus or tap. Renders an icon (default: question mark circle) that shows a positioned balloon with custom text content. The balloon is built by the package itself, with no external dependency.

## Basic Usage

```blade
<x-tooltip text="This field is required." />
```

```blade
<x-tooltip text="Click to save changes" icon="information-circle" color="blue" position="right" />
```

```blade
<x-tooltip text="Warning: this action is irreversible" balloon="red" lg position="bottom" />
```

The content also accepts HTML, through the slot or the `text` prop:

```blade
<x-tooltip>Press <b>Ctrl</b> + <b>S</b> to save</x-tooltip>
```

## Attributes

| Attribute | Type         | Default                | Description                                                                                  |
|-----------|--------------|------------------------|----------------------------------------------------------------------------------------------|
| text      | string\|null | null                   | Balloon content displayed on hover; accepts HTML                                             |
| icon      | string\|null | 'question-mark-circle' | Heroicon name used as the tooltip trigger                                                    |
| color     | string       | 'primary'              | Color of the **icon** (e.g., primary, red, blue, gray)                                       |
| balloon   | string\|null | null                   | Color of the **balloon**; null keeps the high contrast default                               |
| delay     | string\|null | null                   | How long the pointer must rest before opening; null falls back to the config, then to `fast` |
| xs        | bool\|null   | null                   | Extra-small icon size                                                                        |
| sm        | bool\|null   | null                   | Small icon size (default)                                                                    |
| md        | bool\|null   | null                   | Medium icon size                                                                             |
| lg        | bool\|null   | null                   | Large icon size                                                                              |
| position  | string\|null | 'top'                  | Balloon position relative to the icon                                                        |

## The `x-tooltip` Directive

The component is a convenience wrapper. The directive works on any element inside an Alpine tree, and is what Button, Kbd, Breadcrumbs, Editor and the collapsed sidebar use:

```blade
<span x-data x-tooltip="Any element can carry one"></span>
<img x-data x-tooltip="Even an image" src="...">
<x-button tooltip="Buttons have a prop for it" text="Save" />
```

Every option below is available to the directive as a plain attribute, so it also works on components that expose only a `tooltip` prop:

| Attribute               | Description                                                     |
|-------------------------|-----------------------------------------------------------------|
| `data-position`         | Balloon position; same values as the `position` prop            |
| `data-tooltip-delay`    | Delay step; same values as the `delay` prop                     |
| `data-tooltip-color`    | Balloon color; same values as the `balloon` prop                |
| `data-tooltip-disabled` | Suppresses the tooltip while truthy; watched, so it reacts live |

```blade
<x-button tooltip="Opens right away" data-tooltip-delay="flash" data-tooltip-color="emerald" text="Save" />

<span x-tooltip="Hidden while the menu is open"
      x-bind:data-tooltip-disabled="$store['tsui.side-bar'].open"></span>
```

## Allowed Positions

`auto`, `auto-start`, `auto-end`, `top`, `top-start`, `top-end`, `bottom`, `bottom-start`, `bottom-end`, `left`, `left-start`, `left-end`, `right`, `right-start`, `right-end`

The requested position is a preference, not a guarantee. When it does not fit, the balloon flips to the opposite side and slides along the cross axis to stay inside the viewport. The `auto` family picks the side with the most room to begin with.

Long text wraps rather than pushing the balloon away from its trigger: the balloon is capped at `min(20rem, calc(100vw - 2rem))`.

## Delay

| Name     | Delay           |
|----------|-----------------|
| `slow`   | 400ms           |
| `fast`   | 150ms (default) |
| `faster` | 75ms            |
| `flash`  | 0               |

Applies to the pointer only. Keyboard focus and taps always open immediately.

## Balloon Colors

Accepts any palette key, plus `black`:

`black`, `primary`, `secondary`, `slate`, `gray`, `zinc`, `neutral`, `stone`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `mauve`, `olive`, `mist`, `taupe`

The color is resolved as `var(--color-<name>-600)`, so a palette the application defines in its own `@theme` works here too.

Without a color, the balloon is high contrast and follows the theme: dark on light, light on dark. With a color, it keeps that color in both themes.

`<x-reaction balloon="...">` accepts the same list. See [Reaction](reaction.md#balloon-colors).

## Behavior

| Trigger        | Opens                     | Closes                             |
|----------------|---------------------------|------------------------------------|
| Mouse          | On hover, after the delay | On leave; scrolling repositions it |
| Touch          | On tap, immediately       | Tap outside, or scroll             |
| Keyboard focus | Immediately               | On blur, or <kbd>Escape</kbd>      |

A single balloon is shared by the whole page, so only one is ever visible. While open, the trigger carries `aria-describedby` pointing at the balloon, which carries `role="tooltip"`.

## Global Configuration

Under `components.tooltip` in `config/tallstackui.php`:

| Key     | Type         | Default | Description                                         |
|---------|--------------|---------|-----------------------------------------------------|
| `delay` | string\|null | null    | Default delay step for every tooltip on the page    |
| `color` | string\|null | null    | Default balloon color for every tooltip on the page |

```php
'tooltip' => [
    Components\Tooltip\Component::class,
    [
        'delay' => 'faster',
        'color' => null,
    ],
],
```

Both reach every `x-tooltip`, including the ones rendered by other components. These are defaults: the inline prop or attribute always wins.

The balloon also honors `TallStackUi::customize()->globals()->flash()` (including `only`/`except`): the CSS transition is turned off through a `data-instant` attribute, so the balloon appears and disappears instantly. Like the settings above, the flag travels through the `data-tsui-*` attributes on the script tag and is frozen when the view compiles.

## Validation Constraints

- The `position` must be one of the allowed positions listed above.
- The `delay` must be one of `slow`, `fast`, `faster`, `flash`.
- The `balloon` must be one of the colors listed above.

## Styling the Balloon

The balloon is created by JavaScript and shared by triggers that have no component behind them, so it is **not** reachable through `TallStackUi::customize()`. It is styled in `css/plugins/tooltip.css` and overridden through a stable selector:

```css
[data-tsui-tooltip] {
    border-radius: 0;
    font-size: 0.8125rem;
}

[data-tsui-tooltip] > [data-arrow] {
    display: none;
}
```

While open, the balloon carries `data-side` with the side it settled on (`top`, `bottom`, `left`, `right`) and `data-color` when a color was asked for.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance. It covers the trigger icon; the balloon is styled as described above.

### Customization

```php
TallStackUi::customize()
    ->tooltip()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                     |
|------------|---------------------------------------------|
| wrapper    | Outer inline-flex container, non-selectable |
| sizes.xs   | Extra-small icon dimensions                 |
| sizes.sm   | Small icon dimensions                       |
| sizes.md   | Medium icon dimensions                      |
| sizes.lg   | Large icon dimensions                       |
