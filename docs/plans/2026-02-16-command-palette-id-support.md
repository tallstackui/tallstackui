# Command Palette ID Support Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Allow multiple Command Palette instances on the same page by adding ID-based event targeting, following the existing Modal/Slide pattern.

**Architecture:** Add `id` property to the PHP component with default `'command-palette'`, create a Runtime class that generates ID-based event names (`command-palette:{id}-open/close`), and update the Alpine.js component + global helpers to dispatch/listen to those namespaced events.

**Tech Stack:** PHP 8.1+, Alpine.js 3, Blade templates, PestPHP 4

---

### Task 1: Create CommandPaletteRuntime class

**Files:**
- Create: `src/Support/Runtime/Components/CommandPaletteRuntime.php`
- Reference: `src/Support/Runtime/Components/ModalRuntime.php` (exact same pattern)

**Step 1: Create the Runtime class**

```php
<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

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

**Step 2: Verify file exists**

Run: `php -l src/Support/Runtime/Components/CommandPaletteRuntime.php`
Expected: No syntax errors

---

### Task 2: Add `id` property and Runtime attribute to Component

**Files:**
- Modify: `src/Components/CommandPalette/Component.php`

**Step 1: Add imports and attribute**

Add `use TallStackUi\Attributes\PassThroughRuntime;` and `use TallStackUi\Support\Runtime\Components\CommandPaletteRuntime;` to the imports. Add `#[PassThroughRuntime(CommandPaletteRuntime::class)]` attribute to the class (after the existing `#[SoftCustomization('commandPalette')]` attribute).

**Step 2: Add `id` parameter to the constructor**

Add `public ?string $id = 'command-palette'` as the **first** parameter in the constructor (following the Modal pattern where `$id` is first).

The constructor signature becomes:

```php
public function __construct(
    public ?string $id = 'command-palette',
    public string|array|null $request = null,
    public Collection|array $options = [],
    public ?array $selectable = [],
    public ?array $placeholders = null,
    public ?bool $recycle = null,
    #[SkipDebug]
    public ?bool $grouped = null,
    #[SkipDebug]
    public ?string $select = null,
) {
```

**Step 3: Verify syntax**

Run: `php -l src/Components/CommandPalette/Component.php`
Expected: No syntax errors

---

### Task 3: Update Blade template to use Runtime events and pass `id`

**Files:**
- Modify: `src/resources/views/components/command-palette/main.blade.php`

**Step 1: Update the x-data and event listeners**

In line 6, add `@js($id)` as the **last** (7th) parameter to `tallstackui_commandPalette()`:

```blade
x-data="tallstackui_commandPalette(@js($request),@js($selectable),@js($configurations['shortcut']),@js($recycle),@js($configurations['url'] ?? null),@js($attributes->has('x-on:select')),@js($id))"
```

Replace lines 7-8 (the fixed event listeners) with Runtime-generated event names:

```blade
x-on:command-palette:{{ $open }}.window="open()"
x-on:command-palette:{{ $close }}.window="close()"
```

The `$open` and `$close` variables are automatically available from the `CommandPaletteRuntime::runtime()` return array (injected by the Runtime system via `ManagesRender` trait).

---

### Task 4: Update Alpine.js component to accept and use `id`

**Files:**
- Modify: `src/Components/CommandPalette/alpine.js`

**Step 1: Add `id` parameter to the function signature**

Change line 10-17 from:

```javascript
export default (
  request,
  selectable = {},
  shortcutKey = 'ctrl.k',
  recycle = false,
  url = null,
  inline = false
) => ({
```

To:

```javascript
export default (
  request,
  selectable = {},
  shortcutKey = 'ctrl.k',
  recycle = false,
  url = null,
  inline = false,
  id = 'command-palette'
) => ({
```

**Step 2: Update `open()` method**

In the `open()` method (line 87-104), replace the `overflow()` and `register_ui_element()` calls, and the event dispatch:

```javascript
open() {
    this.show = true;
    this.search = '';
    this.selected = -1;
    this._dirty = true;
    this._options = null;

    if (!recycle) {
      this.response = [];
    }

    overflow(true, id);
    register_ui_element(id, 'command-palette');

    this.$nextTick(() => this.$refs.search?.focus());

    this.$dispatch('open');
    event(`command-palette:${id}:open`, null, false);
  },
```

**Step 3: Update `close()` method**

In the `close()` method (line 111-118), update similarly:

```javascript
close() {
    this.show = false;

    overflow(false, id);
    unregister_ui_element(id);

    this.$dispatch('close');
    event(`command-palette:${id}:close`, null, false);
  },
```

**Step 4: Update `selectOption()` method**

In line 225, update the global event dispatch:

```javascript
event(`command-palette:${id}:select`, sanitized, false);
```

---

### Task 5: Update global helpers to accept `id` parameter

**Files:**
- Modify: `js/globals/globals.js`

**Step 1: Update `$tsui.open.commandPalette`**

Change line 15 from:

```javascript
commandPalette: () => event('command-palette-open', null, false),
```

To:

```javascript
/** @param {String} id @return {void} */
commandPalette: (id = 'command-palette') => event(`command-palette:${id}-open`, null, false),
```

**Step 2: Update `$tsui.close.commandPalette`**

Change line 26 from:

```javascript
commandPalette: () => event('command-palette-close', null, false),
```

To:

```javascript
/** @param {String} id @return {void} */
commandPalette: (id = 'command-palette') => event(`command-palette:${id}-close`, null, false),
```

---

### Task 6: Build assets

Run: `npm run build`
Expected: Successful build with no errors

---

### Task 7: Update feature tests

**Files:**
- Modify: `src/Components/CommandPalette/FeatureTest.php`

**Step 1: Add test for default id rendering**

Add after the `can render` test:

```php
it('can render with default id', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('command-palette:command-palette-open')
        ->toContain('command-palette:command-palette-close');
});
```

**Step 2: Add test for custom id rendering**

```php
it('can render with custom id', function () {
    $component = <<<'HTML'
    <x-command-palette id="search-palette" request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('command-palette:search-palette-open')
        ->toContain('command-palette:search-palette-close');
});
```

**Step 3: Run feature tests**

Run: `./vendor/bin/pest --filter="CommandPalette" --group=Feature --parallel`
Expected: All tests pass

---

### Task 8: Update browser tests

**Files:**
- Modify: `src/Components/CommandPalette/BrowserTest.php`

**Step 1: Update all existing tests**

All existing tests use `$tsui.open.commandPalette()` without an ID. Since the default ID is `'command-palette'`, these should continue working without changes. However, update the `can_open_using_helper` and `can_close_using_helper` tests to explicitly pass the default ID to validate the parameter works:

In `can_open_using_helper`, change the button to:
```blade
<x-button dusk="open" x-on:click="$tsui.open.commandPalette('command-palette')">Open</x-button>
```

In `can_close_using_helper`, change the script call to:
```php
$browser->script('$tsui.close.commandPalette("command-palette")');
```

**Step 2: Add test for opening a specific Command Palette by ID**

Add new test method:

```php
#[Test]
public function can_open_specific_palette_by_id(): void
{
    Livewire::visit(new class extends Component
    {
        public function render(): string
        {
            return <<<'HTML'
            <div>
                <x-command-palette id="first" request="https://example.com/search" select="label:title|value:id" />
                <x-command-palette id="second" request="https://example.com/search" select="label:title|value:id" />
                <x-button dusk="open-first" x-on:click="$tsui.open.commandPalette('first')">Open First</x-button>
                <x-button dusk="open-second" x-on:click="$tsui.open.commandPalette('second')">Open Second</x-button>
            </div>
            HTML;
        }
    })
        ->assertMissing('[dusk="tallstackui_command_palette"]')
        ->click('@open-first')
        ->waitFor('[dusk="tallstackui_command_palette"]')
        ->assertVisible('[dusk="tallstackui_command_palette"]');
}
```

**Step 3: Add test for closing a specific Command Palette by ID**

```php
#[Test]
public function can_close_specific_palette_by_id(): void
{
    $browser = Livewire::visit(new class extends Component
    {
        public function render(): string
        {
            return <<<'HTML'
            <div>
                <x-command-palette id="first" request="https://example.com/search" select="label:title|value:id" />
                <x-command-palette id="second" request="https://example.com/search" select="label:title|value:id" />
                <x-button dusk="open-first" x-on:click="$tsui.open.commandPalette('first')">Open First</x-button>
            </div>
            HTML;
        }
    });

    $browser->click('@open-first')
        ->waitFor('[dusk="tallstackui_command_palette"]')
        ->assertVisible('[dusk="tallstackui_command_palette"]');

    $browser->script('$tsui.close.commandPalette("first")');

    $browser->waitUntilMissing('[dusk="tallstackui_command_palette"]')
        ->assertMissing('[dusk="tallstackui_command_palette"]');
}
```

**Step 4: Update lifecycle event tests**

The `can_dispatch_open_lifecycle_event` and `can_dispatch_close_lifecycle_event` tests listen to `command-palette:open` and `command-palette:close` window events. These need to be updated to use the new ID-based format:

In `can_dispatch_open_lifecycle_event`, change:
```blade
x-on:command-palette:open.window="opened = true"
```
To:
```blade
x-on:command-palette:command-palette:open.window="opened = true"
```

In `can_dispatch_close_lifecycle_event`, change:
```blade
x-on:command-palette:close.window="closed = true"
```
To:
```blade
x-on:command-palette:command-palette:close.window="closed = true"
```

In `can_dispatch_global_event_as_fallback`, change:
```blade
x-on:command-palette:select.window="selected = $event.detail.label"
```
To:
```blade
x-on:command-palette:command-palette:select.window="selected = $event.detail.label"
```

In `inline_select_takes_priority_over_global_event`, change:
```blade
x-on:command-palette:select.window="globalResult = 'global-fired'"
```
To:
```blade
x-on:command-palette:command-palette:select.window="globalResult = 'global-fired'"
```

**Step 5: Run browser tests for Command Palette**

Run: `./vendor/bin/pest --filter="CommandPalette" --group=Browser`
Expected: All tests pass

---

### Task 9: Update `.ai/` documentation

**Files:**
- Modify: `.ai/components/command-palette.md`

**Step 1: Update Attributes table**

Add `id` row as the first attribute:

```markdown
| id           | string\|null        | 'command-palette'                                                           | Unique identifier for targeting with `$tsui.open.commandPalette(id)`. Required when using multiple palettes. |
```

**Step 2: Update JavaScript Control section**

Replace:
```markdown
## JavaScript Control

\`\`\`js
$tsui.open.commandPalette()
$tsui.close.commandPalette()
\`\`\`
```

With:
```markdown
## JavaScript Control

\`\`\`js
// Default (targets id="command-palette")
$tsui.open.commandPalette()
$tsui.close.commandPalette()

// Target specific palette by ID
$tsui.open.commandPalette('search')
$tsui.close.commandPalette('search')
\`\`\`
```

**Step 3: Update Lifecycle Events section**

Update the event table to reflect ID-based events:

```markdown
| Event                            | Channel     | Trigger        |
|----------------------------------|-------------|----------------|
| `open` (inline)                  | `$dispatch` | Palette opens  |
| `close` (inline)                 | `$dispatch` | Palette closes |
| `command-palette:{id}:open`      | `window`    | Palette opens  |
| `command-palette:{id}:close`     | `window`    | Palette closes |
```

Update the global lifecycle events example:
```blade
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

**Step 4: Update Global Event (Fallback) section**

Update the example:
```blade
<div x-on:command-palette:command-palette:select.window="handleSelection($event.detail)">
    <x-command-palette request="/api/search" select="label:name|value:id" />
</div>
```

**Step 5: Add Multiple Instances section after Usage Patterns**

```markdown
### Multiple Instances

Use the `id` attribute to place multiple command palettes on the same page and target them independently:

\`\`\`blade
<x-command-palette id="search" request="/api/search" select="label:name|value:id" />
<x-command-palette id="actions" request="/api/actions" select="label:name|value:id" />

<x-button x-on:click="$tsui.open.commandPalette('search')">Search</x-button>
<x-button x-on:click="$tsui.open.commandPalette('actions')">Actions</x-button>
\`\`\`
```

---

### Task 10: Final verification

**Step 1: Run feature tests**

Run: `./vendor/bin/pest --filter="CommandPalette" --group=Feature --parallel`
Expected: All pass

**Step 2: Run browser tests**

Run: `./vendor/bin/pest --filter="CommandPalette" --group=Browser`
Expected: All pass

**Step 3: Run static analysis**

Run: `composer analyse`
Expected: No new errors

**Step 4: Run formatter**

Run: `./vendor/bin/pint --parallel`
Expected: Clean or auto-fixed
