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

```blade
<x-avatar text="AB" size="3xl" />
```

## Attributes

| Attribute        | Type          | Default     | Description                                                                   |
|------------------|---------------|-------------|-------------------------------------------------------------------------------|
| model            | Model\|null   | null        | Eloquent model for generating a UI Avatars URL                                |
| text             | string\|null  | null        | Text or initials displayed inside the avatar                                  |
| color            | string\|null  | 'primary'   | Background color theme                                                        |
| image            | string\|null  | null        | URL to a custom avatar image                                                  |
| gravatar         | bool\|string\|null | null   | An email, a model column holding one, or `true` to read the model's `email`   |
| gravatarDefault  | string\|null  | null        | Gravatar's own fallback when no name is available (404, mp, identicon, monsterid, wavatar, retro, robohash, blank) |
| gravatarRating   | string\|null  | null        | Highest rating Gravatar may serve (g, pg, r, x)                               |
| size             | string\|null  | null        | Size of the avatar, any value of the scale (see Sizes)                        |
| xs               | bool          | false       | Shorthand for `size="xs"` (24x24)                                             |
| sm               | bool          | false       | Shorthand for `size="sm"` (32x32)                                             |
| md               | bool          | false       | Shorthand for `size="md"` (48x48, the resolved default)                       |
| lg               | bool          | false       | Shorthand for `size="lg"` (56x56)                                             |
| xl               | bool          | false       | Shorthand for `size="xl"` (64x64)                                             |
| 2xl              | bool          | false       | Shorthand for `size="2xl"` (80x80)                                            |
| 3xl              | bool          | false       | Shorthand for `size="3xl"` (96x96)                                            |
| 4xl              | bool          | false       | Shorthand for `size="4xl"` (112x112)                                          |
| 5xl              | bool          | false       | Shorthand for `size="5xl"` (128x128)                                          |
| 6xl              | bool          | false       | Shorthand for `size="6xl"` (144x144)                                          |
| 7xl              | bool          | false       | Shorthand for `size="7xl"` (160x160)                                          |
| square           | bool          | false       | Renders with square corners instead of rounded                                |
| property         | string\|null  | 'name'      | Model attribute used for the avatar text                                      |
| background       | string\|null  | '0D8ABC'    | Hex background color for UI Avatars                                           |
| borderless       | bool          | false       | Removes the border around the avatar                                          |
| options          | array\|null   | []          | Additional query parameters passed to UI Avatars API                          |
| presence         | bool\|Closure | false       | Shows an online presence indicator dot                                        |
| presenceColor    | string\|null  | 'green'     | Color of the presence indicator dot                                           |
| presencePosition | string\|null  | 'right-top' | Position of the presence dot (right-top, right-bottom, left-top, left-bottom) |
| pulse            | bool\|Closure | false       | Adds a ping animation to the presence indicator                               |

The size shorthands are usable like any other attribute, including the bound form
`:lg="$condition"`, but they are not declared constructor props — see Sizes.

## Sizes

| Size | Avatar  | Text        | Presence dot |
|------|---------|-------------|--------------|
| xs   | 24x24   | `text-xs`   | 6x6          |
| sm   | 32x32   | `text-sm`   | 8x8          |
| md   | 48x48   | `text-base` | 12x12        |
| lg   | 56x56   | `text-lg`   | 14x14        |
| xl   | 64x64   | `text-xl`   | 16x16        |
| 2xl  | 80x80   | `text-2xl`  | 20x20        |
| 3xl  | 96x96   | `text-3xl`  | 24x24        |
| 4xl  | 112x112 | `text-4xl`  | 28x28        |
| 5xl  | 128x128 | `text-5xl`  | 32x32        |
| 6xl  | 144x144 | `text-6xl`  | 36x36        |
| 7xl  | 160x160 | `text-7xl`  | 40x40        |

Every size of the scale is a shorthand, and `size` takes the same values:

```blade
<x-avatar text="AB" lg />
<x-avatar text="AB" 5xl />
<x-avatar text="AB" size="5xl" />
```

The shorthands are not declared props — `2xl` and above would compile to invalid
PHP variables like `$2xl` — so they are read from the attribute bag and removed
from it before the tag is rendered.

## Gravatar

```blade
<x-avatar gravatar="aj@mail.com" />
<x-avatar :model="$user" gravatar />
<x-avatar :model="$user" gravatar="contact_email" />
```

The `gravatar` prop takes three shapes, told apart by the `@`: a value carrying
one is the email itself, a value without one is the model column holding it, and
`true` reads the model's `email`.

The email is lowercased and trimmed before being hashed with SHA-256, which is
what Gravatar asks for.

**Fallback.** Gravatar accepts a URL in its `d` parameter, so when a name is
available — `text`, or the model's `property` — the component points `d` at the
ui-avatars URL it already knows how to build. An email with no Gravatar account
lands on the same coloured initials the component renders elsewhere. Without a
name, `d` carries `gravatarDefault` instead.

**Size.** `s` is sent at twice the rendered size, so a `7xl` avatar asks for
320px and stays sharp on a retina screen. `size` is sent to ui-avatars the same
way.

**Precedence.** `image` wins over `gravatar`, which wins over `model`.

## Global Configuration

```php
// config/tallstackui.php
'avatar' => [
    \TallStackUi\Components\Avatar\Component::class,
    [
        'size' => 'md',
        'gravatar' => [
            'default' => 'mp',
            'rating' => 'g',
        ],
    ],
],
```

A shorthand wins over `size`, and `size` wins over the configuration, so
`<x-avatar sm size="7xl" />` renders small. `gravatarDefault` and `gravatarRating`
fall back to the `gravatar` block the same way.

## Slots

| Slot      | Description                                                                    |
|-----------|--------------------------------------------------------------------------------|
| (default) | Custom content rendered inside the avatar circle when no image or model is set |

## Validation Constraints

- `size` must be one of: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`, `3xl`, `4xl`, `5xl`, `6xl`, `7xl`. The configuration value is validated the same way.
- `gravatarDefault` must be one of: `404`, `mp`, `identicon`, `monsterid`, `wavatar`, `retro`, `robohash`, `blank`.
- `gravatarRating` must be one of: `g`, `pg`, `r`, `x`.
- `gravatar` needs an email it can actually reach, inline or through the model.
- Only one shorthand can be used at a time: `<x-avatar sm 7xl />` raises an exception.
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

| Block Name                      | Purpose                                                                                 |
|---------------------------------|-----------------------------------------------------------------------------------------|
| wrapper.class                   | Base wrapper styles (inline-flex, alignment)                                            |
| wrapper.sizes.{size}            | Wrapper dimensions of a size of the scale                                               |
| content.image.class             | Image element base styles                                                               |
| content.image.sizes.{size}      | Image dimensions of a size of the scale                                                 |
| content.text.class              | Text/initials font styles                                                               |
| content.text.colors.colorful    | Text color when background is not white                                                 |
| content.text.colors.white       | Text color when background is white                                                     |
| border.base                     | Border width styles                                                                     |
| border.radius                   | Border radius (rounded-full)                                                            |
| presence.base                   | Presence indicator outer wrapper, kept at `w-fit` so it does not stretch as a flex item |
| presence.wrapper                | Presence dot positioning wrapper                                                        |
| presence.dot                    | Presence dot shape and ring styles                                                      |
| presence.ping                   | Pulse animation styles                                                                  |
| presence.sizes.{size}           | Presence dot size at a size of the scale                                                |
| presence.positions.right-top    | Position classes for right-top                                                          |
| presence.positions.right-bottom | Position classes for right-bottom                                                       |
| presence.positions.left-top     | Position classes for left-top                                                           |
| presence.positions.left-bottom  | Position classes for left-bottom                                                        |
| presence.offsets.{position}     | Extra nudge applied only when `square`, so the round dot reaches the corner              |
