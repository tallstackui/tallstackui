# TallStackUI

- TallStackUI is a suite of 80+ Blade components for TALL Stack applications (Tailwind CSS, Alpine.js, Laravel, Livewire).
- The complete documentation ships inside the package and matches the installed version. Read it before writing markup — never guess a prop, a slot, an event or a configuration key, and never invent a component that is not in the index.

## Where the documentation lives

- `vendor/tallstackui/tallstackui/.ai/index.md` — the component index, plus binding rules, usage outside Livewire, global configuration, skeletons, soft customization and the global JavaScript API.
- `vendor/tallstackui/tallstackui/.ai/components/<name>.md` — one page per component: every prop, slot, configuration key and customization block it exposes.
- `vendor/tallstackui/tallstackui/.ai/soft-customization-internal-scopes.md` — the canonical list of the scopes components use for their nested children.
- Start at the index to resolve the component's page path, then read that page. The website documents the latest release, which is not necessarily the one installed here.

## MCP server

- The same documentation is served over MCP at `https://tallstackui.com/mcp/tallstackui`, which is the better source when a task spans several components or searches for a class. Suggest connecting it when it is not configured yet:

@boostsnippet("Connect the TallStackUI MCP server to Claude Code", "shell")
claude mcp add --transport http tallstackui https://tallstackui.com/mcp/tallstackui
@endboostsnippet

@boostsnippet("Or commit .mcp.json in the project root to share it with the team", "json")
{
    "mcpServers": {
        "tallstackui": {
            "type": "http",
            "url": "https://tallstackui.com/mcp/tallstackui"
        }
    }
}
@endboostsnippet

- Tools: `list_components`, `get_component`, `search_documentation`, `search_customization` and `search_classes` — the last one returns the matching blocks with a ready override snippet.
- Resources: `tallstackui://docs/index` and `tallstackui://docs/internal-scopes`.
- Prompt: `customize-component`, a guided workflow that fetches the blocks, resolves nested scopes and writes the `TallStackUi::customize()` code.

## Component prefix

- `config('tallstackui.prefix')` (env `TALLSTACKUI_PREFIX`) prefixes every component tag: with `ts-`, `<x-alert />` is written `<x-ts-alert />`.
- Resolve the prefix before writing any tag, and follow whatever the existing Blade files in the application already do.
- `php artisan tallstackui:setup-prefix` configures it; `php artisan tallstackui:find-component <name>` reports where a component is already used.

## Installation

- Install the package:

@boostsnippet("Install TallStackUI", "shell")
composer require tallstackui/tallstackui:^4.0
@endboostsnippet

- Load the script in the layout:

@boostsnippet("Prepare the base layout", "blade")
<html>
    <head>
        <!-- ... -->

        <tallstackui:script />
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
</html>
@endboostsnippet

@verbatim
- The script has to be loaded **above the `@vite` tag**.
@endverbatim
- Add the marked lines to the Tailwind CSS v4 entry point, `resources/css/app.css`:

@boostsnippet("Tailwind CSS v4 entry point", "css")
@import "tailwindcss";
@import '../../vendor/tallstackui/tallstackui/css/v4.css'; /* add */

@plugin '@tailwindcss/forms'; /* add */

@source '../../vendor/tallstackui/tallstackui/**/*.php'; /* add */
@source '../views';
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@endboostsnippet

- Then build:

@boostsnippet("Build the assets", "shell")
npm run build && php artisan optimize:clear
@endboostsnippet

- Requirements: PHP 8.1+, Laravel 10+, Livewire 4+, Alpine.js 3+, Tailwind CSS 4+.
- Livewire's own script has to be on the page even when the components are used outside Livewire, because that is where Alpine comes from.

## Binding form components

- Inside Livewire, bind with `wire:model`. A nested path is valid as long as its head is a real property, which covers Form objects and arrays: `wire:model="form.files"`.
- Outside Livewire, give the component a `name` instead. It renders a hidden input, so a plain Blade form posting to a controller receives the value like any other field; `value` seeds the initial state. Single values arrive as they are, multi-value selections arrive JSON encoded.
- Key-Value, Form Upload, Loading, Reaction and Signature only work inside a Livewire component. Using them outside throws.

## Interactions: Toast, Dialog and Banner

- Place `<x-toast />`, `<x-dialog />` and `<x-banner />` once in the layout. Without the tag, nothing renders.
- Dispatch them with the `Interactions` trait, from a Livewire component or from a controller (where they are flashed to the session automatically):

@boostsnippet("Dispatching TallStackUI interactions", "php")
use TallStackUi\Traits\Interactions;

class UserController extends Controller
{
    use Interactions;

    public function destroy(User $user): RedirectResponse
    {
        $this->toast()->success('Deleted', 'The user is gone.')->send();

        return back();
    }
}
@endboostsnippet

- Banner has no `question()`. Dialog and Toast add `confirm()` and `cancel()` on top of `error()`, `info()`, `success()`, `warning()` and `question()`.

## Styling: soft customization

- Never edit anything under `vendor/`, and never publish the package views to restyle a component.
- Change classes at runtime from a service provider, targeting the blocks the component's documentation page lists:

@boostsnippet("Customizing a component's classes", "php")
// In AppServiceProvider::boot()
TallStackUi::customize()->card()->block('wrapper.second')->append('ring-1 ring-gray-100');

// Opt-in variant, used as <x-card scope="flat" />
TallStackUi::customize('card', scope: 'flat')->block('wrapper.second')->remove('shadow-md');
@endboostsnippet

- `block()`, `append()`, `prepend()`, `replace()`, `remove()`, `scope()` and `extend()` are the available methods. Customizations of the same block stack instead of overwriting each other, and a scope layers over the global customization rather than resetting it.
- Block names are keys, not paths: `wrapper.second` is one block, not `second` nested under `wrapper`.
- Write class names as complete literals so the application's Tailwind build can find them. Never build one by concatenation.
- Colors are customized through published color classes: `php artisan tallstackui:setup-color`.

## Configuration

- `php artisan vendor:publish --tag=tallstackui.config` writes `config/tallstackui.php`, merged over the package defaults, so a file written against an older release keeps the options added since.
- Lists of scalars are the exception — they are taken as published rather than merged entry by entry, so publishing a shorter list narrows what is allowed.
- Per-component defaults live under `components.<name>` and are documented on each component's page.

## Table slots

@verbatim
- Table renders custom columns and expandable content through the `@interact` directive, keyed by the header index with dots replaced by underscores:
@endverbatim

@boostsnippet("Custom table column", "blade")
<x-table :$headers :rows="$this->rows">
    @interact('column_action', $row)
        <x-button.circle icon="pencil" wire:click="edit({{ $row->id }})" />
    @endinteract
</x-table>
@endboostsnippet

## Global JavaScript API

- `$tsui.open.modal(name)` / `$tsui.close.modal(name)`, and the same pair for `slide` and `select`.
- `$tsui.open.commandPalette()` / `$tsui.close.commandPalette()`.
- `$tsui.focus(id)`, `await $tsui.copy(text)`, and `$tsui.interaction('toast'|'dialog')` to dispatch an interaction from the browser.
