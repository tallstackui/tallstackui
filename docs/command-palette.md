# Command Palette

A global search overlay component that provides keyboard-driven API search functionality with full keyboard navigation, customizable shortcuts, and dark mode support.

## Basic Usage

```blade
<x-command-palette request="https://api.example.com/search"
                   select="label:name|value:id" />
```

The component requires:

- `request` — The data source (URL string, array, or Laravel named route)
- `select` — Maps API response fields to display properties

## Request Configuration

The `request` can be defined **inline** on the component or **globally** in the config file. Inline always takes priority over config.

### Simple URL (GET)

```blade
<x-command-palette request="https://api.example.com/search"
                   select="label:title|value:id" />
```

### Array Format

```blade
<x-command-palette :request="[
    'url' => 'https://api.example.com/search',
    'method' => 'post',
    'params' => ['category' => 'users'],
]" select="label:name|value:id" />
```

| Key      | Required | Description                        | Default |
|----------|----------|------------------------------------|---------|
| `url`    | Yes      | The endpoint URL                   | —       |
| `method` | No       | HTTP method (`get` or `post`)      | `get`   |
| `params` | No       | Additional query/body parameters   | `[]`    |

### Laravel Named Route

```blade
<x-command-palette request="api.users.search"
                   select="label:name|value:id" />
```

The component automatically resolves Laravel named routes. If the string is not a valid route name, it is treated as a plain URL.

### Global Config

Define `request` in the config file so the component works without inline attributes:

```php
// config/tallstackui.php
'command-palette' => [
    TallStackUi\Components\CommandPalette\Component::class,
    [
        'request' => 'https://api.example.com/search',
        // or a named route:
        // 'request' => 'api.users.search',
        // or an array:
        // 'request' => ['url' => 'https://...', 'method' => 'post'],
    ],
],
```

Then use the component without `request`:

```blade
<x-command-palette select="label:name|value:id" />
```

If `request` is not defined anywhere (inline or config), a validation exception is thrown.

### Priority

Inline `request` always overrides the config value:

```blade
{{-- Uses inline URL, ignores config --}}
<x-command-palette request="https://api.example.com/custom-search"
                   select="label:name|value:id" />
```

## Select Mapping

The `select` attribute maps API response fields to the component's display slots:

```
label:fieldName|value:fieldName|description:fieldName|image:fieldName|icon:fieldName
```

| Key           | Purpose                              | Default       |
|---------------|--------------------------------------|---------------|
| `label`       | Main display text                    | `label`       |
| `value`       | Unique identifier                    | `value`       |
| `description` | Secondary text below the label       | `description` |
| `image`       | Avatar/image URL                     | `image`       |
| `icon`        | HTML/SVG icon (rendered via x-html)  | `icon`        |

When both `image` and `icon` are present on an option, the image takes priority.

### Example

Given an API response:

```json
[
  { "name": "John Doe", "id": 1, "role": "Engineer", "avatar": "https://..." },
  { "name": "Jane Smith", "id": 2, "role": "Designer", "avatar": "https://..." }
]
```

```blade
<x-command-palette request="/api/users"
                   select="label:name|value:id|description:role|image:avatar" />
```

### Icon Support

Options can include an HTML/SVG icon instead of an image. The `icon` field is rendered via `x-html`, so it supports raw SVG or any HTML content:

```json
[
  { "name": "Settings", "id": 1, "icon": "<svg>...</svg>" },
  { "name": "Profile", "id": 2, "icon": "<svg>...</svg>" }
]
```

```blade
<x-command-palette request="/api/actions"
                   select="label:name|value:id|icon:icon" />
```

Map a custom field name:

```blade
<x-command-palette request="/api/actions"
                   select="label:name|value:id|icon:my_icon" />
```

### Disabled Options

Options with `"disabled": true` in the API response are rendered with reduced opacity and cannot be selected.

## Recycle Mode

By default, previous results are preserved when reopening the palette (`recycle` is `true`). To clear results on every open, disable recycle:

```blade
<x-command-palette request="/api/search"
                   select="label:name|value:id"
                   :recycle="false" />
```

Recycle can also be configured globally via config:

```php
'command-palette' => [
    TallStackUi\Components\CommandPalette\Component::class,
    [
        'recycle' => false, // clear results on every open
        // ...
    ],
],
```

Inline `recycle` overrides the config value.

## Programmatic Open/Close

### Alpine.js Helpers

```blade
<x-button x-on:click="$tsui.open.commandPalette()">Search</x-button>
<x-button x-on:click="$tsui.close.commandPalette()">Close</x-button>
```

### Browser Events

```blade
<x-button x-on:click="$dispatch('command-palette-open')">Open</x-button>
<x-button x-on:click="$dispatch('command-palette-close')">Close</x-button>
```

### From JavaScript

```javascript
window.dispatchEvent(new Event('command-palette-open'));
window.dispatchEvent(new Event('command-palette-close'));
```

## Selection Events

When a user selects an option, a `tallstackui:command-palette` window event is dispatched with the selected option's data:

```blade
<div x-on:tallstackui:command-palette.window="handleSelection($event.detail)">
    <x-command-palette request="/api/search" select="label:name|value:id" />
</div>
```

The `$event.detail` contains all fields from the selected option (internal keys prefixed with `__` are stripped).

### Livewire Integration

```blade
<div x-on:tallstackui:command-palette.window="$wire.call('onSelect', $event.detail)">
    <x-command-palette request="/api/search" select="label:name|value:id" />
</div>
```

```php
// In your Livewire component
public function onSelect(array $option): void
{
    // $option = ['name' => 'John Doe', 'id' => 1, 'role' => 'Engineer', ...]
}
```

### Using x-on Directly

You can also listen for the event directly on the component:

```blade
<x-command-palette request="/api/search"
                   select="label:name|value:id"
                   x-on:tallstackui:command-palette.window="handleSelection($event.detail)" />
```

## Empty State

Customize the empty state when no results are found:

```blade
<x-command-palette request="/api/search" select="label:name|value:id">
    <x-slot:empty>
        <p class="text-center py-8 text-gray-500">No matches. Try a different search.</p>
    </x-slot:empty>
</x-command-palette>
```

The default message is "No results found." and can be customized via i18n (see Internationalization below).

## Keyboard Shortcuts

### Default Shortcut

The palette opens/closes with **Ctrl+K** (or **Cmd+K** on Mac) by default.

### Custom Shortcuts

Format: `modifier1.modifier2.key` (dot-separated)

**Available modifiers:** `ctrl`, `meta`, `shift`, `alt`

```blade
<x-command-palette request="/api/search"
                   select="label:name|value:id"
                   shortcut="ctrl.shift.p" />
```

Or globally via config:

```php
'command-palette' => [
    TallStackUi\Components\CommandPalette\Component::class,
    [
        'shortcut' => 'ctrl.shift.p',
        // ...
    ],
],
```

| Shortcut          | Keys                             |
|-------------------|----------------------------------|
| `ctrl.k`          | Ctrl+K / Cmd+K (default)         |
| `ctrl.shift.p`    | Ctrl+Shift+P / Cmd+Shift+P       |
| `meta.k`          | Cmd+K / Ctrl+K                   |
| `alt.space`        | Alt+Space                        |
| `ctrl.shift.f`    | Ctrl+Shift+F / Cmd+Shift+F       |
| `shift.p`          | Shift+P                          |

> **Note:** `ctrl` and `meta` are interchangeable — when either modifier is specified, both `ctrlKey` and `metaKey` are accepted for cross-platform compatibility.

### Keyboard Navigation

| Key       | Action                    |
|-----------|---------------------------|
| `↑` / `↓` | Navigate results          |
| `Enter`   | Select highlighted option |
| `Escape`  | Close palette             |

## Configuration

All options in `config/tallstackui.php` under `components.command-palette`:

```php
'command-palette' => [
    TallStackUi\Components\CommandPalette\Component::class,
    [
        // Data source: URL string, array, or Laravel named route
        'request' => null,

        // Z-index of the overlay
        'z-index' => 'z-50',

        // Background blur effect (false, 'sm', 'md', 'lg', 'xl')
        'blur' => false,

        // Allow page scroll when palette is open
        'overflow' => false,

        // Keyboard shortcut to toggle
        'shortcut' => 'ctrl.k',

        // Prevent closing by clicking outside
        'persistent' => false,

        // Preserve previous results when reopening
        'recycle' => true,

        // Show keyboard hints in the footer
        'elements' => true,

        // Apply a custom minimal scrollbar to the results list
        'scrollbar' => true,
    ],
],
```

| Option       | Type              | Default    | Description                                     |
|--------------|-------------------|------------|-------------------------------------------------|
| `request`    | `string\|array\|null` | `null`     | Global data source (overridden by inline prop)  |
| `z-index`    | `string`          | `z-50`     | Tailwind z-index class for the overlay          |
| `blur`       | `false\|string`   | `false`    | Backdrop blur (`false`, `sm`, `md`, `lg`, `xl`) |
| `overflow`   | `bool`            | `false`    | Allow page scroll when open                     |
| `shortcut`   | `string`          | `ctrl.k`   | Keyboard shortcut to toggle                     |
| `persistent` | `bool`            | `false`    | Prevent closing by clicking outside             |
| `recycle`    | `bool`            | `true`     | Preserve results when reopening                 |
| `elements`   | `bool`            | `true`     | Show keyboard hints footer                      |
| `scrollbar`  | `bool`            | `true`     | Apply custom minimal scrollbar to results list  |

## API Response Format

The API endpoint must return a JSON array of objects. The search term is sent as a `search` query parameter (GET) or body field (POST).

### GET Request

```
GET /api/search?search=john
```

### POST Request

```
POST /api/search
Content-Type: application/json

{ "search": "john" }
```

### Expected Response

```json
[
  {
    "name": "John Doe",
    "id": 1,
    "role": "Engineer",
    "avatar": "https://example.com/avatar.jpg"
  }
]
```

Each object must contain at least the field mapped to `label` in the `select` attribute. Other fields (`description`, `image`) are optional.

### Disabling Options

Include `"disabled": true` on any option to prevent selection:

```json
[
  { "name": "Active User", "id": 1 },
  { "name": "Archived User", "id": 2, "disabled": true }
]
```

## Mobile Behavior

On mobile devices:

- The palette appears at the bottom of the screen (sheet-like)
- Keyboard hints in the footer are hidden (`hidden sm:flex`)
- The box uses top-rounded corners (`rounded-t-xl`) for a native feel

## Internationalization

Translation keys under `ts-ui::messages.command-palette`:

| Key        | Default (en)       | Usage                          |
|------------|--------------------|--------------------------------|
| `search`   | Search...          | Search input placeholder       |
| `empty`    | No results found.  | Default empty state message    |
| `navigate` | navigate           | Footer keyboard hint           |
| `select`   | select             | Footer keyboard hint           |
| `close`    | close              | Footer keyboard hint           |

Translations are available in 15 locales: ar, de, en, es, fr, id, it, km, ms, nl, pl, pt, pt_BR, tr, vi.

## Soft Customization

All visual blocks can be customized via the soft personalization API:

```php
TallStackUi::personalize()
    ->commandPalette()
    ->block('backdrop', '...')
    ->block('wrapper', '...')
    ->block('box', '...')
    ->block('input.wrapper', '...')
    ->block('input.icon', '...')
    ->block('input.base', '...')
    ->block('input.loading', '...')
    ->block('list', '...')
    ->block('option.base', '...')
    ->block('option.active', '...')
    ->block('option.disabled', '...')
    ->block('option.image', '...')
    ->block('option.icon', '...')
    ->block('option.content', '...')
    ->block('option.label', '...')
    ->block('option.description', '...')
    ->block('empty', '...')
    ->block('footer', '...');
```

### Available Blocks

| Block                 | Default Classes                                                                        |
|-----------------------|----------------------------------------------------------------------------------------|
| `backdrop`            | `fixed inset-0 bg-gray-400/75 transform transition-opacity`                            |
| `blur.sm\|md\|lg\|xl` | `backdrop-blur-{size}`                                                                 |
| `wrapper`             | `fixed inset-0 flex items-end sm:items-start justify-center sm:pt-[15vh]`              |
| `box`                 | `w-full max-w-lg overflow-hidden rounded-t-xl sm:rounded-xl bg-white shadow-2xl ...`   |
| `input.wrapper`       | `flex items-center border-b border-dark-100 px-4 dark:border-dark-700`                 |
| `input.icon`          | `h-5 w-5 text-dark-400 dark:text-dark-500`                                             |
| `input.base`          | `h-12 w-full border-0 bg-transparent text-sm ... focus:ring-0 focus:outline-none ...`  |
| `input.loading`       | `flex items-center`                                                                    |
| `list`                | `max-h-72 scroll-py-2 overflow-y-auto p-2`                                             |
| `option.base`         | `flex w-full cursor-pointer items-center gap-x-3 rounded-lg px-3 py-2 text-left`       |
| `option.active`       | `bg-primary-50 dark:bg-dark-700`                                                       |
| `option.disabled`     | `opacity-50 cursor-not-allowed`                                                        |
| `option.image`        | `h-8 w-8 flex-shrink-0 rounded-full object-cover`                                      |
| `option.icon`         | `h-8 w-8 flex-shrink-0 text-dark-400 dark:text-dark-500 [&>svg]:h-full [&>svg]:w-full` |
| `option.content`      | `flex flex-col overflow-hidden`                                                        |
| `option.label`        | `truncate text-sm font-medium text-dark-600 dark:text-dark-300`                        |
| `option.description`  | `truncate text-xs text-dark-500 dark:text-dark-400`                                    |
| `empty`               | `px-4 py-8 text-center text-sm text-dark-500 dark:text-dark-400`                       |
| `footer`              | `hidden sm:flex items-center gap-x-4 border-t border-dark-100 px-4 py-2.5 text-xs ...` |
