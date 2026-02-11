# TallStackUI: Command Palette

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

A searchable command palette overlay that fetches results from a server endpoint, supporting keyboard navigation, images, icons, descriptions, and grouped results. Triggered by a configurable keyboard shortcut (default: Ctrl+K).

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

## Attributes

| Attribute    | Type                | Default                                                                     | Description                                                                                                 |
|--------------|---------------------|-----------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------|
| request      | string\|array\|null | null (from config)                                                          | Data source URL (string, route name, or array with `url`, `method`, `params` keys)                          |
| options      | Collection\|array   | []                                                                          | Static options array (each item should have label, value, and optionally description, image, icon)          |
| selectable   | array\|null         | []                                                                          | Parsed field mapping (auto-generated from `select`)                                                         |
| placeholders | array\|null         | null                                                                        | Override default placeholder texts (keys: `search`, `empty`, `navigate`, `select`, `close`)                 |
| recycle      | bool\|null          | true (from config)                                                          | When true, preserves previous search results when reopening the palette                                     |
| select       | string\|null        | 'label:label\|value:value\|description:description\|image:image\|icon:icon' | Field mapping string for option data (format: `label:key\|value:key\|description:key\|image:key\|icon:key`) |

## Slots

| Slot  | Description                                                                             |
|-------|-----------------------------------------------------------------------------------------|
| empty | Custom content displayed when search yields no results (replaces default empty message) |

## Validation Constraints

- The `request` attribute must be configured either as an inline attribute or in the config file.
- When `request` is an array, the `url` key is required.
- When `request` is an array with a `method` key, it must be `get` or `post`.
- When `request` is an array with a `params` key, it must be a non-empty array.

## Configuration

In `config/tallstackui.php` under `components.command-palette`:

| Option     | Type                | Default  | Description                                                       |
|------------|---------------------|----------|-------------------------------------------------------------------|
| request    | string\|array\|null | null     | Default data source for all command palettes                      |
| z-index    | string              | 'z-50'   | Default z-index class                                             |
| blur       | false\|string       | false    | Background blur effect (false, sm, md, lg, xl)                    |
| overflow   | bool                | false    | When true, avoids hiding body overflow                            |
| shortcut   | string              | 'ctrl.k' | Keyboard shortcut to toggle the palette                           |
| persistent | bool                | false    | When true, prevents closing by clicking outside                   |
| recycle    | bool                | true     | When true, preserves previous results when reopening              |
| elements   | bool                | true     | When true, shows keyboard hint elements in the footer             |
| scrollbar  | bool                | true     | When true, applies a custom minimal scrollbar to the results list |

## JavaScript Control

```js
$tsui.open.commandPalette()
$tsui.close.commandPalette()
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Personalization

```php
TallStackUi::personalize()
    ->commandPalette()
    ->block('backdrop', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                           |
|--------------------|---------------------------------------------------|
| backdrop           | Fixed overlay background behind the palette       |
| blur.sm            | Small backdrop blur effect                        |
| blur.md            | Medium backdrop blur effect                       |
| blur.lg            | Large backdrop blur effect                        |
| blur.xl            | Extra-large backdrop blur effect                  |
| wrapper            | Fixed container that positions the palette        |
| box                | Main palette card with rounded corners and shadow |
| input.wrapper      | Flex container for the search input area          |
| input.icon         | Search magnifying glass icon styles               |
| input.base         | Search text input field styles                    |
| input.loading      | Loading spinner container                         |
| list               | Scrollable results list container                 |
| option.base        | Base styles for each result option                |
| option.active      | Active/highlighted option styles                  |
| option.disabled    | Disabled option styles                            |
| option.image       | Option image (avatar) styles                      |
| option.icon        | Option icon container styles                      |
| option.content     | Option text content wrapper                       |
| option.label       | Option label text styles                          |
| option.description | Option description text styles                    |
| empty              | Empty state message styles                        |
| footer             | Keyboard hints footer container                   |
