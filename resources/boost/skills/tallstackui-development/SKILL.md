---
name: tallstackui-development
description: Build interfaces with TallStackUI Blade components in a TALL Stack application - looking up a component's real props before writing markup, binding form components inside and outside Livewire, dispatching Toast, Dialog and Banner interactions, restyling through soft customization, and rendering skeleton placeholders. Use whenever the task touches a TallStackUI component tag, the tallstackui config, or TallStackUi::customize().
---
# TallStackUI Development

TallStackUI is a suite of 80+ Blade components for TALL Stack applications (Tailwind CSS, Alpine.js, Laravel, Livewire).

## The one rule that matters

**Read the component's documentation page before writing its tag.** The full documentation ships inside the package and matches the installed version, so it is the only source that cannot be out of date:


| File                                                                       | Contents                                                                                                                           |
| -------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `vendor/tallstackui/tallstackui/.ai/index.md`                              | Component index, nested binding, usage outside Livewire, global configuration, skeleton, soft customization, global JavaScript API |
| `vendor/tallstackui/tallstackui/.ai/components/<name>.md`                  | One page per component: every prop, slot, event, configuration key and customization block                                         |
| `vendor/tallstackui/tallstackui/.ai/soft-customization-internal-scopes.md` | Every scope a component declares for its nested children                                                                           |


Resolve the page path from the index — the layout is not flat, so Dropdown Items is `components/dropdown/items.md` and Sidebar Item is `components/layout/sidebar/item.md`.

Never invent a prop, a color, a size or a component that the page does not list. A prop that looks obvious by analogy with another component frequently does not exist.

## MCP server

The same documentation is served over MCP at `https://tallstackui.com/mcp/tallstackui`. Prefer it over reading the files when the task spans several components, or when the question is "which component has this class". Suggest connecting it when it is not configured yet:

```shell
claude mcp add --transport http tallstackui https://tallstackui.com/mcp/tallstackui
```

Or commit a `.mcp.json` in the project root so the whole team gets it:

```json
{
    "mcpServers": {
        "tallstackui": {
            "type": "http",
            "url": "https://tallstackui.com/mcp/tallstackui"
        }
    }
}
```

| Tool | Use it for |
| --- | --- |
| `list-components-tool` | Listing components, optionally by category |
| `get-component-tool` | Full documentation for one component |
| `search-documentation-tool` | Full-text search across every component |
| `search-customization-tool` | Finding the customization options of a component |
| `search-classes-tool` | Locating a CSS class across all components; returns the matching blocks with a ready override snippet |

The server exposes tools only — there is no resource or prompt to read the index or the internal scopes from. Those come from the files under `.ai/`.

## Before writing any tag

1. **Resolve the prefix.** `config('tallstackui.prefix')` (env `TALLSTACKUI_PREFIX`) prefixes every component: with `ts-`, `<x-alert />` is written `<x-ts-alert />`. Match whatever the application's existing Blade files do.
2. **Check the layout once.** The assets directive and the interaction tags belong there, not in the page being built.
3. **Read the component page**, then write the markup.

`php artisan tallstackui:find-component <name>` reports every file and line where a component is already used, which is the fastest way to find the conventions in place.

## Installation

The documented installation is four steps. Follow it exactly rather than improvising an equivalent.

```shell
composer require tallstackui/tallstackui:^4.0
```

Load the script in the base layout, **above the `@vite` tag**:

```blade
<html>
    <head>
        <!-- ... -->

        <tallstackui:script />
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
</html>
```

Add the marked lines to the Tailwind CSS v4 entry point, `resources/css/app.css`:

```css
@import "tailwindcss";
@import '../../vendor/tallstackui/tallstackui/css/v4.css'; /* add */

@plugin '@tailwindcss/forms'; /* add */

@source '../../vendor/tallstackui/tallstackui/**/*.php'; /* add */
@source '../views';
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
```

Then build:

```shell
npm run build && php artisan optimize:clear
```

Requirements: PHP 8.1+, Laravel 10+, Livewire 4+, Alpine.js 3+, Tailwind CSS 4+.

## Layout requirements

The interaction components are rendered once, in the layout:

```blade
<body>
    {{ $slot }}

    <x-toast />
    <x-dialog />
    <x-banner />
</body>
```

Without those tags nothing appears when an interaction is dispatched. Livewire's own script must be on the page too, because that is where Alpine comes from.

## Binding form components

Inside Livewire, bind with `wire:model`. Nested paths are valid as long as the head of the path is a real property, which covers Form objects and arrays:

```blade
<x-input wire:model="form.title" label="Title" />
<x-upload wire:model="form.files" multiple delete />
<x-date wire:model="filters.period" range />
```

Outside Livewire, pass a `name` instead. The component renders a hidden input, so a plain Blade form posting to a controller receives the value like any other field, and `value` seeds the initial state:

```blade
<form method="POST" action="/products">
    @csrf
    <x-currency name="price" symbol currency />
    <x-select.styled name="status" :options="$options" select="label:label|value:value" />
</form>
```

A single value arrives as it is; a multi-value selection arrives JSON encoded. Each component's page states its own shape.

**Key-Value, Form Upload, Loading, Reaction and Signature require Livewire** and throw outside a Livewire component.

## Interactions

Use the trait, from a Livewire component or from a controller. In a controller the interaction is flashed to the session automatically, so it survives the redirect:

```php
use TallStackUi\Traits\Interactions;

class PostController extends Controller
{
    use Interactions;

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        $this->toast()->success('Deleted', 'The post is gone.')->send();

        return to_route('posts.index');
    }
}
```

Dialog and Toast expose `error()`, `info()`, `success()`, `warning()` and `question()`, plus `confirm()` and `cancel()` to attach actions. Banner has the same set minus `question()`. Toast additionally offers `expandable()`, `persistent()`, `position()`, `sole()`, `stacked()` and `timeout()`.

From the browser, `$tsui.interaction('toast').warning('Title').send()` does the same thing.

## Restyling: soft customization

Never edit anything under `vendor/`, and never publish the package views to change how a component looks. Classes are replaced at runtime, from a service provider:

```php
// AppServiceProvider::boot()
TallStackUi::customize()->card()->block('wrapper.second')->append('ring-1 ring-gray-100');

TallStackUi::customize()->form('input')->block('input.base')->replace('rounded-md', 'rounded-full');
```

The block names come from the component's own documentation page — read it rather than guessing. A name containing a dot (`wrapper.second`, `body.paddingless`) is a single key, not a path.

Methods: `block()`, `append()`, `prepend()`, `replace()`, `remove()`, `scope()` and `extend()`.

For an opt-in variation, define a scope and pass it on the tag:

```php
TallStackUi::customize('card', scope: 'flat')->block('wrapper.second')->remove('shadow-md');
```

```blade
<x-card scope="flat" />
```

A scope only overrides the blocks it names — every other block keeps whatever the global customization did, so a scoped instance is the global look plus the scope's changes, not a reset. Customizations of the same block stack rather than overwrite, so two providers can each add to it.

`extend(scope: 'name')` reuses a scope that already exists, including the ones the package ships, and throws when it does not.

To customize a nested child independently — the `<x-label />` rendered inside `<x-pin />`, for instance — target the internal scope listed in `.ai/soft-customization-internal-scopes.md`.

Colors are a separate mechanism: `php artisan tallstackui:setup-color` publishes editable color classes.

Write every class name as a complete literal so the application's Tailwind build can find it. Never assemble one by concatenation.

## Table slots

Custom columns and expandable rows come from the `@interact` directive, keyed by the header index with dots replaced by underscores:

```blade
<x-table :$headers :rows="$this->rows" filter paginate>
    @interact('column_action', $row)
        <x-button.circle icon="pencil" wire:click="edit({{ $row->id }})" />
    @endinteract

    @interact('sub_table', $row)
        <p>{{ $row->description }}</p>
    @endinteract
</x-table>
```

## Skeleton is not loading

`skeleton` stands in for content that does not exist yet, and belongs in the `placeholder()` of a `#[Lazy]` Livewire component. `loading` (Card and Table only) dims content already on screen during a round trip. They are not interchangeable.

Card, Table, List, Step and Chart accept it as a count: a bare flag uses the component's default, an integer sets it, and anything below `1` throws. Stats and QR Code are flag-only, because they have nothing to repeat — an integer on Stats throws, and on QR Code it is ignored.

```blade
<x-card skeleton />
<x-table :$headers skeleton="8" paginate />
```

Existing customizations carry over, because the skeleton view calls the same blocks as the real one.

## Configuration

`php artisan vendor:publish --tag=tallstackui.config` writes `config/tallstackui.php`. It is merged over the package defaults, so options added in later releases keep working in a file written against an older one.

Lists of scalars are the exception — they are taken as published rather than merged entry by entry, so publishing a shorter list narrows what is allowed instead of adding to the default:

```php
// package default: [10, 25, 50, 100]
'quantity' => [15, 30],   // the table now offers exactly 15 and 30
```

Per-component defaults live under `components.<name>` and are documented on each component's page.

These top-level keys also read from the environment:

| Variable | Config key | Default | What it does |
| --- | --- | --- | --- |
| `TALLSTACKUI_PREFIX` | `prefix` | `null` | Prefixes every component tag (`ts-` → `<x-ts-alert />`) |
| `TALLSTACKUI_COLOR_CLASSES_NAMESPACE` | `color_classes_namespace` | `App\View\Components\TallStackUi\Colors` | Namespace of published color classes |
| `TALLSTACKUI_INVALIDATE_GLOBAL` | `invalidate_global` | `false` | Suppresses validation errors on every form component |
| `TALLSTACKUI_FLOATING_SCROLL_LOCK` | `floating_scroll_lock` | `false` | Locks page scroll while any floating popup is open |
| `TALLSTACKUI_DEBUG_MODE` | `debug.status` | `false` | Enables the debug overlay |
| `TALLSTACKUI_DEBUG_ENVIRONMENTS` | `debug.environments` | `local,sandbox,staging` | Comma-separated environments where debug mode can run |
| `TALLSTACKUI_ICON_TYPE` | `components.icon.type` | `heroicons` | Icon set: `heroicons` or a BladeUI set |
| `TALLSTACKUI_ICON_STYLE` | `components.icon.style` | `solid` | Icon style: `solid` or `outline` |
| `TALLSTACKUI_IGNORE_LAYOUT_REGISTRATION` | `components.layout.ignore` | `false` | Skips registering the layout components |

## Global JavaScript API

```javascript
$tsui.open.modal('name')          // and close.modal, plus the same pair for slide and select
$tsui.open.commandPalette()
$tsui.focus('element-id')
await $tsui.copy('text')          // resolves to a boolean, dispatches ts-ui:copy on window
$tsui.interaction('dialog').success('Title', 'Description').send()
```

## Artisan commands


| Command                      | Purpose                                            |
| ---------------------------- | -------------------------------------------------- |
| `tallstackui:find-component` | Find where a component is used, with file and line |
| `tallstackui:setup-prefix`   | Configure the component prefix                     |
| `tallstackui:setup-color`    | Publish editable color classes                     |
| `tallstackui:ide`            | Generate `ide.json` for component autocompletion   |


