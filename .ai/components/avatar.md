# TallStackUI: Avatar

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A versatile avatar component supporting images, text initials, Eloquent model integration via ui-avatars.com, presence indicators with pulse animation, and multiple sizes.

## Basic Usage

```blade
<x-avatar image="https://example.com/photo.jpg" />
```

```blade
<x-avatar text="AB" color="blue" lg />
```

```blade
<x-avatar :model="$user" property="name" presence pulse />
```

## Attributes

| Attribute        | Type          | Default     | Description                                                                   |
|------------------|---------------|-------------|-------------------------------------------------------------------------------|
| model            | Model\|null   | null        | Eloquent model for generating a UI Avatars URL                                |
| text             | string\|null  | null        | Text or initials displayed inside the avatar                                  |
| color            | string\|null  | 'primary'   | Background color theme                                                        |
| image            | string\|null  | null        | URL to a custom avatar image                                                  |
| xs               | bool          | null        | Extra-small size (24x24)                                                      |
| sm               | bool          | null        | Small size (32x32)                                                            |
| md               | bool          | null        | Medium size (48x48, default)                                                  |
| lg               | bool          | null        | Large size (56x56)                                                            |
| square           | bool          | false       | Renders with square corners instead of rounded                                |
| property         | string\|null  | 'name'      | Model attribute used for the avatar text                                      |
| background       | string\|null  | '0D8ABC'    | Hex background color for UI Avatars                                           |
| borderless       | bool          | false       | Removes the border around the avatar                                          |
| options          | array\|null   | []          | Additional query parameters passed to UI Avatars API                          |
| presence         | bool\|Closure | false       | Shows an online presence indicator dot                                        |
| presenceColor    | string\|null  | 'green'     | Color of the presence indicator dot                                           |
| presencePosition | string\|null  | 'right-top' | Position of the presence dot (right-top, right-bottom, left-top, left-bottom) |
| pulse            | bool\|Closure | false       | Adds a ping animation to the presence indicator                               |

## Slots

| Slot      | Description                                                                    |
|-----------|--------------------------------------------------------------------------------|
| (default) | Custom content rendered inside the avatar circle when no image or model is set |

## Validation Constraints

- When `presence` is true, `presencePosition` must be one of: `right-top`, `right-bottom`, `left-top`, `left-bottom`.
- When `model` is provided, the specified `property` must exist and be non-blank on the model.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->avatar()
    ->block('wrapper.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                      | Purpose                                      |
|---------------------------------|----------------------------------------------|
| wrapper.class                   | Base wrapper styles (inline-flex, alignment) |
| wrapper.sizes.xs                | Extra-small wrapper dimensions               |
| wrapper.sizes.sm                | Small wrapper dimensions                     |
| wrapper.sizes.md                | Medium wrapper dimensions                    |
| wrapper.sizes.lg                | Large wrapper dimensions                     |
| content.image.class             | Image element base styles                    |
| content.image.sizes.xs          | Extra-small image dimensions                 |
| content.image.sizes.sm          | Small image dimensions                       |
| content.image.sizes.md          | Medium image dimensions                      |
| content.image.sizes.lg          | Large image dimensions                       |
| content.text.class              | Text/initials font styles                    |
| content.text.colors.colorful    | Text color when background is not white      |
| content.text.colors.white       | Text color when background is white          |
| border.base                     | Border width styles                          |
| border.radius                   | Border radius (rounded-full)                 |
| presence.base                   | Presence indicator outer wrapper             |
| presence.wrapper                | Presence dot positioning wrapper             |
| presence.dot                    | Presence dot shape and ring styles           |
| presence.ping                   | Pulse animation styles                       |
| presence.sizes.xs               | Presence dot size at xs                      |
| presence.sizes.sm               | Presence dot size at sm                      |
| presence.sizes.md               | Presence dot size at md                      |
| presence.sizes.lg               | Presence dot size at lg                      |
| presence.positions.right-top    | Position classes for right-top               |
| presence.positions.right-bottom | Position classes for right-bottom            |
| presence.positions.left-top     | Position classes for left-top                |
| presence.positions.left-bottom  | Position classes for left-bottom             |
