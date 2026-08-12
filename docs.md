# TallStackUI 4.x

Running record of everything that changed on the `4.x` branch relative to `3.x`.

Entries are grouped by component, most recently touched first. Within a component,
changes are split into **Added**, **Changed** and **Fixed**. Anything that requires
action from an upgrading application carries a **Migration** note.

Soft customization keys are part of the public API: renaming, nesting or removing a
block breaks applications that target it through `TallStackUi::customize()`. Every
such change is listed under **Migration**.

---

## Accordion

### Changed — the flat look is `shadowless` and `bordered`, and `flat` is gone

The wrapper drew its border, its rounding and its shadow from a single block, and
`flat` was the one switch that took all three away together. That left the
component with exactly two looks and no way to ask for anything between them: the
shadow could not go without the border, and the border could not go at all.

It now carries the pair the rest of the library already shares — Card, Stats,
Calendar, Tab and Errors:

| Written                | Renders                                        |
|------------------------|------------------------------------------------|
| *(nothing)*            | rounded and clipped, with the shadow, no border |
| `shadowless`           | the flat look: same shape, no shadow           |
| `bordered`             | the border, keeping the shadow                 |
| `shadowless bordered`  | flat, with the border                          |

```blade
<x-accordion bordered>
    <x-accordion.items title="First" id="first">Bordered, with the shadow.</x-accordion.items>
</x-accordion>

<x-accordion shadowless bordered>
    <x-accordion.items title="First" id="first">Flat, with the border.</x-accordion.items>
</x-accordion>
```

Both exist as global defaults, the inline prop always winning, so
`:bordered="false"` and `:shadowless="false"` opt a single accordion out of
whatever the configuration says:

```php
'accordion' => [
    Components\Accordion\Main\Component::class,
    ['shadowless' => false, 'bordered' => false],
],
```

The rounding and the clipping stopped being optional along the way. They were
only ever removable together with the shadow, and the two have to travel
together regardless: the wrapper is what clips the first item's hover fill and
the last item's separator against the rounded corners, so a radius without the
clipping squares them off again. Square corners are reachable through
`TallStackUi::globals()->square()` or a customization of `wrapper.base`.

**Migration** — `flat` no longer exists. It does not raise: an undeclared
attribute falls through to the bag, so `<x-accordion flat>` still renders and
simply lands a stray `flat` attribute on the `<div>` while changing nothing.
`shadowless` is the replacement for what it was mostly reached for.

The border being opt-in is the other break: an accordion that relied on it has to
pass `bordered`, or set the configuration default once.

| Block              | Was                                                            | Is                                                 |
|--------------------|----------------------------------------------------------------|----------------------------------------------------|
| `wrapper.base`     | the background and the width                                   | also the rounding, the clipping and the shadow     |
| `wrapper.bordered` | the border, the rounding, the clipping and the shadow, unless `flat` | removed                                      |
| `shadowless`       | `shadow-none!`                                                 | unchanged                                          |
| `bordered`         | —                                                              | new: `border border-gray-200 dark:border-dark-700` |

`wrapper.bordered` is gone rather than renamed because everything it carried
either moved into `wrapper.base` or became a flag of its own, and a block by that
name sitting next to a real `bordered` block would read as the same thing twice.

### Changed — the flat-look defaults resolve in the constructor

The accordion was the only one of the components carrying these flags that read
its default through `CompileConfigurations`. `shadowless` joined `bordered` in
the constructor, matching Card, Stats, Calendar, Tab, Errors and Alert, and
`CompileConfigurations::accordion()` is gone along with its entry in the
dispatcher.

**Migration** — nothing for an application on the bundled view. One that replaced
the accordion view through deep customization has to read the `$shadowless` and
`$bordered` props: `$configurations['shadowless']` is no longer populated, since
the component no longer produces a configuration array at all.

## Clipboard

### Fixed — the two rings met at the seam and stacked into a brighter mark

The input and the copy button each carried a `ring-1` of their own, and `-ml-px`
(or `-mr-px` with `left`) only butted the two boxes together. That collapses the
doubled line along most of the seam — the button's background covers the input's
ring — but not where the bands cross: at the corners where the vertical seam meets
the top and the bottom edge, both rings paint the same pixel. Both are `/50`, so the
alpha stacks to `1 - 0.5² = 75%` and the crossing reads as a brighter nick hanging
off the ends of the divider.

Sampled from a dark render, with the surfaces at `dark-800` on a `dark-900` page:

| Point                        | Value | Is                                    |
|------------------------------|-------|---------------------------------------|
| outer edge                   | `36`  | `dark-600` at 50% over the page       |
| seam, mid height             | `40`  | `dark-600` at 50% over `dark-800`     |
| seam, where it meets the top | `51`  | `dark-600` at **75%** over `dark-800` |

It reads the same with the button on either side, since the overlap is only
mirrored. Light mode has the same geometry and hides it: `gray-200` on `bg-white`
is far enough from the surface that the extra 25% of alpha does not register.

The outline moved up to the outer wrapper instead, which is the arrangement
`<x-input>` already uses for its prefix and suffix slots — one box draws the
border, the pieces inside it draw none:

| Element        | Was                             | Is                                    |
|----------------|---------------------------------|---------------------------------------|
| outer wrapper  | nothing                         | `ring-1` plus `focus-within:ring-2`   |
| input          | `ring-1`, `focus:ring-2`        | `ring-0`, no ring at all              |
| button         | `ring-1`, `-ml-px` / `-mr-px`   | no ring, one border on the seam side  |

Only one element paints a line anywhere now, so there is nothing left to stack. The
divider is a `border-l` (or `border-r`) rather than a ring because a border is the
one thing that applies to a single side — a ring is a box-shadow spread and always
covers all four.

The focus treatment changed with it. It was `focus:ring-2` on the input, so the
control only lit up while the input itself held the focus; it is now
`focus-within:ring-2` on the wrapper, which also covers the button.

**Migration** — a new block, and four that changed shape:

| Block                | Was                                            | Is                                             |
|----------------------|------------------------------------------------|------------------------------------------------|
| `wrapper.base`       | —                                              | new: the ring, the radius and the focus ring   |
| `input.wrapper`      | carried `ring-inset` and `focus-within:z-10`   | layout only                                    |
| `input.base`         | `ring-1 ring-gray-200`, `focus:ring-2`         | `ring-0`, focus neutralized                    |
| `input.color.base`   | carried `ring-gray-200 dark:ring-dark-600/50`  | text color only                                |
| `input.buttons.base` | carried the ring                               | no ring                                        |
| `input.buttons.*`    | radius plus the negative margin                | radius plus the divider border                 |

An application customizing any of them has to choose between the chrome, which now
lives on `wrapper.base`, and what is left on the piece it used to target.

`wrapper.base` is applied only in input mode: the icon mode has no field to enclose.

Two side effects worth knowing. The negative margins are gone, so the control is one
pixel wider than it was. And `input.color.base` is now written by the component
rather than taken whole from `FormDefaultInputClasses`, since the ring classes it
contributes are exactly what had to go — the trait is untouched, so every other form
component keeps reading it as before.

## Form / Password

### Added — `generator` takes the field it should fill

Generating a password and mirroring it into the confirmation field was left to the
application, and the shortest form of it reached into the DOM:

```blade
<x-password wire:model="password"
            generator
            x-on:generate="document.getElementById('password_confirmation').value = $event.detail.password" />
```

That line writes the value and nothing else — `wire:model` never hears about it, so the
confirmation reaches the server empty. The working version had to go through Livewire
instead (`$wire.set('password_confirmation', $event.detail.password)`), which is easy to
get wrong and impossible to guess.

`generator` now accepts the name of the field it should fill:

```blade
<x-password wire:model="password" generator="password_confirmation" />

<x-password wire:model="password_confirmation" />
```

Nothing is asked of the target. The input already renders its `id` from the bound
property, so a field bound to `password_confirmation` is addressable by that name; an
explicit `id` or `x-ref` works the same way, resolved in that order, the way
`$tsui.focus` does it. A CSS selector is not accepted.

How the value lands depends on what the target is. Another `<x-password>` owns its own
state, so the fill goes through its setter and the input, the entangled model and the
rules checklist all move together. Anything else — `<x-input>`, a bare `<input>` — takes
the value plus an `input` event, which is what `wire:model` listens to, so it also works
outside Livewire.

The generated password is revealed on the component that generated it, as before, and the
target stays masked: the confirmation is a field the user is meant to confirm, not read.

The `generate` event still fires, after the fill, so an existing `x-on:generate` keeps
working and remains the last word if it wants to overwrite the target. A target that does
not exist logs to the console and leaves the generating field filled.

Passing an empty string — the shape `:generator="$name"` takes when the variable is
empty — raises an exception instead of silently dropping the button.

## Card, Stats, Calendar, Tab, Errors, Alert & Avatar

### Added — the flat-look flags answer to the configuration

`shadowless`, `bordered` and `borderless` were per-instance decisions, so an application
that wants the flat look everywhere had to repeat the flags on every component. They join
the other defaults in the configuration, following the Accordion and the Kbd:

```php
'card'     => [Components\Card\Component::class,          ['shadowless' => false, 'bordered' => false]],
'stats'    => [Components\Stats\Component::class,         ['shadowless' => false, 'bordered' => false]],
'calendar' => [Components\Calendar\Component::class,      ['shadowless' => false, 'bordered' => false]],
'tab'      => [Components\Tab\Main\Component::class,      ['shadowless' => false, 'bordered' => false]],
'errors'   => [Components\Errors\Component::class,        ['shadowless' => false, 'bordered' => false]],
'alert'    => [Components\Alert\Component::class,         ['shadowless' => false, 'bordered' => null]],
'avatar'   => [Components\Avatar\Component::class,        ['borderless' => false]],
```

The inline prop always wins, so `:shadowless="false"` gives a single component its shadow
back. On Card and Stats the skeleton reads the same flags, so a placeholder configured as
flat stays flat while it loads.

Alert's `bordered` keeps the string it always took — `"left"`, `"right"` or
`"<side>:<color>"` — and the configured value goes through the same validation, so a bad
side raises the usual exception instead of reaching the template.

The defaults are resolved in the constructor, ahead of the color compilation and the
validation, which is what lets Alert and Errors color their border from a configured
value. Nothing changes for an application that never touches the configuration: the props
that used to default to `false` now default to `null` and resolve to `false`.

## Table

### Added — `compact` answers to the configuration

The density was a per-table decision, so an application that wants tight rows
everywhere had to repeat `compact` on every table. It joins the other table
defaults in the configuration:

```php
'table' => [
    Components\Table\Component::class,
    [
        'paginator' => 'simple',
        'paginate' => false,
        'simple-pagination' => false,
        'filter' => false,
        'quantity' => [10, 25, 50, 100],
        'compact' => false,
    ],
],
```

The inline prop always wins, so `:compact="false"` gives a single table the
roomy padding back. The skeleton reads the same flag, so a table configured as
compact stays compact while it loads.

## Avatar

### Added — the size scale goes all the way up to `7xl`

The scale stopped at `lg`, 56 pixels, which is the size of an avatar sitting in a
table row. A profile header, an empty state or a card that leads with a face had
to leave the component behind and write the dimensions by hand, or reach for a
customization that then applied to every avatar in the application.

The scale now runs the full length the Icon component already had — `xs`, `sm`,
`md`, `lg`, `xl`, `2xl`, `3xl`, `4xl`, `5xl`, `6xl`, `7xl` — from 24 to 160
pixels. The wrapper, the image and the presence dot all grow together, the dot
holding the quarter of the avatar it has always held:

| Size  | Avatar  | Text       | Presence dot |
|-------|---------|------------|--------------|
| `xs`  | 24x24   | `text-xs`  | 6x6          |
| `sm`  | 32x32   | `text-sm`  | 8x8          |
| `md`  | 48x48   | `text-base` | 12x12       |
| `lg`  | 56x56   | `text-lg`  | 14x14        |
| `xl`  | 64x64   | `text-xl`  | 16x16        |
| `2xl` | 80x80   | `text-2xl` | 20x20        |
| `3xl` | 96x96   | `text-3xl` | 24x24        |
| `4xl` | 112x112 | `text-4xl` | 28x28        |
| `5xl` | 128x128 | `text-5xl` | 32x32        |
| `6xl` | 144x144 | `text-6xl` | 36x36        |
| `7xl` | 160x160 | `text-7xl` | 40x40        |

Every size of the scale is a shorthand, `size` takes the same values, and the two
can be mixed across call sites:

```blade
<x-avatar text="AB" xl />
<x-avatar text="AB" 5xl />
<x-avatar :model="$user" size="7xl" presence pulse />
```

`xs`, `sm`, `md` and `lg` stopped being declared props to get there. A parameter
cannot be named `2xl` — `$2xl` is not a valid PHP variable — so the whole scale is
read from the attribute bag instead, the way the Icon component already reads its
own, and the keys are removed from the bag before the tag renders. Nothing changes
at the call site: `<x-avatar lg />` and `:lg="$condition"` behave as they did.

Using two at once is now an error rather than a silent pick:

```blade
<x-avatar sm 7xl /> {{-- Only one size can be used at a time, but [sm, 7xl] were given --}}
```

The new keys follow the ones that were already there, so a customization that
targets a single size keeps the shape it had:

```php
TallStackUi::customize()
    ->avatar()
    ->block('wrapper.sizes.7xl', 'w-48 h-48 text-8xl');
```

### Added — `gravatar`

The component could already turn a model into coloured initials through
ui-avatars. It now also reads a real photo from Gravatar:

```blade
<x-avatar gravatar="aj@mail.com" />
<x-avatar :model="$user" gravatar />
<x-avatar :model="$user" gravatar="contact_email" />
```

One prop covers the three shapes because an email always carries an `@` and a
column name never does. A value with one is the address itself, a value without
one names the model column holding it, and `true` reads the model's `email`. The
address is lowercased and trimmed before being hashed with SHA-256, which is what
Gravatar asks for today.

Gravatar accepts a URL in its `d` parameter, so the two sources chain instead of
competing: when a name is around — `text`, or the model's `property` — `d` points
at the ui-avatars URL the component already builds, and an email with no Gravatar
account lands on the same initials it would have rendered anyway. Without a name,
`d` carries `gravatar-default`.

Both services are now asked for twice the rendered size, so a `7xl` avatar
requests 320 pixels and stays sharp on a retina screen. ui-avatars was previously
asked for no size at all and served its own default.

The fallback image and the rating answer to the configuration:

```php
'avatar' => [
    Components\Avatar\Component::class,
    [
        'size' => 'md',
        'gravatar' => [
            'default' => 'mp',
            'rating' => 'g',
        ],
    ],
],
```

`image` still wins over everything, then `gravatar`, then `model`.

### Changed — the template picks the image source through `$src`

The template used to decide between `image` and `modelable()` inline, which no
longer holds with a third source in the ladder. `AvatarRuntime` now resolves it
once and hands the template `$src`. The name is deliberate: a public method is
already exposed to the view under its own name as an invokable variable, so a
runtime key called `source` would be silently overwritten by the wrapper around
`source()`. Only an application that replaced the avatar view through deep
customization needs to care.

### Fixed — the presence dot fell short of the corner on a square avatar

`presence.positions.*` pins the dot's own box to the corner of the avatar's box,
which is exactly right for the round avatar: the dot lands on the diagonal where
the circle passes, so it straddles the rim. On a square avatar the shape does
reach the corner, but the dot is still `rounded-full`, so its curve pulled back
from the quoin and left a gap — about 2 pixels at `md`, 8 at `7xl`.

A new `presence.offsets.{position}` block nudges the dot out along the diagonal by
`(√2-1)/2` of its own size, 14.6% per axis, and the template applies it only when
`square` is on. The existing `presence.positions.*` blocks are untouched, so an
application already customizing them keeps working — the nudge is a `translate`
that stacks on top rather than a competing `top`/`right`.

### Fixed — the presence dot drifted away from the avatar inside a flex column

`presence.base` wrapped the avatar in `relative inline-flex` and nothing else. Put
that wrapper inside a flex container and it becomes a flex item, so the default
`align-items: stretch` blew its width out to the whole cross axis — and `right-0`
anchored the dot to the edge of the container instead of the edge of the avatar:

```blade
<div class="flex flex-col gap-2">
    <x-avatar text="AJ" presence /> {{-- the dot sat at the far right of the row --}}
</div>
```

Avatars without `presence` never showed it because their root carries an explicit
width — `w-12`, `w-40` — and stretch does not apply to a flex item whose width is
not `auto`. The presence wrapper had no width at all. It now carries `w-fit`,
which pins it back to the avatar in flex, grid and block parents alike.

### Fixed — `md` asked for a class Tailwind does not have

`wrapper.sizes.md` and `content.image.sizes.md` carried `text-md`, which is not a
Tailwind class and therefore did nothing: the initials of a medium avatar simply
inherited the font size of whatever wrapped them. They now carry `text-base`, the
step the rest of the scale is built on. An avatar sitting inside a container with
its own font size — a `text-sm` card, say — renders its initials at 16 pixels now
instead of following the container.

### Changed — the template reads the size through `$scale`

The size is only known once the attribute bag is populated, which happens after
the component data has been captured, so writing it back into `$size` was silently
undone on render. The avatar joined the components that carry a runtime — the new
`AvatarRuntime` — and its template reads `$scale`. This only matters to an
application that replaced the avatar view through deep customization.

### Added — `size` answers to the configuration

An application that leads with one avatar size had to repeat the shorthand at
every call site. The default now lives in the component configuration:

```php
'avatar' => [
    Components\Avatar\Component::class,
    [
        'size' => 'md',
    ],
],
```

A shorthand wins over `size`, and `size` wins over the configuration, so
`<x-avatar sm size="7xl" />` renders small and a lone `<x-avatar />` renders
whatever the configuration says.

### Added — `size` is validated

An unknown size used to reach the Blade template and fail on a missing
customization key. It now raises a validation exception naming the whole scale,
which also covers a bad value coming from the configuration.

## Link

### Added — `navigate` and `navigate-hover` answer to the configuration

An application that navigates through Livewire had to repeat `navigate` on every
single link. Both flags now exist as global defaults in the component
configuration:

```php
'link' => [
    Components\Link\Component::class,
    [
        'navigate' => false,
        'navigate-hover' => false,
    ],
],
```

They are mutually exclusive — the template only ever emits one of the two — so
declaring either one of them inline suppresses the global default of both, and
`:navigate="false"` opts a single link out of a global `navigate`:

```blade
<x-link href="/dashboard" :navigate="false" />
<x-link href="/dashboard" navigate-hover />
```

## Form / Autocomplete

### Added — `select` remaps the item keys

The items had to arrive shaped as `value`, `description`, `image` and `metadata`;
anything else was dropped during normalization, so an endpoint returning
`name`/`email`/`avatar` had to be reshaped before it reached the component.
`select` now remaps them, with the same syntax the other components use:

```blade
<x-autocomplete wire:model="user"
                request="/api/users"
                select="value:name|description:email|image:avatar" />
```

Any part left out falls back to the key of the same name, and `disabled` is
always read from `disabled`. The remap runs inside the normalization step, so
filtering, the `select` event payload and `wire:model` all keep speaking the
canonical names — only the source keys change.

It works the same for local `:items` and for the rows fetched by `:request`.

### Added — `select` answers to the configuration

```php
'autocomplete' => [
    Components\Form\Autocomplete\Component::class,
    [
        'strict' => false,
        'select' => 'value:name|description:email|image:avatar',
    ],
],
```

The inline attribute always wins.

## Form / Select (Styled & Native), Checkbox Group, Radio Group & Swap

### Added — `select` answers to the configuration

Every component that remaps its option keys through `select` now reads a global
default from the configuration, the same way the Command Palette does. An
application whose payloads always come as `name`/`id` declares the mapping once:

```php
'checkbox.group' => [
    Components\Form\Checkbox\Group\Component::class,
    ['select' => 'label:name|value:id'],
],
'radio.group' => [
    Components\Form\Radio\Group\Component::class,
    ['select' => 'label:name|value:id'],
],
'select.native' => [
    Components\Form\Select\Native\Component::class,
    ['select' => 'label:name|value:id'],
],
'select.styled' => [
    Components\Form\Select\Styled\Component::class,
    [
        'unfiltered' => false,
        'recycle' => false,
        'select' => 'label:name|value:id',
    ],
],
'swap' => [
    Components\Swap\Component::class,
    [
        'preview' => false,
        'vertical' => false,
        'loop' => true,
        'select' => 'label:name|value:id',
    ],
],
```

and drops it from the call sites:

```diff
-<x-select.styled wire:model="city" :options="$cities" select="label:name|value:id" />
+<x-select.styled wire:model="city" :options="$cities" />
```

The inline attribute always wins. With neither, the fallback is what it always
was: `label:label|value:value|description:description|image:image` for the two
selects, and the key of the same name for the selection groups and the swap.

Each component reads its own entry, so the mapping can differ per component —
nothing is shared between them.

**Migration** — none. `select` defaults to `null` everywhere, so a configuration
that does not declare it behaves exactly as before.

`select.native`, `checkbox.group` and `radio.group` had no settings array in
`config/tallstackui.php` and now carry one. Applications with a published config
file keep working untouched; the new key is only read when it is there.

## Command Palette

### Added — `select` answers to the configuration

A palette placed in the layout still had to carry the field mapping of its own
endpoint at the call site, which is the one place a global component has nothing
to say. `select` is now a global default:

```php
'command-palette' => [
    Components\CommandPalette\Component::class,
    [
        'actionable' => null,
        'request' => '/api/users',
        'select' => 'label:name|value:id|description:email|image:avatar',
        // ...
    ],
],
```

which reduces the usage to the tag alone:

```diff
-<x-command-palette id="users"
-                   request="/api/users"
-                   select="label:name|value:id|description:email|image:avatar" />
+<x-command-palette id="users" />
```

The inline attribute always wins, and with neither the mapping falls back to
`label:label|value:value|description:description|image:image|icon:icon` as
before. The configuration takes the same string syntax, so a partial mapping
only names the keys that differ.

## Clipboard

### Changed — `icons` folded into `icon`

Customizing the copy and copied icons took two attributes that could not be used
apart: `icons` did nothing without `icon`, and `icon` alone was the only way to
ask for the default pair. `icon` now accepts the array directly, and an array
turns the icon mode on by itself:

```blade
<x-clipboard text="TallStackUI" icon />

<x-clipboard text="TallStackUI" :icon="['copy' => 'pencil', 'copied' => 'check']" />

<x-clipboard text="TallStackUI" :icon="['copy' => 'pencil']" />
```

Either key can be omitted and falls back to the default icon of that state. Only
`false` and `null` keep the input mode, so `:icon="[]"` renders the icon with the
defaults rather than the input.

**Migration** — drop `icons` and move its value into `icon`:

```diff
-<x-clipboard text="TallStackUI" icon :icons="['copy' => 'pencil', 'copied' => 'check']" />
+<x-clipboard text="TallStackUI" :icon="['copy' => 'pencil', 'copied' => 'check']" />
```

`icons` is gone, not deprecated, and an array attribute the component does not
declare is dropped by the attribute bag — so a leftover `:icons` neither renders
nor raises, it simply stops having any effect.

### Added — the `icon` array keys are validated

A typo in the array used to fall back to the default icon without a word.
Anything other than `copy` and `copied` now raises the usual validation
exception.

## Back to Top

### Added — the whole look answers to the configuration

`immediate`, `square`, `color`, `icon`, `position` and `size` are now global
defaults, so a floating button that is meant to look the same on every page no
longer has to repeat itself at every call site:

```php
'back-to-top' => [
    Components\BackToTop\Component::class,
    [
        'immediate' => false,
        'square' => false,
        'color' => 'primary',
        'icon' => 'chevron-up',
        'position' => 'bottom-right',
        'size' => 'md',
    ],
],
```

The inline prop always wins, negative forms included — `:square="false"` brings a
single button back to the circle while the configuration keeps the rest square.
`size` is the value behind the `xs`/`sm`/`md`/`lg` flags, so it only applies when
none of them is present.

### Changed — the props default to `null`

`color`, `position`, `immediate` and `square` used to carry their defaults in the
constructor signature, which left no room to tell "not informed" apart from
"informed with the default value". They now start as `null` and resolve in
`setup()`, ahead of the color compilation and the validation that both depend on
them.

### Added — `size` is validated

An unknown size used to reach the Blade template and fail on a missing
customization key. It now raises the same validation exception `position` does,
which also covers a bad value coming from the configuration.

## Form / Date

### Fixed — the floating panel no longer resizes when a picker opens

Opening the month/year picker used to shrink the panel: the day buttons were
hidden behind an `x-show` while a picker was open — a leftover from before the
picker became a covering overlay — so the grid collapsed and the panel jumped
to the fixed `h-[17rem]` the `floating.expanded` block imposed. The day grid
now stays in flow under the overlay, the panel keeps its natural height, and
the expanded bound became a minimum (`min-h-[17rem]`) that only matters under
`month-year-only`, where the grid genuinely is not there.

**Migration** — the `floating.expanded` block moved to `box.picker.expanded`,
matching the calendar, and its value changed from `h-[17rem]` to
`min-h-[17rem]`.

## Reaction & Tooltip

### Fixed — the balloons now honor the flash global

`globals()->flash()` works by stripping the `x-transition` directives from the
Blade templates, and the two JavaScript-built balloons never had any: they
animate through a plain CSS transition, so they kept fading regardless of the
global. Both now mark the balloon with `data-instant`, which turns the
transition off.

The reaction resolves the flag per instance, through the same `only`/`except`
resolution every Blade component uses. The tooltip is a directive that can run
on elements with no component behind them, so it reads the flag from the same
`data-tsui-*` attributes on the script tag that carry the other tooltip
globals — meaning it is frozen when the view compiles, exactly like
`tooltip.delay` and friends.

```php
TallStackUi::customize()->globals()->flash();
// or
TallStackUi::customize()->globals()->flash(only: [Reaction::class, Tooltip::class]);
```

## Modal

### Added — `handle`, the mobile drag-to-close grabber

Below `sm` the modal already behaves as a bottom sheet — edge to edge, pinned to
the bottom, sliding up from below. `handle` completes the idiom: a small grabber
bar on top of the panel, visible only on mobile (`sm:hidden`), that follows the
finger through pointer events and closes the modal when released beyond a
quarter of the panel height. Below the threshold the panel snaps back through
its own transition; beyond it the panel keeps sliding down and the modal only
really closes once it is off-screen. Pulling upwards meets rubber band
resistance.

```blade
<x-modal handle>...</x-modal>
```

The flag also exists as a global default in the component configuration, the
inline prop always winning:

```php
'modal' => [
    Components\Modal\Component::class,
    [..., 'handle' => false],
],
```

A fully centered modal (`center` as `true`, not a breakpoint) never behaves as a
bottom sheet, so combining it with `handle` throws. Soft customization gained
the `handle.wrapper` and `handle.bar` blocks.

## Errors

### Added — `paddingless`, `shadowless` and `bordered`

`paddingless` removes the horizontal padding of the wrapper so the divider
between the header and the body runs edge to edge — the title, list and footer
recover their own inset, so only the line touches the extremity. `shadowless`
drops the shadow, `bordered` draws a border following the component color
through the new `bordered` palette of `ErrorsColors` — published color classes
can override it like any other palette.

```blade
<x-errors paddingless />
<x-errors shadowless bordered />
```

Soft customization gained the `shadowless`, `bordered` and `paddingless.*`
blocks.

### Changed — `only` accepts a comma separated list and collections

Besides a single field and an array, `only` now parses a comma separated string
and accepts a `Collection`. Surrounding spaces around each field are trimmed:

```blade
<x-errors only="name,description" />
<x-errors only="name, description" />
<x-errors :only="collect(['name', 'description'])" />
```

## Editor

### Changed — the toolbar tooltips open without delay

Every toolbar button carried the default `fast` delay, 150ms, which is the right
pause for a tooltip attached to prose but reads as lag on a row of icons the
pointer sweeps across. The anchors now declare `flash`, so the balloon opens on
the tick the pointer arrives. The fade itself is untouched.

### Changed — internal scopes renamed to the dotted convention

The editor was the only component naming its internal scopes with dashes. They
now follow the dotted convention every other internal scope uses:

| Was              | Is now               |
| ---------------- | -------------------- |
| `editor-toolbar` | `editor.toolbar`     |
| `editor-link`    | `editor.modal.link`  |
| `editor-image`   | `editor.modal.image` |

**Migration** — `TallStackUi::customize('dropdown', scope: 'editor-toolbar')`
and the modal equivalents must point at the new names.

## Form / Input

### Changed — the addon buttons now sit inset

The prefix/suffix `button` slots used to render flush against the wrapper, and
the focused ring exposed the seam: two independently rasterized rounded corners
that never quite met, and a button glued to the ring with no breathing room.
The addon container now carries a small padding (`p-1`) and the button becomes
a pill of its own (`rounded-sm` on every corner), floating inside the field —
the ring, the input and the button read as separate pieces at rest and under
focus.

**Migration** — the `input.addon.button.left` and `input.addon.button.right`
customization blocks no longer exist, since the asymmetric rounding went away
with the flush design. The padding and the rounding live in
`input.addon.button.base`.

## Form / Color

### Added — `picker`, `selectable` and `clearable` in the configuration

The three flags now exist as global defaults in the component configuration,
resolved through the usual rule — the inline prop always wins:

```php
'color' => [
    Components\Form\Color\Component::class,
    ['colors' => [], 'picker' => false, 'selectable' => false, 'clearable' => false],
],
```

`excluded-step` keeps requiring the picker, whichever side enables it.

### Fixed — the custom colors configuration key was never read

The configuration documented `custom` while the resolution read `colors`, so
the global palette silently did nothing. The key is now `colors`, matching the
prop it feeds.

**Migration** — applications that guessed `colors` keep working; anything set
under `custom` must be renamed to `colors`.

## Form / Number

### Added — `centralized`, `selectable`, `delay` and `chevron` in the configuration

The four knobs now exist as global defaults in the component configuration,
the inline prop always winning:

```php
'number' => [
    Components\Form\Number\Component::class,
    ['centralized' => false, 'selectable' => false, 'delay' => 2, 'chevron' => false],
],
```

`delay` keeps its meaning: the press-and-hold repeat interval, in `delay * 100`
milliseconds.

## Dropdown

### Added — `hover`

Opens the dropdown when the pointer enters the trigger and closes it when the
pointer leaves, with a 300ms grace period to cross the gap between the trigger
and the floating panel. The handlers are pointer events filtered to
`pointerType === 'mouse'`, so touch keeps the click behavior — on a tap the
synthetic enter would otherwise cancel the click toggle. The click toggle keeps
working alongside the hover.

```blade
<x-dropdown text="Options" hover>
    <x-dropdown.items text="Settings" />
</x-dropdown>
```

## Alert

### Added — `shadowless`

The light style carries a soft shadow in every palette entry. `shadowless`
drops it — whole shadow tokens are stripped from the resolved background, so
published palettes with a different shadow are covered too.

```blade
<x-alert title="TallStackUi" text="Primary" light shadowless />
```

## Carousel

### Added — `round` variations

`round` keeps applying the current look (`rounded-xl`) when used as a flag, and
now also accepts a Tailwind suffix: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`, `3xl`
and `full`. Anything else throws. The single `images.rounded` customization
block became the `images.rounded.*` map (`default` plus one key per suffix).

```blade
<x-carousel :images="$images" round />
<x-carousel :images="$images" round="xs" />
```

## Kbd

### Added — `borderless` and `shadowless` in the configuration

The two flags now exist as global defaults in the component configuration, the
inline prop always winning:

```php
'kbd' => [
    Components\Kbd\Component::class,
    ['borderless' => false, 'shadowless' => false],
],
```

## Calendar

### Fixed — `bordered` was left one step behind the dark ladder

The recalibration moved borders from `dark-600` to `dark-700`, and Card, Stats and
Tab followed, but the Calendar `bordered` block kept the old shade. Against a
`dark-800` panel it read as a lighter outline than the same flag draws anywhere
else. It is now `border border-gray-200 dark:border-dark-700`, like its siblings.

### Changed — the pickers are a faithful copy of the date picker

The calendar is meant to be the `<x-date>` panel without the input, and the
month/year pickers were the piece that diverged: they opened as `<x-floating>`
popovers teleported to `<body>`, with their own width, no typographic
inheritance — the labels rendered larger and lighter than the date picker's —
and a Today shortcut styled as a gray pill (`bg-dark-200` even in light mode).

They now render exactly like the date picker: in place, as an absolute overlay
covering the calendar card (`box.picker.wrapper.first`), inheriting the
`text-sm font-semibold` typography from the picker header. `monthYearOnly`
keeps its room through the new `box.picker.expanded` block, bound while a
picker is open. The Yesterday/Today/Tomorrow helpers also moved inside the
card, matching where the date picker draws them — spanning both halves in
`double` mode.

**Migration** — the `calendar.floating` internal scope no longer exists, since
no floating is rendered anymore; `customize('floating', scope:
'calendar.floating')` must go. The `floating.default`, `floating.class`,
`box.picker.button-label-wrapper` and `box.picker.navigate-wrapper` blocks were
removed, `box.picker.wrapper.second`/`third` now carry the date picker values,
and `box.picker.today` slimmed down to `cursor-pointer`, inheriting everything
else.

## Reaction

### Fixed — the arrow detached from the balloon

The arrow math was copied from the tooltip, whose balloon has no border: the
edge offset used `offsetHeight`/`offsetWidth` (border-box) while an absolutely
positioned child is measured from the padding box, so the panel's 1px border
pushed the arrow outside the balloon — most visibly when it opened upwards,
with the panel border running straight through the diamond's shoulders. The
border widths (`clientTop`/`clientLeft`) are now discounted, and the arrow
inset grew from 12 to 16 so the whole rotated square clears the `rounded-lg`
corner arc when it clamps near an edge.

---

## Swap

### Added — `<x-swap>`

A compact value cycler shaped like an input: a chevron button on each side, the
selected value in the middle. The value moves through the buttons, through a drag
over the value itself — pointer events, so mouse and touch behave identically —
or through the keyboard arrows while either button holds focus. The middle is
deliberately not focusable: Tab stops only on the buttons.

```blade
<x-swap wire:model="fruit" :options="['Apple', 'Banana', 'Cherry']" />
<x-swap label="Size" hint="Drag or use the arrows" :options="$sizes" select="label:name|value:id" />
<x-swap wire:model.live="month" block preview :options="$months" />
<x-swap wire:model="day" vertical :options="$days" />
```

Options accept flat arrays, Collections and dimensional arrays, with dimensional
keys remapped through the same `select="label:...|value:..."` string the styled
select uses. The model carries the option value, never the index. A null model
shows the first option without writing anything back until the user navigates.
Outside Livewire the component keeps a hidden input in sync through `name` and
pairs with `x-model` through `x-modelable`.

The track slides on `transform` inside an overflow viewport, 300ms ease-out by
default, degrading to an instant jump under `globals()->flash()`. During a drag
the transition is suspended so the value follows the pointer 1:1 — a long
gesture crosses several options — and the release snaps to the nearest one.
Navigation loops by default: crossing an edge animates into a clone of the
opposite end and silently teleports to the real option, so the cycle reads as
continuous. `:loop="false"` disables the cycle — the matching button disables
at either end and the drag gains rubber band resistance past them.

`preview` widens the component and splits the viewport in thirds: the previous
and next options stay visible whole at reduced opacity and fade toward the
edges through a CSS mask. `vertical` rolls the value top-to-bottom instead —
the chevrons become up/down and the drag axis follows. The two cannot be
combined, because sideways slices make no sense on a vertical roll:

```blade
<x-swap preview vertical :options="$options" />  {{-- throws --}}
```

The three flags also exist as global defaults in the component configuration,
the inline prop always winning:

```php
'swap' => [
    Components\Swap\Component::class,
    ['preview' => false, 'vertical' => false, 'loop' => true],
],
```

Every navigation dispatches a `swap` CustomEvent carrying
`{ value, label, index, direction }`, and `wire:change` compiles the same way
as the other form components. `readonly` and `disabled` both freeze the
buttons, the drag and the keyboard — `disabled` also dims the control, while
`readonly` keeps the resting look. `label`, `hint`, `tooltip`, validation
errors and `invalidate` follow the form conventions.

Soft customization ships under the `swap` key — `TallStackUi::customize('swap')`
or `customize()->swap()` — with the blocks `wrapper`, `input.*` (the shell),
`button.*`, `viewport.*` (including `mask`, the `touch.*` axis locks and the
`width.*` presets), `track.*` (including `transition`) and `item.*` (including
the preview `fade.*` pair).

---

## Button

### Added — `spinner`, the loading indicator picks a Spinner variant

The `wire:loading` indicator was one hardcoded SVG. Both buttons now render any
of the nine visual `<x-spinner>` variants in its place:

```blade
<x-button text="Save" loading="save" spinner="dots" />
<x-button.circle icon="trash" loading="delete" spinner="bars" />
```

Resolution is prop → config → default. The config key lives on `button` and
drives `button.circle` too, so one setting covers both:

```php
'button' => [
    Components\Button\Normal\Component::class,
    [
        'spinner' => null,
    ],
],
```

`null` — the shipped value — keeps the default effect: the old SVG was
byte-identical to the `gradient` variant (same path, `opacity-25` track,
`opacity-75` head), so an application that never touches the prop or the config
renders exactly what it always rendered.

The four textual variants (`shimmer`, `caret`, `terminal`, `thinking`) animate
their own text and make no sense inside a button, so they throw — as does any
unknown value:

```blade
<x-button text="Save" spinner="shimmer" />   {{-- throws --}}
```

Under the hood the buttons reuse the Spinner's own type partials, but feed them
a button-owned `spinner.*` customization block scaled to the button's icon box
instead of the Spinner's standalone scale. The two stay isolated: customizing
the Spinner does not leak into buttons, and vice versa.

The indicator markup also gained a block-level flex wrapper, which removes the
inline baseline gap that sat every spinner a couple of pixels above the visual
center of the button. The outer `wire:loading` element keeps its modifiers an
exact match of Livewire's injected hiding CSS — a display modifier chained with
`delay` (`wire:loading.inline-flex.delay`) escapes those attribute selectors
and the indicator never hides, which is why the centering lives in a child
element instead.

### Migration

**`icon.spinner-animation` is gone from both buttons.** Each variant's own
classes carry the animation now, mirroring the Spinner, so the block had
nothing left to do. The loading indicator also no longer reads `icon.sizes.*` —
its size comes from the new `spinner.*` blocks; `icon.sizes.*` still applies to
regular icons.

```php
// before
TallStackUi::customize()->button()->block('icon.spinner-animation', 'animate-pulse');

// after
TallStackUi::customize()->button()->block('spinner.gradient.base', 'inline-block animate-pulse');
```

The new blocks mirror the Spinner's structure under a `spinner.` prefix —
`spinner.delays.{0..4}`, `spinner.ring.{base,sizes.*}`,
`spinner.throbber.{base,segment,sizes.*}`, `spinner.gradient.{base,track,head,sizes.*}`,
`spinner.ping.{wrapper,echo,core,sizes.*}`, `spinner.dots.{wrapper,dot,sizes.*}`,
`spinner.pulse.{dot,sizes.*}`, `spinner.typing.{wrapper,dot,sizes.*}`,
`spinner.bars.{wrapper,bar,sizes.*}` and `spinner.wave.{wrapper,bar,sizes.*}` —
defined on `button` and `button.circle` independently, sized to each button's
icon box.

### Tests

Both `FeatureTest.php` files cover the default markup, every visual variant, the
config fallback, the invalid config value and the rejected textual and unknown
values. Browser tests assert a non-default variant is hidden at rest and becomes
visible while the action runs.

---

## Form / InputSelect

### Added — `floating`, the panel width floor as an attribute

Inside `<x-input.select>`, a styled select's panel follows its trigger through
Floating's width sync, and a `min-w-72` floor keeps a narrow trigger — a phone
code, a bare "Select an option" — from collapsing the panel into something
unreadable. The floor was one-size: 288px reads fine around a search input,
oversized next to a short list of e-mail providers. It is now an attribute on
the wrapper:

```blade
<x-input.select label="E-mail Provider" wire:model="email" floating="min-w-40">
    <x-slot:right>
        <x-select.styled :options="['@gmail.com', '@yahoo.com']" wire:model="provider" />
    </x-slot:right>
</x-input.select>
```

The value is a class string and lands in place of `min-w-72` — the styled
select's `floating.side` block — so it can carry more than one class
(`min-w-40 max-w-56`). The width sync stays on and the panel never sits below
the trigger's width, which makes the value a floor (`min-w-*`) or a cap
(`max-w-*`), not an exact width. A `<x-select.native>` in the slot opens the
browser's own list and has no panel, so the attribute is a no-op there.

The value travels from the wrapper to the select through Blade's consumable
component data — the `@aware` channel — and is read only when the select
detects side mode, so a standalone `<x-select.styled>` never inherits a
`floating` from an unrelated ancestor.

## Step

### Added — the navigation bar learned variants, like the Table paginator

`helpers` was a flag that produced one fixed pair of buttons. It now behaves
like the Table's `paginator`: a bare `helpers` renders the `default` variant, a
string picks another look, and a value containing `.` or `::` is treated as a
view path so an application can ship its own bar. Anything else throws.

```blade
<x-step selected="1" helpers>              {{-- default: bordered buttons --}}
<x-step selected="1" helpers="minimal">    {{-- borderless ghost buttons --}}
<x-step selected="1" helpers="compact">    {{-- grouped shell + position indicator --}}
<x-step selected="1" helpers="app.steps.custom">
```

- **default** — individual bordered buttons (`rounded-lg border shadow-xs`,
  hover fill, focus ring), label + chevron.
- **minimal** — the same layout with borderless text buttons.
- **compact** — a single shell anchored right with icon-only buttons and a
  `current/total` indicator drawn with tabular figures. At the edges the
  buttons disable instead of hiding, so the shell never changes width; the
  finish button/slot renders to the left of the shell.

The variant behind a bare `helpers` comes from the new
`tallstackui.components.step.helpers` config default (`default` out of the
box), so an application can switch every wizard at once. The bundled views
live in `components/step/helpers/`, one file per variant, mirroring
`table/paginators/`.

### Added — `previous` and `next` slots for fully custom buttons

Each slot replaces its built-in button entirely. The component keeps only the
visibility wrapper (previous hides on the first step, next on the last; in
`compact` slot content stays always visible) — the click behavior belongs to
the application. Two Alpine methods, `next()` and `previous()`, are exposed in
the component scope: they move `selected` and dispatch the `change` event, so
a custom button behaves exactly like the built-in one. Mutating `selected`
directly also works and skips the event. Guarding is one expression away:
`x-on:click="if (valid()) next()"`.

```blade
<x-step selected="1" helpers>
    <x-step.items step="1" title="Account">...</x-step.items>
    <x-step.items step="2" title="Review">...</x-step.items>
    <x-slot:previous>
        <x-button color="secondary" outline icon="arrow-left" x-on:click="previous()">Back</x-button>
    </x-slot:previous>
    <x-slot:next>
        <x-button icon="arrow-right" position="right" x-on:click="next()">Continue</x-button>
    </x-slot:next>
</x-step>
```

A custom `previous` slot shows without requiring `navigate-previous` — passing
the slot already states the intent.

### Changed — navigation logic moved into named Alpine methods

The two inline `x-on:click` handlers duplicated on the buttons (floating-flush
dispatch, `selected` mutation, `change` event) became the `next()` and
`previous()` methods above. To free the `previous` name, the internal `x-data`
flag that carried `navigate-previous` was renamed to `navigatePrevious` — the
step variations' click guard reads the new name.

**Migration.** The navigation buttons left soft customization: the
`button.base`, `button.icon` and `button.icon-spacing.*` blocks are gone.
Restyle the bar by picking a variant, replacing the buttons through the
`previous`/`next` slots, or pointing `helpers` at your own view.
`helpers.wrapper` survives, now only laying out the skeleton's helper row.
`previous` and `next` on `<x-step>` are slot names now — a stray bare
`previous`/`next` attribute (which previously fell through to the attribute
bag) lands on a `ComponentSlot|string|null` prop and throws; use
`navigate-previous` for the previous-button flag.

## Card, Stats, Calendar & Tab

### Added — `shadowless` and `bordered` flags for the flat look

The flat look — no shadow, a border in its place — could only be reached through the
predefined scopes (`card-shadowless`, `stats-shadowless`, `calendar-shadowless`,
`tab-shadowless`), which bundle both changes and live in the service provider. The same
result is now a pair of attributes on the component itself:

```blade
<x-card shadowless bordered>...</x-card>
<x-stats :number="100" shadowless bordered />
<x-calendar shadowless bordered />
<x-tab selected="A" shadowless bordered>...</x-tab>
```

The flags are independent: `shadowless` alone drops the shadow, `bordered` alone draws
`border border-gray-200` around the wrapper, with the dark shade of the recalibrated
ladder, while keeping the
shadow. Each one is a block of its own — `shadowless` (`shadow-none!`) and `bordered` —
so soft customization can retarget them. On Card and Stats the flags also reach the
skeleton view, keeping the placeholder shaped like the card it stands in for.

The predefined scopes keep working unchanged.

### Changed — Card's `bordered` became `accent`

`bordered` on Card never drew a border around the card: combined with `color`, it
switched the header from a filled background to a colored top border. That name now
belongs to the wrapper border above, so the header variation moved to `accent`.

```blade
{{-- was --}}
<x-card color="red" bordered header="Report">...</x-card>

{{-- is --}}
<x-card color="red" accent header="Report">...</x-card>
```

**Migration.** Replace `bordered` with `accent` on cards that combine it with `color`.
A `bordered` flag left behind stops coloring the header and draws the neutral wrapper
border instead.

### Removed — the `table-shadowless` scope had nothing left to remove

The Table wrapper lost its `shadow` when it moved to `ring-1 ring-gray-200`, so the
scope's single `remove('shadow')` matched nothing and the scope was a no-op. It is gone
from `registerPredefinedScopes()`.

**Migration.** Drop `scope="table-shadowless"` from tables — the rendering does not
change. Extending it through `extend(scope: 'table-shadowless')` now throws, since the
scope no longer exists.

## Theme

### Changed — the dark ladder dropped one step across the library

With the near-black palette in place, the dark layering was recalibrated everywhere
to keep a visible step between each layer:

- **Page background:** `dark-900`.
- **Component surfaces:** `dark-800` (was `dark-700`) — Card, Stats, Accordion,
  Floating, KeyValue, Dialog, Toast, Modal, Slide, List, Table body, the Date and
  Calendar panels, the Layout header and sidebar.
- **Borders, dividers, tracks and hover/focus states:** `dark-700` (was `dark-600`) —
  including the Toast timeout track, Kbd, ThemeSwitch rail, skeleton bars, Chart
  tooltip ring, Editor chrome, Upload tiles, the Timeline connector and the table
  paginators.
- **Input borders:** `dark-600/50` — a translucent half-step (Input, Checkbox, Radio,
  Pin, Selection cards, Clipboard rings) that reads softer than a solid line.
- **Light mode followed:** structural borders moved from `gray-300` to `gray-200`.
- **Loading scrim:** `dark-900/70`, so the overlay actually darkens a near-black page.

**Migration** — soft customization keys are unchanged; only class strings inside the
blocks moved. A customization that `replace()`s one of the old `dark-700`/`dark-600`
values in these blocks should target the new step.

### Changed — the dark palette dropped Slate for a neutral near-black scale

`--color-dark-*` was an exact copy of Slate — the same values as `--color-secondary-*`,
blue tint included — so every dark surface, border and text leaned cold. The scale is
now pure neutral (chroma `0`), declared in `oklch()` and anchored near black:

```css
/* was (Slate) */                /* now (neutral) */
--color-dark-700: #334155;      --color-dark-700: oklch(0.253 0 0); /* #242424 */
--color-dark-800: #1e293b;      --color-dark-800: oklch(0.185 0 0); /* #141414 */
--color-dark-900: #0f172a;      --color-dark-900: oklch(0.145 0 0); /* #0a0a0a */
```

The lighter shades follow Tailwind Neutral with two adjustments — `400` sits at
`#999999` and `500` at `#666666` — so muted and disabled text keep their contrast on
the darker surfaces. The dark steps are deliberately tighter than a uniform ladder:
`600` → `950` spans hover surfaces, default surfaces, lowered surfaces and the page
background with a visible step between each.

Components using `color="secondary"` are untouched on purpose: those variants follow
`--color-secondary-*` (still Slate) in both modes, and remain customizable on their own.

**Migration** — no class or token was renamed; markup needs no change. Applications
that override `--color-dark-*` in their own `@theme` keep winning and see no
difference. Applications on the stock palette render darker and neutral; a page
background chosen to match the old Slate look (`dark:bg-gray-800`,
`dark:bg-slate-900`) now sits better as `dark:bg-dark-900`.

### Fixed — components that ignored the dark palette

A handful of components carried hardcoded `gray-*`/`slate-*` classes inside `dark:`
variants, so they kept their old tint no matter what `--color-dark-*` said. Twelve
occurrences were moved to the equivalent `dark-*` shade, 1:1: the Progress bar and
Upload progress tracks (`dark:bg-dark-700`), the Number separator and the Color
slider track (`dark:border-dark-600` / `dark:bg-dark-600`), the Step chip surface
(`dark:bg-dark-800`, hover `dark:bg-dark-700`, border `dark:border-dark-600`), the
Timeline description and date texts (`dark:text-dark-300` / `dark:text-dark-400`),
and the Gallery and Carousel broken-image alt text (`dark:text-dark-300`).

The color-variant maps are untouched: `color="gray"`, `color="slate"`, `color="zinc"`,
`color="stone"` and `color="neutral"` still resolve to their own Tailwind palettes —
picking a palette by name is the point of those options.

**Migration** — soft customization keys are unchanged. Only the class strings inside
the blocks above differ; a customization that `replace()`s one of the old
`dark:*-gray-*` classes in those blocks no longer finds it and should target the
`dark-*` equivalent.

### Changed — secondary is a true accent now, and the chrome stopped borrowing it

`secondary` used to be three things at once: the light-mode neutral scale the
components' own chrome was built on (`text-secondary-600`, `border-secondary-200`…),
the palette behind `color="secondary"`, and the color pitched to applications as the
second brand color. Overriding `--color-secondary-*` therefore repainted text,
borders and dividers across the library — the long-standing complaint. On top of
that, the chrome mixed `gray-*` and `secondary-*` for the same roles depending on
the component.

Two changes untangle it:

- `--color-secondary-*` is now Tailwind **Violet** (in `oklch()`), sitting next to
  the Indigo primary as a real accent. The intended pairing: **primary carries the
  main action and active states; secondary carries supporting actions and subtle
  emphasis.** `color="secondary"` renders violet exactly the way `color="red"`
  renders red — no special-cased shades.
- Component chrome no longer references `secondary-*` at all. The ~30 base usages
  across Card, Modal, Slide, List, Dropdown, Accordion, Tab, Toggle, Tag and the
  sidebar moved to the equivalent `gray-*` shade, 1:1 (Slate → Gray, near-identical
  values). Light mode now has a single neutral system (`gray-*`), mirroring `dark-*`
  in dark mode. The question dialog icon followed along
  (`text-gray-600 dark:text-dark-500`) so it does not turn violet.

**Migration** — `color="secondary"` on any component now renders violet instead of
slate-gray; applications that relied on the gray look should switch those calls to
`color="slate"` or `color="gray"`, which are unchanged. Applications that override
`--color-secondary-*` with a brand color get exactly what they always expected —
accents change, chrome does not. Customizations that `replace()` a `*-secondary-*`
class inside a component block should target the `gray-*` equivalent now. Feature
tests asserting the old chrome strings were updated accordingly.

## Layout

### Changed — the header lost its shadow and its translucent border

```php
// was
'wrapper' => '... border-b border-gray-300/10 bg-white px-4 shadow-sm ...'
// is
'wrapper' => '... border-b border-gray-200 bg-white px-4 ...'
```

The `shadow-sm` and a border at 10% opacity were doing the same job twice, and neither
did it well: the shadow bled over the content on scroll while the border was too faint
to draw the line on its own. A solid `border-gray-200` draws it once.

**Migration.** Applications restoring the old look append `shadow-sm` and replace the
border through `layout.header` → `wrapper`.

### Changed — the `footer` slot sits with the content, and holds the bottom of the page

The slot rendered as a sibling of the padded column, so on desktop it started at x=0,
under the fixed sidebar — a footer with anything on its left had that part swallowed by
the menu. It also sat immediately under the content, floating mid-screen on short pages.

It now renders inside the same column as `<main>`, which puts it past the sidebar, and
the column becomes a full-height flex when the slot is filled, which pins the footer to
the bottom.

**Migration.** Two new blocks, both applied only when the slot is filled:
`wrapper.second.footer` (the full-height column) and `main.grow` (what pushes the footer
down). An application that wants the old flush-left footer removes them.

### Fixed — filling the `footer` slot shrank the content to its natural width

Introduced and closed inside this same branch. The `main` block carried `mx-auto
max-w-full`, where `mx-auto` had always been inert: in block layout an auto margin
does nothing without a real width cap. The full-height column that a filled `footer`
slot introduces is a flex container, and on a flex item an auto margin on the cross
axis overrides `align-items: stretch` — so `<main>` shrink-wrapped to its content
and floated centered in the page.

`w-full` joined the block, restoring the stretch. `mx-auto` stays, so an application
that swaps `max-w-full` for a real cap through customization still gets a centered
column.

### Fixed — the content was padded for a sidebar that was not there

`<x-layout>` applied `md:pl-72` from the sidebar store whether or not a `menu` slot was
given, so a layout used only for its header indented its content by 18rem. The padding
is now bound only when the slot is filled.

### Fixed — the content could slide in from under the sidebar on load

The padding that clears the sidebar is bound, so it only lands once Alpine boots, and the
transition was declared on the same class. A transition starts whenever the property and
the `transition-property` covering it arrive together, so on a boot that lands after the
first paint — a heavy page, a cold cache, a busy main thread — the content painted flush
left, under the menu, and then slid 18rem into place.

The transition is now attached two frames after the padding, so the first application is
always instant and only a collapse or an expand animates.

**Migration.** `transition-[padding] duration-300` left `wrapper.second.expanded` and
`wrapper.second.collapsed`, which now carry the padding alone, and moved to the new
`wrapper.second.transition` block. An application that changed the duration through either
of the two targets the new block instead.

### Fixed — the drawer's scroll lock leaked and fought the other overlays

Opening the mobile drawer added `overflow-hidden` to the `html` element by hand. That
blocks the page in a standards-mode document, but it is the only case it covers: it
hands the scroll over to `body` in quirks mode, it reserves nothing where the scrollbar
took layout width, so the page shifts sideways as it locks, and it knows nothing about
the lock a modal or a slide may already hold.

The drawer now goes through the same `overflow()` helper as every other overlay, under
the `side-bar` key, so the lock is refcounted against the others, the scrollbar gutter is
compensated, and a drawer torn down while open (`wire:navigate`) releases it instead of
leaving the body locked for good.

## Layout / SideBar

### Added — a floating panel for the groups of a collapsed sidebar

A group has nothing to show on a collapsed sidebar: its items live in a list that only
opens inline, and the rail has no room for it. The icon was a dead end.

Hovering — or clicking, for touch — a collapsed group now opens its items in a panel
anchored beside the icon, headed by the group name. It closes on leave, on click outside,
on Escape, and when the sidebar is expanded again. Single items keep their tooltip;
groups no longer show one, since the panel names itself.

The panel is an `<x-floating>`, so it is teleported out of the sidebar and is not clipped
by the scroll container, and it is capped at `min(24rem, 100dvh - 2rem)` with its own
scroll: a group of thirty items neither runs off the screen nor stretches the page. The
frame and the scroll are separate elements on purpose — a scrollbar is painted in the
border box, so a radius only shapes it when an ancestor clips along with it.

Four new blocks: `group.flyout.wrapper` (frame), `group.flyout.scroll` (the height cap
and the scroll), `group.flyout.header` (the sticky group name) and `group.flyout.items`.

### Added — a badge becomes a dot on the collapsed rail

A badge is the one thing on an item that carries information the icon cannot: a count of
things waiting. Collapsing the sidebar dropped it, so the compact mode was also the mode
that hid what needed attention.

It now degrades to a dot on the corner of the icon, in the color the badge was given, so
the signal survives at rail width. Two new blocks, `item.dot` and `group.dot`.

### Added — the drawer closes on Escape, and the panel follows the sidebar scrollbar

Escape closes the mobile drawer, unless a floating element opened on top of it claimed
the key first. The flyout of a group takes the `thin-scroll` and `thick-scroll` of the
sidebar instead of a scrollbar of its own, through the new `group.flyout.scrollbar.thin`
and `group.flyout.scrollbar.thick`.

### Fixed — the collapsed rail was a column of gaps and misaligned icons

Three things pushed the icons off center. The gap between icon, label and badge stayed
in the layout after the label collapsed to zero width; the badge used `scale-0`, which
hides an element without taking it out of the flow, and kept an `ml-auto` that ate the
remaining space; and a group button never had the centering the item links had.

Separators left their own hole: the text collapsed but the wrapper kept its padding, so
the rail showed gaps where the sections used to be.

The gap is now a block of its own, applied only while the sidebar is expanded; the badge
animates through a wrapper that collapses its width; and separators animate to no height.

**Migration.** The keys kept their names, but three changed shape and three are new:

| Block                                   | Was                                | Is                                      |
|-----------------------------------------|------------------------------------|-----------------------------------------|
| `item.state.base` / `group.button`      | carried `gap-x-3`                  | no gap                                  |
| `item.state.gap` / `group.button.gap`   | —                                  | new: the gap, while expanded            |
| `group.button.collapsed`                | —                                  | new: the centering, while collapsed     |
| `item.badge` / `group.badge`            | classes of the badge itself        | classes of the wrapper around the badge |
| `simple.wrapper`                        | carried `py-2`                     | no padding                              |
| `simple.wrapper.visible` / `.hidden`    | —                                  | new: the padding and the height         |
| `line[-right].wrapper.first.visible` / `.hidden` | —                         | new: the height, while collapsing       |

### Fixed — the collapsed state was a side effect, not a preference

The store forced `open` to `false` on a mobile viewport and never gave it back, so a
window crossing the breakpoint from mobile to desktop left the sidebar as a rail for
someone who had never collapsed it. Opening the mobile drawer went the other way: it
called `toggle(true)`, which writes to `localStorage`, so the drawer wiped the collapse
preference of the desktop.

`open` is now the desktop preference alone, and a new `collapsed` getter answers whether
the sidebar is drawn as a rail — `collapsible && !open && !mobile`. The drawer is expanded
by definition, so it no longer has an opinion about `open`.

### Fixed — a non-collapsible sidebar could render with no width

The desktop width was bound to `open` with no regard for `collapsible`, and `open` is
persisted globally. A sidebar without `collapsible` therefore lost its `md:w-72` — and had
the layout indent the content by the collapsed width — for anyone who had collapsed a
collapsible sidebar on another page. The width is now static unless the sidebar collapses.

### Fixed — the mobile drawer borrowed the collapsed look

Inside the drawer the brand slot rendered its `brand-collapsed` variant, and the line of
a separator was hidden, both because they were bound to `open`, which is false on mobile.
The drawer now declares itself expanded and its items read that instead of the store.

### Fixed — an item dropped the class it was given

The item link wrote its own `class` attribute and then printed the remaining attributes,
which emitted a second `class` for anything passed in — the browser keeps the first, so
`<x-side-bar.item class="uppercase" />` did nothing. The classes are merged now.

### Changed — the item states no longer overlap

An item matched by `smart` was given the normal state and the current state at once, so
it carried a hover treatment it should not have. Both states are decided by one flag now,
which also cuts the route matching from three calls to one.

### Added — the navigation says what it is

`aria-current="page"` on the active item, `aria-expanded` on a group and on both header
buttons, `aria-haspopup` on a group that opens as a flyout, `aria-label` on the toggle,
the mobile trigger and the drawer close, and a label on each `<nav>`.

## KeyValue

### Added — `compact`, a denser row rhythm

```blade
<x-key-value wire:model="metadata" compact />
```

Tightens the vertical padding of the header, the rows, the empty message and the add
button, leaving the horizontal padding, the type scale and the colors alone. A compact
row carries the same `py-2.5` as a compact `<x-table>` data cell, so a page holding both
reads as one rhythm.

Each affected block gained a `-compact` twin — `header.wrapper-compact`,
`list.wrapper-default-padding-compact`, `empty.wrapper-compact` and `button.add-compact`
— and the flag swaps the whole string instead of layering an override on top of it. An
application customizing `header.wrapper` has to customize `header.wrapper-compact` too
if it uses both modes.

`deletable` is the exception. Those rows already carry no vertical padding — the delete
button is absolutely positioned against the row and the padding was dropped to make room
for it — so there is nothing left for `compact` to take. The header, the empty message
and the add button still tighten.

### Changed — a lighter surface, and the fields stop hiding in it

The component stacked three grays: a gray body, a darker gray header and footer, and
inputs sharing the body's gray. The last one was the real problem — nothing read as
editable, because the fields had the same fill as the thing behind them.

It is now a white surface with hairline rules. The header is a caption over a bottom
border rather than a filled bar, the footer is an action rather than a gray strip, and
the inputs are transparent, so the only thing drawing a box is the component itself.

Only the fills moved. The header keeps the size and the weight it had on `3.x`, because
a flat surface already tells it apart from the rows: taking the type down as well left
it reading as a footnote beside content it is supposed to label.

**Migration.** The blocks kept their names, but four of them changed shape and two are
new. An application that customized the old palette has to revisit it:

| Block            | Was                                    | Is                                  |
|------------------|----------------------------------------|-------------------------------------|
| `wrapper`        | `bg-gray-100`                          | `bg-white`                          |
| `header.wrapper` | filled bar, carried its own text color | border and layout only              |
| `header.neutral` | —                                      | new: the header color, when uncolored |
| `button.add`     | filled strip, carried its own colors   | border and layout only              |
| `button.neutral` | —                                      | new: the button color, when uncolored |
| `list.divider`   | `divide-gray-300`                      | `divide-gray-100`                   |

### Added — `color`, an accent on the header and the add button

```blade
<x-key-value wire:model="metadata" color="green" />
```

Tints the header text and the add button, keeping the flat treatment — no filled bars,
so the accent reads without the component turning into a colored block. Accepts every
TallStackUI color plus `black`, and is backed by a `KeyValueColors` class, so
`php artisan tallstackui:setup-color` can publish and override the palette like any
other colored component.

The header and the button do not default alike. The header is a caption, so it stays
neutral until a color is asked for; the button is an action, so it carries `primary`
unasked.

### Added — `colorless`, for an add button with no accent

```blade
<x-key-value wire:model="metadata" colorless />
```

`color` has no "off" value: leaving it out is what gives the button its `primary`, so
there was no way to ask for a neutral one. `colorless` is that way, in light and dark,
and it wins over an explicit `color`.

It hands the header and the button back to `header.neutral` and `button.neutral`. A
color and a neutral never land on the same element, which is why neither has to
out-specify the other.

## Form / Tag

### Added — `options`, a floating list of tags to reuse

```blade
<x-tag wire:model="tags" :options="Tag::pluck('name')" />
```

Free typing still works; the list is an extra way in, for the case where tags are records
that get reused rather than invented each time. Clicking the field toggles the list and
typing opens it, and it narrows as the term is typed. Options already added drop out of
it, since they are visible as tags right above.

Arrow keys move through the list, Enter takes the highlighted option and Escape closes
it. With nothing highlighted, Enter falls through to the typed value, so the two ways of
adding never fight over the key. A `prefix` is ignored while matching, so typing `foo`
still finds `#foo`. Reaching `limit` closes the list and keeps it from opening again.

Values are cast to strings and de-duplicated, and a `Collection` is accepted, so
`pluck()` can be passed straight in.

### Added — `<x-slot:after>`, an action under the list

```blade
<x-tag wire:model="tags" :options="$existing">
    <x-slot:after>
        <x-button sm x-on:click="$tsui.open.modal('create-tag')">New tag</x-button>
    </x-slot:after>
</x-tag>
```

Rendered under the list and always reachable, including when nothing matches — which is
exactly when creating a new tag is what the reader wants. The slot alone is enough to
make the list open, so a field whose reusable tags are still an empty set offers the
action anyway.

Worth knowing if you already use the slot of the same name elsewhere: on
`<x-select.styled>` and `<x-autocomplete>`, `after` **replaces** the empty message and
appears only when nothing matches. Here it sits below the list at all times, because it
exists to reach an action rather than to explain an empty result — and that action stays
useful while matches are still on screen.

Two new events, `open` and `close`, fire as the list is toggled.

The list messages come from `ts-ui::messages.tag`, new in all 15 bundled languages, and
`placeholders` overrides them per instance.

## Kbd

### Changed — `borderless` no longer removes the shadow

`borderless` stripped the border **and** the shadow, which left no way to drop one
without the other. It now removes only the border, and a new `shadowless` removes only
the shadow. Passing both reproduces the old behaviour:

```blade
<x-kbd borderless />              {{-- no border, still raised --}}
<x-kbd shadowless />              {{-- bordered, flat --}}
<x-kbd borderless shadowless />   {{-- what borderless alone used to do --}}
```

**Migration.** An application passing `borderless` to hide the shadow keeps the shadow
after upgrading and has to add `shadowless`.

The `borderless` customization block lost its `shadow-none`, which now lives in a new
`shadowless` block:

| 3.x                                       | 4.x                                       |
|-------------------------------------------|-------------------------------------------|
| `borderless` → `border-transparent! shadow-none` | `borderless` → `border-transparent!` |
| —                                         | `shadowless` → `shadow-none!`             |

## Form / Input

### Changed — the slot paddings became `!important`

`input.paddings.prefix` and `input.paddings.suffix` zero the padding on the side the
slot occupies, since the slot supplies that spacing itself. Those zeros are now
`pl-0!` / `pr-0!` instead of `pl-0` / `pr-0`.

The non-important form only worked by accident. Tailwind emits `.pl-0` before `.pl-3`,
so on a component carrying **both** slots the two rules collided and the later one won:
the input kept `pl-3 pr-3` and the zeros did nothing. Only Color and Currency pass both
slots — every other component in the library passes a suffix alone, where there was
nothing to collide with and nothing changed.

Currency is therefore 12px tighter on each side than it was on `3.x`; the space between
the symbol and the value is now the `ml-2 mr-1` of the slot alone.

## Form / Color

### Fixed — the value sat on top of the selected swatch

The input carried `-ml-3` to cancel the margins of the prefix slot, which is where the
swatch lives. That offset only balanced out while the input still had `pl-3` — the
padding the change above actually removed. With the padding gone, the negative margin
had nothing left to cancel and pulled the value 12px too far left:

```blade
{{-- the value overlapped the swatch, and the caret touched the border when empty --}}
<x-color wire:model="color" />
```

`-ml-3` is gone. The prefix slot positions the value on its own now, which leaves it
12px from the edge while no color is selected — where a plain input puts its text — and
4px past the swatch once one is.

## Runtime

### Fixed — a nested `wire:model` read as null on the server

```php
if (is_null($property) || ! property_exists($this->livewire, $property)) {
    return null;
}

return data_get($this->livewire, $property);
```

`property_exists()` cannot resolve `"form.files"`; the `data_get()` on the next line
can. The guard rejected exactly what it was there to protect, so every component asking
the runtime for its value got `null` whenever the binding was nested — which is what a
Livewire Form object and any nested array look like.

```blade
{{-- threw: The [value] must be an array --}}
<x-key-value wire:model="form.metadata" />

{{-- uploaded, then listed nothing: no thumbnail, no name, no delete, no per-file error --}}
<x-upload wire:model="form.files" multiple delete />
```

Written as `wire:model="files"` both worked, which is why it went unnoticed: no test in
the suite used a dotted binding.

Only the head of the path is a property, so only the head is checked. `Number`, `Rating`,
`UploadAsync`, `Calendar`, `Date` and `Time` were degrading quietly through the same
path.

---

## Form components outside Livewire

### Added — coverage for the plain-form path, and the fixes it surfaced

The library started as UI for Livewire components. Support for plain Blade pages posting
to a controller arrived later, component by component, on request — and nothing tested
it. Every browser test drove a Livewire component, so the `name`/hidden-input path was
exercised by no one.

Nine components now carry a `NativeBrowserTest`, each rendering a real `<form>` on a
plain Blade page with no Livewire component anywhere, submitting it, and asserting what
the controller received: Currency, Date, Time, Color, Pin, Tag, Select Styled,
Autocomplete and Calendar.

Writing them turned up three things.

**Calendar never filled its hidden input.** It received `property` and stored it, but
nothing ever read it back — no `getElementsByName` anywhere in the component. The hidden
input was rendered and left empty, so `<x-calendar name="scheduled_at" />` submitted
nothing at all. The `model` watcher even had `if (!this.livewire) return;` at the top,
skipping the one case that needed it. It now mirrors the model into the input, the same
way Date already did.

**Tag submitted the form on the first tag.** `x-on:keydown="add($event)"` let Enter
through, so inside a `<form>` the key that adds a tag also submitted the page. Adding a
second tag was impossible. `add()` now calls `preventDefault()` when it handles the key.

**Autocomplete had never been adapted.** It rendered no hidden input and dropped `name`
entirely, so the value reached the server under no circumstances. It now follows the same
contract as its siblings:

```blade
<form method="POST" action="/subscriptions">
    @csrf
    {{-- request('city') is the value of the picked item --}}
    <x-autocomplete name="city" :items="$cities" clearable />
</form>
```

### Fixed — packaging and analysis globs missed non-canonical test names

`.gitattributes`, `composer.json` and `phpstan.neon` all excluded test files by exact
name, `src/**/BrowserTest.php`, which requires a `/` right before it. Four files named
otherwise slipped through into `git archive` and into the optimised classmap, and
`phpstan.neon` had grown two hand-written entries for individual offenders — one of them
for a file that no longer exists.

All three now match on `*BrowserTest.php` and `*FeatureTest.php`, which covers the
existing strays and the `NativeBrowserTest` files added here.

---

## Form / Checkbox, Radio & Toggle

### Fixed — every option of a group rendered with the same id

`BindProperty::id()` falls back to the bound property when no `id` is given, and every
option of a group carries the same property:

```blade
<x-radio wire:model="plan" label="Basic" value="basic" />
<x-radio wire:model="plan" label="Pro"   value="pro" />
<x-radio wire:model="plan" label="Team"  value="team" />
```

That produced three `<input id="plan">` and three `<label for="plan">`. A label with
`for` wins over the input nested inside it, and `for` resolves to the first element
carrying the id, so clicking "Pro" or "Team" selected "Basic". Only hitting the dot
itself worked.

The value is what tells the options apart, so it joins the id: `plan-basic`, `plan-pro`,
`plan-team`. An explicit `id` is still used as given, and an option with no value keeps
the property alone.

### Fixed — the validation message repeated once per option

Each option is its own wrapper resolving the same property, so a failing `plan` printed
"The plan field is required." three times, stacked. The `.group` components never had
this, since they centralise the message on the `<fieldset>`.

The first wrapper to render a property now claims the message for that request and the
rest stay quiet. `invalidate` still suppresses it everywhere, as before.

### Added — `<x-slot:label left>` places the label before the input

The runtime already computed the position from the slot, and it was dead code. Laravel
applies the component's prop snapshot after the runtime data — `View::with()` is an
`array_merge` — so the `position` prop, declared `'right'` on all three, always won.

The slot cannot be read from the component that owns it either: its body is captured
after the props are snapshotted, so `$this->label` is still `null` in `setup()`.

`Wrapper\Radio` receives the label as a prop rather than a slot, so its attributes are
readable there. Typing it `string|ComponentSlot|null` stops them from being coerced
away, and both keywords are resolved next to the markup that renders the label:

```blade
<x-checkbox wire:model="agree">
    <x-slot:label left>I agree to the <a href="/terms">terms</a></x-slot:label>
</x-checkbox>
```

`start`, which aligns a multi-line label to the top, already worked and moved along with
it. The `position` prop is unchanged.

---

## Form / Currency

### Fixed — a native form received the formatted value

`name` is not a constructor parameter, so it survived
`whereDoesntStartWith('wire:model')` and was re-emitted on the visible input. The DOM
ended up with two inputs of the same name — the hidden one carrying the raw value, then
the visible one carrying `1.234,56` — and PHP keeps the last.

Every sibling already stripped it: `date` and `color` through `except(['name', 'value'])`,
`time` through `except('name')`, `tag` through `except(['value', 'name'])`. Currency was
the only one that did not.

```blade
{{-- request('price') was "1.234,56", numeric validation broke on it --}}
<x-currency name="price" symbol currency />
```

Alpine's `sync()` is unaffected: it writes through `getElementsByName(...)[0]`, which
was already the hidden input.

---

## Form / Time

### Fixed — the current-time helper wrote an out-of-range hour

`current()` read the hour off a 24-hour clock and assigned it as is, setting the
interval separately. On `format="12"` that produced `"13:45 PM"` at a quarter to two,
and `"00:30 AM"` at half past midnight. The slider runs from 1 to 12, so the value was
outside its own range, and `TimeRuntime::validate()` only checks that `AM|PM` is present.

The reading is folded into the 12-hour range when the format asks for it, and the
`x-on:current` event carries the same converted hour.

### Added — the numbers scroll and drag

Adjusting the time meant working the two sliders. They still work, but the panel now
answers the two gestures people try first: the wheel and the finger.

Scrolling over either slider — or over the hour and minute numbers themselves — moves
the value one step per wheel tick, up to increase and down to decrease. A trackpad is
not a wheel: it emits a stream of small pixel deltas instead of discrete ticks, and
stepping once per event would fly through the minutes on a light two-finger swipe.
Pixel-mode deltas are accumulated and step once per hundred pixels, which lands at the
same pace as one tick of a discrete wheel.

Pressing the numbers and dragging up or down does the same, one step every ten pixels,
which is the gesture that works on a phone. The number lights up while held, the same
highlight the sliders already gave on hover. Both paths drive the slider itself through
`stepUp()` and `stepDown()`, so `step-hour`, `step-minute` and the min/max bounds are
respected exactly as if the slider had been moved, and `x-on:hour` and `x-on:minute`
fire as usual.

The numbers show a `cursor-ns-resize` cursor and carry `touch-none`, so dragging them
on a phone moves the time instead of the page.

### Fixed — the AM and PM buttons kept the arrow cursor

Both act on click but nothing signalled it. They carry `cursor-pointer` now.

### Fixed — a leftover `alert(1)` on the hour slider

The hour slider carried a second `x-on:change="alert(1)"` attribute. HTML drops a
duplicated attribute, so it never fired, but it was debug residue and is gone.

---

## Signature

### Fixed — the canvas was erased by resizes that never changed its width

`window.addEventListener('resize', this.size.bind(this))` forwards the event object as
the first argument, and `size(clear = false)` takes a flag there. Every resize therefore
ran `size(Event)`, which is truthy, and called `clear()`.

Clearing on resize is the intended behaviour — assigning `width` or `height` wipes a
canvas by specification, and a signature captured at one width is no longer the geometry
the person drew once the width changes. What was wrong is that it happened
unconditionally: scrolling on a phone with a collapsing address bar, or any resize that
left the canvas exactly as wide as it already was, wiped a finished signature and set the
model to `null`, so a save right after went out empty.

The width is compared before anything is touched, so a resize that does not change it is
now a no-op.

The canvas is `w-full`, and the window is not the only thing that changes its width. A
collapsing sidebar, an opening slide or any reflow of the container resizes it without
firing a resize event, which left the bitmap stretched by CSS. A `ResizeObserver` on the
container replaced the listener and covers all of them. It also delivers the first
measurement on its own, which is what used to be a `$nextTick`.

The component also had no `destroy()`, leaving the listener — and the canvas and undo
stack behind it — alive across morphs and `wire:navigate`. It disconnects the observer
now.

### Added — `persistent`, for a signature that must survive the reflow

Clearing is the default, but a long form that reflows while it is being filled — a slide
opening, a sidebar collapsing, a phone rotating — has no business throwing away a
signature the person already drew.

```blade
<x-signature wire:model="signature" persistent />
```

The name is the one Modal, Slide and Toast already use for the same idea: the component
stays where it is instead of dismissing itself.

Carrying a canvas across a resize used to mean copying it to an offscreen canvas and
painting it back scaled, which resamples a bitmap: the drawing returns blurred, and every
further resize resamples the previous resample.

The strokes are stored as points instead, with the horizontal axis kept as a fraction of
the canvas width, and redrawn at the new width. Nothing is resampled, so the signature
stays as sharp as it was drawn however many times the container changes. Only the width
reflows — `height` is fixed — so the drawing is stretched horizontally in proportion to
the new width, which is the trade this attribute accepts.

Undo and redo hold strokes rather than `ImageData`, which drops a full RGBA bitmap per
state and lets both survive the resize with the drawing. Pixel stacks could not:
`putImageData` would have painted them back unscaled, so they had to be discarded.

An empty stroke list is what tells a real signature from a blank canvas, so a resize
before anything is drawn leaves the model `null` rather than storing a blank data URL —
with or without the attribute.

---

## Banner

### Fixed — an empty text array took the page down

```php
$this->text = $this->rotate !== false
    ? implode($this->separator, $this->text)
    : $this->text[array_rand($this->text)];
```

`array_rand()` throws `ValueError: Argument #1 ($array) must not be empty`. Arrays and
Collections are documented input, so `:text="$notices"` with a query that returned
nothing was enough to reach it. The `rotate` branch was already safe, since `implode()`
accepts an empty array.

An empty array now renders no text instead of throwing.

---

## Timeline

### Fixed — items in the slot ignored the container

`Timeline\Items` declared `horizontal`, `alternate`, `compact`, `color` and `style` with
defaults of its own. `@aware` reads the child's own component data first, so it never
reached the parent, and the documentation told people to repeat every prop on each item.

`<x-timeline horizontal>` with slot children produced a `flex-row` wrapper whose items
still drew the single vertical line, and `<x-timeline color="red">` rendered every
marker `primary`.

The three layout flags left the constructor, which is all `@aware` needed. `color` and
`style` could not: `CompileColors` reads them off the component before the view runs.
They default to `null` and are inherited in `setup()`, which runs earlier, through the
same `getConsumableComponentData()` that backs `@aware`.

Passing `:items="[...]"` was never affected, and an item can still override its own
`color` and `style`.

---

## Layout / SideBar

### Fixed — `smart` took down error pages and unnamed routes

```php
$route = Route::getCurrentRoute();

return $this->route === route($route->getName(), ...);
```

Two ways to reach a fatal. An error view has no current route at all, so `getName()` was
called on `null` — turning a 404 into a 500, on the one page where a second error hurts
most. And a route declared without `->name()` returns `null` from `getName()`, which
makes `route(null)` throw `RouteNotFoundException`, so the whole page became
`ViewException: Route [] not defined.`

Both are the same guard: an item cannot match where there is nothing to compare against,
so it reports no match instead of throwing.

---

## Loading

### Fixed — the body overflow was never locked

```js
Livewire.hook('commit.prepare', ...)
```

Livewire 4 has no such hook. `Livewire.hook` is a bare `listeners[name].push(callback)`
with no name validation, so it failed silently. Only the `morph.updated` half ran, which
is the one that *unlocks*, and the `'overflow' => false` option documented as avoiding
the hidden overflow described a lock that never happened.

`commit` is the hook that fires while the request is being assembled. Its payload also
carries `fail`, used to release the lock when a request fails or is cancelled — neither
of which reaches the morph, so the lock would otherwise outlive the spinner.

---

## Configuration

### Fixed — a published config could not shorten a list

`array_replace_recursive` merges numerically indexed arrays index by index, so a
published list could only grow or be replaced entry by entry:

| Key                     | Default            | Published        | Result                 |
|-------------------------|--------------------|------------------|------------------------|
| `table.quantity`        | `[10, 25, 50, 100]`| `[15, 30]`       | `[15, 30, 50, 100]`    |
| `editor.toolbar`        | 20 buttons         | `['bold']`       | 20, first one replaced |
| `editor.allowed_tags`   | 24 tags            | a shorter list   | never narrows          |
| `debug.environments`    | 3                  | `['local']`      | still 3                |

The `allowed_tags` and `mimes` rows are the ones that matter: the config presents them
as defence in depth, and the whitelist could not be tightened.

`__ts_merge_configuration()` replaces lists of scalars wholesale and keeps merging
everything else. Component entries are `[Class::class, [...options]]` tuples, which are
not scalar lists, so they still merge and a published file written against an older
release keeps options added since.

---

## Tooling

### Fixed — the customization check counted components it never read

`find-unused-customization-blocks.php` resolved the component's view with a regex
requiring a string literal directly inside `view(`. Seven components build the name with
a ternary — Chart, Card, Stats, Table, `List\Items`, `List\Main` and `Step\Main` — so
the match failed and they were skipped.

The skip was a `continue` placed *after* the counters, so the summary read
`Scanned 86 components, 1672 customization keys. All customization blocks are in use!`
while roughly 252 of those keys had never been looked at. The check passed and reported
a guarantee it did not have.

It now collects every view literal the `blade()` body can return, so both branches of
the ternary are scanned, and a component whose view cannot be resolved fails the run
instead of being counted. The `hasSpread` flag, detected and then ignored, is reported.

---

## Floating

### Fixed — Escape closed the popup and the overlay behind it

Both listeners sit on `window`, so neither could stop the other: pressing Escape with a
select open inside a modal closed the select **and** the modal, taking the form in
progress with it.

The modal and the slide guard their listener with `top_ui`, but a floating is
deliberately kept out of `window.__tsui_elements` — the registry that answers that
question — so the modal believed it was the topmost element. That exclusion is what
keeps the scroll lock from deadlocking and was not worth undoing.

An open panel now claims the press, and the overlays ask before acting:

```blade
{{-- floating --}}
x-on:keydown.escape.window="show && window.tallstackui_escapeClaim($event) && (show = false)"

{{-- modal and slide --}}
x-on:keydown.escape.window="top_ui && !window.tallstackui_escapeClaimed($event) && (show = false)"
```

`escapeClaimed` answers from two sources, and both are needed because the listeners run
in registration order, which nothing controls. An overlay running before the panel sees
it still listed in `window.__tsui_floating_open`; one running after sees the mark the
panel left on the event itself.

The first Escape now closes the popup and the second closes the overlay. Reaches every
component built on `<x-floating>`.

### Fixed — `auto` positions were validated and then ignored

`InvalidSelectedPositionException` accepts fifteen values, including `auto`,
`auto-start` and `auto-end`. Alpine's anchor plugin knows twelve, and `auto*` is not
among them: the placement came out `undefined` and Floating UI fell back to `bottom`,
so `position="auto-end"` rendered centered below the anchor with no warning anywhere.

The shared allow-list is left alone, because Tooltip and Reaction resolve positions
through `js/helpers/placement.js`, which supports all fifteen for real — `<x-reaction>`
even defaults to `auto`. Removing the values there would have broken both.

`Floating\Component::anchor()` resolves them instead, mapping `auto*` onto `bottom*`.
That keeps the side Floating UI already fell back to while recovering the `-start` /
`-end` alignment that used to be silently dropped.

### Fixed — closing a popup dropped the focus on the body

The panel is teleported to the end of `<body>`, and keyboard navigation moves the
focus into it: the styled select's arrow keys call `options[next].focus()`, so the
focus sits on an option that lives nowhere near the form. Closing the panel hid that
option, the browser handed `activeElement` back to `<body>`, and the next Tab
restarted from the first focusable element on the page.

Reported in [#1286](https://github.com/tallstackui/tallstackui/issues/1286) as tabbing
through a form and landing back at the top after touching a field.

The panel now returns the focus to its anchor when it closes holding it. Since the
anchor is not always focusable (the select passes its toggle button, most components
pass the wrapper `div`), the first focusable descendant is used when the anchor itself
cannot take the focus.

Whether the focus was inside is tracked as it happens rather than read on close, since
`x-show` may have hidden the panel by the time the watcher runs:

```js
el.addEventListener('focusin', () => (held = true));

el.addEventListener('focusout', (event) => {
    if (event.relatedTarget && !el.contains(event.relatedTarget)) {
        held = false;
    }
});
```

A `relatedTarget` outside the panel means the user moved on by themselves, and their
focus is left alone. A null one means the panel took the focus down with it.

Nothing is restored when the anchor is no longer visible, which is the case where the
popup closed precisely because the anchor left layout.

The fix sits in the `show` watcher, the single funnel every close path already goes
through: the component's own choice, the anchor-visibility guard, the modal and slide
close hooks, and the flush event. Every component built on `<x-floating>` gets it —
Dropdown and its Submenu, Select Styled, Autocomplete, Color, Date, Password, Time,
Upload, Calendar and the List Items menu.

---

## Scroll lock

### Fixed — the scrollbar compensation was a hardcoded 15px

Every overlay that locks the page — Modal, Slide, Loading, and Floating when
`floating_scroll_lock` is on — puts `overflow: hidden` on the `<body>` and gives the
scrollbar's width back as `padding-right`, so the content does not jump sideways when
the scrollbar disappears.

The width was the constant `15px`, applied whenever the page happened to be
scrollable:

```js
const scroll = document.documentElement.scrollHeight > document.documentElement.clientHeight;

if (scroll) {
    element.style.paddingRight = '15px';
}
```

That asks whether the page scrolls. The question that matters is whether the scrollbar
takes width away from the layout, and the two part company on **overlay scrollbars** —
the macOS default, and what any Mac on a trackpad is using. Those float above the
content and take nothing, so removing them frees nothing, and 15px of padding pushed
the whole page off the right edge to make room for a scrollbar that had never been
there. On a classic scrollbar the constant happened to be close enough to be invisible,
which is why it survived.

It is measured now, and read before the lock, since afterwards the scrollbar is already
gone and the measurement would always come back zero:

```js
const gutter = window.innerWidth - document.documentElement.clientWidth;
```

Zero on overlay scrollbars, so nothing is written at all. The real width elsewhere.

This is the same expression Vuetify computes for `--v-scrollbar-offset`, down to the
`padding-inline-end` it feeds.

### Added — `--tsui-scrollbar-offset` and `.tsui-scrollbar-bleed`

Reserving the scrollbar's width keeps the content still, but it also leaves a strip of
canvas along the right edge, and anything full bleed stops short of the viewport. A
sticky header with its own background suddenly ends 15px early, against a strip in
whatever colour the canvas happens to be. Whether that reads as a seam is pure luck:
dark app on a dark canvas hides it, a white header on a grey canvas does not.

The lock now publishes what it measured, so an element can grow back over the strip:

```css
:root { --tsui-scrollbar-offset: 15px; } /* only while the body is locked */
```

And `css/plugins/scrollbar-bleed.css` ships the utility that uses it:

```css
.tsui-scrollbar-bleed {
    margin-right: calc(-1 * var(--tsui-scrollbar-offset, 0px));
    border-right: var(--tsui-scrollbar-offset, 0px) solid transparent;
}
```

The negative margin grows the box out over the strip so the background reaches the
edge. The transparent border pushes the content back by the same amount, and since
backgrounds paint under the border, the fill stays while nothing inside moves. Both
terms collapse to zero while the body is free, so the class is inert the rest of the
time.

`<x-layout.header>` and `<x-banner>` carry it. They are the only two components that
are in flow and full bleed with a background of their own — everything else that
touches the edge is `position: fixed` and never sees the body's padding. Applications
with a header of their own can add the class to it.

Vuetify solves the same thing the same way, one component at a time:

```css
.v-overlay-scroll-blocked .v-navigation-drawer--right.v-navigation-drawer--active {
    margin-right: var(--v-scrollbar-offset);
}
```

### Migration

None. The variable and the utility are additive, and the measurement only ever writes
less padding than the constant did.

---

## Button

### Added — `round` accepts a size

`round` was a switch: on it gave `rounded-full`, off it gave `rounded-md`, and there was
nothing in between. Anything else meant reaching for soft customization or writing the
class by hand.

It now also takes a size:

```blade
<x-button round>Pill</x-button>
<x-button round="lg">Large radius</x-button>
```

| Value          | Class          |
|----------------|----------------|
| (none)         | `rounded-md`   |
| `round`        | `rounded-full` |
| `round="xs"`   | `rounded-xs`   |
| `round="sm"`   | `rounded-sm`   |
| `round="md"`   | `rounded-md`   |
| `round="lg"`   | `rounded-lg`   |
| `round="xl"`   | `rounded-xl`   |
| `round="full"` | `rounded-full` |

The default and the pill are what they always were, so nothing renders differently until
a size is passed.

`square` is unchanged and still wins: it drops the radius outright, which is what makes
the global `TallStackUi::globals()->square()` work while a button asks for a radius of
its own.

This is the shape Badge and Environment already use, down to the derivation:

```php
$this->rounded = $this->round === true ? 'full' : (is_string($this->round) ? $this->round : 'md');
```

Card is the odd one out — it has no `square`, so it resolves the radius with a single
unconditional lookup instead.

`round="full"` is accepted here, which is the one place Button diverges from Badge and
Environment: they define a `full` block but reject the string, so only the boolean
reaches it.

Anything outside the six sizes throws at render time. Button had no `validate()` at all
until now.

### Migration

**`wrapper.border.radius.rounded` and `wrapper.border.radius.circle` are gone.** They
were a two-entry map for a two-state prop. The radius blocks are now a size map, and
they moved out of `wrapper` to sit where Badge and Environment keep theirs:

```php
// before
TallStackUi::customize()->button()->block('wrapper.border.radius.rounded', 'rounded-2xl');
TallStackUi::customize()->button()->block('wrapper.border.radius.circle', '...');

// after
TallStackUi::customize()->button()->block('border.radius.md', 'rounded-2xl');
TallStackUi::customize()->button()->block('border.radius.full', '...');
```

`border.radius.md` is the one the default button reads, since an omitted `round`
resolves to `md`.

### Tests

`FeatureTest.php` covers the default, the boolean, one case per size, `square` beating
`round`, and the rejected values — `2xl` and `circle` among them, the second being the
name of the block that no longer exists.

---

## Modal

### Changed — the mobile open reads as a sheet instead of a nudge

Below `sm` the modal is already a bottom sheet: anchored to the bottom edge, flush,
with only its top corners rounded. It opened like a dialog anyway — a 16px lift plus a
fade, over 300ms of `ease-out`:

```
enter-start = opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95
```

Sixteen pixels is a nudge, not a sheet, and the fade is what gives it away: a native
sheet never fades, it travels opaque from off screen. It now does the same:

```
enter = ease-emphasized-decelerate duration-400
enter-start = translate-y-full motion-reduce:translate-y-0 motion-reduce:opacity-0 sm:translate-y-0 sm:opacity-0 sm:scale-95
leave = ease-emphasized-accelerate duration-200
```

There is no opacity class below `sm`, so only the transform animates and the sheet
stays solid the whole way. The backdrop still fades, which is what a scrim does.

From `sm` up nothing changed — it is a floating dialog again, and it fades while it
scales, exactly as before.

`center` as a boolean opts out entirely: it is a centered dialog at every width, and
sliding a centered box up from the bottom edge would be wrong. A breakpoint does not
opt out, since the phone is still a sheet.

### Added — two Material 3 easing tokens

```css
--ease-emphasized-decelerate: cubic-bezier(0.05, 0.7, 0.1, 1);
--ease-emphasized-accelerate: cubic-bezier(0.3, 0, 0.8, 0.15);
```

Paired asymmetrically — 400ms in, 200ms out — which is most of what separates a native
open from a symmetric web fade. Available to every component as `ease-emphasized-*`,
not only to the modal.

### Added — `center` accepts a breakpoint

`center` was a boolean: the modal was either centered on every viewport or on none of
them. The layout most applications actually want sits between the two — a bottom sheet
on the phone, a centered dialog on the desktop — and there was no way to ask for it.

It now also takes a Tailwind breakpoint:

```blade
<x-modal center="md">
    Bottom sheet below 768px, centered from there upwards.
</x-modal>
```

Accepted values are `sm`, `md`, `lg`, `xl` and `2xl`, alongside the booleans that
already worked.

**A breakpoint means "not centered below it".** `center="md"` is not `items-end
md:items-center`: below `md` the modal behaves exactly like `<x-modal>` with no
`center` at all, which includes the `sm:items-start` step. Dropping that step would
have turned the 640px–768px range into a bottom sheet, and today it is not one.

| value          | classes                                     |
|----------------|---------------------------------------------|
| `false`        | `items-end sm:items-start`                  |
| `true`         | `items-center`                              |
| `"sm"`         | `items-end sm:items-center`                 |
| `"md"`         | `items-end sm:items-start md:items-center`  |
| `"lg"`         | `items-end sm:items-start lg:items-center`  |
| `"xl"`         | `items-end sm:items-start xl:items-center`  |
| `"2xl"`        | `items-end sm:items-start 2xl:items-center` |

The boolean also forces `p-4` on the flex container and `rounded-xl` on the card, so a
centered modal floats free of the screen edges on a phone too. A breakpoint does not:
below it the modal is a bottom sheet and has to stay flush, and above it `wrapper.third`
and `wrapper.fourth` already carry `sm:p-4` and `sm:rounded-xl` on their own. Those two
blocks are therefore untouched, and no new ones were needed for them.

The resolution lives in `CompileConfigurations::modal()`, next to the mapping that turns
`size` into a width class, and reaches the view as a `position` key. The template picks
the block by name and gained no `@php` of its own.

The same values work as a global default:

```php
'modal' => [
    'center' => 'md',
],
```

Anything outside the five breakpoints throws at render time. That includes
`center="desktop"`, which reads well but names no breakpoint, and `center="true"` —
Blade hands a quoted attribute over as a string, and without the check it would have
resolved to a `positions.center-true` block that does not exist.

Arbitrary values such as `center="min-[900px]"` are out by construction: Tailwind only
generates a class it can see written out in the source, and a class assembled at runtime
is invisible to it.

### Added — five `positions.center-*` blocks

| Block                  | Purpose                                   |
|------------------------|-------------------------------------------|
| `positions.center-sm`  | alignment when centering from `sm` upwards  |
| `positions.center-md`  | alignment when centering from `md` upwards  |
| `positions.center-lg`  | alignment when centering from `lg` upwards  |
| `positions.center-xl`  | alignment when centering from `xl` upwards  |
| `positions.center-2xl` | alignment when centering from `2xl` upwards |

`positions.top` and `positions.center` keep their names and their classes, so a
customization written against either still applies.

### Migration

None. `<x-modal>` and `<x-modal center>` render what they rendered before.

### Tests

`FeatureTest.php` covers the default, the boolean, one case per breakpoint, the config
default, and the rejected values — `desktop` and `true` among them.

`BrowserTest.php` resizes across all three bands rather than only across the breakpoint
being tested: 1400px asserts `center`, 720px asserts `flex-start` and 400px asserts
`flex-end`. The middle band is the one that proves the `sm:items-start` step survived;
without it the test would pass against an `items-end md:items-center` that silently turns
tablets into bottom sheets.

---

## Clipboard

### Changed — clipboard.js is gone

`<x-clipboard />` and the exported `copy()` helper were built on clipboard.js, a library
from the era before browsers had a clipboard API of their own. Its entire copy path was
one call:

```js
document.execCommand('copy')
```

`execCommand` copies the current selection rather than an argument, which is why the
library created a throwaway `<textarea>` on every copy, filled it, selected it and tore
it down again. The package carried ~9.6 KB to orchestrate that.

The write now goes through `navigator.clipboard.writeText()`, with the same
`execCommand` underneath as a fallback. Both live in one place:

```js
// src/Components/Clipboard/write.js
write(text) // → Promise<Boolean>
```

**The fallback is not a courtesy, it is the behaviour the package already had.**
`navigator.clipboard` is only exposed in a secure context — HTTPS, `localhost` or
`127.0.0.1` — and is plain `undefined` everywhere else. An application served over
`http://myapp.test` by Valet or Herd, or reached at `http://192.168.0.10:8000` from a
phone on the same network, is not a secure context. Going straight to the native API
would have broken copying in the setups developers use day to day, and broken it
silently: a click that does nothing, with no error to read.

So the native path is tried first and `execCommand` catches everything else — including
the rejections that happen *inside* a secure context, when the document is not focused,
when an iframe carries no `clipboard-write` permission, or when the user gesture has
expired.

**Both paths require a user gesture.** A browser only allows a clipboard write while a
real interaction is being handled, which is why `write()` is called straight from the
click with nothing awaited before it — Safari rejects the write once the transient
activation is gone. Since `copy()` is exported publicly, this is worth stating plainly:
calling it from a timer or after an API response fails in every browser, and always did.

### Migration

**`clipboard` left `package.json`.** An application importing clipboard.js directly has
to install it on its own.

The component's public surface is unchanged: `copy()` still resolves to a boolean, still
dispatches `ts-ui:copy` on `window`, and `<x-clipboard />` still emits its local `copy`
event.

### Tests

`BrowserTest.php` gained `can_copy_when_the_clipboard_api_is_unavailable`, which deletes
`navigator.clipboard` before clicking.

The five tests that already existed copy for real and paste with <kbd>Ctrl</kbd>+<kbd>V</kbd>,
so they cover the native path — Dusk serves on `127.0.0.1`, which is a secure context.
Without the new one the fallback would never run under test, and that is the branch
carrying every application on plain HTTP.

---

## Icon

### Fixed — `computer-desktop` was missing from the Heroicons guide

The segmented ThemeSwitch asks for `computer-desktop` for its system segment, but
the key was absent from `IconGuide::heroicons()` — every render raised an
"Undefined array key" warning and the segment came out without an icon. The key is
registered now.

### Added — size and color shorthands

Sizing an icon meant writing the utilities by hand every single time, which is why
`h-5 w-5` is scattered across the package and across every application using it. The
component now carries its own scale:

```blade
<x-icon name="users" xs red />
<x-icon name="users" 2xl secondary />
```

Eleven steps, one bare attribute each:

| Shorthand | Classes     | Size |
|-----------|-------------|------|
| `xs`      | `h-3 w-3`   | 12px |
| `sm`      | `h-4 w-4`   | 16px |
| `md`      | `h-5 w-5`   | 20px |
| `lg`      | `h-6 w-6`   | 24px |
| `xl`      | `h-7 w-7`   | 28px |
| `2xl`     | `h-8 w-8`   | 32px |
| `3xl`     | `h-10 w-10` | 40px |
| `4xl`     | `h-12 w-12` | 48px |
| `5xl`     | `h-14 w-14` | 56px |
| `6xl`     | `h-16 w-16` | 64px |
| `7xl`     | `h-20 w-20` | 80px |

Two sizes or two colors at once throws, on the same reasoning as Spinner — a mistyped
shorthand is a different icon and silence would hide it:

```blade
<x-icon name="users" xs 2xl />    {{-- throws --}}
<x-icon name="users" red blue />  {{-- throws --}}
```

**The shorthands are read from the attribute bag, not from constructor properties**,
and that is not a stylistic choice. Spinner and Avatar can declare `xs`, `sm`, `md` and
`lg` as booleans because those are valid PHP variable names; `2xl` is not, and neither
is any other step past `xl`. Declaring half the scale as properties and half as
attributes would be worse than reading all of it from one place. The same applies to the
29 color keys.

Because they are attributes, they are also **removed from the bag before the icon
renders**. `x-dynamic-component` builds a template out of the attribute names it
receives, so a surviving `2xl` compiles to the invalid variable `$2xl` and the render
dies with a PHP syntax error. Anything touching that path has to keep the removal.

#### Color through `currentColor`

Colors reuse Spinner's approach: one `text-*` class on the `<svg>`, painting the icon
through `currentColor`. The palette is the same 29 keys, and
`php artisan tallstackui:setup-color` publishes an `IconColors` class with a single
`textColors()` map.

`error` still wins over any color, since a validation state is not a style choice:

```blade
<x-icon name="exclamation-circle" error blue />  {{-- stays red --}}
```

### Changed — a bare icon now has a size

`<x-icon name="users" />` used to reach the browser with no width and no height, which
left the SVG to the default sizing of an inline element — never what anyone wanted, so
in practice a class was always passed. It now falls back to `md`, and the fallback is
configurable:

```php
'icon' => [
    Components\Icon\Component::class,
    [
        'size' => 'md',
    ],
],
```

An invalid value there throws, the same way Spinner's does.

### Added — soft customization

`icon` joins the soft customization surface with a `sizes.*` block per step, so the
scale can be retuned without touching the component:

```php
TallStackUi::customize()->icon()->block('sizes.md', 'h-9 w-9');
TallStackUi::customize('icon', scope: 'hero')->block('sizes.md', 'h-12 w-12');
```

### Migration

**A bare `<x-icon>` renders at 20px now.** Only calls that pass no `class` at all are
affected, and those were rendering at an unusable default size before, so the change is
almost always a fix. An application that was sizing icons through a wrapper rule still
wins on specificity — `.wrapper svg` outranks `.h-5` — but one relying on the absent
dimension has to pass `class` explicitly.

**Declaring `class` turns both shorthands off**, including an empty `class=""`:

```blade
<x-icon name="users" 2xl red class="size-4" />  {{-- size-4, nothing else --}}
```

This is what keeps the package itself untouched: all 190 internal icon usages pass a
class, so none of them picked up a size or a color from this change.

**Forty attribute names are now reserved on `x-icon`** — eleven sizes and 29 colors.
They are consumed and stripped, so they cannot be forwarded to the `<svg>` for any
other purpose.

**IDE autocompletion does not know them.** `ide.json` maps a component to its class and
the IDE reads props off the constructor, so the shorthands do not appear in completion
and may be flagged as unknown attributes. That is the cost of the attribute-bag
approach described above.

---

## Tooltip

### Changed — tippy.js is gone

`x-tooltip` was a thin wrapper over tippy.js. The dependency cost a bundle of its own,
its instance API leaked into Blade, and its viewport handling was the part nobody could
adjust. The directive is now built by the package.

`js/helpers/placement.js` holds the geometry, and nothing else:

```js
place(reference, floating, { placement, offset, padding, arrow })
// → { x, y, side, alignment, arrow }
```

It resolves a Popper-style placement (`bottom-end`, `auto-start`, the fifteen the
package already accepted), flips to the opposite side when the requested one does not
fit, shifts along the cross axis to stay inside the viewport, and reports where the
arrow has to sit after the shift. The caller applies the result through `translate` on a
`position: fixed` element, so there is no scroll offset math anywhere.

Two decisions in there are not obvious. Sizes are read from
`offsetWidth`/`offsetHeight` rather than a bounding rect, because the balloon is
measured while it still carries the closed state's `scale` and a rect would report the
scaled size. And **both** axes are clamped, not only the cross one: when neither side
fits — a viewport shorter than anchor plus balloon, which is what a narrow phone does to
a long tooltip — overlapping the anchor beats rendering off-screen.

**One balloon serves the whole page.** Tooltips are mutually exclusive by nature, and a
node per anchor piles up detached elements every time Livewire morphs a toolbar — the
editor alone renders two dozen of them. The single node is created on first use,
reused by every anchor, and dropped on `livewire:navigating`.

Long text no longer pushes the balloon away from its anchor. `max-width` is
`min(20rem, calc(100vw - 2rem))`, so the text wraps instead of forcing an extreme shift,
with `text-wrap: balance` and `overflow-wrap: anywhere` for long words and URLs.

### Changed — how it opens and closes

Under a mouse, hovering opens after a delay and scrolling repositions. Under a touch, a
tap opens and a tap outside or a scroll closes — a tapped tooltip has no pointer to
follow, so dragging it along the scroll reads as stuck. Keyboard focus opens without
waiting, blur closes, and <kbd>Escape</kbd> closes from anywhere.

The anchor carries `aria-describedby` pointing at the balloon while it is open, and the
balloon carries `role="tooltip"`. Neither existed before.

### Added — `delay`

Four named steps, because a number in a Blade attribute invites values nobody wants:

| Name     | Delay |
|----------|-------|
| `slow`   | 400ms |
| `fast`   | 150ms |
| `faster` | 75ms  |
| `flash`  | 0     |

```blade
<x-tooltip text="Foo" delay="flash" />
<x-button tooltip="Foo" data-tooltip-delay="slow" />
<span x-data x-tooltip="Foo" data-tooltip-delay="faster"></span>
```

It applies to the pointer only. Keyboard focus and taps open immediately — delaying a
deliberate action rather than an accidental hover has no reason to exist.

### Added — `balloon`, coloring the balloon

`color` still paints the icon. `balloon` paints the balloon:

```blade
<x-tooltip text="Foo" balloon="red" />
<x-button tooltip="Foo" data-tooltip-color="emerald" />
<x-kbd tooltip="Foo" data-tooltip-color="amber" />
```

The directive never holds a color map. It writes `--tsui-tooltip-bg` as
`var(--color-<name>-600)`, so any palette the application adds to `@theme` works with no
list to keep in sync — a project that redefines `--color-primary-*` gets its own primary
here for free. `black` maps to `var(--color-black)`.

A colored balloon keeps its color in both themes, and so does the default one: dark on
light and dark themes alike. An application that wants a light balloon on its dark theme
opts in through the `invert` setting below.

### Added — `scale`, growing the balloon

The balloon had one size, and it is small on purpose — a tooltip is a hint, not a
panel. But a hint holding a sentence reads cramped at `text-xs`, and the only way to
grow it was restyling `[data-tsui-tooltip]`, which resizes every tooltip on the page.
The balloon is a single shared node in `<body>`, so there is no per-anchor selector to
reach one tooltip through CSS alone.

`balloon` colors the balloon; `scale` sizes it:

```blade
<x-tooltip text="Foo" scale="lg" />
<x-button tooltip="Foo" data-tooltip-size="lg" />
<span x-data x-tooltip="Foo" data-tooltip-size="md"></span>
```

Three named steps, for the same reason `delay` has them — a number in a Blade attribute
invites values nobody wants:

| Name | Type scale  | Max width |
|------|-------------|-----------|
| `sm` | `text-xs`   | `20rem`   |
| `md` | `text-sm`   | `24rem`   |
| `lg` | `text-base` | `28rem`   |

`sm` is the default look under an explicit name, so an inline size can undo a global
one. Each step also grows the padding, and the max width keeps its viewport guard —
`min(<step>, 100vw - 2rem)` — so a long text still wraps instead of running off a
narrow phone.

The directive holds no size map. The steps live in `css/plugins/tooltip.css`, keyed by
`[data-tsui-tooltip][data-size='md']` and `[data-size='lg']`, so an application retunes
a step through the same stable selector everything else uses. A step of its own is also
reachable — the data attribute passes any value through — but only on anchors:
`<x-tooltip>` validates `scale` against the three names and throws on the rest.

The same value works as a global default through the `size` setting below, next to
`delay` and `color`; the inline attribute always wins.

### Added — `data-tooltip-disabled`

Turns a tooltip off without removing the directive:

```blade
<span x-tooltip="Foo" x-bind:data-tooltip-disabled="condition"></span>
```

The flag is watched, not only read when the balloon opens: the sidebar disables its
tooltips the moment the menu expands, while the pointer is still sitting on the item, and
the balloon has to disappear right then.

### Added — global settings

```php
'tooltip' => [
    Components\Tooltip\Component::class,
    [
        'delay' => null,
        'color' => null,
        'size' => null,
        'invert' => false,
    ],
],
```

All of them reach every `x-tooltip` on the page, including the ones rendered by Button,
Kbd, Breadcrumbs, Editor and the sidebar. `delay`, `color` and `size` are defaults: the
inline prop always wins.

`invert` flips the default balloon in dark mode — light background, dark text — which is
how some design systems draw a tooltip on a dark canvas. It is off unasked, so the
balloon keeps its dark look in both themes, exactly as it did on `3.x`. A colored
balloon never inverts, whatever the setting says.

The tooltip lives in a directive, not in a component, so those anchors have no PHP
instance to read the config from. `@tallStackUiScript` publishes it as attributes on the
main script tag, which keeps it out of an inline script that a strict CSP would reject:

```html
<script type="module" src="/tallstackui/script/tallstackui-*.js"
        data-tsui-tooltip-delay="flash" data-tsui-tooltip-color="rose"></script>
```

### Changed — where the balloon's classes live

The balloon is created by JavaScript and shared by anchors that have no component behind
them, so it cannot go through `customization()`. It is styled in `css/plugins/tooltip.css`
and overridden through a stable selector:

```css
[data-tsui-tooltip] { border-radius: 0; }
[data-tsui-tooltip] > [data-arrow] { display: none; }
```

`translate` is deliberately left out of the transition: only the open and close states
animate, so repositioning on scroll stays instant instead of lagging behind the anchor.

### Fixed — the selection highlight left behind by a click

Clicking the icon selected it, and the browser painted its selection highlight as a box
around the icon that outlived the click. The component's `wrapper` block gained
`select-none`. `[x-tooltip]` also gets `-webkit-tap-highlight-color: transparent`, for
the same flash on touch.

`select-none` is not applied to `[x-tooltip]` globally on purpose — a `<span>` of real
text carrying a tooltip has to stay selectable.

### Migration

**`$el._tippy` no longer exists.** Anything reaching for the tippy instance to enable or
disable a tooltip has to move to the attribute. The sidebar did:

```blade
{{-- before --}}
x-effect="$el._tippy && ($store['tsui.side-bar'].open ? $el._tippy.disable() : $el._tippy.enable())"

{{-- after --}}
x-bind:data-tooltip-disabled="$store['tsui.side-bar'].open"
```

**`tippy.js` left `package.json`**, and with it the `tippy.css` the package used to
serve. An application importing either directly has to install it on its own.

**`js/tallstackui-tooltip.js` is gone.** It existed only to keep tippy out of the main
bundle; the directive and Reaction moved into `js/tallstackui.js`. Loading is driven by
the manifest, so `@tallStackUiScript` needs no change — three files totalling ~87 KB
became one at ~58 KB, and one request less.

**A balloon styled through tippy's theme classes has to be restyled** through
`[data-tsui-tooltip]`.

---

## Reaction

### Changed — off tippy, onto the shared placement helper

Reaction was the other tippy consumer, and it is not a tooltip: it is a click-triggered
interactive popover. It keeps its own trigger, click-outside and <kbd>Escape</kbd>
handling, and asks `place()` for coordinates like the tooltip does.

The panel is built once and appended **inside** the `wire:ignore` wrapper, next to the
trigger. Not to `<body>`, which is where a floating element would normally go: `$wire`
walks upwards looking for a Livewire root, and outside of one it degrades to a no-op
that swallows the call without an error. The emoji buttons run `$wire.call`, so a panel
in `<body>` would open, animate and react to clicks while nothing ever reached the
server. Staying inside `wire:ignore` keeps it clear of the morph all the same.

It does not need to escape an `overflow: hidden` ancestor to be visible either — the
panel is `position: fixed`, placed in viewport coordinates.

### Changed — the panel look

Tippy's default theme is a black box, which is what the emoji panel used to be. It is now
a real panel — `dark-900` with a `dark-700` border, `dark-800`/`dark-600` in dark mode,
rounded with a shadow.

Like the tooltip balloon, it is built by JavaScript and therefore outside
`customization()`. It is styled in `css/plugins/popover.css` and overridden through
`[data-tsui-popover]`:

```css
[data-tsui-popover] { background-color: #101828; }
```

### Migration

**Panel markup changed shape.** It used to be tippy's root, box and content wrappers,
in `<body>`; it is now a single `[data-tsui-popover]` element next to the trigger,
holding the emoji grid. Anything selecting into the old structure — a browser test
walking an XPath, most of all — has to be repointed.

The panel carries `dusk="tallstackui_reaction_popover"`, and every emoji button now
carries `dusk="tallstackui_reaction_<name>"`, so a test names the reaction it clicks
instead of counting nodes:

```php
->click('@tallstackui_reaction_button')
->waitFor('@tallstackui_reaction_thumbs-up')
->click('@tallstackui_reaction_thumbs-up')
```

### Tests

`BrowserTest.php` traded `clickAtVisibleXPath('html/body/div[3]/div/div/div/div[1]/div/button[7]')`
for the named hook above, in the three tests that react.

That XPath was worth more than a refactor: pointed at the old markup it kept passing
against a panel whose `$wire.call` had silently become a no-op, because the assertion
never got as far as the server. Naming the button is what surfaced it.

---

## List

### Added — `compact`, a denser row rhythm

```blade
<x-list compact :items="$tags" />
```

Tightens the vertical padding of the rows, the search bar and the empty message, leaving
the horizontal padding, the type scale and the colors alone. The skeleton follows the
flag, so a list that opens as a placeholder does not change height when the rows arrive.

The flag lives on `<x-list>` alone and reaches the rows through `@aware`, so it holds
across all three ways of writing them — `:items`, `lazy` and rows spelled out in the
slot — without repeating it on every `<x-list.items>`.

Each affected block gained a `-compact` twin — `search.wrapper-compact`,
`empty.wrapper-compact` and `skeleton.items.row-compact` on `<x-list>`, plus
`wrapper-compact` on `<x-list.items>` — and the flag swaps the whole string instead of
layering an override on top of it. An application customizing the `wrapper` of
`list.items` has to customize `wrapper-compact` too if it uses both modes.

`wrapper-compact` also lowers the reserved `contain-intrinsic-size` from `2.5rem` to
`1.75rem`, matching the height a compact row actually lays out at, so a long lazy list
does not overstate its scroll height before the rows are painted.

### Added — `lazy`, rendering the rows on the client

A `:items` list pays one full Blade component per row: attribute reflection, runtime
compilation, customization resolution and the nested `<x-icon>`/`<x-floating>` of the
menu. At a few hundred rows that cost lands on the response that opens the modal or
the page holding the list, before the user sees anything.

`lazy` moves the rows to the client. The server serializes `:items` into a single JSON
array and Alpine renders a slice of it through `x-for`, growing the slice as a sentinel
at the bottom of the scroll container comes into view:

```blade
<x-list :items="$tags" height="60" lazy />        {{-- first slice of 20 --}}
<x-list :items="$tags" height="60" lazy="10" />   {{-- first slice of 10 --}}
```

The row markup is still the same component. `<x-list.items>` renders once inside the
`x-for` template, with `x-text` bindings in place of values, so every soft customization
of `list.items` reaches the lazy rows unchanged.

**Search still sees the whole dataset.** The filter runs over the JSON array, not over
the rendered rows, so a term matching only the five-hundredth item finds it while twenty
rows are on screen. `register()` becomes a no-op in this mode — the array is already the
source, and the per-row `x-init` disappears with the rows themselves.

`height` is required: the sentinel needs a scroll container to intersect with. And
`lazy` refuses `@interact('item_caption')`, `@interact('item_action')` and
`@interact('item_menu')` — those slots are closures the server resolves while rendering
each row, and there is no per-row server render left to resolve them. Degrading silently
would trade a visible error for an invisible one, so the combination throws. A list that
needs per-row Blade keeps working exactly as before, without `lazy`.

When the revealed slice is shorter than the container, nothing can scroll, the sentinel
never leaves the viewport and `x-intersect` does not fire a second time. After each
growth `more()` waits a frame and keeps going while the rows still fit — overflow is the
signal that the scroll, and with it the sentinel, is back in play. Measuring the
overflow rather than the sentinel's position is deliberate: rows carry
`content-visibility: auto`, so an off-screen row reports the reserved
`contain-intrinsic-size` until the browser lays it out for real, and a position read
taken mid-flight can stall the fill half-way with no scroll left to recover it.

The name matches the `lazy` of `<x-select.styled>`, which is also a render slice, and
not the `lazy` of `<x-tag>` and `<x-autocomplete>`, which is a minimum typing length.

### Fixed — phantom divider above the first visible row after a search

Filtering a searchable list down to a row that was not the first row in the DOM
painted a spurious top border against the search row's bottom border, reading as a
single thick divider.

The dividers were keyed on an adjacent-sibling selector over `data-list-row`. Rows
are hidden with `x-show`, which sets `display: none`, and hidden elements still
participate in CSS sibling matching — so a visible row preceded only by *hidden*
rows still matched `[data-list-row] + [data-list-row]` and got a `border-t`.

Every row now also carries `data-list-on`, which Alpine drops while the row is
filtered out, and the divider is keyed on the general-sibling combinator:

```
[&>[data-list-on]~[data-list-on]]:border-t
```

`A ~ B` matches a visible row that has at least one *visible* row before it, which
is exactly "every visible row except the first visible one". No DOM-position
selector (`+`, `:not(:first-child)`, `divide-y`) can express this while hidden
siblings are still in the tree.

**Migration.** Applications overriding the `items.wrapper` block of `<x-list>` must
key their dividers on `data-list-on` rather than `data-list-row`, or the artifact
comes back.

### Added — raw content in the caption and a new `action` slot

`<x-list.items>` accepts consumer markup in two positions.

`caption` keeps working as a plain string attribute (HTML-escaped) and additionally
accepts a slot for arbitrary markup:

```blade
<x-list.items name="production">
    <x-slot:caption>
        <x-badge text="12 servers" color="red" sm />
    </x-slot:caption>
</x-list.items>
```

The new `action` slot renders controls on the right of the row without the ellipsis
dropdown chrome, and coexists with `<x-slot:menu>`:

```blade
<x-list.items name="general" caption="1 server">
    <x-slot:action>
        <x-button sm wire:click="deploy('general')">Deploy</x-button>
    </x-slot:action>
    <x-slot:menu>
        <x-dropdown.items text="Edit" wire:click="edit('general')" />
    </x-slot:menu>
</x-list.items>
```

Both are mirrored in data-driven mode through `@interact('item_caption', $item)` and
`@interact('item_action', $item)`, alongside the existing `@interact('item_menu')`.

Search still works when the caption is markup: the component registers a plain-text
projection of the slot (tags stripped, whitespace collapsed), so a caption rendered
as a badge continues to match its visible text.

**Migration.** When `action` and/or `menu` are present, both are grouped inside a new
`content.aside` wrapper (`flex shrink-0 items-center gap-x-2`). Rows that previously
rendered only a menu now carry one extra `<div>`. Applications selecting the menu
wrapper by DOM position rather than by class may need adjusting.

### Changed — `caption` excluded from the debug overlay

`caption` gained `#[SkipDebug]`, matching `menu` and `empty`, because a
`ComponentSlot` value would otherwise dump raw HTML into the debug panel. The
caption no longer appears in the overlay when `TALLSTACKUI_DEBUG_MODE` is on.

---

## Table

### Changed — `simple-pagination` alone turns pagination on

Enabling the prev/next-only footer used to take both flags:

```blade
{{-- was --}}
<x-table :rows="$rows" paginate simple-pagination />

{{-- is --}}
<x-table :rows="$rows" simple-pagination />
```

`simple-pagination` now implies `paginate`. An explicit `:paginate="false"` still
wins, and the global config default keeps working. Nothing breaks for tables that
pass both — the extra flag is just redundant now.

### Changed — the simple paginator's disabled buttons went ghost

The disabled Previous/Next of the `simple` paginator carried a filled pill
(`bg-gray-50` / `dark:bg-dark-800`) that weighed more on the eye than the enabled
button next to it. It now renders as faded text only
(`text-gray-300 dark:text-dark-500`), the same treatment the disabled chevrons and
the `minimal` and `compact` paginators already used.

### Added — `compact`, a denser row rhythm

```blade
<x-table :$headers :$rows compact />
```

Tightens the vertical padding of the header cells, the data cells, the empty message and
the expandable content, leaving the horizontal padding, the type scale and the colors
alone. The skeleton follows the flag, so a lazy table does not change height when the
real rows arrive.

Each affected block gained a `-compact` twin — `table.th-compact`, `table.td-compact`,
`empty-compact` and `expandable.content-compact` — and the flag swaps the whole string
instead of layering an override on top of it. An application customizing `table.td` has
to customize `table.td-compact` too if it uses both modes.

Unrelated to `paginator="compact"`, which names a pagination look. The two combine.

### Fixed — `simplePaginate()` was fatal

The constructor accepts `LengthAwarePaginator|Paginator|Collection|array`, and the
runtime treated anything extending `AbstractPaginator` as paginated. The three paginator
views then called `total()`, `lastPage()` and walked `$elements`, none of which a simple
paginator carries:

```blade
{{-- Method Illuminate\Support\Collection::total does not exist. --}}
<x-table :$headers :rows="User::simplePaginate(10)" paginate />
```

It only worked when `simple-pagination` happened to be set as well.

Rows that are not length-aware now render the simple views on their own, flag or no
flag. The flag still forces the simple look on a length-aware paginator, so nothing
that worked before changes.

### Added — the table renders outside Livewire

`<x-table>` carried `#[RequireLivewireContext]`, so reaching for it from a controller
or a plain Blade view threw `MissingLivewireException`. The attribute is gone, and the
three features that depended on a round trip now travel through the query string:

```blade
{{-- routes/web.php → a plain controller, no Livewire anywhere --}}
<x-table :$headers
         :rows="$users"
         :sort="request('sort', ['column' => 'id', 'direction' => 'desc'])"
         filter
         paginate />
```

```
?search=foo&quantity=25&sort[column]=name&sort[direction]=asc&page=2
```

The `search` and `quantity` parameter names come from `:filter`, so the application
still owns them. Filtering or sorting drops `page`; every other parameter survives.

| Feature    | Inside Livewire        | Outside                                          |
|------------|------------------------|--------------------------------------------------|
| pagination | `wire:click="gotoPage"` | `<a href>`, built from the URLs the paginator already exposes |
| sorting    | `wire:click="$set"`     | `<a href>` with the inverted direction           |
| filter     | `wire:model.live`       | Alpine rewriting `location`                      |
| loading    | `wire:loading`          | not rendered                                     |
| selectable | entangled array         | plain array, reported through events             |

Most of this was already prepared: `filter`, `loading`, `sort` and `wire:key` were
guarded by `$livewire` in the template long before this change. What was missing was
the attribute, the anchor branch in the paginator, and the entangle fallback.

The page links come from `$elements`, which has always carried `[$page => $url]` and
whose `$url` the template discarded. Outside Livewire the paginator is also passed
through `withQueryString()`: without it Laravel builds `?page=2` alone, and paginating
would silently drop the active filter and sort.

Two things do not survive the trip. `loading` needs `wire:loading` and is not rendered.
And a `Collection` passed together with `paginate` used to reach `$rows->hasPages()` and
fatal; the guard is now an `AbstractPaginator` check, which matters more here because a
controller is far more likely to hand over a plain collection.

The quantity select is bound with `x-on:select.capture`, not `x-on:select`.
`select.styled` dispatches `new CustomEvent('select')` on its `$refs.button` **without**
`bubbles`, so the event never reaches the wrapper on the way up; only the capture phase
sees it.

### Added — `persistent` accepts an element id

`persistent` scrolled back to the table after paginating, through
`$refs.persist.scrollIntoView()`. That works inside Livewire, where nothing reloads.
Outside it every link is a full page load: the document is destroyed and the handler
never runs.

The fragment is the native answer — the browser scrolls after the load, with no script,
and it survives back and forward. But anchoring on the table itself pins it to the top
of the viewport, leaving the card header and the filter bar out of frame. So the prop
now takes an id as well:

```blade
<div id="users">
    <x-card>
        <x-table :$headers :$rows paginate persistent="users" />
    </x-card>
</div>
```

| Value                 | Anchor              | Inside Livewire                        | Outside                                   |
|-----------------------|---------------------|----------------------------------------|-------------------------------------------|
| `false`               | —                   | nothing                                | nothing                                   |
| `persistent`          | the table itself    | `$refs.persist.scrollIntoView()`       | `id` on the wrapper, `#table-{pageName}` on the links |
| `persistent="users"`  | the given element   | `document.getElementById('users')?.scrollIntoView()` | `#users` on the links, no `id` on the wrapper |

Inside Livewire the string form cannot use `x-ref`, which only reaches refs declared in
the table's own `x-data` — hence `getElementById`, guarded with `?.` for an id that is
not on the page.

A self-anchored id has to be **the same on the next request**. Were it a `uniqid()`, page
two would render a different one, the fragment would point at nothing and the scroll
would simply not happen, with no error anywhere. The fallback is therefore the
paginator's page name, which is stable and already unique per table on the page. Without
a paginator and without an `id` there is no stable name to derive, and the anchor stays
null.

The anchor is carried into the filter too: filtering from the bottom of a page reloads
exactly like paginating does.

An empty string is rejected by `validate()` — it would render `href="...#"`, which
scrolls to the top, and the failure would be silent.

### Added — `selected`, carrying the whole selection

`select` fires from the row checkbox with the full row, and is untouched. It never fired
for **select all**, though: the header checkbox goes through `all()` → `push()`/`remove()`,
which never reach `select()`. Anyone listening only to `select` never heard about it.

```blade
<div x-data="{ rows: [] }" x-on:selected="rows = $event.detail.rows">
    <x-table :$headers :$rows selectable />
</div>
```

`selected` carries `{ rows }` — the values of `selectable-property` — and covers both
paths. It comes from a `$watch('model')` rather than a call at each mutation point:
`x-model` and `x-on:change` answer the same event on the row checkbox, so emitting from
inside `select()` would race with the model being updated. Watching also picks up
changes pushed from the server into the entangled property, which means that inside
Livewire `selected` can fire on a re-render and not only on a click.

`model` also falls back to `[]`. Outside Livewire there is nothing to entangle and
`Wireable::entangle()` resolves to the string `null`, so the first `push()` threw.

### Added — three paginator variants, and a global default for them

`paginator` used to be the view path handed to `links()`. It now names a look, and the
same name styles **both** the numbered mode and `simple-pagination`:

```blade
<x-table :$headers :$rows paginate />                      {{-- the configured default --}}
<x-table :$headers :$rows paginate paginator="compact" />  {{-- this table only --}}
```

```php
'table' => [
    Components\Table\Component::class,
    ['paginator' => 'minimal'],
],
```

| Variant   | Numbered                                        | `simple-pagination`              |
|-----------|-------------------------------------------------|----------------------------------|
| `simple`  | rail with a floating pill, chevrons outside it   | two tinted `rounded-full` buttons |
| `minimal` | no surfaces at all, current page ruled underneath | two underline-on-hover text links |
| `compact` | one bordered shell holding `‹ 3 / 12 ›`          | the same shell, page number only  |

`compact` is the one that changes the shape rather than the skin: the page list collapses
into an indicator, so a single control serves every width and there is no separate mobile
block. The figures are `tabular-nums`, which stops the shell resizing between 9 and 10.
It reads `lastPage()`, which a simple paginator does not have — hence the page number
alone in that mode.

A dotted value is still treated as a view path, so a paginator of your own keeps working:

```blade
<x-table :$headers :$rows paginate paginator="components.my-paginator" />
```

Anything else raises a validation exception listing the bundled names.

Each variant is a view under `components/table/paginators/`, and what they share —
the page name, the dusk suffix, the URL fragment and the scroll snippet — is resolved
once in `TableRuntime` and handed over as data, so a variant is only classes and markup.

### Added — global defaults for `paginate`, `filter`, `quantity` and `simple-pagination`

Four props that were repeated on every table can now be set once:

```php
'table' => [
    Components\Table\Component::class,
    [
        'paginate' => true,
        'filter' => true,
        'quantity' => [5, 10, 25],
        'simple-pagination' => false,
    ],
],
```

Each is a default, not a lock. The props default to `null`, which means "not given", so
an explicit value always wins — including turning a global default back off:

```blade
<x-table :$headers :$rows :paginate="false" />
<x-table :$headers :$rows :filter="false" />
```

`filter` takes the same values it takes inline: `true` for the conventional `quantity`
and `search` property names, or an array mapping them to your own.

This moved the `filter` normalisation and the `wire:target` list out of the constructor
and into `setup()`, which is where a component may read its configuration — the
constructor runs before the global default is available to merge with.

### Changed — the pagination restyle

The paginator view was the last untouched corner of the component: every class hardcoded,
no hover on any button, and `focus:shadow-outline-blue` — a Tailwind 2 class that does
not exist in 4 — as the only focus treatment, which left keyboard navigation with no
visible focus at all. Disabled states used `cursor-pointer`, and `dark:border-transparent`
erased the dividers in dark mode, collapsing the group into one solid block.

It was a bordered button group: every page a boxed cell, welded to its neighbour with
`-ml-px`, the whole thing framed. That shape is now a **rail with a floating pill** — the
numbers sit on a rounded track, the current one is the only filled surface, and the
chevrons step outside the track as free-standing round buttons.

```
      ╭─────────────────────────────╮
  ‹   │  ⬤1   2    3    4    5     │   ›
      ╰─────────────────────────────╯
```

| Before                             | After                                          |
|------------------------------------|------------------------------------------------|
| bordered cells welded by `-ml-px`  | borderless slots on a `rounded-full` rail      |
| chevrons inside the group          | round buttons outside it                       |
| no hover                           | idle slots lift to a white pill on hover       |
| `focus:shadow-outline-blue` (dead) | `focus-visible:ring-2 ring-primary-500`        |
| `cursor-pointer` when disabled     | `cursor-not-allowed`                           |
| `bg-primary-100` active            | `bg-primary-600` pill, white text, `shadow-sm` |
| width from content                 | `min-w-8 justify-center`                       |
| `w-5 h-5` chevrons                 | `size-4` in a `size-9` button                  |
| dots styled like a button          | `text-gray-400`, no surface                    |
| flat summary                       | numbers in `font-semibold`, connectives in `text-gray-500` |

Dropping the borders removes the three problems the bordered group kept generating rather
than fixing: no border means no `-ml-px`, no `-ml-px` means no overlap to compensate for,
and no box per item means no divider to keep visible in dark mode. What is left is one
surface — the rail — and one accent — the pill.

The uniform slot width still matters: without it the rail resizes as the digit count
changes, and going from page 9 to 10 shifts every number.

`simple-pagination` follows the same language: the two buttons lose their borders and
become `rounded-full` tinted surfaces.

`mb-4` was dropped from the mobile block. It produced dead space in both modes: in the
default one the block is `sm:hidden` and is the only content on a phone, and with
`simple-pagination` it is the only content at any width.

**No soft customization key was added, renamed or removed.** The pagination stays
outside `TallStackUi::customize()`, exactly as before.

### Fixed — the current page sat on a different baseline

With a filled surface behind it, the active page was visibly a pixel or two off from its
neighbours. The cause predates the restyle; `bg-primary-100` was simply too light to
show it.

The group is `inline-flex`, and its direct children are the `<span>` wrappers that carry
`wire:key` and the ARIA roles. As plain spans they became flex items, but their content
stayed inline-level — so each created a line box and aligned on the *baseline*, against
the strut of the inherited line-height. The page links are nested one level deep; the
current page and the chevrons are nested two. Different nesting, different baseline.

The wrappers are `inline-flex` themselves now, which turns their content into flex items:
no line box, no strut, no baseline.

### Fixed — a crafted `persistent` could run script

Introduced and closed inside this same branch, recorded because the mechanism is easy to
reproduce elsewhere.

The Livewire scroll snippet interpolated the id straight into a JavaScript string:

```php
"document.getElementById('{$scrollTo}')?.scrollIntoView();"
```

Escaping it with `{{ }}` does not help. The value lands in an HTML attribute, and the
browser decodes entities **before** Alpine ever evaluates the expression, so `&#039;`
becomes a quote again and closes the call:

```
document.getElementById('x'); alert(1); //')?.scrollIntoView();
```

It is now built with `Js::from` and printed with `{!! !!}`, so the id arrives as a
JavaScript string literal with its quotes escaped as `'`.

### Migration

No soft customization block was added, renamed or removed, so nothing targeting the
table through `TallStackUi::customize()` breaks.

`persistent` widened from `?bool` to `bool|string|null`. Existing boolean usage is
unaffected.

Three behaviours to be aware of:

- The paginator markup changed class by class. Applications with their own CSS aimed at
  the old classes need to re-point it. Applications that already replace the view through
  the `paginator` prop are untouched.
- **`components/table/paginators.blade.php` no longer exists.** It became
  `paginators/simple.blade.php`, and the directory now holds one file per variant. A
  `paginator` pointing at the old path has to be updated; the prop itself keeps accepting
  view paths, so only the path changed.
- The data the paginator view receives changed shape: `scrollTo` and `simplePagination`
  became `scroll`, `simple`, `name`, `dusk` and `fragment`, precomputed by `TableRuntime`.
  This only matters to a custom paginator view, which now reads those instead of deriving
  them.

Still open, and deliberately out of scope: the summary reads `Showing`, `to`, `of` and
`results` through loose JSON translation keys rather than `ts-ui::messages`, so the
fifteen languages this package ships do not cover that line.

### Tests

`FeatureTest.php` grew three groups. *Outside the livewire context* covers rendering,
anchor pagination, the filter surviving a page change, sort links carrying the inverted
direction and dropping the page, and the filter emitting `navigate()` instead of
`wire:model`. *The persistent anchor* covers all three anchor sources, the string form
not claiming the id, the anchor reaching the filter, and the empty string throwing.

*Backward compatibility inside the livewire context* is the one that guards the
regression surface: `gotoPage`/`nextPage`/`previousPage` still on `wire:click` with no
anchor anywhere, the four `dusk` hooks including a custom page name, `wire:key` per page
element, `$refs.persist` for the boolean `persistent` and `getElementById` for the
string, a crafted id not breaking out of the snippet, and `simple-pagination` rendering
neither `gotoPage` nor the summary.

Reaching that branch needs no Livewire component: the paginator view is rendered on its
own with `livewire => true`, fed by `invade($paginator)->elements()` — `elements()` is
protected, and it is what `links()` passes, so `linkCollection()` is not a substitute.

*The paginator variants* renders each bundled name, checks the configured default and the
inline override, and covers what makes two of them distinct: the collapsed indicator on
`compact` and the rule under the current page on `minimal`. A view path of its own still
resolves, and an unknown name throws.

*The global defaults* covers each of the four in both directions — read from the
configuration, then turned back off inline. Every one of these goes through a
`tableConfig()` helper that calls `__ts_get_component_configuration(..., flush: true)`:
the helper memoizes the component map in a static, so a `config()` set afterwards is
invisible without it, and several of these tests passed for the wrong reason until the
flush was added.

`BrowserTest.php` adds what only a browser shows: `selected` reporting the whole array as
rows are ticked and unticked and firing for select all in both directions, plus the scroll
snippet — a string `persistent` reaching `getElementById`, and a crafted one failing to
break out of it. That pair lives here rather than in the feature suite because the snippet
is only generated inside a Livewire context, which `Blade::render` does not provide.

---

## Spinner

### Added — `<x-spinner>`

A purely visual loading indicator. It binds nothing to Livewire, holds no state and
requires no context, so it can sit inside a card, next to a button, in an empty state
or in a `#[Lazy]` placeholder:

```blade
<x-spinner />
<x-spinner lg bars color="red" />
<x-spinner wave text="Sending the file" />
<x-spinner thinking />
```

Thirteen variants, one boolean flag each:

| Flag       | Appearance                                       |
|------------|--------------------------------------------------|
| `ring`     | Spinning border with a transparent top (default) |
| `throbber` | Twelve SVG segments in ramping opacity           |
| `gradient` | Two-tone SVG arc                                 |
| `ping`     | Hollow ring with an expanding echo               |
| `dots`     | Three bouncing dots                              |
| `pulse`    | One scaling dot                                  |
| `typing`   | Three chat-style dots                            |
| `bars`     | Three vertical bars                              |
| `wave`     | Five vertical bars travelling as a wave          |
| `shimmer`  | Gradient sweeping across the text                |
| `caret`    | Text followed by a blinking block                |
| `terminal` | Prompt sign with a blinking block                |
| `thinking` | Cycling braille glyphs with a translated label   |

Two flags at once throws, because a mistyped variant is a different component and
silence would hide it:

```blade
<x-spinner wave bars />   {{-- throws --}}
```

Size flags follow the library's own convention instead, resolving by precedence
(`lg`, `md`, `sm`, `xs`) with `md` as the default.

`shimmer` and `caret` animate the text itself, so one of `text` or the default slot
is required — without content there is nothing on the screen to animate. `terminal`
draws the prompt and the caret on its own.

**This does not replace `loading` or `skeleton`.** The three cover different moments:

| State                           | Tool       | Situation                           |
|---------------------------------|------------|-------------------------------------|
| First paint, no data yet        | `skeleton` | `#[Lazy]` placeholder, initial load |
| Refetch, data already on screen | `loading`  | Sort, paginate, search, save        |
| Anything else that has to spin  | `spinner`  | Inline, in a button, in an empty state |

#### Color through `currentColor`

Every variant paints itself from `currentColor`, so the whole palette is a single
`text-*` class on the root — it drives borders, dot fills, bar fills, SVG strokes and
the shimmer gradient at once. That is one map of 29 colors instead of the four a
`solid`/`light` pair would need, and it makes the escape hatch a plain utility:

```blade
<x-spinner bars color="emerald" />
<x-spinner class="text-[#ff5f1f]" />
```

`php artisan tallstackui:setup-color` publishes a `SpinnerColors` class with a single
`textColors()` palette.

One consequence worth recording, since it is easy to undo by accident: `shimmer` paints
its fill transparent through `[-webkit-text-fill-color:transparent]` rather than
`text-transparent`. The gradient stops are `currentColor`, and `text-transparent`
compiles to `color: transparent` on the very element that carries the gradient, so all
three stops would resolve to transparent and the text would render as nothing.
`shimmer_paints_a_visible_gradient` in the browser suite asserts the computed gradient,
not the class string, so swapping the technique back fails the test.

#### The `thinking` variant

The braille frames cycle through Alpine, one timer per spinner, cleared on
`destroy()`. The label defaults to a translation and `text` overrides it:

```blade
<x-spinner thinking />                     {{-- ⠋ Thinking... --}}
<x-spinner thinking text="Analyzing" />    {{-- ⠋ Analyzing --}}
<x-spinner thinking :text="false" />       {{-- ⠋ only --}}
<x-spinner thinking :interval="250" />     {{-- slower --}}
```

The first frame is rendered server-side, so there is no empty gap before Alpine boots.

#### Accessibility

The root carries `role="status"`. Without any label the component emits a `sr-only`
fallback, so a screen reader never announces an empty region:

```php
'spinner' => [
    'thinking' => 'Thinking...',
    'loading' => 'Loading...',
],
```

Both keys ship in all 15 locales.

Animations are not disabled under `prefers-reduced-motion`, matching every other
animated component in the library — a frozen loading indicator reads as a stuck one.
Applications that want it can neutralize the animation blocks through customization.

### Added — global settings

```php
'spinner' => [
    'type' => 'ring',
    'size' => 'md',
],
```

An unknown `type` or `size` throws when the component renders, rather than falling
back and hiding the typo.

### Added — soft customization blocks

Each variant owns its namespace, plus a shared `wrapper`, `text.*` and `delays.*`:

| Namespace   | Blocks                                                                    |
|-------------|---------------------------------------------------------------------------|
| shared      | `wrapper`, `text.base`, `text.sizes.*`, `delays.0` … `delays.4`            |
| `ring`      | `base`, `sizes.*`                                                          |
| `throbber`  | `base`, `segment`, `sizes.*`                                               |
| `gradient`  | `base`, `track`, `head`, `sizes.*`                                         |
| `ping`      | `wrapper`, `echo`, `core`, `sizes.wrapper.*`, `sizes.border.*`             |
| `dots`      | `wrapper`, `dot`, `sizes.wrapper.*`, `sizes.dot.*`                         |
| `pulse`     | `dot`, `sizes.*`                                                           |
| `typing`    | `wrapper`, `dot`, `sizes.wrapper.*`, `sizes.dot.*`                         |
| `bars`      | `wrapper`, `bar`, `sizes.wrapper.*`, `sizes.bar.*`                         |
| `wave`      | `wrapper`, `bar`, `sizes.wrapper.*`, `sizes.bar.*`                         |
| `shimmer`   | `base`, `sizes.*`                                                          |
| `caret`     | `wrapper`, `caret`, `sizes.text.*`, `sizes.caret.*`                        |
| `terminal`  | `wrapper`, `prompt`, `caret`, `sizes.text.*`, `sizes.caret.*`              |
| `thinking`  | `wrapper`, `glyph`, `label`, `sizes.glyph.*`, `sizes.text.*`               |

`delays` is a single indexed list shared by `dots`, `typing`, `bars` and `wave`, so the
stagger is tuned in one place instead of four.

```php
TallStackUi::customize()->spinner()->block('bars.bar', 'rounded-none bg-slate-400');
TallStackUi::customize('spinner', scope: 'chat')->block('typing.dot', 'size-2');
```

### Added — seven keyframes

`ts-spinner-dots`, `ts-spinner-pulse`, `ts-spinner-typing`, `ts-spinner-bars`,
`ts-spinner-wave`, `ts-spinner-shimmer` and `ts-spinner-caret` join the `@theme`
block. `ring`, `throbber` and `gradient` reuse `animate-spin`, `ping` reuses
`animate-ping`, and `terminal` shares the caret keyframe with `caret`.

The four staggered variants — `dots`, `typing`, `bars` and `wave` — declare
`backwards` in the animation shorthand. `animation-fill-mode` defaults to `none`,
and an animation is *not executing* during its `animation-delay`, so without it the
delayed children render at their static state rather than at the `0%` keyframe: five
`wave` bars stand at full height and collapse into the wave one by one as each delay
expires, the last of them 480ms in. The artifact is easy to miss inline, because it
plays once while the page boots, and impossible to miss inside `<x-loading>`, where
going from `display: none` to visible restarts the animation on every request.

`dots` does not strictly need it — its `0%` is `translateY(0)`, which is what the
element already renders — but the rule is "everything that consumes the `delays`
block", not the coincidence that one keyframe happens to start at rest.
`staggered_variants_start_at_the_first_keyframe` asserts the computed
`animation-fill-mode` and the computed transform of every child, so removing
`backwards` fails rather than degrading quietly.

---

## Toast

### Fixed — a flashed toast came back on every later toast

`add()` handles both the initial page load and the `ts-ui:toast` window event, and the
`flash` it reads is a closure parameter that never empties. The guard was written for
the load path, where the two entry points (`window.onload` and `livewire:navigated`)
could otherwise show it twice.

Reached through the window event, the same block flushed everything on screen and
pushed the old flash back with a fresh timer — for the rest of the page's life, and
destroying the whole pile in `stacked` mode.

The flash is consumed once now. Both load paths stay covered: the first to arrive shows
it, the second finds it spent.

Dialog and Banner never had this; they only assign.

### Fixed — the toast timer outlived the card

The interval id was a `const` inside `$nextTick`, out of reach of `destroy()`, which
disconnected the `ResizeObserver` and removed the `visibilitychange` listener but left
the timer running. The self-clear inside the loop only covers `hide()`, and a toast
dropped by the parent's `flush()` keeps `show === true`, so it never ran.

`$this->toast()->info('B')->sole()->send()` with A on screen therefore left A's interval
alive: it fired `toast:timeout` for a toast nobody saw expire, and called
`Livewire.find(...)` for it when A carried a timeout hook. Frozen by a hover or an
expanded pile, `elapsed >= max` never arrived and the interval simply never stopped.
After a `wire:navigate`, `Livewire.find()` returns `undefined` and the orphan threw.

The id moved onto the instance, behind an idempotent `stop()` that `destroy()`, the
self-clear and the timeout path all call.

### Added — `top-center` and `bottom-center` positions

The toast accepted four positions, all of them cornered. The two centered variants
now join them, both in the fluent method and in the global default:

```php
$this->toast()->position('top-center')->success('Saved!')->send();
```

```php
'toast' => [
    'position' => 'bottom-center',
],
```

The allowed list lived in two places that had drifted apart: `Interactions\Toast`
validated the fluent argument, and `Toast\Component::validate()` validated the config
value. Adding a position to only one of them left the other rejecting it, so both were
updated together.

Centered alignment comes from its own customization block instead of leaning on the
base classes of `wrapper.second`, and the enter transition no longer slides
horizontally when the position is centered — a toast in the middle of the screen has
no edge to come from.

Based on the proposal in #1330.

### Added — `stacked`, piling the toasts instead of listing them

Off by default.

Sending many toasts grew an endless vertical list that eventually ran past the
viewport. With `stacked` on, they overlap into a pile, and the pile expands back into
the list while the pointer is over it:

```php
'toast' => [
    'stacked' => true,
],
```

The most recent toast is the front of the pile, anchored to the edge its position
points at. Older ones sit behind it, offset toward the center of the screen and
slightly smaller. Three layers peek out; deeper toasts wait at `opacity: 0` and
reappear as the ones in front leave.

In the closed pile **only the front card renders content** — the ones behind are
reduced to their card shape. Showing every card's text at once put two paragraphs in
the same space during the transition, and in `top-*` the visible strip of a card
behind is its bottom edge, which is where the progress bar sits: the pile turned into
stacked progress bars.

Hovering the pile expands it and **freezes every timer and progress bar in it** until
the pointer leaves.

Cards are absolutely positioned in both states, with `translateY` computed per state —
a fixed step per layer while piled, the summed measured heights once expanded. Keeping
one positioning mode is what lets the pile and the list be a single continuous
animation; swapping `position` between `absolute` and static does not animate. Each
toast reports its own height through a `ResizeObserver`, so a card that changes size —
`expandable` opening its description — re-seats the pile.

The geometry is fixed, not configurable: a 16px step per layer, a 12px gap once
expanded, three visible layers. Only the switch is exposed.

Because the front of the pile is always the newest toast, turning the switch on
**reverses the reading order of the `top-*` positions**: the plain list puts the oldest
toast at the edge, following DOM order, while the expanded pile puts the newest there.
The `bottom-*` positions read the same either way.

**Known limitation:** there is no cap on how many toasts the expanded pile shows, so a
long queue overflows the viewport and its lower cards become unreachable. That is the
behaviour the plain list already has today — an unbounded list inside `fixed inset-0`
with no scroll — so the pile degrades to the status quo rather than below it.

### Added — `top-on-mobile`, pinning the toasts to the top on narrow screens

Off by default.

Below the `md` breakpoint the toast never honoured its position: `wrapper.first` has
`justify-end` as its base and only `md:justify-start` / `md:justify-end` tell the
positions apart, so a `top-right` toast has always landed at the bottom of a phone
screen. That was never documented.

`top-on-mobile` pins them to the top instead, whatever the position says:

```php
'toast' => [
    'top-on-mobile' => true,
],
```

The plain list resolves this in CSS, through a `max-md:justify-start` block. The pile
cannot: it computes its anchor in JavaScript, in `style()`, where a media query is not
available. It therefore watches Tailwind's `md` breakpoint with `matchMedia` and flips
the anchor — and the sign of the `translateY` — when the viewport is narrow. The
breakpoint is mirrored as a constant in `toast-base.js`, next to the pile geometry; it
is the one place where a Tailwind value is duplicated in script.

The enter transition follows the edge the toast comes from, so with the flag on it
enters downward rather than upward.

### Fixed — a hovered toast could stop expiring

A toast held its countdown through a `paused` closure driven by `mouseover` and
`mouseout` on its own card, and each handler also wrote `animationPlayState` on the
progress bar directly. Two consequences:

- A card that moves out from under a **stationary** pointer never receives
  `mouseout` — the mouse did not move, the element did. `paused` latched at `true`
  and that toast's timer never resumed. In normal mode this needed a toast above to
  expire and the ones below to rise under the cursor; the pile made the cards move on
  every event.
- The bar and the timer could disagree, because each was paused from its own place.

The hold is now a single derived value, and the bar follows it from one method:

| Before                                        | After                                    |
|-----------------------------------------------|------------------------------------------|
| `paused` closure plus a separate pile flag    | `paused` and `piled` properties          |
| both summed inline in the `setInterval` guard | a `frozen` getter deriving the two       |
| each handler writing to the bar               | one `animate(running)`, driven by `frozen` |

In stacked mode the per-card hover listeners are not registered at all: the pile's
wrapper does not move, so its hover is the only reliable one. In normal mode they still
are, so the latch itself remains reachable there — rare, and recoverable by moving the
mouse. What the unification fixes in both modes is the bar disagreeing with the timer.

`animate()` also guards for a missing progress bar, which repairs a latent crash: with
`progress` set to `false` the span is never rendered, and the old hover handler reached
into `undefined` on every hover.

### Added — `stacked`, `position` and `sole` reached the fluent APIs

`stacked` was born as a configuration switch, resolved when the Blade rendered: the
markup either was a pile or it was not, and no toast could say otherwise. Both fluent
APIs now carry it per toast:

```php
$this->toast()->stacked()->success('Saved!')->send();
```

```js
$tsui.interaction('toast').stacked().success('Saved!').send();
```

The JavaScript API also gained `position` and `sole`, which the PHP side already had.
`position` is validated against the same allowed list, mirrored as a constant next to
the interaction class — the third home of that list, after the two the centered
positions already forced together.

Making the flag travel per event meant the render-time fork had to go: the template
always renders the pile bindings, and the Alpine state decides between them. With
`stacked` off the wrappers keep their `display: contents` classes and the style
bindings resolve to nothing, so the layout is still the plain list. A pile switched
off mid-flight nulls the inline styles it wrote, since a leftover `visibility: hidden`
would keep a buried card invisible in the list.

The semantics follow `position`: the last event wins for the whole container, and the
PHP side resolves the configuration default into every payload, so a toast that says
nothing falls back to the config. Each card now reports its height regardless of the
mode, which lets a pile switched on later find every height already known — and the
flashed toast applies its `position` and `stacked` too, which the flash path used to
ignore.

`toast-loop` no longer mirrors `stacked` into its own scope: reads fall through to the
pile's state, staying live when the mode flips.

### Migration

`wrapper.position` gained `x-center`, and a new `stack` group was added:

| Block                            | Purpose                                             |
|----------------------------------|-----------------------------------------------------|
| `wrapper.position.x-center`      | horizontal alignment for the centered positions     |
| `wrapper.position.top-on-mobile` | vertical alignment below `md` when the flag is on   |
| `stack.inert`               | `display: contents`, applied when `stacked` is off  |
| `stack.wrapper`             | the pile's box, which owns the hover area           |
| `stack.item`                | the positioned card inside the pile                 |
| `stack.content`             | opacity transition for what the pile hides          |
| `stack.align.*`             | `left`, `right` and `center` alignment of the pile   |

No existing block was renamed or removed, so nothing that targets the toast through
`TallStackUi::customize()` breaks. Applications overriding `wrapper.second` to change
horizontal alignment should know the centered positions now read
`wrapper.position.x-center` instead.

With `stacked` off, both pile wrappers render with `display: contents` and generate no
box, so the layout is the one that shipped before.

### Tests

`FeatureTest.php` covers the rendered markup: the six positions through both the fluent
method and the configuration, an unknown one rejected by each, and the switches being
off by default and taking effect when on. `StackedBrowserTest.php` covers what only a
browser can show — the pile expanding on hover, the buried cards losing their content,
every countdown held while it is open and resuming once the pointer leaves, and a
drained pile still accepting the next toast.

Turning `stacked` on for the Dusk server goes through `defineEnvironment`, together with
a `__ts_get_component_configuration(..., flush: true)`: the helper memoizes the
component configuration in a static that the service provider has already filled by the
time the server boots. It cannot go through `beforeServingApplication`, which Livewire
already claims to register the anonymous test components.

---

## Skeleton

### Added — `skeleton` on Card, Stats, Table, List, Step and Chart

A structural placeholder shaped like the component itself, for the first paint,
before any data exists. One prop, typed `bool|int`:

```blade
<x-card skeleton />                                  {{-- 3 body lines --}}
<x-card skeleton="5" image round="xl" />
<x-table :$headers skeleton="8" selectable paginate />
<x-list skeleton="6" searchable label="Tags" />
<x-step skeleton="4" circles />
<x-chart skeleton="8" type="bar" :height="240" />
<x-stats skeleton />
```

A bare flag uses the component's default count; an integer sets it. Nothing else
needs describing — column count, checkbox column, filter bar, pagination footer,
search input, label, hint, image block, footer, radius, variation, chart type and
height are all derived from props the component already has.

| Component | Unit                    | Default |
|-----------|-------------------------|---------|
| Card      | body lines              | 3       |
| Table     | rows                    | 5       |
| List      | items                   | 4       |
| Step      | step indicators         | 3       |
| Chart     | data points (or slices) | 6       |
| Stats     | —                       | n/a     |

`skeleton` on `Stats` is a flag only: passing an integer throws, because there is
nothing to count. Any integer below `1` throws on every component.

**This does not replace `loading`.** They cover different moments:

| State                           | Tool       | Situation                           |
|---------------------------------|------------|-------------------------------------|
| First paint, no data yet        | `skeleton` | `#[Lazy]` placeholder, initial load |
| Refetch, data already on screen | `loading`  | Sort, paginate, search, save        |

Swapping on-screen content for a skeleton during a refetch jumps the layout and
costs the user their visual anchor, so `loading` is left exactly as it was.

#### Where it belongs: the `#[Lazy]` placeholder

```php
#[Lazy]
class UsersTable extends Component
{
    public array $headers = [
        ['index' => 'name', 'label' => 'Name'],
        ['index' => 'email', 'label' => 'E-mail'],
    ];

    public function placeholder(): string
    {
        return <<<'HTML'
        <div>
            <x-table :$headers skeleton="5" />
        </div>
        HTML;
    }
}
```

Livewire skips `mount()` when rendering a placeholder but does hand the
component's **class-level property defaults** to that view. Headers declared as a
class default — the shape this library already documents — therefore survive into
the placeholder, and the skeleton draws the real column count and the real
labels. Headers assigned inside `mount()` do not, and the skeleton falls back to
four generic columns.

`<x-table>` normally requires the Livewire context and throws without it. In
skeleton mode that requirement is waived: a placeholder renders outside the
component's context, and a skeleton binds nothing to Livewire anyway.

#### What it does not do

`skeleton` does not defer anything. Blade evaluates slot content *before* the
component renders, so in:

```blade
<x-card :skeleton="$loading">
    @foreach ($users as $user) ... @endforeach
</x-card>
```

the loop has already run and the query has already hit the database. The
component can only discard the output. Deferral is Livewire's job, through
`#[Lazy]`; the skeleton is what gets drawn while it happens.

#### Appearance

Skeletons are always neutral: the `color` prop is ignored, and every bar is
`bg-gray-200` / `dark:bg-dark-600` under `animate-pulse`. No new CSS enters the
bundle. The root carries `aria-busy="true"` and `aria-live="polite"`.

For Chart, the placeholder runs the same geometry as a real plot
(`Series`, `Scale`, `Bars`, `Spline`, `Slices`) over invented values, so it lands
in the same `viewBox` with the same proportions. Everything that would let it be
misread as data — axis labels, legend, tooltip, markers, grid — is omitted.

`<x-stats>` omits its background chart layer in skeleton mode. That layer is
`absolute inset-0 -z-10`, so it takes no space in the flow and leaving it out
produces no layout shift.

### Added — soft customization blocks

Every component in scope gained a `skeleton.*` namespace, so the placeholder is
as customizable as the component:

```php
TallStackUi::customize()->table()->block('skeleton.bar', 'rounded-full bg-slate-100');
```

| Component | Blocks                                                                                                                        |
|-----------|-------------------------------------------------------------------------------------------------------------------------------|
| Card      | `animation`, `bar`, `header`, `image`, `body.wrapper`, `body.line`, `body.line-last`, `footer.wrapper`, `footer.button`        |
| Stats     | `animation`, `bar`, `icon`, `title`, `number`, `header`, `footer`                                                              |
| Table     | `animation`, `bar`, `cell`, `checkbox`, `expand`, `header`, `filter.quantity`, `filter.search`, `paginate.wrapper`, `paginate.bar` |
| List      | `animation`, `bar`, `label`, `hint`, `search`, `items.wrapper`, `items.row`, `items.content`, `name`, `caption`, `menu`        |
| Step      | `animation`, `bar`, `circle`, `panel-circle`, `simple-bar`, `title`, `description`, `content`, `helper`                        |
| Chart     | `animation`, `bar`, `fill`, `stroke`, `header`, `footer`                                                                       |

`animation` and `bar` are shared defaults (`animate-pulse` and
`dark:bg-dark-600 rounded bg-gray-200`) contributed by a common trait, then
merged into each component's own block set.

#### Existing customizations carry over

The `skeleton.*` blocks are only the bars. Everything structural is resolved
from the component's **own, existing blocks**, because the skeleton view calls
the same `classes()` as the normal one — customization is resolved on the
component, not on the view. A card whose `wrapper.second` lost its shadow, or
whose `body` gained padding, keeps that in skeleton mode, so the placeholder box
still matches the box it stands in for:

```php
TallStackUi::customize()->card()->block('wrapper.second', 'rounded-3xl bg-white');
```

```blade
<x-card skeleton />   {{-- rounded-3xl, exactly like the real card --}}
```

Scopes behave the same, including when they target the placeholder alone:

```php
TallStackUi::customize('card', scope: 'fancy')->block('skeleton.bar', 'rounded-full bg-slate-100');
```

```blade
<x-card skeleton scope="fancy" />
```

Blocks the skeleton does not render — `header.text.*`, `button.*`, `loading.*`,
`footer.base` and the `footer.{alignment}` set on Card, for instance — have
nothing to act on there. Customizing them is not an error; it simply has no
effect while the placeholder is on screen.

Structural blocks each skeleton reuses from its own component:

| Component | Reused blocks                                                                                                                                                      |
|-----------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Card      | `wrapper.first`, `wrapper.second`, `border.radius.*`, `header.wrapper.base`, `header.wrapper.border`, `body`, `body.paddingless`, `footer.wrapper`, `image.wrapper` |
| Stats     | `wrapper.first`, `wrapper.second`, `wrapper.second-no-header`, `wrapper.second-no-footer`, `slots.header.*`, `slots.footer.*`                                       |
| Table     | `wrapper`, `table.*`, `row.striped`, `filter.*`                                                                                                                    |
| List      | `wrapper`, `box`, `search.wrapper`, `items.scroll`, `items.height.*`                                                                                                |
| Step      | `wrapper.{variation}`, `panels-shape`, `circles.*`, `simple.*`, `panels.*`, `content`, `helpers.wrapper`                                                            |
| Chart     | `wrapper`, `plot.wrapper`, `plot.svg`, `plot.slice`, `axis.*.wrapper`                                                                                               |

---

## Chart

### Added — `<x-chart />`, a dependency-free chart

Five types rendered as inline SVG, with no charting library involved. Every
path, rectangle and arc is computed server-side and shipped as markup, so there
is nothing to hydrate and no flash before Alpine boots:

```blade
{{-- A flat list of numbers is a single unnamed series --}}
<x-chart :series="[10, 40, 25, 60, 30, 80]" />

{{-- Several named series share one scale, so they compare at a glance --}}
<x-chart :labels="['Jan', 'Fev', 'Mar', 'Abr']"
         :series="[
             ['name' => '2026', 'data' => [10, 40, 25, 60]],
             ['name' => '2025', 'data' => [8, 30, 33, 41]],
         ]"
         type="bar"
         grid
         legend
         tooltip
         prefix="R$ " />
```

Types: `area` (default), `line`, `bar`, `pie` and `donut`. Area and bar also
accept `stacked`. Chrome is opt-in through `grid`, `legend`, `tooltip` and
`markers`, and values are formatted with `prefix`, `suffix` and `decimals`.

`height` and the four chrome flags take an application-wide default from the
config, each overridable at the call site. `type` deliberately does not: a
dashboard mixes bars, lines and pies, so it stays a per-chart decision.

**Formatting beyond a prefix takes a closure**, because a locale or a currency
is a decision per chart rather than per application:

```blade
<x-chart :series="$revenue" grid :formatter="fn (float $value) => Number::currency($value, 'BRL', 'pt_BR')" />
```

It receives the axis as a second argument, wins over `prefix`/`suffix`/
`decimals`, and covers the axis labels and the tooltip alike — every displayed
number is formatted server-side, so nothing has to cross over to JavaScript.

**A series can bind itself to a secondary axis** with `'axis' => 'right'`, for
when its magnitude would flatten everything else against a shared scale. Both
axes are pinned to the same tick count, so one set of gridlines serves either
side. Formatting resolves per axis: a scalar `prefix`, `suffix` or `decimals`
applies to both, an array picks the side.

**Interaction runs on Pointer Events**, so it works from mouse, touch and pen.
On touch a tap opens the tooltip and a tap outside closes it; dragging is left
to the page, because capturing it would need `preventDefault` and lock scrolling
inside a chart that often fills a small screen.

The component ships **no card of its own**, so the same class serves a
standalone chart inside `<x-card paddingless>` and the background layer of
`<x-stats>`.

**Interpolation is monotone cubic (Fritsch-Carlson), not Catmull-Rom.** The
curve is guaranteed never to leave the range of the data it passes through. A
plain Catmull-Rom spline overshoots by up to 7% of the plot height on the common
flat-then-jump series, which on an area chart self-intersects the fill under its
own baseline and draws a value lower than the series minimum.

**Nothing textual or circular lives inside the SVG.** The plot stretches through
`preserveAspectRatio="none"`, which would distort both, so axis labels and point
markers are HTML positioned over it — and gain Tailwind typography and dark mode
in the process.

**Toggling a series in the legend rescales the rest through an SVG `transform`,
not a recomputed curve.** Rescaling a domain is an affine map in y and Beziers
are affine invariant, so the interpolation exists in exactly one place, in PHP.
Rescaling is skipped where it would mislead: on a labelled grid, on stacked or
bar charts, and whenever a secondary axis exists.

A pie is the exception: removing a slice redistributes every remaining angle,
which is a real recomputation rather than a transform, so its arc trigonometry
is mirrored in the Alpine layer and the tooltip percentages follow along.

**Livewire lazy loading works natively.** The markup arrives already drawn, and
hit-testing measures the element on the pointer event rather than on `init()`,
which is the usual failure mode for charting libraries mounted before their
container has a size.

Series longer than 120 points are bucketed down, keeping each bucket's lowest
and highest value in the order they appeared, so peaks and the scale anchors
always survive. The chosen indexes are shared across series so multiple curves
stay aligned.

An empty series renders the plot at full height with no path, so a card holding
it does not jump, and a single value spans the plot as a constant series, the
same as `[7, 7, 7]` would.

Everything else fails loudly rather than degrading: non-numeric values, `NAN`
and `INF`, an unknown `type` or `axis`, `stacked` on a line or radial type,
`stacked` alongside a secondary axis, `grid` on a radial type, a negative or
non-integer `decimals`, and a formatting array keyed by anything other than
`left` and `right`. The last one is the quietest of them — an unrecognized key
used to be dropped without a word, which reads as if it had worked.

Full reference in `.ai/components/chart.md`.

### Added — bars and curves in the same chart

A series can declare a `type` of its own, which is what puts a trend line over
a stack of bars:

```blade
<x-chart :labels="$months"
         type="bar"
         stacked
         :series="[
             ['name' => 'Novos', 'data' => $new],
             ['name' => 'Recorrentes', 'data' => $returning],
             ['name' => 'Total', 'data' => $total, 'type' => 'line'],
         ]"
         grid
         legend
         tooltip />
```

It accepts `area`, `line` and `bar`, falls back to the chart's own `type`, and
is refused on a radial chart. Everything else stays where it was: the override
sits next to `axis` in the same series entry, and a chart that declares none
renders exactly as before.

**A single bar anywhere divides the horizontal axis into slots.** A curve owns
the full width and puts its ends on the edges; a bar owns a slot and is read
from the middle of it. Mixed, the slot wins for everything — the curve, the
axis captions, the crosshair and the pointer — because a curve left on the
edges reads half a slot out of line with the bars underneath it. That decision
is a single flag shared by the geometry in PHP and the hit testing in Alpine,
so the two cannot disagree.

**Stacking accumulates within each type.** Bars pile onto bars, areas onto
areas, and anything drawn over them keeps its own values, so the running total
never lifts a line off the number it is reporting. The total line above is a
series you pass rather than one derived behind your back: it appears in the
legend, toggles like the rest, and shows up in the tooltip next to the bars it
sums.

Legend rescaling is disabled while a bar is on the plot, on top of the cases
that already disabled it.

### Fixed — a pie dropped every series but the first

A radial type draws `$series[0]` and nothing else, so a pie built from grouped
series — the shape every other type takes — rendered half its data and said
nothing. It now throws `The [pie] type accepts only one series.`, which is what
the rest of the component already did for anything it could not draw.

### Fixed — a negative value in a stack was painted over the positive ones

`Bars::offsets()` kept one running total per group, so a negative value pulled
that total down and the next segment started from a lower base. Drawn, the
negative segment landed above the axis on top of the positive ones, visually
indistinguishable from a positive of its own.

Each group now accumulates per sign: positives pile up from zero, negatives
hang below it, and the domain reaches both ends. Corner rounding follows,
resolved per index rather than per series, and the axis counts as an end only
while the column stops there — carried past zero it is a seam like any other,
so the two rounded ends are the extremes of the whole column.

### Fixed — a stacked column showed the card through its own seams

Bars were `<rect rx="0.6">`, and `rx` rounds all four corners at once. Stacked,
the segment above rounded its base while the one below rounded its top, so the
two arcs pulled apart at both edges and the card showed through the gap. Under
`preserveAspectRatio="none"` the radius is stretched with the plot, which made
each notch about 4px wide against 2px tall — wide, shallow, and impossible to
miss once seen.

Bars are now paths with per-corner control, so only the two ends of a column
round and the seams between segments meet flush. An unstacked bar keeps all
four corners, and the radius shrinks to fit whatever it is applied to, so the
hairline a zero value renders as cannot fold through itself. That hairline is
also not what ends a column: counted as an end it would take the rounding onto
a sliver and leave the visible segment above it square. The skeleton draws its
bars from the same geometry and picked up the same corners.

### Fixed — a sparkline reserved room for axes it was not drawing

The axis wrappers were meant to collapse when empty — `pr-2 empty:pr-0` on the
left, `pl-2 empty:pl-0` on the right, `h-4 empty:h-0` under the plot — but the
template left whitespace between the tags, and `:empty` matches only an element
with no child nodes at all: a whitespace text node is one, in every browser.
The collapse never happened, so a chart without `grid` — the sparkline in a
card, the background layer of `<x-stats>` — kept an 8px dead column on either
side and a 16px strip at the bottom, and the curve stopped short of the edges
it was computed to touch.

The tags hug the content now, the same way the skeleton view already did, so
an axis with nothing to show is truly empty and the plot bleeds to the edges.

### Changed — the chart ships in its own bundle

`js/tallstackui-chart.js` joined the entry points, weighing 3.9 kB, 1.6 kB
gzipped. Same reasoning as the editor and upload splits: not lazy loading —
`Directives::script()` emits every entry of the manifest on every page — but
cache granularity, so a change to the chart stops invalidating the bundle every
other component lives in. What a static chart skips is the work, not the bytes:
no `tallstackui_chart` instance is created without `tooltip` or `legend`.

## Floating

### Added — `floating_scroll_lock`, locking the page scroll while a popup is open

A modal locks the page behind it; a dropdown never did, so the content under an
open popup kept scrolling while the popup stayed anchored where it was. The lock
is now available to every component built on `<x-floating>`, through a single
top-level key rather than one per component:

```php
// config/tallstackui.php
'floating_scroll_lock' => true,
```

It reaches Dropdown and its Submenu, Autocomplete, Color, Date, Password, Select
Styled, Time, Upload, Calendar and the List Items menu at once. The mechanics are
the ones Modal and Slide already use: `overflow: hidden` on the `<body>` plus the
compensating `padding-right`.

Off by default, and there is no per-instance opt out. Locking the page reads very
differently on a three-item dropdown than it does on a modal: the scrollbar
disappearing from under the cursor is a lot of movement to trade for a menu —
enabling it is a deliberate choice about how the whole application should feel, not
a per-call-site one.

**Nested and stacked popups share a single lock.** A Dropdown Submenu renders a
floating of its own inside its parent, so a naive implementation would re-lock on
open and unlock on close, dropping the lock while the parent menu was still on
screen. References are counted in `window.__tsui_floating_locks`: the first popup
to open takes the lock, the last to close returns it.

The release also covers the paths that never run a close — a floating torn out of
the DOM by a Livewire morph or a collapsing `@if`, and an anchor leaving layout on
a Tab swap or an Accordion collapse.

**A popup opened inside a Modal or a Slide does not touch the lock.** The overlay
already owns it, and closing the popup leaves the body locked.

Floatings are deliberately kept out of `window.__tsui_elements`, the registry
behind `top_ui_element()`. Joining it would have given a refcount for free, but it
would also have made an open dropdown the topmost element, taking `Escape` and
click-outside away from the modal behind it — a behavioural change well outside
what a scroll-lock flag should carry.

**Migration:** nothing. The flag defaults to off and no customization block
changed.

### Fixed — an inner element could unlock a body it did not lock

`overflow()` let any component release the lock regardless of which one had taken
it. The gate for restoring the body tested `__tsui_elements.length === 1`, which a
single open overlay satisfies — so a Loading or an Upload preview closing inside
an open Modal restored the page scroll with the modal still on screen.

Releasing now requires owning the `data-overflow` marker, and the reset itself
requires that no other overlay is still registered. This predates the floating
work; the lock is reachable from more places now, which is what surfaced it.

---

## Modal, Slide, Card & Errors

### Added — footer slot alignment through `start`, `center`, `end`, `between` and `unwrapped`

The footer is where actions live, and until now the way they were distributed was
decided by the component, differently in each one. Modal always pushed them to
the right. Card pushed them right only when the footer came in as a string, and
left a `<x-slot:footer>` untouched. Slide understood `start` and `end` but
defaulted to neither. Errors understood `end` alone. The four now read the
alignment from the slot itself, through the same attributes.

```blade
<x-modal>
    Content

    <x-slot:footer between>
        <x-button color="red">Delete</x-button>
        <x-button>Save</x-button>
    </x-slot:footer>
</x-modal>
```

Five attributes, identical across the four:

| Attribute   | Result                                     |
|-------------|--------------------------------------------|
| *(none)*    | `justify-end` — the previous Modal default |
| `start`     | `justify-start`                            |
| `center`    | `justify-center`                           |
| `end`       | `justify-end`, written out                 |
| `between`   | `justify-between`                          |
| `unwrapped` | no aligning wrapper at all                 |

`unwrapped` drops the flex wrapper and nothing else: the footer area keeps its
border, its padding and its margin, and the slot content becomes their direct
child. It is for footers that lay themselves out — a grid, a full-width bar, a
form row that has its own idea of spacing.

Combining alignments, or mixing one with `unwrapped`, throws.

Every other attribute on the slot — `class`, `x-on:*`, `dusk` — is merged into
the footer container. Slide already did this and leaked the alignment keywords
into the markup as `start="start"`; the keywords are now stripped, and the other
three gained the merge they never had.

Alignment applies to the slot form only. A footer passed as a string attribute
carries no attributes to read, and keeps whatever the component already did with
it — the end-aligned row on Modal, Slide and Card, the plain paragraph on Errors.

The resolution lives in `AbstractRuntime::alignment()` and
`AbstractRuntime::alignable()`, so the two components still carrying an unaligned
footer — Table and Stats — can adopt the same attributes without repeating it.

**Migration:** the footer blocks were split.

| Component | Before          | After                                                                    |
|-----------|-----------------|--------------------------------------------------------------------------|
| Modal     | `footer`        | `footer.wrapper` (border, padding, color) + `footer.base` (`flex gap-2`) |
| Slide     | `footer.base`   | `footer.wrapper` (border, padding) + `footer.base` (`flex gap-2`)        |
| Card      | `footer.text`   | `footer.base` (`flex items-center gap-2`)                                |
| Errors    | `slots.footer`  | `slots.footer.wrapper` (margin) + `slots.footer.base` (`flex gap-2`)     |

All four gained the four alignment blocks under the same prefix — `footer.start`
and siblings, `slots.footer.start` and siblings on Errors. `footer.scrollable` on
Modal and `footer.wrapper` on Card are unchanged. An application customizing
`modal.footer`, `slide.footer.base`, `card.footer.text` or `errors.slots.footer`
has to point at the new block, choosing between the chrome and the alignment row.

Rendering changed in four ways:

- Modal, Slide and Errors footers now nest an extra `<div>` for the alignment.
- A Card footer passed as `<x-slot:footer>` is aligned to the end instead of
  falling through raw — the behaviour a string footer already had. A Card relying
  on that raw fall-through wants `unwrapped`.
- A Slide footer with no attribute used to sit at the start, since the base block
  carried `flex` with no `justify-*`. It now defaults to the end, and `start`
  restores the old look. The Slide base also gained `gap-2`, which it lacked
  while Modal and Card had it.
- An Errors footer slot with no attribute used to render raw, with no container
  at all. It now gets the `mt-2` container and the end alignment. `unwrapped`
  brings back the container without the alignment; the fully raw output is gone.

---

## Modal, Slide, Card & Tab

### Added — `paddingless`, removing the padding of the main slot

The four components that wrap content in a padded area now share a flag that
strips that padding, leaving the slot flush against the edges of the component.
It is what a table, an image or a nested list wants: whatever draws its own
spacing, or is meant to bleed.

```blade
<x-card paddingless>
    <x-table :$headers :$rows />
</x-card>
```

Only the main slot is affected. Headers and footers keep their padding, so a
titled modal holding a flush body still reads as a modal.

The flag maps to a new customization block per component — `body.paddingless`
on Modal, Slide and Card, `base.content-paddingless` on Tab — carrying `p-0!`.
The block is appended to the existing one rather than replacing it, so the
importance modifier is what wins over the padding already there. An application
customizing `body` is unaffected, and one that wants the flush body everywhere
can reach for the new block instead of passing the flag at every call site.

On Slide the result is flush horizontally but not vertically: the `py-6` that
insets the body lives on the outer panel, shared with the header and the footer,
and removing it would move all three. Vertical bleed on a Slide is a soft
customization of `wrapper.fifth`.

**Migration:** nothing. The flag defaults to off and no existing block changed.

---

## Editor

### Added — `<x-editor />`, a WYSIWYG editor with no external dependency

A rich text editor built on `contenteditable`, shipping nothing but the package
itself. It outputs HTML, binds through `wire:model` or through a plain `name`,
and carries twenty buttons across eight groups: headings, the four inline marks,
lists, indentation, alignment, quotes, rules, code, links, images, history and
fullscreen.

```blade
<x-editor wire:model="content" label="Post body" />
```

```blade
<x-editor wire:model="content"
          upload-property="picture"
          upload-method="storeImage"
          :toolbar="['style', 'bold', 'italic', 'link', 'image']"
          min-height="20rem" />
```

**The engine is a hybrid.** `document.execCommand` where the browsers agree, and
the Selection API by hand where they do not. Every structural change is routed
through `insertHTML` rather than through the DOM, because a node inserted by hand
is invisible to the browser's own undo stack and `Ctrl+Z` would walk straight past
it.

**Indentation is two different things.** Inside a list the browser nests, which is
the right shape there. Anywhere else it is a `margin-left` on the block, in steps
of `2rem` up to eight levels — the native command reaches for a `<blockquote>`
there, which is a quote rather than an indent and would be stripped by the
sanitizer on the way back in. That margin is the one action that stays outside the
undo stack: re-serializing the block to get it in there would drop the caret.

**Both dialogs are `<x-modal>` instances**, under the fixed scopes `editor.modal.link`
and `editor.modal.image`. The modal already owns the scroll lock, the overlay registry,
`Escape` with its topmost guard and the focus of its first field, and being
teleported to `<body>` takes its inputs out of any surrounding `<form>`, where
`Enter` would otherwise submit it. The toolbar dropdowns are `<x-dropdown>` under
`editor.toolbar`. What is left in the editor's own surface is the content: 40
blocks rather than the 55 a self-contained dialog would have needed.

**The whole component is `wire:ignore`d under Livewire**, and nothing about it
reacts to the server as a result: changing `readonly` or any other attribute
from a round trip leaves the rendered editor as it was, so a runtime change
needs a `wire:key` to force the replacement. The content travels
through the entangle, never through the HTML the server re-renders — and the
initial value is withheld from the `x-data` when a property is bound, since a
changing `x-data` attribute makes Alpine tear the component down and rebuild it,
which lands the caret back at the start of the document.

**The HTML is sanitized against a whitelist** of tags, attributes and style
properties, parsed in a `<template>` so nothing runs on the way through. It runs
over pasted markup and over anything arriving from the bound property, the value
the editor boots with included: setting `innerHTML` never runs a `<script>`, but
it does fire an `<img onerror>`, and stored content is the path that reaches
every reader. This is still defense in depth — the documentation is explicit that
the HTML must be sanitized again on the server before it is persisted and before
it is rendered back.

`image/svg+xml` is deliberately absent from the default upload mimes: SVG can
carry script, and a package default should not open that on its own.

**Nineteen internal icons** were added to the guide and to the published icon
map, so an application swapping its icon set keeps the toolbar working.

Full reference in `.ai/components/editor.md`.

### Changed — the editor ships in its own bundle

`js/tallstackui-editor.js` joined the entry points, weighing 11.5 kB, 3.7 kB
gzipped. Same reasoning as the upload split: not lazy loading, but cache
granularity, so a change to the editor stops invalidating the bundle every other
component lives in.

### Fixed — the sanitizer discarded alignment on pasted paragraphs

`allowed_attributes` let `style` through on `span` and `div` but not on `p` or the
headings, while the browser writes `text-align` straight onto the paragraph.
Copying a centered paragraph and pasting it back lost the alignment. `style` is
now allowed on `p`, `h1`–`h5` and `li`, which cannot widen the surface: the
`allowed_styles` whitelist is applied afterwards, so only `font-size`,
`text-align` and `margin-left` survive on any of them.

### Added — `markdown`, storing Markdown instead of HTML

The editing surface does not change: it stays a WYSIWYG, and bold text still
looks bold while it is written. Markdown is a serialization format at the
boundary, so the property receives `**bold**` where it would otherwise receive
`<strong>bold</strong>`. An initial value is read as Markdown too.

```blade
<x-editor wire:model="content" markdown />
```

Turn it on for every editor at once through the `markdown` key in the config.
Conversion happens in the browser, in two hand-written modules of roughly two
hundred lines each, so nothing is added to the dependency tree: the editor
bundle grows from 11.4 kB to 18.4 kB, 6.2 kB gzipped.

Covered in both directions: `h1`–`h5`, paragraphs, `strong`, `em`, `s`, inline
code, fenced code, ordered and unordered lists including nesting, blockquotes,
horizontal rules, links, images and hard breaks. Tables, task lists and
footnotes are not.

`underline` and `align` have no Markdown syntax, so they are dropped from the
toolbar along with their shortcut. They are removed quietly rather than refused,
so a global `markdown` in the config does not invalidate an app-wide toolbar; a
slug the component does not know still throws. `indent` and `outdent` survive,
but only inside a list, where they nest: outside one they write a `margin-left`
that would be lost on the next sync, so they do nothing.

`editor:change` keeps its `html` key and gains a `markdown` one while the mode
is on, so nothing already listening breaks.

### Added — `blockquote` and `hr` toolbar buttons

Two constructs Markdown names and the editor had no way to write, in either
mode. `blockquote` serializes to `> ` and `hr` to `---`. Both tags joined the
sanitization whitelist and both carry a customization block of their own,
`editable.typography.quote` and `editable.typography.rule`.

They also joined the default toolbar, which now holds twenty buttons across
eight groups. An application pinning its own `toolbar` array is unaffected.

### Added — Markdown autoformat while typing

While `markdown` is on, the syntax is applied as it is typed: `# `, `## ` and
`### ` open a heading, `- ` and `* ` a bulleted list, `1. ` a numbered one, `> `
a quote, `---` and a triple backtick followed by Enter a rule and a code block,
and `**text**`, `*text*`, `` `text` `` and `~~text~~` their inline marks.

Every transform goes through the same command the toolbar uses, so it lands in
the browser's undo stack: Ctrl+Z right after one reverts the formatting and
leaves the characters that were typed. That is the way out when the marker was
meant literally. Inside a code block nothing is transformed, since there the
syntax is the content.

### Changed — pasting text into a Markdown editor reads it as Markdown

A clipboard carrying structured HTML is sanitized and inserted as rich content,
exactly as before, in both modes. While `markdown` is on, a clipboard carrying
no structure is parsed as Markdown instead, so pasting the contents of a `.md`
file arrives formatted rather than as literal characters. The parsed result
still passes through the sanitizer.

"No structure" means holding none of the tags the serializer can name — a
heading, a list, a quote, a fence, a rule, a link, an image or an inline mark.
The distinction matters because a code editor ships its syntax highlighting as a
`text/html` flavour of nested `<span style>`: the flavour being present is not
the same as the clipboard carrying structure, and reading it as rich content is
what would make a pasted `.md` file arrive as literal characters anyway.

Ordinary prose is left alone. An isolated `*`, an `A-B-C`, a `snake_case_name`
and a URL holding underscores all survive untouched. What does change is a line
opening with `- ` or `1. `, which becomes the list it reads as.

**Migration:** nothing, unless an application relied on pasting raw Markdown
into a Markdown editor and getting literal characters back.

### Fixed — the counters measured `innerText`, which lies twice

`innerText` writes two breaks between paragraphs and one more for the filler
`<br>` engines keep inside an empty block, so "teste" followed by Enter counted
three lines where the screen shows two, and a document booted as
`<p>foo</p><p>bar</p>` counted three. Behind `x-cloak` — which is exactly where
the boot count runs — `innerText` degrades to `textContent`, which holds no
breaks at all: the same document booted as one line, and its words fused across
the block boundary into `foobar`, one word.

Both counters now read off the DOM: one line per block, plus one per `<br>`
that actually ends a line, and the text with a break at every block boundary.
A trailing empty paragraph counts once, a paragraph boundary counts once, and
the count is the same whether it runs at boot or mid-typing.

### Fixed — the toolbar took the mouse but not the keyboard

Every button acted on `x-on:mousedown.prevent="..."` — the prevent is what keeps
the selection in the editable while the mouse clicks — so the roving tabindex
walked the buttons and Enter did nothing on any of them. The actions moved to
`click`, which both the mouse and the keyboard raise, and `mousedown` keeps only
the prevent. The dropdown entries listen on `keydown.enter` instead, since their
`click` already belongs to the dropdown's own close-on-select.

### Fixed — a `javascript:` destination survived the sanitizer

The whitelist filters attributes by name, and `href` and `src` are legitimate
names: `<a href="javascript:alert(1)">` passed through untouched, on paste and
on boot. The scheme is now checked on both attributes — `javascript:`,
`vbscript:` and `data:` are dropped, with `data:image/` kept on an `img` src,
where the image dialog already accepts it. Whitespace is stripped before the
check, since `jav&#x09;ascript:` decodes to a scheme the browser runs and a
prefix test misses. The link dialog refuses the same schemes, disabling its
insert button.

This stays defense in depth: the server must still sanitize before persisting
and before rendering back.

### Fixed — a parenthesis in a link destination broke the Markdown round trip

The serializer wrote `href` and `src` verbatim into `[text](...)`, where `)`
closes the destination early: a Wikipedia-style URL — `.../Foo_(bar)` — came
back truncated, with the leftover parenthesis as text. Destinations now
percent-encode `(`, `)` and whitespace, which the browser reads identically,
and an image `alt` rides the same inline escapes the link text already had.

### Changed — the style dropdown slimmed down

The panel holds four short entries and followed the generic `sm` width
(`w-48`). It is `xs` (`w-40`) now. Longer locales — `Überschrift 1` — wrap
rather than clip.

### Added — Enter in the image dialog's URL field inserts

The link dialog already submitted on Enter; the image dialog now does the same,
guarded by the same URL validation as its insert button.

### Fixed — the caret opened at minimum height beside the placeholder

An editor booted empty set the editable's `innerHTML` to an empty string, and a
contenteditable with no line box draws its caret at a minimum height — a short
blinking bar next to a full-size "Start writing…". Engines avoid this themselves
by keeping a filler `<br>` once the field has been typed in; the editor now
seeds the same filler on boot and whenever the bound property is cleared. Every
filler shape reads as an empty document on the way out, so the seed never
reaches the bound property: it stays `''`, not `'<br>'`.

### Added — the toolbar shows where the keyboard is

The roving tabindex moved the focus between the buttons, but nothing painted
it: reaching the toolbar with Shift+Tab landed on a button that looked exactly
like its neighbours. The buttons and the dropdown triggers now carry a
`focus-visible` ring — inset, so the scrolling toolbar does not clip it — in
the two blocks that already styled them, `toolbar.button.base` and
`toolbar.dropdown.trigger`.

### Fixed — opening a dialog on a small screen jolted it before it settled

The dialogs focus their first field as soon as they open. Under the `sm`
breakpoint the panel enters as a bottom sheet, translated below the screen for
the length of the transition — and focusing a field that is still off-screen
makes the browser scroll the dialog's wrapper to reveal it. The panel snapped
up, drifted back down as that scroll unwound alongside the animation, then
settled: a three-beat stutter on every open, and on iOS a residual offset that
left the sheet floating mid-screen. The focus now passes `preventScroll`, which
is enough — the panel ends its transition fully visible, field and all.

---

## Form / Radio & Checkbox / Group

### Added — `<x-radio.group />` and `<x-checkbox.group />`, a whole set of options from one array

`<x-radio />` and `<x-checkbox />` render a single control. Anything resembling a
plan picker, a segmented control or a feature list meant writing the loop, the
`<label>`, the wrapper and the selected-state classes by hand, every time. Two new
components take the array instead, and are otherwise unrelated to the singular ones,
which are untouched:

```blade
<x-radio.group wire:model="plan" label="Plan" :options="[
    ['label' => 'Startup', 'value' => 'startup', 'description' => 'Up to 5 job postings', 'aside' => '$29 / mo'],
    ['label' => 'Business', 'value' => 'business', 'description' => 'Up to 25 job postings', 'aside' => '$99 / mo'],
]" />

<x-checkbox.group wire:model="features" card :columns="2" color="green" :options="$features" />
```

Extending `<x-radio />` with an `options` attribute was considered and rejected: the
singular component is one input inside a `<x-wrapper.radio>`, while a group is a
`<fieldset>` with a `<legend>` and its own layout, and the two share no markup. The
attribute would have been a second component hiding inside the first.

### Added — four presentations, selected by flag

```blade
<x-radio.group panel :options="$options" />
```

| Variant  | Layout                                        | Control     |
|----------|-----------------------------------------------|-------------|
| `list`   | Stacked rows sharing borders                  | Visible     |
| `card`   | Independent cards in a responsive grid        | Visible     |
| `panel`  | Cards with a check icon marking the selection | `sr-only`   |
| `inline` | Horizontal segmented control with solid fill  | `sr-only`   |

Each presentation is a boolean flag rather than a `variant="panel"` string, matching
how `<x-gallery>` and `<x-carousel>` already read. `variant` survives as an internal
`#[SkipDebug]` property resolved in `SelectionSetup::setup()`, so the templates keep
a single string to switch on.

Passing none renders `list`. Passing more than one resolves to the first of `card`,
`panel`, `inline` — silent precedence, the same rule `xs`/`sm`/`lg` already follow in
the same trait. Throwing on the combination was considered; it would have been the
only size-style flag group in the library that does.

`panel` is `card` plus an `sr-only` control and a check icon. It deliberately does
**not** thicken the border when selected: a `border-2` on the checked state shifts
the card's content by a pixel, and a grid of panels visibly twitches as the selection
moves across it.

`inline` drops `description`, `aside`, `image` and `badge` — a segmented control has
no room for them, and rendering them would break the row rather than merely look
crowded. They are ignored, not rejected, so the same `$options` array can be handed
to any variant.

### Added — selected state with no JavaScript at all

The whole selected appearance is CSS, through the `has-checked` and
`group-has-checked` Tailwind variants:

```html
<label class="group ... has-checked:bg-primary-50 has-checked:border-primary-500">
    <input type="radio" value="business">
    <span class="group-has-checked:text-primary-900">Business</span>
</label>
```

`has-*` styles the `<label>` from the state of the input inside it; `group-has-*`
reaches the descendants that are not the input's siblings. Neither needs `peer`,
which only walks forward from a sibling and could not reach the wrapper.

No `x-data`, no Alpine component, no new entry in the bundle — only
`dist/tallstackui.css` grew. Which also means the groups work identically in plain
Blade and under Livewire, and that a `wire:model` round trip cannot desynchronize the
highlight from the checked input, because the highlight *is* the checked input.

`has-focus-visible` puts the focus ring on the `<label>` rather than the control, so
the `sr-only` variants stay keyboard-navigable: the control keeps its place in the
tab order and toggles with Space, and the ring is drawn around what the user actually
sees.

### Added — the option array, and an escape hatch when it is not enough

| Key           | Type   | Required | Ignored by |
|---------------|--------|----------|------------|
| `label`       | string | yes      | —          |
| `value`       | scalar | yes      | —          |
| `description` | string | no       | `inline`   |
| `aside`       | string | no       | `inline`   |
| `icon`        | string | no       | —          |
| `image`       | string | no       | `inline`   |
| `badge`       | string | no       | `inline`   |
| `disabled`    | bool   | no       | —          |

`image` wins over `icon` when both are present. A missing `label` or `value`, or an
option that is not an array, throws rather than rendering an empty row.

`select` remaps the source keys with the syntax `<x-select.styled>` already uses, so
an array coming from the database does not have to be reshaped first:

```blade
<x-radio.group select="label:name|value:id|description:note" :options="$plans" />
```

`@interact('option', $option)` replaces the body of every item while the `<label>`,
the `<input>` and the selected-state classes stay owned by the component. The closure
receives the option with its **original** keys still reachable, since normalization
spreads the source array before writing the canonical keys over it:

```blade
<x-checkbox.group card :options="$addons">
    @interact('option', $option)
        <span class="font-semibold">{{ $option['name'] }}</span>
        <span class="font-mono">${{ $option['price'] }}</span>
    @endinteract
</x-checkbox.group>
```

The loop variable inside the item template is `$item`, not `$option`, precisely so it
cannot collide with the slot variable the directive introduces.

### Added — one shared `name`, derived when it is not given

Every input carries the same `name`, falling back to `id` and then to the bound
property, suffixed with `[]` on checkbox groups:

```html
<input type="radio"    id="plan-0"     name="plan">
<input type="checkbox" id="features-0" name="features[]">
```

Without it a radio group is not a group. In plain Blade the options stop being
mutually exclusive, and — more subtly — under Livewire a `required` group inside a
`<form wire:submit>` never submits: each input is its own constraint-validation
group, so the browser demands all of them be checked and blocks the submit event
before Livewire's listener ever runs.

`required` behaves differently across the two components, which is why the attribute
tables differ. On the radio group it marks the legend **and** sets the native
attribute. On the checkbox group it marks the legend only: the native attribute there
would demand every box be ticked.

Out of Livewire, `value` drives the checked state — a scalar for radio, an array for
checkbox. Inside Livewire it is ignored, since the bound property is the source of
truth.

### Added — `SelectionColors`, six palettes across 29 colors

`color` drives more than one thing, so the class exposes six palettes rather than the
usual two:

| Palette      | Drives                                                  |
|--------------|---------------------------------------------------------|
| `background` | The selected item's background                          |
| `border`     | The selected item's border                              |
| `control`    | The input itself, and its focus ring                    |
| `text`       | The selected label and icon                             |
| `muted`      | The selected description and aside                      |
| `solid`      | The fill of a selected segment on `inline`              |

`muted` exists because a single `text` palette rendered the label and the description
in the same tint, which flattened the row — the description has to stay secondary
after selection, not just before it. `solid` carries only the background: the label
on top of it switches to white through the `content.inline` block, since
`text-primary-900` on `bg-primary-500` is unreadable.

One class serves both components, the way `ProgressColors` and `TimelineColors`
already serve two each. `SetupColors` resolves a published override through the
`class_basename` of the `#[ColorsThroughOf]` argument, so a single
`SelectionColors.php` in an application customizes both groups at once.

### Changed — the radio and checkbox views moved into their own directories

```
form/radio.blade.php     →  form/radio/main.blade.php
form/checkbox.blade.php  →  form/checkbox/main.blade.php
                            form/radio/group/{main,item}.blade.php
                            form/checkbox/group/{main,item}.blade.php
```

This follows the `main.blade.php` convention the package already uses for `card`,
`dropdown`, `modal`, `timeline` and others. The group templates started life in a
shared `form/selection-group/` directory rendering both components from one copy;
they were split so each component owns its markup, at the cost of `item.blade.php`
existing twice.

**No migration.** Customization block names, scope names and the component classes are
unchanged; only the view paths behind them moved, and nothing publishes these views.

### Notes on internals

`SelectionSetup` normalizes the options and resolves `variant`, `size`, `position`
and the `select` mapping. `SelectionCustomization` returns the block tree, taking the
control's shape (`form-radio rounded-full` / `form-checkbox rounded`) as its only
argument. `SelectionGroupRuntime` resolves the legend, the shared `name`, the
out-of-Livewire selection and a per-variant render profile — that last one is what
lets a single `item.blade.php` serve all four presentations instead of four
near-identical partials. Each template keeps a single `@php` block.

The `<legend>` renders its text directly rather than nesting a `<x-label>`: a
`<label>` with no control inside a `<legend>` is invalid, so the group carries its own
`wrapper.legend`, `wrapper.legend-error` and `wrapper.asterisk` blocks mirroring the
Label's styling. The `label="Plan *"` asterisk convention is preserved.

Attributes are routed rather than merged wholesale — `class` lands on the
`<fieldset>`, everything else (`wire:model`, `x-on:*`, `data-*`) lands on every
`<input>`.

Customization blocks: `wrapper.*`, `container.*`, `columns.*`, `item.*`, `control.*`,
`check` and `content.*`. Internal scopes `form.radio.group.{hint,error}` and
`form.checkbox.group.{hint,error}`.

Covered by 52 feature tests and 6 browser tests. Full reference in
`.ai/components/form/radio/group.md` and `.ai/components/form/checkbox/group.md`.

---

## Gallery

### Fixed — a tile without `ratio` raised a PHP 8.4 deprecation

The tile view used `$ratioClass` directly as an `@class` array key. Without a
`ratio` the value is `null`, and PHP 8.4 deprecates null array offsets — six
warnings per render. The key now falls back to an empty string, which
`Arr::toCssClasses()` discards.

### Fixed — soft customization was unreachable

`<x-gallery>` carries `#[SoftCustomization('gallery')]` and declares its blocks, but
`Customization` never gained the matching fluent method, so every documented entry
point threw `RuntimeException: The method [gallery] is not supported`:

```php
TallStackUi::customize()->gallery()->block('lightbox.image', '...');
TallStackUi::customize('gallery', scope: 'compact')->block('grid.item', '...');
```

Both work now. `tests/Feature/Structure/CustomizationTest.php` derives its coverage
from the `#[SoftCustomization]` attribute instead of a hand-kept list, so the next
component cannot ship with its customization entry point missing.

### Added — `<x-gallery />`, an image gallery with three layouts and a shared lightbox

One component covering the three arrangements an image gallery usually needs,
selected by mutually exclusive boolean flags rather than a string attribute, which
matches how `<x-carousel>` already reads:

```blade
<x-gallery :images="$images" />                          {{-- grid, the fallback --}}
<x-gallery masonry :columns="4" :images="$images" />
<x-gallery feature :limit="7" :images="$images" />
```

`grid` lays out uniform tiles whose shape comes from `ratio` (`square`, `video`,
`portrait`). `masonry` uses CSS multi-column, so each image keeps its natural
height; reading order runs down each column before moving to the next, which is
inherent to multi-column and the reason the CSS Grid alternative was rejected — it
would require a known aspect ratio for every image.

`feature` renders one cover above a thumbnail row. The cover is the entry flagged
`'cover' => true`, falling back to the first, the same rule `<x-carousel>` uses.
When the array holds more images than `limit`, the last thumbnail gets a `+N`
overlay where `N = count($images) - $limit`; clicking it opens the lightbox at that
image and the arrows traverse the whole array, so nothing is silently dropped.
When the array is shorter than `limit`, the row simply renders fewer tiles.

`thumbnails` moves that row beside the cover for product-page layouts:

```blade
<x-gallery feature thumbnails="left" ratio="square" :limit="5" :images="$images" class="max-w-md" />
```

Below `sm` the thumbnails always wrap under the cover, so the arrangement stays
usable on mobile.

The side column is locked to the cover's height instead of growing past it, which
is what a product page expects. It is absolutely positioned with `inset-y-0`, so it
inherits the height the cover sets, and scrolls inside with `custom-scrollbar`; the
cover reserves the space with `ml-26`/`mr-26`, the column's `w-24` plus the `gap-2`.
Raising `limit` adds scrollable thumbnails rather than a column taller than the
image beside it.

Flexbox alternatives were tried and discarded: `h-0` plus `min-h-full` on a flex
item collapses the column, because a percentage `min-height` needs a resolved
height on the parent and a flex container without an explicit height gives it
`auto` — with `overflow-y-auto` on top, the thumbnails disappear entirely.

### Added — an opt-in lightbox mirroring the Carousel

`clickable` expands a tile fullscreen through an overlay teleported to the `<body>`,
with a close button, `Esc`, backdrop click, and — with `navigable` — side arrows
and the `←`/`→` keys. `caption` renders the entry's `title`/`description` as
`overlay` or `footer`, and `without-loop` stops the traversal at both ends. Events
`expand`, `collapse`, `next` and `previous` fire on the root element.

The behaviour, the scroll-lock through `overflow()`, the overlay stacking through
`register_ui_element`/`top_ui_element`, and the teardown that releases an orphaned
lock are a deliberate mirror of `Carousel/alpine.js`. It was implemented inside
`Gallery/` rather than extracted from the Carousel: extraction would have moved the
Carousel's `clickable.*` customization blocks and broken every application
targeting them, for no gain in this release.

Without `clickable` the component emits no JavaScript at all — no `x-data`, no
teleported template. Tiles fall back to the `url`/`target` from the array, or to
plain images.

### Added — sizing through merged attributes and a `height` attribute

Attributes from the consumer are merged onto the root element, so `class`, `id`,
`style`, `data-*` and Livewire directives reach it:

```blade
<x-gallery feature ratio="square" :limit="5" :images="$images" class="max-w-md" />
```

`height` caps the tile area and scrolls inside it, taking the same enumerated
values as `<x-list>` — `40`, `60`, `80`, `96`, mapping to `max-h-40` and friends —
rather than a free CSS length, so the two components stay consistent. The wrapper
is rendered by the component and carries `custom-scrollbar`, following how every
other scrolling container in the library is styled. The `header` and `footer` slots
stay outside the scrolling region, and the lightbox is unaffected because it
teleports out of the container:

```blade
<x-gallery grid height="80" :columns="3" :images="$images" />
```

An arbitrary height is still reachable through utilities, since attributes reach
the root — but the scrollbar styling then belongs to the consumer:

```blade
<x-gallery grid :images="$images" class="custom-scrollbar max-h-64 overflow-y-auto" />
```

### Changed — no DOM virtualization; lazy loading and `content-visibility` instead

Every tile carries `loading="lazy"`, `decoding="async"` and, when the entry
supplies `width`/`height`, those attributes too, plus `content-visibility: auto`
with `contain-intrinsic-size`. The browser skips downloading off-screen images and
skips their layout and paint, which is most of what virtualization buys, with no
JavaScript and without breaking browser find or anchors.

Virtualizing the DOM was considered and rejected: it needs a known item height, so
it could only ever work in `grid` and would make the attribute inconsistent across
layouts; it forces a fixed container height with internal scrolling; it breaks
browser find, anchor deep-links and printing; it conflicts with Livewire DOM
morphing; and it makes the lightbox index map to rendered DOM rather than to the
full array.

Supplying `width`/`height` matters most in `masonry`, where no aspect-ratio class
reserves the space in advance.

### Added — validation that fails loudly on the wrong layout

Attributes passed to a layout that ignores them raise rather than being dropped,
following how `<x-carousel>` rejects `caption` without `clickable`: `columns`
cannot be used with `feature`, `limit` only with `feature`, `ratio` not with
`masonry`, `thumbnails` only with `feature`. `caption`, `navigable` and
`without-loop` all require `clickable`.

This is why the layout flags default to `null` and are never mutated. `validate()`
runs before configurations in `ManagesCompilation::compile()`, so it always sees
the raw attributes and can tell an explicitly passed value from a resolved default.
Resolving `grid` to `true` in the constructor would make `<x-gallery masonry />`
carry two layout flags by the time validation ran, and the mutual-exclusivity rule
would reject valid usage.

### Notes on internals

`layout` and the defaults for `columns`, `ratio` and `limit` resolve in
`CompileConfigurations::gallery()`, which maps them to classes through `match`
exactly as the Modal maps `size`. The feature slicing — cover, thumbnail row and
the `+N` count — lives in `GalleryRuntime`. The component constructor holds no
logic beyond normalising `images` into a zero-indexed array, and the template keeps
a single `@php` block.

The tile markup is a sub-view rendered through
`<x-dynamic-component component="ts-ui::gallery.tile" />`, the mechanism `Progress`,
`Step` and `ThemeSwitch` already use, so the tile is defined once instead of
repeated across layouts.

Customization blocks: `wrapper`, `scroll`, `height.*`, `grid.*`, `masonry.*`,
`feature.*`, `tile.*` and `lightbox.*`. The `columns` and `ratio` maps are *not*
customization blocks — numeric keys such as `grid.columns.4` would be awkward to
target and inconsistent with the rest of the library. The feature wrappers are
keyed by thumbnail position (`feature.wrapper.left`,
`feature.thumbnails.wrapper.left`), so each arrangement can be restyled on its own.

---

## Form / Upload / Async

### Added — `<x-upload.async />`, chunked uploads straight to your own controller

`<x-upload />` rides the Livewire upload pipeline, so a file has to fit inside PHP's
request limits. The new component does not: the browser slices each file and posts
the pieces to an endpoint you own, which means files around 1 GB stop being a
problem. Nothing is shared between the two beyond the namespace, and unlike the old
one this works outside Livewire too.

```blade
<x-upload.async wire:model="gallery"
                :route="route('uploads.gallery')"
                accept="image/*"
                multiple
                :limit="6"
                :max-size="512" />
```

```php
use TallStackUi\Http\AsyncUpload\Uploader;

class UploadController
{
    use Uploader;

    public function store(Request $request)
    {
        return $this->upload($request, [
            'disk' => 'public',
            'directory' => 'posts/attachments',
            'rules' => ['file' => ['mimes:jpg,png,pdf']],
        ]);
    }
}
```

The method is called once per chunk. Intermediate chunks answer `204`; the last one
assembles the file, validates it, stores it and answers `200`.

**Chunks are staged as one part file per index, joined at the end.** They are
uploaded `concurrency`-at-a-time and arrive out of order, so appending them to a
single file interleaves the payload. One `{index}.part` per chunk removes ordering
from the equation and turns "is it complete?" into a file count.

Two atomic filesystem operations carry the coordination. `mkdir()` elects the request
that fires `AsyncUploadStarted`, and renaming the staging directory elects the single
request that finalizes. Counting parts alone is not enough — two requests can observe
a complete set at the same moment.

**Staging is always local, the destination is not.** Joining the pieces needs real
paths and stream handles, which object stores do not have. The finished file then
goes wherever you name, S3 included.

**There is no global default destination directory.** A package-wide fallback would
quietly pile every upload in an application into one folder, so `directory` is
required per endpoint and the handler throws without it. `disk` stays global,
since a project usually has one uploads disk.

**The size ceiling is re-checked server side.** The `max-size` prop is feedback for
the user; a request built by hand ignores it. The handler compares the declared size
on every chunk and the assembled bytes at the end, so neither can be lied about, and
`rules` run against the real bytes rather than the mime the browser claimed.

**State binds two ways.** Through `wire:model`, honouring the `.live` modifier the
way Livewire itself does, or through a `name` attribute that generates hidden inputs
for a plain form submit. Both receive the same array:

```php
[
    ['id' => '...', 'path' => '...', 'real_name' => '...', 'size' => 0, 'mime' => '...', 'url' => '...'],
]
```

**Three Laravel events** — `AsyncUploadStarted`, `AsyncUploadCompleted` and
`AsyncUploadFailed` — cover the server side. They exist for side effects: queueing a
thumbnail, scanning, auditing. A finished upload is not a submitted form, so writing
a database row from `AsyncUploadCompleted` would orphan it; that write belongs where
the form is handled, reading the array the component synced out. There is
deliberately no per-chunk event, since a 500 MB file would fire hundreds.

**Eight Alpine events** are dispatched on the component root: `added`, `rejected`,
`start`, `progress`, `success`, `error`, `removed` and `complete`.

`chunk_size` defaults to 2 MB because that is the stock PHP `upload_max_filesize`;
anything larger has every chunk rejected before it reaches Laravel.

`tallstackui:async-upload:clear` discards staging directories idle for longer than
the `keep` setting. Nothing else collects them, so without scheduling it the staging
directory grows forever. It never touches the destination disk: telling an orphan
from a saved file there would need your database.

Full reference in `.ai/components/form/upload/async.md`.

### Changed — both upload components moved to their own bundle

`js/tallstackui-upload.js` joined the entry points carrying `<x-upload />` and
`<x-upload.async />`. The main bundle went from 56.4 kB to 47.9 kB and the new one
weighs 8.55 kB, 3.24 kB gzipped.

This is not lazy loading. The script directive emits every entry of the manifest on
every page, so the bytes a visitor downloads are the same, only split across one more
request. What it buys is cache granularity: a change to either upload component no
longer invalidates the bundle every other component lives in.

Splitting an entry made rolldown hoist a small shared runtime chunk, which the
directive already preloads along with the other underscore-prefixed chunks, and
redistributed the code shared between entries.

**No migration.** Applications load the assets through `<tallstackui:script />`,
which picks the new entry up on its own.

---

## Dialog

### Added — `Enter` confirms the dialog

A dialog could be dismissed from the keyboard but never accepted: `Escape` closed it,
and the confirm button answered only to the mouse. Anyone using the keyboard had to
reach for it, or `Tab` across to it first.

`Enter` now presses the confirm button:

| Dialog                                           | `Enter`                              |
|--------------------------------------------------|--------------------------------------|
| `success()` / `error()` / `info()` / `warning()` | closes it, like clicking **OK**      |
| `question()->confirm('Yes', 'method')`           | runs `method`, like clicking **Yes** |

The binding lives on the same root element as the `Escape` one and shares its
`top_ui` guard, so a dialog opened underneath a modal or slide does not answer for
the element on top of it.

It fires only while the focus is **outside** the dialog:

```blade
x-on:keydown.enter.window="enter($event)"
```

A `<button>` already activates on `Enter` while focused, so without that guard a
dialog whose cancel button had been reached with `Tab` would cancel *and* confirm on
a single keystroke.

For the same reason the handler calls `preventDefault()` once it decides to claim
the keystroke. The element that opened the dialog keeps the focus after the click
that opened it, and the guard above is precisely what lets `Enter` through while
that is the case — so leaving the default action alone made the keystroke both
confirm the dialog and click the trigger again, closing and reopening it in one go.

`Enter` works on a `persistent()` dialog, where `Escape` does not. Persistence exists
to stop a dialog from being dismissed by accident — pressing the confirm button is
the answer it is waiting for, not a way around it.

The confirm button is always present, including for the four non-question types
where it renders as a centered **OK**, so there is no dialog that `Enter` cannot
answer.

---

## Soft Customization

### Fixed — a scope threw away the global customization

`array_merge($soft, $scoped)` already gives the scope precedence. Restricting the
result to the scoped keys on top of that dropped every block the scope did not name,
and those blocks then fell back to the component's original classes.

```php
TallStackUi::customize()->alert()->block('wrapper')->append('brand-shadow');
TallStackUi::customize('alert', scope: 'flat')->block('text.title')->append('text-xl');
```

`<x-alert title="X" />` carried `brand-shadow`; `<x-alert title="X" scope="flat" />`
lost it. The same happened with the scopes the package ships, so
`<x-card scope="card-shadowless">` discarded every global customization of Card.

A scope now layers over the global customization instead of replacing it.

### Fixed — a scoped block swallowed its dot notation sibling

Block names are keys that happen to contain dots. Writing them through
`data_set($this->parts, $this->scope.'.'.$block, ...)` read those dots as a path, so
`body` became a node on the way to `body.paddingless` and whichever was written last
survived:

```php
TallStackUi::customize('card', scope: 'flat')->block([
    'body'             => 'grow px-2 py-2',
    'body.paddingless' => 'p-0!',
]);
```

`CustomizationFactory::get()` is typed `?string` but would then return the array left
behind by the collision, raising a `TypeError`.

The scope container is resolved first and the block written as a flat key inside it,
which keeps nested scope names such as `form.currency.input` working while leaving the
block's own dots alone. `get()` reads it back the same way.

Affected every component with a colliding pair: Card, Modal, Slide, CommandPalette,
Select Styled, Layout Header, SideBar Item and SideBar Separator.

### Added — `extend()` to change a scope that is already defined

Scopes could only be created, never touched. That made the scopes the package
ships in `registerPredefinedScopes()` — `card-shadowless`, `stats-shadowless`,
`calendar-shadowless`, `tab-shadowless`, `table-shadowless` — read only from an
application's point of view. Calling `scope()` with the same name did not extend
the existing one, it started over from the component's original classes.

`extend()` reuses the scope instead of redefining it:

```php
TallStackUi::customize()
    ->extend(scope: 'card-shadowless')
    ->card()
    ->block('wrapper.second')
    ->append('ring-1 ring-gray-100');
```

The block keeps everything the original definition did to it — the removed
`shadow-md` stays removed, the appended border stays — and the new classes go on
top.

The scope has to exist for the component being customized. Scopes are stored per
component, so `extend(scope: 'card-shadowless')->stats()` throws: that name was
never defined for Stats.

```
InvalidArgumentException: The scope [card-shadowless] was not defined
for the component [stats] and therefore cannot be extended.
```

Requiring the scope to exist is the point of having a separate verb. `scope()`
creates and silently accepts a typo; `extend()` refuses one.

Order matters: the package's own scopes are registered in the service provider's
`boot()`, which runs before the application's providers under Laravel's default
discovery. Applications that disable discovery for TallStackUI have to make sure
their provider boots afterwards.

### Fixed — customizing the same block twice kept only the last change

Two chains touching one block did not stack. The second silently discarded the
first:

```php
TallStackUi::customize('alert')->block('wrapper')->append('from-a');
TallStackUi::customize('alert')->block('wrapper')->append('from-b');
// 3.x: 'p-4 from-b'   — from-a lost
// 4.x: 'p-4 from-a from-b'
```

Inside a single chain it already stacked, which is what made the behaviour hard
to spot: `->append('one')->append('two')` produced both. The inconsistency came
from `block()` reseeding its working copy from the component's original classes
on every call, discarding whatever earlier chains had compiled.

It now resumes from the compiled state, so a package and an application can each
customize the same block without one erasing the other. This is also what makes
`extend()` work.

**Migration.** Anything relying on the last chain winning has to be collapsed
into one chain, or the earlier customization removed. The practical case to watch
is a customization that runs more than once in the same process — it now
accumulates rather than settling on a fixed result.

### Fixed — `remove()` matched substrings instead of classes

`remove()` ran a plain `str_replace`, so removing a class also chewed through
every longer class that contained its name:

```php
// block: 'mb-2 rounded-md border border-gray-300 dark:border-dark-700'
->remove('border')
// 3.x: 'mb-2 rounded-md -gray-300 dark:-dark-700'
// 4.x: 'mb-2 rounded-md border-gray-300 dark:border-dark-700'
```

Removal now works on whitespace-separated tokens and drops only whole classes.
Passing several at once still works, either as a list or as one string:

```php
->remove(['shadow-md', 'rounded-lg'])
->remove('shadow-md rounded-lg')
```

`replace()` deliberately stays a substring operation — swapping a palette with
`->replace('gray-', 'zinc-')` depends on it. Which means `replace('rounded',
'rounded-full')` still turns `rounded-md` into `rounded-full-md`; target the full
class name when that is not what you want.

### Fixed — a shortcut chained after `block($name, $code)` was dropped

Providing the code inline and then reaching for a shortcut wrote the shortcut's
result to an empty key, and nothing reached the component:

```php
TallStackUi::customize('alert')->block('wrapper', 'p-8')->append('foo-bar');
// 3.x: 'p-8'          — the append vanished
// 4.x: 'p-8 foo-bar'
```

Calling a shortcut before any block now throws instead of writing nowhere:

```
RuntimeException: No block has been set. Call block() before
append(), prepend(), replace() or remove().
```

### Fixed — `<x-avatar.group>` could not be customized

`avatar.group` is a registered customization key with blocks of its own, but
`Customization::avatar()` took no sub-component. `customize('avatar.group')`
resolved to the plain Avatar and quietly treated `group` as a **scope name**, so
the customization compiled against the wrong component and never applied.

`avatar()` now accepts a sub-component, matching `accordion()`, `button()`,
`dial()`, `dropdown()`, `timeline()` and `wrapper()`:

```php
TallStackUi::customize()->avatar('group')->block('wrapper', '...');
TallStackUi::customize('avatar.group')->block('wrapper', '...');
```

All 78 registered customization keys now resolve to the component that declared
them.

### Fixed — an unknown sub-component became a scope instead of an error

Any dotted name whose second segment was not a real sub-component fell through to
the `$scope` parameter. `customize('badge.main')` built a scope called `main`
that no component ever reads, and reported nothing.

The segment is now rejected when the target does not accept one:

```
RuntimeException: The component [badge] does not have the sub-component [main]
```

This also fixes `customize('accordion.accordion')`, which used to fail with the
nonsensical `Component [1] is not allowed to be customized`.

### Fixed — the unknown-block error named a component you cannot pass back

The message derived the component from its Blade view name, so it said
`badge.main`. Feeding that back into `customize()` hit the bug above. It now
reports the customization key:

```
Component [badge] does not have the block [nope] to be customized. Allowed: ...
```

### Fixed — `get()` returned null for scoped customizations

Scoped blocks are stored nested under the scope name, and `get()` only looked at
the flat top level. It is now scope-aware.

### Fixed — the `square` global mangled arbitrary values and unrelated classes

The global strips border-radius utilities with a regular expression that had no
token boundaries, so it ate parts of classes it should not have touched and left
fragments behind:

| Class              | 3.x       | 4.x            |
|--------------------|-----------|----------------|
| `rounded-[10px]`   | `-[10px]` | removed        |
| `rounded-tl-[2px]` | `-[2px]`  | removed        |
| `not-rounded`      | `not-`    | `not-rounded`  |
| `unrounded-md`     | `un`      | `unrounded-md` |

It now matches whole tokens, so arbitrary values are removed cleanly and classes
that merely contain `rounded` are left alone.

### Changed — `colorful()` assigns instead of appending

`colorful()` pushed onto its list where `flash()` and `square()` assign, so
calling it twice registered duplicate entries and narrowing it never took effect:
`colorful()` followed by `colorful(toast: false)` still left Toast enabled. It now
replaces the list, matching the other two globals.

### Changed — the `colorful` palette of the `question` type follows `primary`

The `question` type painted itself with a grayscale palette, `bg-neutral-500` on
Dialog and `bg-stone-500` on Toast. Since `question` is the type behind every
confirmation dialog, the most common way to see `colorful()` was also the only
one that produced no color at all, which reads as a broken global ([#1203](https://github.com/tallstackui/tallstackui/issues/1203)).

| Component | 3.x               | 4.x                 |
|-----------|-------------------|---------------------|
| Dialog    | `bg-neutral-500!` | `bg-primary-500!`   |
| Toast     | `bg-stone-500!`   | `bg-primary-500!`   |

`primary` is what the confirm button of the `question` type already used outside
of `colorful()`, so the two modes now agree with each other. The palette remains
overridable through the published `DialogColors` and `ToastColors` classes.

### Changed — the `colorful` dialog buttons no longer share the same background

Cancel was `bg-white/10` and confirm `bg-white/20` over the colored panel, two
translucent whites four percent apart. The destructive action lost the weight it
has outside of `colorful()`, where cancel is red and confirm carries the type
color:

| Button  | 3.x                                     | 4.x                                       |
|---------|-----------------------------------------|-------------------------------------------|
| Cancel  | `bg-white/10 ... text-white/80`         | `bg-transparent` + `hover:bg-white/20`    |
| Confirm | `bg-white/20 ... font-bold! text-white` | `bg-white` + `text-{type}-700!`           |

The pair now reads as one filled button and one flat button, the same
relationship `<x-button flat>` has with a solid one: confirm is solid white with
the type color as its text, and cancel carries no background until it is hovered
or focused, where it picks up `bg-white/20` and its label goes from `text-white/80`
to full white.

The `font-bold!` override went away with the translucent confirm: the solid
background already carries the weight, so confirm keeps the `font-semibold` it has
outside of `colorful()`.

### Changed — every `colorful` button color moved into the published color classes

The colors of the `colorful` buttons lived in the components' customization
blocks, so the only way to change them was `customize()->block()`. Everything
that varies by type already lived in the color classes instead — `background`,
`confirm`, `icon` — and the `colorful` cancel of Dialog was there too. The
confirm followed the cancel, and Toast gained the same treatment:

| Was (customization block) | Is now (color class)             |
|---------------------------|----------------------------------|
| `dialog.colorful.confirm` | `DialogColors::colorfulColors()` |
| `toast.colorful.confirm`  | `ToastColors::colorfulColors()`  |
| `toast.colorful.cancel`   | `ToastColors::colorfulColors()`  |

Those three blocks no longer exist, so `customize()->block()` on them now throws.
Publish the color classes with `php artisan tallstackui:setup-color` and override
`colorfulColors()` instead — partial overrides are merged over the defaults, so
naming a single key leaves the rest untouched:

```php
public function colorfulColors(Component $component): array
{
    return [
        'cancel' => null,                            // keeps the default
        'confirm' => ['success' => 'text-lime-900!'],// the other types stay
    ];
}
```

The blocks that do not depend on the type — `colorful.icon`, `colorful.title`,
`colorful.description`, `colorful.close` and friends — stay where they are and
remain reachable through `customize()->block()`.

---

## Step

### Changed — the three variations share one visual language on a card

Sitting on a `dark-800` card, the variations disagreed with each other: `simple`
drew its inactive bars in `dark-700` (invisible on the card), `panels` did the same
with its outlines and used lighter rings and a pink inactive title, while `circles`
was the only one that read correctly. They now share one scale:

- **Inactive rings** (circles and panels): `gray-300` / `dark:border-dark-500`, with
  the pending number in `gray-500 dark:text-dark-300`.
- **Structure** — inactive bars, panel outlines, row borders and the chevron
  separator: `gray-200` / `dark:*-dark-600`.
- **Inactive titles**: `gray-600 dark:text-dark-300` in all three variations; the
  completed title stays green in both modes.
- **Navigate chips**: `dark:bg-dark-700` with a `dark-600` border (they sat at
  `dark-800` and vanished into the card), hovering one step lighter — the same
  treatment as the Date and Calendar helper chips.

**Migration** — key names are unchanged. Customizations replacing the old
`border-dark-200`, `dark:border-dark-700`, `dark:border-dark-300` or the pink
`text-primary-500` inactive title inside the `step` blocks should target the new
values.

### Fixed — the horizontal scrollbar of the `panels` variation squared off the rounded corners

With enough steps to overflow, the `panels` variation grows a horizontal scrollbar
whose thumb ran flat into the bottom corners, flattening the radius and sitting on
top of the bottom border instead of inside the frame.

WebKit paints a scrollbar as chrome in the border box, outside the element's own
content clip, so a border-radius on the scrolling element does not shape it. The
only thing that does is an ancestor with `overflow-hidden` and the radius. The
`<ul>` carried the border, the radius **and** the scroll all at once, and its
`mb-2` pushed it clear of the `<nav>` that could otherwise have clipped it.

The frame moved out to the `<nav>`, leaving the `<ul>` as a bare scroll container:

```html
<!-- before -->
<nav class="overflow-hidden rounded-md">
    <ul class="rounded-md border border-gray-300 md:flex overflow-auto soft-scrollbar mb-2">

<!-- after -->
<nav class="overflow-hidden mb-2 rounded-md border border-gray-300">
    <ul class="md:flex overflow-auto soft-scrollbar">
```

This is the arrangement `<x-table>` already used, and the thumb now ends on the
same curve there as it does here.

The `simple` and `circles` variations are untouched — neither draws a border around
the scroll area, so neither had a corner to lose.

**Migration.** Soft customization keys kept their names but swapped roles:

| Block            | 3.x                                      | 4.x                                                           |
|------------------|------------------------------------------|---------------------------------------------------------------|
| `panels-shape`   | `rounded-md`                             | `mb-2 rounded-md border border-gray-300 dark:border-dark-700` |
| `wrapper.panels` | border, radius, bottom margin and scroll | scroll only                                                   |

Applications restyling the panels frame through `wrapper.panels` have to target
`panels-shape` instead. Moving the border back onto `wrapper.panels` brings the
artifact back.

The dead `dark:divide-dark-700` on `wrapper.panels` went away with it; the panels
list separates its items with `border-b` on `panels.li`, never with `divide-*`.

---

## Form / Select / Styled

### Fixed — arrow keys stopped landing on disabled options

Keyboard navigation advanced by plain index arithmetic, so ArrowUp/ArrowDown happily
focused options a `disabled` flag was supposed to fence off — selection was blocked,
but the focus ring still parked there. Navigation now walks in the pressed direction
skipping disabled options, wrapping around, and stands still when every option is
disabled.

The keyboard highlight also disagreed with the mouse: hovering painted
`dark:bg-dark-700` while focusing painted `dark:bg-dark-500`. Both paths now use
`dark-700`.

### Fixed — the search only reached the lazy window

`available` sliced the options down to `lazy` **before** filtering them, so the search
ran over the first N entries and nothing else. `lazy` exists for long lists, which is
exactly where the search matters most:

```blade
{{-- typing 9999 found nothing --}}
<x-select.styled searchable :options="range(1, 10000)" :lazy="10" />
```

The window is now applied only while no term is typed; with a term, the filter runs over
the whole list and the window bounds the matches instead.

Nothing was needed for the selection to survive: `hydrate()` already cross-references
the model against the full `options` list rather than the rendered slice, so an option
picked from a search result keeps displaying after the search is cleared.

### Fixed — `0` was submitted as an empty value

Both the hidden input setter and the vanilla initializer tested truthiness, so a
legitimate `0` came out as `''`:

```blade
{{-- picking Inactive submitted status= --}}
<x-select.styled name="status"
                 :options="[['label' => 'Inactive', 'value' => 0], ['label' => 'Active', 'value' => 1]]"
                 select="label:label|value:value" />
```

Both now test for `null`, `undefined` and `''` explicitly.

### Changed — qs is gone

A `request` sent with `method: 'get'` had its parameters serialised by qs, of which the
package used exactly one function, `stringify`. That single call cost ~39 KB of the
select bundle.

`helpers.js` now builds the query string itself, matching what qs emitted:

- nested values use bracket notation, `filters[status]=active`, which is what PHP
  expands back into an array on the other side;
- arrays are keyed by index, `tags[0]=alpha`, qs's default `indices` format;
- `null` serialises to an empty value, `undefined` is dropped, and an empty array or
  object contributes nothing at all;
- a `Date` goes out as an ISO string;
- encoding follows RFC 3986, so `!'()*` are escaped as well.

That last point is the one thing `encodeURIComponent` gets wrong on its own: it leaves
those five characters untouched while qs percent-encodes them. PHP decodes both forms
identically, so nothing here would have failed a test — but a query string that quietly
changes shape between versions is what surfaces months later inside a signature check or
a cache key, far from the change that caused it.

The select bundle went from ~54 KB to ~14.8 KB.

### Migration

**`qs` left `package.json`.** An application importing it directly has to install it on
its own. The parameters the component puts on the wire are unchanged.

### Tests

`SelectStyledApiBrowserTest.php` gained
`request_params_are_encoded_preserving_nesting_and_special_characters`, pointed at a new
`searchable.echoing-parameters` route that echoes back what PHP actually received. It
sends a nested object, an array, and `raw = 'a b&c=d'` to cover the escaping.

The GET path had no coverage whatsoever: every request-parameter test that already
existed declares `method: 'post'`, which goes out through `JSON.stringify` and never
reaches the serialiser.

### Fixed — grouped children were unreachable under a custom `value` key

The grouped template read children from a hardcoded `option.value` while the rest
of the component resolved them through the configured mapping. With
`select="label:name|value:id"` the dropdown rendered the group headers and nothing
else — the select opened but nothing could be picked.

The template now reads `option[selectable.value]`, matching what `_flatItems()` and
the `available` getter already did.

Note the shape this implies: the mapped `value` key carries the child list on a
group **and** the scalar value on a child, so both levels use the same key.

```php
// select="label:name|value:id"
['name' => 'Brazil', 'id' => [
    ['name' => 'São Paulo', 'id' => 4],
]]
```

### Fixed — a grouped child hid its image when it had no description

The `<img>` inside a grouped row bound its `src` to the image but gated its
visibility on the **description**. A child with an image and no description
rendered the image with `display: none`; a child with a description and no image
rendered an empty `<img>`. Visibility now follows the image, as it already did for
non-grouped rows.

The group header had the sibling problem: it read `option.image` and
`option.description` raw while the label next to it went through the mapping. Both
now respect `select`.

### Fixed — a list mixing groups and loose options dropped the grouped items

`grouped` was assigned inside the loop that normalizes options, so it kept only the
**last** option's answer. A list whose last entry was a plain option resolved to
"not grouped", the grouped branch never rendered, and every nested item vanished.
Worse, the group itself became a selectable row whose value was the whole child
array, so picking it pushed an array into a scalar `wire:model`.

The flag is now the union across all options, and it no longer overwrites an
explicit `grouped` attribute. On the JavaScript side `_flatItems()` and the
`available` getter probed only the first option for the same decision; both now
test every option.

Groups and loose options render side by side, the way `<optgroup>` and `<option>`
coexist in a native select:

```php
:options="[
    ['label' => 'Brazil', 'value' => [
        ['label' => 'São Paulo', 'value' => 4],
    ]],
    ['label' => 'Uncategorized', 'value' => 99],
]"
```

Loose rows render without the group indent and are selectable like any other item.

### Fixed — multiple grouped select closed the panel after the wrong number of picks

In multiple mode the dropdown is meant to stay open until every option has been
taken. The check compared the number of selections against `available.length`, but
for grouped options `available` holds the **groups**, not the selectable items.

With two groups of two cities each, the panel closed after the second pick and then
never closed at all:

| Pick           | selections | `available.length` | result         | expected   |
|----------------|------------|--------------------|----------------|------------|
| São Paulo      | 1          | 2                  | stays open     | stays open |
| Rio de Janeiro | 2          | 2                  | **closes**     | stays open |
| New York       | 3          | 2                  | stays open     | stays open |
| Los Angeles    | 4          | 2                  | **stays open** | closes     |

The count now comes from the flattened item list, which is what `_flatItems()`
already produces for hydration. Non-grouped selects are unaffected — `_flatItems()`
returns its input untouched when the list is not grouped.

### Changed — selected items from a grouped list are labelled `group > item`

Inside the open dropdown an item sits under its group header, so its own label is
enough to identify it. Once the dropdown closes that context disappears: a select
showing `São Paulo` no longer says which country it came from, and two groups
holding an item of the same name became indistinguishable.

Selected items are now qualified with their group:

```
single:   [ Brazil > São Paulo                          ✕ ⌵ ]

multiple: [ (Brazil > São Paulo ✕) (United States > New York ✕) ]
```

This covers the closed single-select label, the chips in multiple mode, and labels
restored on page load from `wire:model`.

Only the display changes. `wire:model` still receives the item's raw `value`, and
the rows inside the open dropdown keep showing their plain label under the group
header. Non-grouped selects are untouched.

Mechanically, `preNormalize()` now stamps each child with its group label while it
already walks the group tree, and a `display()` helper builds the qualified string
at the three points that render a selection. Because the stamp rides on the option
objects themselves, it survives `_flatItems()` and works the same for local
`:options` and remote `:request` sources.

**Migration.** The separator is fixed as ` > ` and there is no attribute to opt out.
Applications that render grouped selects in narrow containers may need to widen
them, and any test asserting the exact text of a selected grouped item has to
expect the qualified form.

---

## Form / Autocomplete

### Fixed — keyboard highlight skips disabled items and matches the hover color

The same two defects the styled select had, plus one: ArrowUp/ArrowDown moved the
highlight by modulo without checking `disabled`; the initial highlight landed on
index `0` even when that item was disabled (both on open and after a `request`
response); and the highlighted row painted `dark:bg-dark-600` while hovering painted
`dark:bg-dark-700`. Navigation now skips disabled items with wrap-around, the
initial highlight finds the first enabled item, and highlight and hover share
`dark-700`.

### Changed — the panel width comes from the Floating now

The panel matched the input by reading `offsetWidth` in an `x-effect` on the component
root. Floating already does that, opt-in through `w-full` on the panel, and it does more:
it re-applies on every open, on a MutationObserver over the panel content, and on
Livewire's `commit` hook. The local copy only re-ran when `show` changed, so a round trip
that replaced the teleported panel while it was open left the width behind.

Autocomplete was the one component in the library still hand-rolling this — Select/Styled
already opted in. `floating.class` gained `w-full` and the `x-effect` is gone. Nothing
renders differently.

### Added — `metadata` passthrough on items

Items now accept a `metadata` key carrying arbitrary consumer data. The component
never reads, filters or renders it — it only keeps it reachable from the Alpine
`selected` state and from the `select` event payload, so the application can react
to which item was picked.

```php
[
    'value' => 'Alice',
    'description' => 'admin',
    'metadata' => ['id' => 42, 'role' => 'admin', 'team_id' => 7],
]
```

```json
[
  { "value": "Alice", "description": "admin", "metadata": { "id": 42, "role": "admin" } }
]
```

```blade
<x-autocomplete wire:model="user"
                :request="route('api.users')"
                x-on:select="$wire.userPicked($event.detail.item.metadata)" />
```

Works identically for local items (`:items`) and remote results (`:request`), since
both go through the same normalization step.

`metadata` is deliberately a namespaced bucket rather than a flat passthrough of
unknown keys. Flattening would mean that any internal key the component adds in a
future version silently overwrites consumer data.

What `metadata` does **not** do:

- it is not matched by the search filter (only `value` and `description` are);
- it is not rendered in the dropdown row;
- it is not sent to `wire:model` — the model still receives `value`.

### Fixed — documented item passthrough that never worked

The 3.x documentation described `$event.detail.item` as
`{ value, description?, image?, disabled?, ...whatever you put there }` and showed
`$event.detail.item.id` in an example. That was never true: normalization rebuilt
each item from the four known keys and dropped everything else, so any extra field
arrived as `undefined`. The docs now describe the real shape, and `metadata` is the
supported way to attach custom data.

---

## Stats

### Added — optional background chart

A card can now carry a `<x-chart>` behind its content, full-bleed and dimmed, in
two forms. The array shorthand renders the chart internally and inherits the
card's `color`:

```blade
<x-stats number="45231" title="Revenue" increase :chart="[10, 40, 25, 60, 30, 80]" />
```

The slot takes over completely, for a chart that should differ from the card:

```blade
<x-stats number="45231" title="Revenue">
    <x-slot:chart>
        <x-chart :series="$revenue" color="emerald" class="h-full w-full" />
    </x-slot:chart>
</x-stats>
```

The two are mutually exclusive and throw when combined. An absent chart, an empty
array and an empty slot are all treated as no chart, and none of the positioning
classes are applied in that case.

Three new soft customization blocks:

| Block               | Purpose                                                       |
|---------------------|---------------------------------------------------------------|
| `wrapper.first-chart` | Stacking context on the card, only when a chart is present  |
| `chart.wrapper`     | The full-bleed layer: placement, clipping and opacity          |
| `chart.element`     | Sizing handed to the internal chart                            |

The layer clips itself rather than the card, so nothing a slot renders outside
the box gets cut. Two behaviours change on a charted card: it becomes the
containing block for absolutely positioned slot content, and it traps positive
`z-index` inside itself. Everything TallStackUI teleports (floating, modal,
tooltip) is unaffected.

In `solid` style the icon tile is opaque and covers the watermark behind it.

### Added — `duration` prop

Controls the count-up animation length. Defaults to `1` and is clamped to a
non-negative integer.

### Changed — number styling driven by the `number` prop and the `color` prop

Number styles are applied only through the `number` prop, leaving the default slot
free for raw markup. The `number` customization block no longer hardcodes
`text-primary-500` — the text color now follows the component's `color` prop. The
count-up animation is gated to numeric values.

Non-`href` clickable roots render as a `div` with a pointer cursor instead of an
anchor.

**Migration.** Soft customization keys changed shape:

| 3.x                                | 4.x                                   |
|------------------------------------|---------------------------------------|
| `wrapper.second-no-slot`           | removed, folded into `wrapper.second` |
| `header` / `header-string-wrapper` | `header.text` / `header.wrapper`      |
| `footer` / `footer-string-wrapper` | `footer.text` / `footer.wrapper`      |

### Added — validation for conflicting direction flags

`increase` and `decrease` can no longer be used together; doing so throws
`InvalidArgumentException` (surfaced by Blade as `ViewException`).

## QrCode

### Added — `<x-qr-code />`, a dependency-free QR code

The whole of ISO/IEC 18004 lives in `src/Support/QrCode/`: Reed-Solomon over
GF(256), the version and error correction tables, data placement, the eight
masks and their penalty rules. Nothing is fetched, nothing is shelled out to,
and no encoding library is involved:

```blade
<x-qr-code link="https://tallstackui.com" />

<x-qr-code link="https://tallstackui.com"
          color="blue"
          size="xl"
          watermark="bolt"
          copy
          download="svg" />
```

`size` takes `xs` through `2xl` and only sets the rendered box — the module
count comes from the payload. Both `size` and the export resolution take an
application-wide default from the config.

**The grid ships as a single SVG path** with consecutive dark modules merged
into one run. One node per module would put thousands of elements on the page
for a mid sized code, which is what makes a list of them stutter.

**The error correction level is not a prop.** It is `M` normally and `H` when a
watermark is present, because removing modules from the middle of the symbol is
exactly what the highest level pays for. Offering it as a choice would let a
watermark be combined with a level that cannot carry it.

**Without `color` the modules follow the theme** — dark on a light page, light
on a dark one. A named color is the same color under both themes, because a
brand is. Note that the dark theme therefore inverts the symbol: the
specification asks for dark modules on a light background, and while the iOS
camera, Google Lens and Apple Vision all read an inverted code, the default
reader in ZXing does not. Pass an explicit `color` where conformance matters
more than the theme.

**`watermark` resolves to an icon or to text from the same attribute.** A value
backed by an existing icon view draws the icon; anything else is drawn as
`<text>`, capped at eight characters so it still fits the strip. Arbitrary
Blade is deliberately not accepted: `<foreignObject>` is dropped when the SVG
is rasterized, so a watermark expressed as HTML would vanish from every
exported file.

The modules under a watermark are **removed rather than covered**. The
component draws no background, so anything painted over them would still show
them through. The region never reaches the timing patterns or either format
information block, and both kinds are capped at roughly six per cent of the
symbol area — a caption strip that grew with its text would take most of the
width of a large symbol and no reader would take that code. A long caption
shrinks its font rather than widening the strip.

**`copy` and `download` export the code as a file.** Copy always writes a PNG,
because pasting a vector into a chat or a document does not work anywhere it
matters; download takes `png` or `svg`. The exported file is rendered without
the page stylesheet, so the color the classes resolved to is inlined into the
clone before it is serialized. There is no background, so the PNG is
transparent.

**`skeleton` renders a placeholder and stops requiring a `link`**, which is
what a placeholder stands in for:

```blade
<x-qr-code :link="$resolved" :skeleton="$resolved === null" size="lg" />
```

### Scannability

`size` sets the box and the payload sets the module count, so a long link
inside a small box leaves very few pixels per module. Below roughly three the
code stops being readable from a one-times display:

| payload | version | xs   | sm   | md   | lg   | xl   | 2xl  |
|---------|---------|------|------|------|------|------|------|
| 23 B    | v2      | 2.91 | 3.88 | 4.85 | 5.82 | 6.79 | 7.76 |
| 120 B   | v7      | 1.81 | 2.42 | 3.02 | 3.62 | 4.23 | 4.83 |
| 330 B   | v13     | 1.25 | 1.66 | 2.08 | 2.49 | 2.91 | 3.32 |
| 800 B   | v23     | 0.82 | 1.09 | 1.37 | 1.64 | 1.91 | 2.19 |

A retina display doubles every number. Shorten the link or raise the size when
a code has to be scanned off a screen or printed small.

### Verification

An encoder that silently produces the wrong symbol is worse than one that
throws, so correctness is asserted rather than assumed:

- Every one of the 160 version and level combinations has its block table
  cross-checked against a geometric count of the modules a version leaves for
  data. The two derivations are independent.
- The check codewords are verified against the worked example of the
  specification, and, for every error correction degree the tables use, against
  the defining property of a Reed-Solomon codeword: it is a multiple of the
  generator polynomial, so it evaluates to zero at each of its roots. The test
  reaches GF(256) through carry-less multiplication rather than the log tables
  the encoder builds, so a fault in those cannot hide behind itself.
- Every version at every level was rendered and read back with an independent
  decoder outside the test suite.
