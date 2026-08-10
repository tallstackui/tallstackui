# TallStackUI: Errors

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A validation error summary component that displays all (or filtered) Laravel validation errors in a styled list with a title, icon, optional close button, and footer slot. Automatically hides when no errors are present.

## Basic Usage

```blade
<x-errors />
```

```blade
<x-errors title="Please fix the following:" color="red" close />
```

```blade
<x-errors :only="['email', 'password']" icon="exclamation-triangle" color="yellow">
    <x-slot:footer>
        <p class="text-sm mt-2">Need help? <a href="/support">Contact support</a></p>
    </x-slot:footer>
</x-errors>
```

The `only` filter accepts a single field, a comma separated list, an array or a
collection:

```blade
<x-errors only="name" />
<x-errors only="name,description" />
<x-errors :only="['name', 'description']" />
<x-errors :only="collect(['name', 'description'])" />
```

Hide the title and its divider, and render a numbered list:

```blade
<x-errors without-title list-numeric />
```

A footer slot is aligned to the end by default. Pass another alignment to change
it:

```blade
<x-errors>
    <x-slot:footer between>
        <x-button>Dismiss</x-button>
        <x-button>Fix now</x-button>
    </x-slot:footer>
</x-errors>
```

Taking over the footer layout with `unwrapped`. The top margin of the footer
area stays; only the aligning wrapper is dropped:

```blade
<x-errors>
    <x-slot:footer unwrapped>
        <div class="grid grid-cols-2 gap-2">
            <x-button>Dismiss</x-button>
            <x-button>Fix now</x-button>
        </div>
    </x-slot:footer>
</x-errors>
```

## Attributes

| Attribute     | Type                            | Default            | Description                                                                                                                |
|---------------|---------------------------------|--------------------|----------------------------------------------------------------------------------------------------------------------------|
| title         | string\|null                    | Translated default | Title displayed at the top of the error box; supports `:count` placeholder for the number of errors                        |
| only          | string\|array\|Collection\|null | null               | Filter to show errors for specific field names only; a string accepts a comma separated list                               |
| icon          | string\|null                    | 'x-circle'         | Heroicon name displayed next to the title                                                                                  |
| color         | string\|null                    | 'red'              | Color theme for the error box                                                                                              |
| close         | bool                            | false              | Shows a dismiss button to hide the error box                                                                               |
| without-title | bool                            | false              | Hides the title, icon, and the divider line between the title and the error list                                           |
| list-numeric  | bool                            | false              | Renders the error list as an ordered (numbered) list instead of bullet points                                              |
| paddingless   | bool                            | false              | Removes the horizontal padding of the wrapper so the title/body divider runs edge to edge; the content keeps its own inset |
| shadowless    | bool                            | false              | Removes the wrapper shadow                                                                                                 |
| bordered      | bool                            | false              | Draws a border following the component color (through the `bordered` palette of `ErrorsColors`)                            |

## Slots

| Slot   | Description                                                                                         |
|--------|-----------------------------------------------------------------------------------------------------|
| footer | Content rendered below the error list; a plain string stays a paragraph, a ComponentSlot is aligned |

### Footer Slot Attributes

Only apply when the footer comes in as a `<x-slot:footer>`. A footer passed as a
string attribute is rendered as a plain paragraph and takes no alignment.

| Attribute | Description                                                        |
|-----------|--------------------------------------------------------------------|
| start     | Aligns the footer content to the start                             |
| center    | Centers the footer content                                         |
| end       | Aligns the footer content to the end (default when none is passed) |
| between   | Distributes the footer content with space between                  |
| unwrapped | Drops the aligning wrapper, keeping the footer top margin          |

Any other attribute on the slot — `class`, `x-on:*`, `dusk` — is merged into the
footer container.

## Validation Constraints

- The `title` attribute cannot be empty, unless `without-title` is used.
- The `footer` slot cannot combine two or more alignments.
- The `footer` slot cannot use `unwrapped` together with an alignment.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->errors()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name           | Purpose                                                           |
|----------------------|-------------------------------------------------------------------|
| wrapper              | Outer container with rounded corners, padding, and shadow         |
| shadowless           | Shadow reset applied by `shadowless`                              |
| bordered             | Border width applied by `bordered` (color comes from the palette) |
| paddingless.wrapper  | Horizontal padding reset applied by `paddingless`                 |
| paddingless.title    | Title inset restored under `paddingless`                          |
| paddingless.body     | Body inset restored under `paddingless`                           |
| paddingless.footer   | Footer inset restored under `paddingless`                         |
| title.wrapper        | Title bar flex layout                                             |
| title.divider        | Title bar bottom border and spacing (hidden by `without-title`)   |
| title.text           | Title text font and inline-flex alignment                         |
| title.icon           | Title icon dimensions                                             |
| body.wrapper         | Error list container with left margin and padding                 |
| body.list            | List spacing and text size (marker style set by `list-numeric`)   |
| close                | Close button icon dimensions                                      |
| slots.footer.wrapper | Footer container top margin                                       |
| slots.footer.base    | Footer aligning wrapper (flex row)                                |
| slots.footer.start   | Footer alignment applied by `start`                               |
| slots.footer.center  | Footer alignment applied by `center`                              |
| slots.footer.end     | Footer alignment applied by `end` (default)                       |
| slots.footer.between | Footer alignment applied by `between`                             |
