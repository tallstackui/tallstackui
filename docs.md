# TallStackUI 4.x

Running record of everything that changed on the `4.x` branch relative to `3.x`.

Entries are grouped by component, most recently touched first. Within a component,
changes are split into **Added**, **Changed** and **Fixed**. Anything that requires
action from an upgrading application carries a **Migration** note.

Soft customization keys are part of the public API: renaming, nesting or removing a
block breaks applications that target it through `TallStackUi::customize()`. Every
such change is listed under **Migration**.

---

## KeyValue

### Changed — a lighter surface, and the fields stop hiding in it

The component stacked three grays: a gray body, a darker gray header and footer, and
inputs sharing the body's gray. The last one was the real problem — nothing read as
editable, because the fields had the same fill as the thing behind them.

It is now a white surface with hairline rules. The header is a small caption over a
bottom border rather than a filled bar, the footer is an action rather than a gray
strip, and the inputs are transparent, so the only thing drawing a box is the component
itself.

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

---

## Signature

### Fixed — resizing the window erased the signature

`window.addEventListener('resize', this.size.bind(this))` forwards the event object as
the first argument, and `size(clear = false)` takes a flag there. Every resize therefore
ran `size(Event)`, which is truthy, and called `clear()`.

Rotating a phone, opening the mobile keyboard or dragging the window edge wiped the
drawing and set the model to `null`, so a save right after went out empty.

Assigning `width` or `height` clears a canvas by specification, so the fix is not just
the listener: the drawing is copied to an offscreen canvas, the canvas is resized, and
the copy is painted back scaled to the new size. A `drawn` flag distinguishes a real
stroke from a blank canvas, so a resize before anything is drawn still leaves the model
`null` rather than storing a blank data URL.

The undo stack holds `ImageData` sized for the old canvas, which `putImageData` would
paint back unscaled, so it restarts from the reflowed drawing.

The component also had no `destroy()`, leaving the listener — and the canvas and undo
stack behind it — alive across morphs and `wire:navigate`. It has one now.

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

A colored balloon keeps its color in both themes. Only the default one inverts, dark on
light themes and light on dark ones.

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
    ],
],
```

Both reach every `x-tooltip` on the page, including the ones rendered by Button, Kbd,
Breadcrumbs, Editor and the sidebar. These are defaults: the inline prop always wins.

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

**Both dialogs are `<x-modal>` instances**, under the fixed scopes `editor-link`
and `editor-image`. The modal already owns the scroll lock, the overlay registry,
`Escape` with its topmost guard and the focus of its first field, and being
teleported to `<body>` takes its inputs out of any surrounding `<form>`, where
`Enter` would otherwise submit it. The toolbar dropdowns are `<x-dropdown>` under
`editor-toolbar`. What is left in the editor's own surface is the content: 40
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
