# TallStackUI: Password

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A password input component with toggle visibility, strength indicator rules (minimum length, mixed case, numbers, symbols), password generator, and caps lock detection. Rules are displayed in a floating panel and validate as the user types.

## Basic Usage

```blade
<x-password wire:model="password" label="Password" />
```

```blade
<x-password wire:model="password" label="Password" :rules="true" generator />
```

```blade
<x-password wire:model="password"
            label="Password"
            :rules="['min:12', 'symbols:!@#$', 'numbers', 'mixed']"
            generator />
```

```blade
<x-password wire:model="password" label="Password" :rules="true" typing-only />
```

```blade
<x-password wire:model="password" label="Password" :rules="true" generator="password_confirmation" />

<x-password wire:model="password_confirmation" label="Confirm password" :rules="true" />
```

## Attributes

| Attribute   | Type                          | Default | Description                                                                                                                                             |
|-------------|-------------------------------|---------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| label       | string\|ComponentSlot\|null   | null    | Label text displayed above the input                                                                                                                    |
| hint        | string\|ComponentSlot\|null   | null    | Hint text displayed below the input                                                                                                                     |
| rules       | Collection\|array\|bool\|null | null    | Password strength rules. Pass true for defaults from config, or an array of rule strings (e.g., 'min:8', 'symbols', 'numbers', 'mixed')                 |
| mixed-case  | bool\|null                    | false   | When true, disables the caps lock indicator icon                                                                                                        |
| generator   | bool\|string\|null            | null    | Shows a password generator button that creates a random password matching the rules. A string names the field the generated password is also written to |
| invalidate  | bool\|null                    | null    | Prevents displaying validation error messages for this input                                                                                            |
| typing-only | bool\|null                    | null    | When true, the rules floating panel only appears while typing (not on focus)                                                                            |
| disabled    | bool                          | false   | Locks the input. The value is not submitted.                                                                                                            |
| readonly    | bool                          | false   | Locks the input. The value is still submitted.                                                                                                          |

## Alpine.js Events

| Event         | Description                                             |
|---------------|---------------------------------------------------------|
| x-on:reveal   | Triggered when the password visibility is toggled       |
| x-on:generate | Triggered when the password generator button is clicked |

## Validation Constraints

- The `generator` requires the `rules` to be set. You cannot use the generator without defining password rules.
- The `generator` target cannot be an empty string.

## Configuration

Default password rules can be configured in `config/tallstackui.php`:

```php
'password' => [
    'rules' => [
        'min' => '8',
        'mixed' => true,
        'numbers' => true,
        'symbols' => '!@#$%^&*()_+-=',
    ],
],
```

## Rules & Generator Details

### Strength Rules

```blade
<x-password :rules="['min:8', 'symbols:!@#', 'numbers', 'mixed']" />
```

Available rules: `min:{length}`, `symbols` or `symbols:{chars}`, `numbers`, `mixed` (uppercase + lowercase).

### Password Generator

When enabled, adds a generate button that creates passwords matching the specified rules:

```blade
<x-password generator :rules="['min:5', 'symbols:!@']" />
```

### Auto-Filling a Confirmation Field

Passing a string to `generator` also writes the generated password into another field, so
the confirmation does not have to be wired by hand:

```blade
<x-password wire:model="password" :rules="true" generator="password_confirmation" />

<x-password wire:model="password_confirmation" :rules="true" />
```

The target is resolved by `id` first, then by `x-ref`. CSS selectors are not accepted. The
input renders its `id` from the bound property, so a field bound to `password_confirmation`
is already addressable by that name without an explicit `id`.

Any field works as a target, not only another password:

```blade
<x-password wire:model="password" :rules="true" generator="password_confirmation" />

<x-input wire:model="password_confirmation" />
```

When the target is another `<x-password>`, the fill goes through its own state, so the
input, the bound model and the rules checklist stay in sync. Any other element receives the
value plus an `input` event, which is what `wire:model` listens to, so it also works outside
Livewire.

The generating field reveals the password; the target stays masked. The `generate` event is
still dispatched after the fill, so an `x-on:generate` handler keeps working and can
override the target. A target that cannot be found is reported in the console and does not
prevent the generating field from being filled.

### Custom Generator Algorithm

Override the default password generator with a custom JavaScript function:

```html
<script>
    window.TallStackUi = window.TallStackUi || {};

    window.TallStackUi.passwordGenerator = function (min, mixed, numbers, symbols) {
        return 'your-generated-password';
    };
</script>
```

### Event Payload Details

```blade
<x-password generator
            :rules="['min:8', 'symbols', 'numbers', 'mixed']"
            x-on:reveal="alert(`Password Revealed: ${$event.detail.status}`)"
            x-on:generate="alert(`Password Generated: ${$event.detail.password}`)" />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('password')
    ->block('icon.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                | Purpose                                          |
|---------------------------|--------------------------------------------------|
| icon.wrapper              | Visibility toggle and generator button container |
| icon.class                | Icon dimensions and cursor style                 |
| floating.default          | Base floating panel positioning                  |
| floating.class            | Floating panel width and padding                 |
| rules.title               | Rules heading text style and color               |
| rules.block               | Rules list flex container                        |
| rules.items.base          | Individual rule item layout and text style       |
| rules.items.icons.error   | Red icon for unmet rules                         |
| rules.items.icons.success | Green icon for met rules                         |
