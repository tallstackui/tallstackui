# Command Palette ID Support

## Problem

When multiple `<x-command-palette>` components exist on the same page, `$tsui.open.commandPalette()` opens all of them because it dispatches a fixed `command-palette-open` window event with no targeting.

## Solution

Add an optional `id` property (default: `'command-palette'`) following the existing Modal/Slide pattern. Event names become ID-based so each instance can be targeted independently.

## Changes

### 1. PHP Component (`src/Components/CommandPalette/Component.php`)

- Add `public ?string $id = 'command-palette'` to the constructor
- Add `#[PassThroughRuntime(CommandPaletteRuntime::class)]` attribute

### 2. New Runtime Class (`src/Support/Runtime/Components/CommandPaletteRuntime.php`)

Identical pattern to `ModalRuntime`:

```php
class CommandPaletteRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'event' => $event = str($this->data('id'))->slug()->kebab(),
            'open' => $event.'-open',
            'close' => $event.'-close',
        ];
    }
}
```

### 3. Blade Template (`src/resources/views/components/command-palette/main.blade.php`)

Replace fixed event listeners with Runtime-generated ones:

```blade
<!-- Before -->
x-on:command-palette-open.window="open()"
x-on:command-palette-close.window="close()"

<!-- After -->
x-on:command-palette:{{ $open }}.window="open()"
x-on:command-palette:{{ $close }}.window="close()"
```

Pass `id` to Alpine component as a new parameter.

### 4. Alpine.js (`src/Components/CommandPalette/alpine.js`)

- Accept `id` as new parameter (7th position)
- Store `this.id = id` for use in event dispatches
- Update `overflow()` and `register_ui_element()` calls to use `id`
- Update lifecycle event dispatches to include ID:
  - `event('command-palette:open')` becomes `event(`command-palette:${id}:open`)`
  - `event('command-palette:close')` becomes `event(`command-palette:${id}:close`)`
  - `event('command-palette:select')` becomes `event(`command-palette:${id}:select`)`

### 5. Global Helpers (`js/globals/globals.js`)

Accept optional ID parameter with default:

```javascript
commandPalette: (id = 'command-palette') => event(`command-palette:${id}-open`, null, false),
commandPalette: (id = 'command-palette') => event(`command-palette:${id}-close`, null, false),
```

### 6. Alpine Registration (`js/tallstackui.js`)

Update the component registration to pass the new `id` parameter from the Blade template.

### 7. Tests

**Feature tests**: Add test verifying the `id` attribute renders in output.

**Browser tests**: Update all existing tests to pass `id` to the helper. Add a new test with two Command Palettes verifying only the targeted one opens.

### 8. Documentation (`.ai/components/command-palette.md`)

- Add `id` to the Attributes table
- Update JavaScript Control section with ID parameter examples
- Update Lifecycle Events section with ID-based event names

## Backwards Compatibility

- `$tsui.open.commandPalette()` without arguments defaults to `'command-palette'` ID
- `<x-command-palette>` without `id` defaults to `'command-palette'`
- Existing code continues working unchanged
