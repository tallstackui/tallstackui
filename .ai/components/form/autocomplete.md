# TallStackUI: Autocomplete

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An input-first single-select component: a regular text `<input>` paired with a floating dropdown of suggestions that filters as the user types. Each item supports a `value`, an optional `description` shown as a subtitle line, and an optional `image` rendered as a circular avatar to the left. Free text is allowed by default; an opt-in `strict` mode constrains `wire:model` to predefined values only. Items can be supplied locally via `:items` or fetched on demand with `:request`.

The component layers on top of `Form/Input` (so `floatable`, label, hint, error styling, and the input wrapper come from the same primitives as `Form/Date` and `Form/Password`) and uses `Floating` for the dropdown.

## Basic Usage

```blade
<x-autocomplete wire:model="city" label="City" :items="[
    ['value' => 'São Paulo'],
    ['value' => 'Rio de Janeiro'],
    ['value' => 'Belo Horizonte'],
]" />
```

```blade
<x-autocomplete wire:model="assignee" label="Assign to" clearable :items="[
    ['value' => 'Alice', 'description' => 'admin', 'image' => '/avatars/alice.png'],
    ['value' => 'Bob',   'description' => 'editor', 'image' => '/avatars/bob.png'],
]" />
```

```blade
<x-autocomplete wire:model="status" label="Status" strict :items="[
    ['value' => 'Pending'],
    ['value' => 'Approved'],
    ['value' => 'Rejected'],
]" />
```

```blade
<x-autocomplete wire:model="user" label="User" request="/api/users" lazy="2" clearable />
```

## Attributes

| Attribute    | Type                    | Default                                             | Description                                                                                                                   |
|--------------|-------------------------|-----------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------|
| items        | Collection\|array\|null | null                                                | Local source. Mutually exclusive with `request`.                                                                              |
| request      | string\|array\|null     | null                                                | Remote source. String shorthand uses `GET <url>`; array form: `['url' => ..., 'method' => 'get'\|'post', 'params' => [...]]`. |
| id           | string\|null            | null                                                | Forwarded to the underlying `<input>`.                                                                                        |
| label        | string\|null            | null                                                | Rendered above the input.                                                                                                     |
| hint         | string\|null            | null                                                | Rendered below the input when there is no error.                                                                              |
| placeholder  | string\|null            | from translations                                   | Input placeholder text.                                                                                                       |
| prefix       | string\|null            | null                                                | Adornment on the left of the input (forwarded to `Form/Input`).                                                               |
| suffix       | string\|null            | null                                                | Adornment on the right of the input. Renders before the loading/clear icons.                                                  |
| clearable    | bool\|null              | null                                                | Shows an `×` to empty the input. Dispatches the `clear` event.                                                                |
| invalidate   | bool\|null              | null                                                | Same semantics as `Form/Input`: opts the component into Livewire validation styling.                                          |
| strict       | bool\|null              | null (or global from config)                        | Constrains `wire:model` to values that exist in the items list. See "Strict mode".                                            |
| lazy         | int\|null               | null                                                | Minimum chars before the dropdown opens / a remote request is fired.                                                          |
| disabled     | bool\|null              | null                                                | Disables the input and prevents the dropdown from opening.                                                                    |
| placeholders | array\|null             | merged from `trans('ts-ui::messages.autocomplete')` | Override translation strings (`empty`, `loading`, `default`).                                                                 |

The component does **not** support `multiple`. Reach for `Form/Select/Styled` when multiple selection is required.

## Item Object Structure

| Key         | Type   | Required | Description                                                                                  |
|-------------|--------|----------|----------------------------------------------------------------------------------------------|
| value       | string | Yes      | Visible text in the input and value bound to `wire:model`. Filter matches against this.      |
| description | string | No       | Subtitle line shown below `value` inside the dropdown row. Filter also matches against this. |
| image       | string | No       | URL displayed as a circular avatar on the left of the dropdown row.                          |
| disabled    | bool   | No       | Dims the row and blocks selection.                                                           |

## Slots

| Slot  | Description                                                                                                                                                                                                                   |
|-------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| after | Rendered inside the dropdown when the filtered list is empty. Mirrors `Form/Select/Styled`'s `after`. Useful for "no results — create one?" flows. When omitted, the component falls back to the `placeholders.empty` string. |

## Validation Constraints

- `items` and `request` cannot be defined at the same time.
- When `request` is an array, `url` is required.
- When `request['method']` is set, it must be `get` or `post`.
- When `request['params']` is set, it must be a non-empty array.

## Strict Mode

By default, `wire:model` reflects whatever the user types — even values that don't appear in the list. With `:strict`, the binding only updates when a row is picked from the dropdown. If the user blurs or presses Esc with an unmatched query, the input reverts to the last selected value (or empties if nothing was ever picked). Use the `after` slot as the escape hatch for "I can't find this option, create one".

A global default is available so an entire application can opt every Autocomplete into strict mode at once:

```php
'autocomplete' => [
    \TallStackUi\Components\Form\Autocomplete\Component::class,
    [
        'strict' => true,
    ],
],
```

(in `config('tallstackui.components.autocomplete.strict')`.)

## Local vs Remote Source

```blade
<!-- Local: filtering is fully client-side, case-insensitive, against value + description -->
<x-autocomplete :items="$cities" />

<!-- Remote: GET request with `?search=<query>` -->
<x-autocomplete request="/api/users" />

<!-- Remote: explicit array form -->
<x-autocomplete :request="[
    'url' => '/api/users',
    'method' => 'post',
    'params' => ['team_id' => 7],
]" />
```

For remote mode the component fires a debounced request (~250 ms) every time the user types, sending the current query as `search`. Loading state shows a spinner inside the dropdown panel; the dropdown only opens once typing has begun (or if `lazy` is satisfied).

## Lazy Threshold

Setting `lazy="N"` makes the component ignore any open/fetch attempt while the query has fewer than `N` characters. Useful with remote sources to avoid firing requests on every single keystroke:

```blade
<x-autocomplete request="/api/users" lazy="2" />
```

Below the threshold the panel stays closed; once the user crosses it, the panel opens straight into the loading state and resolves to results / "no results".

## Keyboard

| Key   | Behavior                                       |
|-------|------------------------------------------------|
| ↓ / ↑ | Move the highlighted row.                      |
| Enter | Pick the highlighted row.                      |
| Esc   | Close the dropdown without changing the model. |
| Tab   | Close the dropdown and move focus naturally.   |

The highlighted row is auto-scrolled into view inside the dropdown.

## Alpine.js Event Payloads

```blade
<!-- $event.detail.item: { value, description?, image?, disabled?, ...whatever you put there } -->
<x-autocomplete :items="$users"
    x-on:select="$wire.set('userId', $event.detail.item.id)"
    x-on:clear="$wire.set('userId', null)"
    x-on:open="console.log('opened')"
    x-on:close="console.log('closed')" />
```

| Event  | `$event.detail`      | When                                     |
|--------|----------------------|------------------------------------------|
| select | `{ item: <object> }` | The user picked a row from the dropdown. |
| clear  | `null`               | The user pressed the clear `×` button.   |
| open   | `null`               | The floating dropdown opened.            |
| close  | `null`               | The floating dropdown closed.            |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('autocomplete')
    ->block('box.list.item.wrapper', 'your-tailwind-classes');
```

### Available Blocks

The Autocomplete delegates the input rendering to `Form/Input`, so any styling related to the input wrapper, label, hint, error state, or prefix slot is customized via `form.input` (and the `Form/Input` AI doc), not here. The blocks below are only the bits the Autocomplete adds on top.

| Block Name                | Purpose                                                                               |
|---------------------------|---------------------------------------------------------------------------------------|
| adornment.suffix          | Wrapper around the user-provided `suffix` text inside the input's suffix slot.        |
| icon.wrapper              | Container for the loading/clear icons inside the input.                               |
| icon.clear                | Clear `×` icon.                                                                       |
| icon.loading              | Spinner shown next to the input while remote requests are in flight.                  |
| floating.default          | Inherits `Floating::customization()['wrapper']`.                                      |
| floating.class            | Overflow tweaks for the panel (the panel width is auto-synced to the input wrapper).  |
| box.list.wrapper          | The `<ul>` container of the dropdown.                                                 |
| box.list.item.wrapper     | Each option row.                                                                      |
| box.list.item.base        | Inner flex container (image + content).                                               |
| box.list.item.value       | Primary line (from `item.value`).                                                     |
| box.list.item.description | Secondary subtitle line (from `item.description`).                                    |
| box.list.item.image       | Avatar circle on the left of the row.                                                 |
| box.list.item.content     | Wrapper around value + description.                                                   |
| box.list.item.highlighted | Keyboard-highlighted row.                                                             |
| box.list.item.selected    | Currently selected row.                                                               |
| box.list.item.disabled    | Disabled row.                                                                         |
| box.list.empty            | Default "no results" message when the `after` slot is not used.                       |
| box.list.loading.wrapper  | Loading container inside the dropdown.                                                |
| box.list.loading.class    | Spinner icon classes inside the dropdown (matches Select Styled's loading animation). |
