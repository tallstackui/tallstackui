# Input Button Addon

The Input component supports placing buttons inside `prefix` and `suffix` slots. This is useful for search inputs, URL fields, quantity controls, and similar patterns where a button needs to be visually attached to the input.

## Basic Usage

### Suffix Button

```blade
<x-input label="Search">
    <x-slot:suffix button>
        <x-button text="Go" sm />
    </x-slot:suffix>
</x-input>
```

### Prefix Button

```blade
<x-input label="URL">
    <x-slot:prefix button>
        <x-button text="https" sm />
    </x-slot:prefix>
</x-input>
```

### Buttons on Both Sides

```blade
<x-input label="Amount">
    <x-slot:prefix button>
        <x-button icon="minus" sm />
    </x-slot:prefix>
    <x-slot:suffix button>
        <x-button icon="plus" sm />
    </x-slot:suffix>
</x-input>
```

## How It Works

Adding the `button` attribute to a `<x-slot:prefix>` or `<x-slot:suffix>` activates addon mode. In this mode:

- An outer wrapper is added around the input and button(s) providing a unified ring and focus style
- The button's own border, shadow, and focus ring are suppressed
- Border radius is adjusted so the button sits flush against the input

Without the `button` attribute, existing prefix/suffix behavior is completely unchanged.

## Button Types

Both normal buttons and circle buttons work, as well as link buttons (using `href`):

```blade
<x-input label="Search">
    <x-slot:suffix button>
        <x-button icon="magnifying-glass" sm />
    </x-slot:suffix>
</x-input>
```

```blade
<x-input label="Search">
    <x-slot:suffix button>
        <x-button.circle icon="magnifying-glass" sm />
    </x-slot:suffix>
</x-input>
```

## Size Recommendations

Use the `sm` size on buttons for the best visual alignment with the input field. The `xs` size also works for compact layouts.

## Error State

When the input is in an error state, the outer wrapper automatically shows the error ring styling, keeping the button visually consistent with the input.

## Soft Customization

The addon layout introduces new customization blocks under `input.addon.*`:

```php
TallStackUi::personalize()
    ->form('input')
    ->block('input.addon.wrapper', '...')
    ->block('input.addon.round.left', '...')
    ->block('input.addon.round.right', '...')
    ->block('input.addon.error', '...')
    ->block('input.addon.button.base', '...')
    ->block('input.addon.button.left', '...')
    ->block('input.addon.button.right', '...');
```

### Available Blocks

| Block                      | Default Classes                                                                                                                                                                      |
|----------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `input.addon.wrapper`      | `flex w-full rounded-md ring-1 focus-within:ring-2 focus-within:ring-primary-600 dark:focus-within:ring-primary-600`                                                                 |
| `input.addon.round.left`   | `rounded-l-none!`                                                                                                                                                                    |
| `input.addon.round.right`  | `rounded-r-none!`                                                                                                                                                                    |
| `input.addon.error`        | `ring-red-300 focus-within:ring-red-500 dark:ring-red-500 dark:focus-within:ring-red-500`                                                                                            |
| `input.addon.button.base`  | `flex-none [&>button]:border-0! [&>a]:border-0! [&>button]:focus:ring-0! [&>a]:focus:ring-0! [&>button]:focus:ring-offset-0! [&>a]:focus:ring-offset-0! [&>button]:shadow-none! ...` |
| `input.addon.button.left`  | `[&>button]:rounded-r-none! [&>a]:rounded-r-none! [&>button]:rounded-l-md [&>a]:rounded-l-md`                                                                                        |
| `input.addon.button.right` | `[&>button]:rounded-l-none! [&>a]:rounded-l-none! [&>button]:rounded-r-md [&>a]:rounded-r-md`                                                                                        |
