# TallStackUI: Command Palette

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A searchable command palette overlay that fetches results from a server endpoint, supporting keyboard navigation, images, icons, descriptions, grouped results, and disabled options. Triggered by a configurable keyboard shortcut (default: Ctrl+K). Search input is debounced (300ms) to avoid excessive API calls.

## Usage Patterns

The command palette supports two usage patterns:

### Global Usage (Layout-Level)

Place the component in your application layout (e.g., `resources/views/components/layouts/app.blade.php`) for app-wide access. Combine with an `actionable` class in config for server-side selection handling:

```blade
{{-- resources/views/components/layouts/app.blade.php --}}
<body>
    {{ $slot }}
    <x-command-palette />
</body>
```

```php
// config/tallstackui.php
'command-palette' => [
    TallStackUi\Components\CommandPalette\Component::class,
    [
        'actionable' => App\Actions\CommandPaletteAction::class,
        'request' => '/api/search',
        // ...
    ],
],
```

### Page-Specific Usage (Inline Events)

Place the component on specific pages and handle selection with inline `x-on:select`:

```blade
<x-command-palette request="/api/search"
                   select="label:name|value:id"
                   x-on:select="handleSelection($event.detail)" />
```

### Multiple Instances

Use the `id` attribute to place multiple command palettes on the same page and target them independently:

```blade
<x-command-palette id="search" request="/api/search" select="label:name|value:id" />
<x-command-palette id="actions" request="/api/actions" select="label:name|value:id" />

<x-button x-on:click="$tsui.open.commandPalette('search')">Search</x-button>
<x-button x-on:click="$tsui.open.commandPalette('actions')">Actions</x-button>
```

## Basic Usage

```blade
<x-command-palette request="/api/search" />
```

```blade
<x-command-palette :request="['url' => '/api/search', 'method' => 'get']"
                   select="label:name|value:id|description:summary|image:avatar" />
```

```blade
<x-command-palette request="search.route.name"
                   :recycle="false"
                   :placeholders="['search' => 'Type to search...', 'empty' => 'No results found.']">
    <x-slot:empty>
        <div class="p-4 text-center text-gray-500">Nothing here.</div>
    </x-slot:empty>
</x-command-palette>
```

```blade
{{-- Centered: vertically centers on mobile with fully rounded corners --}}
<x-command-palette request="/api/search" centered />
```

## Attributes

| Attribute    | Type                | Default                                                                     | Description                                                                                                 |
|--------------|---------------------|-----------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------|
| id           | string\|null        | 'command-palette'                                                           | Unique identifier for targeting with `$tsui.open.commandPalette(id)`. Required when using multiple palettes |
| request      | string\|array\|null | null (from config)                                                          | Data source URL (string, route name, or array with `url`, `method`, `params` keys)                          |
| options      | Collection\|array   | []                                                                          | Static options array (each item should have label, value, and optionally description, image, icon)          |
| selectable   | array\|null         | []                                                                          | Parsed field mapping (auto-generated from `select`)                                                         |
| placeholders | array\|null         | null                                                                        | Override default placeholder texts (keys: `search`, `empty`, `navigate`, `select`, `close`)                 |
| recycle      | bool\|null          | true (from config)                                                          | When true, preserves previous search results when reopening the palette                                     |
| shortcut     | string\|null        | 'ctrl.k' (from config)                                                      | Keyboard shortcut in dot notation (e.g., `ctrl.k`, `ctrl.shift.p`, `meta.k`). Inline overrides config       |
| select       | string\|null        | 'label:label\|value:value\|description:description\|image:image\|icon:icon' | Field mapping string for option data (format: `label:key\|value:key\|description:key\|image:key\|icon:key`) |
| centered     | bool\|null          | false (from config)                                                         | When true, centers the palette vertically on mobile with rounded corners on all sides                       |

## Slots

| Slot  | Description                                                                             |
|-------|-----------------------------------------------------------------------------------------|
| empty | Custom content displayed when search yields no results (replaces default empty message) |

## Validation Constraints

- The `request` attribute must be configured either as an inline attribute or in the config file.
- When `request` is an array, the `url` key is required.
- When `request` is an array with a `method` key, it must be `get` or `post`.
- When `request` is an array with a `params` key, it must be a non-empty array.
- When `actionable` is set in config, the class must exist and be invocable (`__invoke`).

## Option Features

### Disabled Options

Options can be marked as disabled in the API response. Disabled options are displayed with muted styles and cannot be selected:

```json
[
    { "label": "Active Item", "value": 1 },
    { "label": "Unavailable Item", "value": 2, "disabled": true }
]
```

## Selection Handling

When a user selects an option, the component uses a priority chain to determine how to handle it:

1. **Inline event** (`x-on:select`) — If the component has an `x-on:select` listener, dispatches via Alpine's `$dispatch()` (component-scoped, not window). The actionable and global event are suppressed.
2. **Actionable** (`config actionable`) — If an actionable class is configured, sends a POST request to a Laravel signed route. The server invokes the class and returns a `Callback` response (redirect or event).
3. **Global event** (fallback) — Dispatches a `command-palette:{id}:select` window event with the selected option data.

In all cases, internal keys prefixed with `__` are stripped from the option data before dispatching.

### Inline Event (x-on:select)

```blade
<x-command-palette request="/api/search"
                   select="label:name|value:id"
                   x-on:select="handleSelection($event.detail)" />
```

The `$event.detail` contains all fields from the selected option (internal keys prefixed with `__` are stripped).

### Actionable (Server-Side Action)

Configure an invocable class in `config/tallstackui.php`:

```php
'command-palette' => [
    TallStackUi\Components\CommandPalette\Component::class,
    [
        'actionable' => App\Actions\CommandPaletteAction::class,
        // ...
    ],
],
```

The class receives an `ItemSelected` value object and must return a `Callback`:

```php
use TallStackUi\Support\CommandPalette\Callback;
use TallStackUi\Support\CommandPalette\ItemSelected;

class CommandPaletteAction
{
    public function __invoke(ItemSelected $selected): Callback
    {
        return Callback::redirect("/items/{$selected->value}");
    }
}
```

The actionable endpoint uses Laravel's signed URLs (`URL::signedRoute()`) for security. The controller validates the signature with `abort_unless($request->hasValidSignature(), 403)`.

#### ItemSelected Value Object

Immutable DTO implementing `Arrayable`. All properties are `readonly`.

| Property    | Type    | Description                          |
|-------------|---------|--------------------------------------|
| search      | string  | The search term at time of selection |
| label       | mixed   | The selected option's label          |
| value       | mixed   | The selected option's value          |
| description | ?string | Optional description text            |
| image       | ?string | Optional image URL                   |
| icon        | ?string | Optional icon HTML                   |
| additional  | array   | Extra fields from the API response   |

`toArray()` returns all properties as an associative array.

#### Callback Response Object

```php
// Redirect to an internal page
Callback::redirect('/dashboard');

// Redirect to an external URL (opens in new tab)
Callback::redirect('https://example.com')->external();

// Redirect using Livewire.navigate (SPA-style navigation)
Callback::redirect('/dashboard')->navigate();

// Dispatch a browser event
Callback::event('item-selected');

// Dispatch a browser event with parameters
Callback::event('item-selected')->with(['id' => $selected->value]);
```

The JavaScript receives the full callback response structure:

```js
{
    type: 'redirect' | 'event',
    data: { to: '...' } | { name: '...', params: {...} },
    external: boolean,
    navigate: boolean
}
```

When `navigate` is `true`, the redirect uses `Livewire.navigate()` for SPA-style navigation without a full page reload. Falls back to `window.location.href` if Livewire is not available.

### Global Event (Fallback)

When no inline `x-on:select` or actionable is configured:

```blade
{{-- Default id --}}
<div x-on:command-palette:command-palette:select.window="handleSelection($event.detail)">
    <x-command-palette request="/api/search" select="label:name|value:id" />
</div>

{{-- Custom id --}}
<div x-on:command-palette:search:select.window="handleSelection($event.detail)">
    <x-command-palette id="search" request="/api/search" select="label:name|value:id" />
</div>
```

## Lifecycle Events

Open/close events are always dispatched regardless of selection mode. Window events include the component's `id` in the event name:

| Event                        | Channel     | Trigger        |
|------------------------------|-------------|----------------|
| `open` (inline)              | `$dispatch` | Palette opens  |
| `close` (inline)             | `$dispatch` | Palette closes |
| `command-palette:{id}:open`  | `window`    | Palette opens  |
| `command-palette:{id}:close` | `window`    | Palette closes |

```blade
{{-- Inline lifecycle events --}}
<x-command-palette request="/api/search"
                   select="label:name|value:id"
                   x-on:open="console.log('opened')"
                   x-on:close="console.log('closed')" />

{{-- Global lifecycle events (default id) --}}
<div x-on:command-palette:command-palette:open.window="console.log('opened')"
     x-on:command-palette:command-palette:close.window="console.log('closed')">
    <x-command-palette request="/api/search" select="label:name|value:id" />
</div>

{{-- Global lifecycle events (custom id) --}}
<div x-on:command-palette:search:open.window="console.log('search opened')"
     x-on:command-palette:search:close.window="console.log('search closed')">
    <x-command-palette id="search" request="/api/search" select="label:name|value:id" />
</div>
```

## Keyboard Navigation

The component supports full keyboard navigation:

- **Arrow Up/Down** — Navigate through search results, auto-scrolling into view
- **Enter** — Select the highlighted option
- **Escape** — Close the palette
- Mouse hover updates the highlighted option, but does not conflict with keyboard navigation (the component tracks input mode internally)

## Configuration

In `config/tallstackui.php` under `components.command-palette`:

| Option     | Type                | Default  | Description                                                                                |
|------------|---------------------|----------|--------------------------------------------------------------------------------------------|
| actionable | string\|null        | null     | Invocable PHP class for server-side action handling                                        |
| request    | string\|array\|null | null     | Default data source for all command palettes                                               |
| z-index    | string              | 'z-50'   | Default z-index class                                                                      |
| blur       | bool\|string        | false    | Background blur effect (`false` disables, `true` defaults to 'sm', or 'sm'/'md'/'lg'/'xl') |
| overflow   | bool                | false    | When true, avoids hiding body overflow                                                     |
| shortcut   | string              | 'ctrl.k' | Keyboard shortcut in dot notation (e.g., `ctrl.k`, `ctrl.shift.p`, `meta.k`)               |
| recycle    | bool                | true     | When true, preserves previous results when reopening                                       |
| elements   | bool                | true     | When true, shows keyboard hint elements in the footer                                      |
| scrollbar  | bool                | true     | When true, applies a custom minimal scrollbar to the results list                          |
| centered   | bool                | false    | When true, centers the palette vertically on mobile with all corners rounded               |

## JavaScript Control

```js
// Default (targets id="command-palette")
$tsui.open.commandPalette()
$tsui.close.commandPalette()

// Target specific palette by ID
$tsui.open.commandPalette('search')
$tsui.close.commandPalette('search')
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->commandPalette()
    ->block('backdrop', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                         |
|--------------------|-------------------------------------------------|
| backdrop           | Fixed overlay background behind the palette     |
| blur.sm            | Small backdrop blur effect                      |
| blur.md            | Medium backdrop blur effect                     |
| blur.lg            | Large backdrop blur effect                      |
| blur.xl            | Extra-large backdrop blur effect                |
| wrapper            | Fixed container that positions the palette      |
| positions.bottom   | Bottom-aligned position classes (default)       |
| positions.center   | Center-aligned position classes (centered mode) |
| box                | Main palette card with shadow                   |
| box.radius.default | Border radius for default (bottom) position     |
| box.radius.center  | Border radius for centered position             |
| input.wrapper      | Flex container for the search input area        |
| input.icon         | Search magnifying glass icon styles             |
| input.base         | Search text input field styles                  |
| input.loading      | Loading spinner container                       |
| list               | Scrollable results list container               |
| option.base        | Base styles for each result option              |
| option.active      | Active/highlighted option styles                |
| option.disabled    | Disabled option styles                          |
| option.image       | Option image (avatar) styles                    |
| option.icon        | Option icon container styles                    |
| option.content     | Option text content wrapper                     |
| option.label       | Option label text styles                        |
| option.description | Option description text styles                  |
| empty              | Empty state message styles                      |
| footer             | Keyboard hints footer container                 |
