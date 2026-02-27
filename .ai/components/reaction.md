# TallStackUI: Reaction

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

An emoji reaction picker component using Google Noto Emoji icons. Displays a floating panel of emoji options that users can click to react. Supports animated GIF emojis, filtering to specific reactions, and a quantity counter.

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

## Validation Constraints

- The `react-method` attribute must not be blank.
- The `only` array may only contain valid emoji names from the supported list.
- The `position` must be one of the allowed positions listed above.

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
