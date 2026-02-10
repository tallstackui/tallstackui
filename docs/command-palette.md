# Command Palette

A global search overlay component that provides keyboard-driven API search functionality.

## Basic Usage

```blade
<x-command-palette request="https://api.example.com/search"
                   select="label:name|value:id" />
```

The component requires two attributes:

- `request` — The API endpoint URL (string or array)
- `select` — Maps API response fields to display properties

## Request Configuration

### Simple URL

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

- `url` (required) — The endpoint URL
- `method` (optional) — `get` (default) or `post`
- `params` (optional) — Additional query/body parameters

## Select Mapping

The `select` attribute maps API response fields to the component's display slots:

```
label:fieldName|value:fieldName|description:fieldName|image:fieldName
```

| Key           | Purpose                          | Default       |
|---------------|----------------------------------|---------------|
| `label`       | Main display text                | `label`       |
| `value`       | Unique identifier                | `value`       |
| `description` | Secondary text below the label   | `description` |
| `image`       | Avatar/image URL                 | `image`       |

### Example

API returns:

```json
[
  { "name": "John Doe", "id": 1, "role": "Engineer", "avatar": "https://..." }
]
```

```blade
<x-command-palette request="/api/users"
                   select="label:name|value:id|description:role|image:avatar" />
```

## Recycle Mode

Keep previous search results visible when reopening the palette:

```blade
<x-command-palette request="/api/search"
                   select="label:name|value:id"
                   recycle />
```

Without `recycle`, the results list clears every time the palette opens.

## Programmatic Open/Close

Open or close the palette from any Alpine.js context:

```blade
<x-button x-on:click="$commandPaletteOpen()">Search</x-button>
<x-button x-on:click="$commandPaletteClose()">Close</x-button>
```

Or via browser events:

```blade
<x-button x-on:click="$dispatch('command-palette-open')">Open</x-button>
<x-button x-on:click="$dispatch('command-palette-close')">Close</x-button>
```

## Selection Events

When a user selects an option, a `tallstackui:command-palette` window event is dispatched:

```blade
<div x-on:tallstackui:command-palette.window="handleSelection($event.detail)">
    <x-command-palette request="/api/search" select="label:name|value:id" />
</div>
```

The `$event.detail` contains the selected option's data (all internal keys are stripped).

### Livewire Integration

```blade
<div x-on:tallstackui:command-palette.window="$wire.call('onSelect', $event.detail)">
    <x-command-palette request="/api/search" select="label:name|value:id" />
</div>
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

## Keyboard Navigation

| Key       | Action                    |
|-----------|---------------------------|
| `Ctrl+K`  | Toggle palette (default)  |
| `↑` / `↓` | Navigate results          |
| `Enter`   | Select highlighted option |
| `Escape`  | Close palette             |

## Configuration

Publish or override in `config/tallstackui.php`:

```php
'command-palette' => [
    TallStackUi\Components\CommandPalette\Component::class,
    [
        // Controls the z-index of the overlay
        'z-index' => 'z-50',

        // Background blur effect (false, 'sm', 'md', 'lg', 'xl')
        'blur' => false,

        // Allow page scroll when palette is open
        'overflow' => false,

        // Keyboard shortcut to toggle ('ctrl.k', 'ctrl.shift.p', 'meta.k')
        'shortcut' => 'ctrl.k',

        // Prevent closing by clicking outside
        'persistent' => false,

        // Hide keyboard hints in the footer
        'elements' => false,

        // Scrollbar style for results list (null, 'soft', 'custom')
        'scrollbar' => null,
    ],
],
```

## Mobile Behavior

On mobile devices:

- The palette appears at the bottom of the screen (sheet-like)
- Keyboard hints in the footer are hidden
- The box uses top-rounded corners for a native feel

## Soft Customization

All visual blocks can be customized:

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
    ->block('option.content', '...')
    ->block('option.label', '...')
    ->block('option.description', '...')
    ->block('empty', '...')
    ->block('footer', '...');
```
