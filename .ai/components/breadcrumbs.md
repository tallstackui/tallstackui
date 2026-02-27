# TallStackUI: Breadcrumbs

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A breadcrumb navigation component that renders a trail of links with configurable separators, icons, tooltips, and multiple sizes. Items can be passed directly as an array or resolved automatically from a route-based breadcrumb registry.

## Basic Usage

```blade
<x-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/'],
    ['label' => 'Products', 'link' => '/products'],
    ['label' => 'Detail'],
]" />
```

```blade
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'link' => 'dashboard', 'icon' => 'home'],
    ['label' => 'Users', 'link' => 'users.index'],
    ['label' => 'Edit'],
]" separator="icon:chevron-right" />
```

```blade
{{-- Auto-resolved from route-based registry (no items needed) --}}
<x-breadcrumbs lg />
```

## Attributes

| Attribute      | Type                    | Default | Description                                                                                                                                           |
|----------------|-------------------------|---------|-------------------------------------------------------------------------------------------------------------------------------------------------------|
| items          | array\|Collection\|null | null    | Array of breadcrumb items; each item has `label`, optional `link`, `icon`, and `tooltip`. When null, items are resolved from the breadcrumb registry. |
| separator      | string\|null            | '/'     | Separator between items; use `icon:icon-name` for icon separators                                                                                     |
| separatorClass | string\|null            | null    | Additional CSS classes applied to the separator                                                                                                       |
| xs             | bool                    | null    | Extra-small size                                                                                                                                      |
| sm             | bool                    | null    | Small size                                                                                                                                            |
| lg             | bool                    | null    | Large size                                                                                                                                            |

## Item Array Structure

Each item in the `items` array supports these keys:

| Key     | Type         | Required | Description                                                                          |
|---------|--------------|----------|--------------------------------------------------------------------------------------|
| label   | string       | Yes      | Display text for the breadcrumb                                                      |
| link    | string\|null | No       | URL, relative path, or named route. Items without a link render as the current page. |
| icon    | string\|null | No       | Heroicon name displayed before the label                                             |
| tooltip | string\|null | No       | Tooltip text shown on hover                                                          |

## Slots

| Slot  | Description                                 |
|-------|---------------------------------------------|
| left  | Content rendered before the breadcrumb list |
| right | Content rendered after the breadcrumb list  |

## Configuration

The breadcrumbs component supports a file-based registry for defining breadcrumbs per route. Configure the definition files in `config/tallstackui.php`:

```php
'breadcrumbs' => [
    \TallStackUi\Components\Breadcrumbs\Component::class,
    [
        'files' => [
            'routes/breadcrumbs.php',
        ],
    ],
],
```

In `routes/breadcrumbs.php`, register breadcrumbs using the `BreadcrumbRegistry`:

```php
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Breadcrumbs\BreadcrumbTrail;

TallStackUi::breadcrumbs()
    ->for('dashboard', fn (BreadcrumbTrail $trail) =>
        $trail->push('Dashboard', route('dashboard'))
    )
    ->for('users.show', fn (BreadcrumbTrail $trail, User $user) =>
        $trail->parent('dashboard')
              ->push($user->name)
    );
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->breadcrumbs()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name              | Purpose                                  |
|-------------------------|------------------------------------------|
| wrapper                 | Outer nav flex container                 |
| list.class              | Ordered list flex layout                 |
| list.sizes.xs           | List gap at extra-small size             |
| list.sizes.sm           | List gap at small size                   |
| list.sizes.md           | List gap at medium size                  |
| list.sizes.lg           | List gap at large size                   |
| separator.wrapper       | Separator list item wrapper              |
| separator.text.class    | Text separator color and styling         |
| separator.text.sizes.xs | Text separator size at xs                |
| separator.text.sizes.sm | Text separator size at sm                |
| separator.text.sizes.md | Text separator size at md                |
| separator.text.sizes.lg | Text separator size at lg                |
| separator.icon.class    | Icon separator color and styling         |
| separator.icon.sizes.xs | Icon separator size at xs                |
| separator.icon.sizes.sm | Icon separator size at sm                |
| separator.icon.sizes.md | Icon separator size at md                |
| separator.icon.sizes.lg | Icon separator size at lg                |
| item.wrapper            | Individual breadcrumb item container     |
| item.link.class         | Linked item text color and hover styles  |
| item.link.sizes.xs      | Linked item text size at xs              |
| item.link.sizes.sm      | Linked item text size at sm              |
| item.link.sizes.md      | Linked item text size at md              |
| item.link.sizes.lg      | Linked item text size at lg              |
| item.current.class      | Current (non-linked) item font and color |
| item.current.sizes.xs   | Current item text size at xs             |
| item.current.sizes.sm   | Current item text size at sm             |
| item.current.sizes.md   | Current item text size at md             |
| item.current.sizes.lg   | Current item text size at lg             |
| item.icon.class         | Item icon base styles                    |
| item.icon.sizes.xs      | Item icon size at xs                     |
| item.icon.sizes.sm      | Item icon size at sm                     |
| item.icon.sizes.md      | Item icon size at md                     |
| item.icon.sizes.lg      | Item icon size at lg                     |
