# TallStackUI: Reaction

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

An emoji reaction picker using Google Noto Emoji icons. Displays a floating panel of emoji options that users can click to react. Supports animated GIF emojis, filtering to specific reactions, a quantity counter, a colored panel, a named open/close animation, and optional hover to open.

## Basic Usage

```blade
<x-reaction wire:model="reactions" />
```

```blade
<x-reaction wire:model="reactions" :only="['heart', 'thumbs-up', 'fire']" animated />
```

```blade
<x-reaction wire:model="reactions" quantity react-method="addReaction" position="bottom" />
```

```blade
<x-reaction delay="faster" balloon="red" hover />
```

## Available Emoji Names

`smile`, `laugh`, `love`, `screaming`, `rage`, `pray`, `thumbs-up`, `thumbs-down`, `heart`, `broken-heart`, `clap`, `rocket`, `fire`, `mind-blown`, `sick`, `poop`, `eyes`, `party-popper`, `clown`, `check-mark`

## Attributes

| Attribute    | Type                        | Default | Description                                                                                                                     |
|--------------|-----------------------------|---------|---------------------------------------------------------------------------------------------------------------------------------|
| only         | array\|null                 | null    | Array of emoji names to display (shows all 20 when null)                                                                        |
| animated     | bool                        | false   | Uses animated GIF emojis instead of static PNG                                                                                  |
| quantity     | ComponentSlot\|string\|null | null    | When set as a string, displays the reaction count from the wire:model data. When used as a slot, renders custom quantity markup |
| react-method | string                      | 'react' | Livewire method name called when an emoji is clicked                                                                            |
| position     | string\|null                | 'auto'  | Floating panel position relative to the button                                                                                  |
| delay        | string\|null                | null    | Panel open/close animation. Null falls back to the config, then to `fast` (150ms). Allowed: `slow`, `fast`, `faster`, `flash`   |
| balloon      | string\|null                | null    | Panel color. Null falls back to the config, then to the high-contrast default. Same palette keys as the tooltip `balloon` prop  |
| hover        | bool\|null                  | null    | Opens the panel when the pointer rests on the trigger. Null falls back to the config, then to `false`. Touch still uses tap     |

## Slots

| Slot      | Description                                                                                     |
|-----------|-------------------------------------------------------------------------------------------------|
| (default) | Custom content for the trigger button preview area (replaces the default first-3-emoji preview) |
| quantity  | Custom markup for the quantity display                                                          |

## Alpine.js Events

| Event      | Description                            |
|------------|----------------------------------------|
| x-on:react | Fires when a reaction emoji is clicked |

## Allowed Positions

`auto`, `auto-start`, `auto-end`, `top`, `top-start`, `top-end`, `bottom`, `bottom-start`, `bottom-end`, `left`, `left-start`, `left-end`, `right`, `right-start`, `right-end`

## Delay

Same named steps as the tooltip. Here they control the **panel animation**, not a pointer wait:

| Name     | Duration        |
|----------|-----------------|
| `slow`   | 400ms           |
| `fast`   | 150ms (default) |
| `faster` | 75ms            |
| `flash`  | 0 (instant)     |

```blade
<x-reaction delay="faster" />
<x-reaction delay="flash" />
```

## Balloon Colors

Accepts the same palette as the tooltip `balloon` prop, plus `black`:

`black`, `primary`, `secondary`, `slate`, `gray`, `zinc`, `neutral`, `stone`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `mauve`, `olive`, `mist`, `taupe`

The fill is `var(--color-<name>-600)` and the border is `var(--color-<name>-700)`, so a palette the application defines in its own `@theme` works here too. `black` maps both to `var(--color-black)`. The arrow inherits both.

Without a color, the panel is high contrast and follows the theme: `dark-900` / `dark-700` on light, `dark-800` / `dark-600` on dark. With a color, it keeps that color in both themes.

```blade
<x-reaction balloon="red" />
<x-reaction balloon="emerald" delay="faster" />
```

See [Tooltip](tooltip.md#balloon-colors).

## Hover

`hover` opens the panel on `pointerenter` and closes it on `pointerleave`, with a 300ms grace period so the pointer can cross the offset gap to the panel. Restricted to `pointerType === 'mouse'`: a tap still toggles on touch. A mouse click on the trigger does not toggle while hover is on, because the pointer already opened it.

```blade
<x-reaction hover />
```

## Behavior

| Trigger     | Opens                        | Closes                                   |
|-------------|------------------------------|------------------------------------------|
| Click       | On click (the default)       | Click outside, or <kbd>Escape</kbd>      |
| Mouse hover | On enter, when `hover` is on | On leave, after 300ms; <kbd>Escape</kbd> |
| Touch       | On tap                       | Tap outside, or <kbd>Escape</kbd>        |

## Global Configuration

Under `components.reaction` in `config/tallstackui.php`:

| Key       | Type         | Default | Description                                           |
|-----------|--------------|---------|-------------------------------------------------------|
| `delay`   | string\|null | null    | Default panel animation step                          |
| `balloon` | string\|null | null    | Default panel color                                   |
| `hover`   | bool         | false   | Opens the panel when the pointer rests on the trigger |

```php
'reaction' => [
    Components\Reaction\Component::class,
    [
        'delay' => 'faster',
        'balloon' => null,
        'hover' => false,
    ],
],
```

These are defaults. Resolution is always **inline prop → config → (delay only) flash global**. The flash global (`TallStackUi::customize()->globals()->flash()`, including `only`/`except`) only fills `delay` when neither the tag nor the config named one. It turns the CSS transition off through `data-instant`.

## Validation Constraints

- The `react-method` attribute must not be blank.
- The `only` array may only contain valid emoji names from the supported list.
- The `position` must be one of the allowed positions listed above.
- The `delay` must be one of `slow`, `fast`, `faster`, `flash`.
- The `balloon` must be one of the colors listed above.

## Livewire Integration

When an emoji is clicked, the `react` method is triggered on your Livewire component, receiving the emoji name as a parameter:

```php
use Livewire\Component;

class MyComponent extends Component
{
    public function react(string $reaction): void
    {
        // $reaction will be the emoji name: 'thumbs-up', 'thumbs-down', etc.
        // Your logic to persist the reaction here...
    }
}
```

To use a different method name, set the `react-method` attribute:

```blade
<x-reaction react-method="addReaction" />
```

Your Livewire component must then have an `addReaction(string $reaction)` method instead.

### Quantity with Real-Time Binding

To show a live-updating reaction count, bind a Livewire property with `wire:model`:

```blade
<!-- $quantity is an integer Livewire public property -->
<x-reaction wire:model="quantity" :$quantity />
```

## Styling the Panel

The panel is created by JavaScript, so it is **not** reachable through `TallStackUi::customize()`. It is styled in `css/plugins/popover.css` and overridden through a stable selector:

```css
[data-tsui-popover] {
    background-color: #101828;
    border-color: #1d2939;
}
```

Its position is resolved by the same placement helper the tooltip uses: the requested `position` flips to the opposite side when it does not fit and slides along the cross axis to stay inside the viewport. Clicking outside or pressing <kbd>Escape</kbd> closes it. The panel carries an arrow pointing at the trigger; it inherits the panel's background and border color, so the override above restyles it as well.

While open, the panel carries:

| Attribute      | When                                                                 |
|----------------|----------------------------------------------------------------------|
| `data-side`    | Always: the side it settled on (`top`, `bottom`, `left`, `right`)    |
| `data-delay`   | A named animation other than `flash`                                 |
| `data-instant` | `delay="flash"`, or the flash global when nothing else named a delay |
| `data-color`   | A `balloon` color was asked for                                      |

The animation duration is `--tsui-popover-duration`. A colored panel sets `--tsui-popover-bg` and `--tsui-popover-border` from JavaScript.

The emoji grid inside the panel is regular soft customization, through the `box.*` and `icon` blocks below.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->reaction()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name     | Purpose                                                          |
|----------------|------------------------------------------------------------------|
| wrapper.first  | Trigger button styles (inline-flex, rounded, padding)            |
| wrapper.second | Preview emoji row layout (flex, overlap spacing)                 |
| box.grid       | Emoji panel layout when more than 5 emojis (CSS grid, 5 columns) |
| box.inline     | Emoji panel layout when 5 or fewer emojis (inline flex)          |
| image          | Preview emoji thumbnail styles (rounded, background, ring)       |
| icon           | Emoji icon dimensions inside the floating panel                  |
| quantity       | Quantity counter text styles                                     |
