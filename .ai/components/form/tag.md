# TallStackUI: Tag

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A tag input component that allows users to add multiple tag values by pressing Enter, with individual tag removal, a bulk erase button, optional prefix character filtering, and configurable tag limits. It can also offer a floating list of tags to reuse. The value is stored as an array.

## Basic Usage

```blade
<x-tag wire:model="tags" label="Tags" />
```

```blade
<x-tag wire:model="tags" label="Tags" :limit="5" />
```

```blade
<x-tag wire:model="tags" label="Hashtags" prefix="#" />
```

```blade
<x-tag wire:model="tags" label="Tags" :options="['php', 'laravel', 'livewire']" />
```

Outside Livewire, give it a `name` and it backs a plain form through a hidden input.
A single tag arrives as a plain value and several arrive JSON encoded:

```blade
<form method="POST" action="/posts">
    @csrf
    {{-- one tag: "php" — several: ["php","laravel"] --}}
    <x-tag name="tags" />
</form>
```

Enter adds a tag rather than submitting the surrounding form, so the two do not fight
over the key.

## Attributes

| Attribute    | Type                        | Default | Description                                                                                                                              |
|--------------|-----------------------------|---------|------------------------------------------------------------------------------------------------------------------------------------------|
| label        | string\|ComponentSlot\|null | null    | Label text displayed above the input                                                                                                     |
| hint         | string\|ComponentSlot\|null | null    | Hint text displayed below the input                                                                                                      |
| prefix       | string\|null                | null    | A single character prefix automatically prepended to each tag                                                                            |
| limit        | int\|null                   | null    | Maximum number of tags allowed                                                                                                           |
| lazy         | int\|null                   | null    | Minimum length the typed content must reach before a tag is accepted on Enter or comma. The `prefix` character does not count toward it. |
| invalidate   | bool\|null                  | null    | Prevents displaying validation error messages                                                                                            |
| options      | Collection\|array\|null     | null    | Tags offered for reuse in a floating list. Values are cast to strings and de-duplicated.                                                 |
| placeholders | array\|null                 | null    | Overrides the list messages. Only `empty` is used.                                                                                       |
| disabled     | bool                        | false   | Locks the input and the remove buttons. The value is not submitted.                                                                      |
| readonly     | bool                        | false   | Locks the input and the remove buttons. The value is still submitted.                                                                    |

## Slots

| Slot  | Description                                                                                                                                                                    |
|-------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| after | Rendered under the floating list, always — including when the list is empty. The slot on its own is enough to open the list, so a field with no reusable tags still offers it. |

> Note the difference from the slot of the same name on `Form/Select/Styled` and
> `Form/Autocomplete`. There, `after` **replaces** the empty message and shows only when
> nothing matches. Here it sits below the list at all times, because it exists to reach an
> action — creating a tag that does not exist yet — which stays useful while matches are
> still on screen.

## Alpine.js Events

| Event       | Description                                     |
|-------------|-------------------------------------------------|
| x-on:add    | Triggered when a tag is added                   |
| x-on:remove | Triggered when an individual tag is removed     |
| x-on:erase  | Triggered when the bulk erase button is clicked |
| x-on:open   | Triggered when the floating list opens          |
| x-on:close  | Triggered when the floating list closes         |

## Validation Constraints

- The `prefix` must be a single character.
- The `lazy` must be greater than zero.

## Event Payload Details

```blade
<x-tag x-on:add="alert(`Introduced: ${$event.detail.tag}`)"
       x-on:remove="alert(`Removed: ${$event.detail.tag}`)"
       x-on:erase="alert(`Erased: ${$event.detail.tags}`)" />
```

Note: for the `erase` event the key is `$event.detail.tags` (plural), not `$event.detail.tag`.

## Reusing tags

`options` turns the field into a picker over tags that already exist, without giving up
free typing — whatever is typed and confirmed with Enter still becomes a tag.

```blade
<x-tag wire:model="tags" :options="$existing" />
```

Clicking the field toggles the list and typing opens it, and it narrows as the term is
typed. Options already added drop out of it, since they are visible as tags right above.
Arrow keys move through it, Enter takes the highlighted option, and Escape closes it.
With nothing highlighted, Enter falls through to the typed value as usual.

The click toggles rather than only opening because the panel closes itself on any click
outside it, and the field is outside it — so a click that only opened would be undone by
that handler on the way out.

A `prefix` is ignored while matching, so typing `foo` still finds `#foo`. Matching is
case-insensitive, and a typed value that matches an option is stored with the option's
casing: typing `Alpine` and confirming adds `alpine` when that is the option.

Reaching `limit` closes the list and keeps it from opening again.

### `<x-slot:after>`

Rendered under the list and always reachable, including when nothing matches. This is
where an action to create a tag that does not exist yet belongs:

```blade
<x-tag wire:model="tags" :options="$existing">
    <x-slot:after>
        <x-button sm x-on:click="$tsui.open.modal('create-tag')">New tag</x-button>
    </x-slot:after>
</x-tag>
```

The slot alone is enough to make the list open, so a field with no reusable tags yet
still offers the action.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('tag')
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name             | Purpose                                                                                                             |
|------------------------|---------------------------------------------------------------------------------------------------------------------|
| wrapper                | Flex container for tags and input                                                                                   |
| label.base             | Individual tag badge styles                                                                                         |
| label.icon             | Tag remove icon size and color                                                                                      |
| input.base             | Inner text input styles                                                                                             |
| input.color.base       | Default ring and text colors                                                                                        |
| input.color.background | Background color                                                                                                    |
| input.color.disabled   | Disabled/readonly background color                                                                                  |
| input.slot             | Slot text styles (prefix/suffix area)                                                                               |
| input.wrapper          | Outer input wrapper with ring and focus styles                                                                      |
| button.wrapper         | Erase button positioning                                                                                            |
| button.icon            | Erase button icon size and hover color                                                                              |
| floating.default       | Floating panel surface                                                                                              |
| floating.class         | Floating panel overflow and stacking. Carries `w-full`, which opts the panel into the Floating's anchor width sync. |
| box.wrapper            | Scrollable list wrapper                                                                                             |
| box.item               | One option in the list                                                                                              |
| box.highlighted        | Option under the cursor or the arrow keys                                                                           |
| box.empty              | Message shown when nothing matches                                                                                  |
| box.after              | Divider above the `after` slot                                                                                      |
| error                  | Error state ring and text styles                                                                                    |
