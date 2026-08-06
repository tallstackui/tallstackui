# TallStackUI: Toast

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A notification toast component for displaying brief, auto-dismissing messages with icon, title, description, optional confirm/cancel actions, progress bar, and expandable content. Dispatched programmatically from Livewire components or controllers via the Interactions trait.

## Basic Usage

Place the component tag once in your layout file:

```blade
<x-toast />
```

Dispatch from a Livewire component:

```php
use TallStackUi\Traits\Interactions;

class MyComponent extends Component
{
    use Interactions;

    public function save(): void
    {
        $this->toast()->success('Saved!', 'Your changes have been applied.')->send();
    }

    public function processLong(): void
    {
        $this->toast()
            ->warning('Processing...', 'This may take a while.')
            ->timeout(10)
            ->persistent()
            ->send();
    }

    public function confirmAction(): void
    {
        $this->toast()
            ->question('Confirm?', 'Do you want to proceed?')
            ->confirm('Yes', 'doAction')
            ->cancel('No')
            ->send();
    }
}
```

Dispatch from a Controller (auto-flashes to session):

```php
use TallStackUi\Traits\Interactions;

class ItemController extends Controller
{
    use Interactions;

    public function store(Request $request): RedirectResponse
    {
        // ... create item

        $this->toast()->success('Created!', 'New item has been added.')->send();

        return redirect()->back();
    }
}
```

## Interaction Methods

| Method     | Signature                                                                   | Description                                                                                           |
|------------|-----------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| success    | `success(string $title, ?string $description = null)`                       | Shows a success toast with a check circle icon                                                        |
| error      | `error(string $title, ?string $description = null)`                         | Shows an error toast with an X circle icon                                                            |
| info       | `info(string $title, ?string $description = null)`                          | Shows an info toast with an information circle icon                                                   |
| warning    | `warning(string $title, ?string $description = null)`                       | Shows a warning toast with an exclamation circle icon                                                 |
| question   | `question(string $title, ?string $description = null)`                      | Shows a question toast with a question mark circle icon                                               |
| confirm    | `confirm(?string $text, ?string $method, array\|string\|int\|null $params)` | Adds a confirm button that calls a Livewire method                                                    |
| cancel     | `cancel(?string $text, ?string $method, array\|string\|int\|null $params)`  | Adds a cancel button that optionally calls a Livewire method                                          |
| expandable | `expandable(bool $expand = true)`                                           | Enables the expandable effect for long descriptions                                                   |
| persistent | `persistent()`                                                              | Removes timeout and progress bar, toast stays until manually closed                                   |
| position   | `position(string $position)`                                                | Sets position dynamically (top-right, top-left, top-center, bottom-right, bottom-left, bottom-center) |
| sole       | `sole(bool $sole = true)`                                                   | When true, flushes all previous toasts and shows only this one                                        |
| stacked    | `stacked(bool $stacked = true)`                                             | Piles the toasts instead of listing them, switching the whole container per event                     |
| timeout    | `timeout(?int $seconds = null)`                                             | Sets the auto-dismiss timeout in seconds                                                              |
| hook       | `hook(array $hooks)`                                                        | Registers lifecycle hooks (allowed: `close`, `timeout`)                                               |
| flash      | `flash(bool $dispatch = false)`                                             | Flashes the interaction to session for display after redirect                                         |
| send       | `send()`                                                                    | Dispatches the toast                                                                                  |

The same fluent surface exists in JavaScript through `$tsui.interaction('toast')`,
including `position`, `sole` and `stacked` — `position` validated against the same
allowed list. `hook` and `flash` are server-side only; `wireable(id)` takes their
place to point the confirm/cancel methods at a Livewire component.

## Configuration

In `config/tallstackui.php` under `components.toast`:

| Option        | Type   | Default     | Description                                                                                  |
|---------------|--------|-------------|----------------------------------------------------------------------------------------------|
| z-index       | string | 'z-50'      | Default z-index class                                                                        |
| progress      | bool   | true        | Enables the progress bar                                                                     |
| expandable    | bool   | false       | Enables the expandable effect by default                                                     |
| position      | string | 'top-right' | Default position (top-right, top-left, top-center, bottom-right, bottom-left, bottom-center) |
| timeout       | int    | 3           | Default auto-dismiss timeout in seconds                                                      |
| stacked       | bool   | false       | Piles the toasts instead of listing them                                                     |
| top-on-mobile | bool   | false       | Pins the toasts to the top below the `md` breakpoint                                         |

### Stacked Toasts

With `stacked` on, the toasts overlap into a pile instead of growing a vertical list.
The pile expands back into the list while the pointer is over it, and every timer and
progress bar in it freezes until the pointer leaves.

```php
'toast' => [
    'stacked' => true,
],
```

The most recent toast is the front of the pile, anchored to the edge its position
points at. Three layers peek out; deeper toasts wait at `opacity: 0` and reappear as
the ones in front leave. In the closed pile only the front card renders content — the
ones behind are reduced to their card shape.

The configuration is the default, not the only switch: both fluent APIs carry
`stacked` per toast, and the last event wins for the whole container — like `position`:

```php
$this->toast()->stacked()->success('Saved!')->send();
```

```js
$tsui.interaction('toast').stacked().success('Saved!').send();
```

The geometry (16px step per layer, 12px gap when expanded, three visible layers) is
not configurable. There is also no cap on how many toasts the expanded pile shows, so
a long queue can overflow the viewport — the same as the plain list does today.

Since the front of the pile is always the newest toast, the switch reverses the reading
order of the `top-*` positions: the plain list puts the oldest toast at the edge, the
expanded pile puts the newest. `bottom-*` reads the same either way.

Not to be confused with `expandable`, which is per-toast and truncates a long
*description* until the card is hovered. What Sonner and Nuxt UI call `expand` is this
`stacked` switch, inverted.

### Top on Mobile

Below the `md` breakpoint the toast ignores its position and sits at the bottom — the
base of `wrapper.first` is `justify-end`, and only `md:` variants tell the positions
apart. `top-on-mobile` pins them to the top instead, whatever the position says:

```php
'toast' => [
    'top-on-mobile' => true,
],
```

Works with `stacked`: the pile flips its anchor and the direction it grows. The enter
transition also follows the edge the toast comes from.

## Confirm/Cancel Method Signatures

Works the same as Dialog:

```php
$this->toast()
    ->question('Warning!', 'Are you sure?')
    ->confirm('Confirm', 'confirmed', 'Confirmed Successfully')
    ->cancel('Cancel', 'cancelled', 'Cancelled Successfully')
    ->send();

public function confirmed(string $message): void
{
    $this->toast()->success('Success', $message)->send();
}
```

Both methods are optional. Unlike Dialog, when only one button is defined, only that button appears.

### Additional Options

```php
// Timeout in seconds
$this->toast()->timeout(seconds: 10)->success('...')->send();

// Persistent (no auto-dismiss)
$this->toast()->persistent()->success('...')->send();

// Expandable (for long descriptions 30+ chars)
$this->toast()->expandable()->success('...')->send();

// Position: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
$this->toast()->position('top-left')->success('...')->send();

// Sole (only one toast at a time)
$this->toast()->sole()->success('...')->send();

// Stacked (piles the toasts instead of listing them)
$this->toast()->stacked()->success('...')->send();
```

### Flash (Redirect Support)

```php
$this->toast()
    ->success('Done!', 'Your money has been sent!')
    ->flash()
    ->send();

return $this->redirect(route('dashboard'));
```

A flashed toast is shown once, on the page it lands on. It is not replayed when a later
toast is dispatched on that same page, and it does not clear toasts already on screen.
It applies its `position` and `stacked` to the container when it lands.

### Controller Usage

```php
use TallStackUi\Traits\Interactions;

class PaymentController extends Controller
{
    use Interactions;

    public function update(Request $request)
    {
        $this->toast()->success('Updated!')->send();
    }
}
```

### Lifecycle Hooks

```php
$this->toast()
    ->success('...')
    ->hook([
        'close' => ['method' => 'onClose', 'params' => ['param1']],
        'timeout' => ['method' => 'onTimeout', 'params' => ['param1']],
    ])
    ->send();
```

### Window Events

```blade
<div x-on:toast:accepted.window="alert($event.detail.description)"
     x-on:toast:rejected.window="alert($event.detail.description)"
     x-on:toast:timeout.window="alert($event.detail.description)">
    ...
</div>
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->toast()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                     | Purpose                                                  |
|--------------------------------|----------------------------------------------------------|
| wrapper.first                  | Fixed full-screen positioning container                  |
| wrapper.second                 | Flex container for individual toast alignment            |
| wrapper.third                  | Toast card with rounded corners and shadow               |
| wrapper.fourth                 | Inner flex container for icon and content                |
| wrapper.position.top-x         | Vertical alignment for the `top-*` positions             |
| wrapper.position.bottom-x      | Vertical alignment for the `bottom-*` positions          |
| wrapper.position.x-left        | Horizontal alignment for `top-left` / `bottom-left`      |
| wrapper.position.x-right       | Horizontal alignment for `top-right` / `bottom-right`    |
| wrapper.position.x-center      | Horizontal alignment for `top-center` / `bottom-center`  |
| stack.inert                    | `display: contents`, applied when `stacked` is off       |
| stack.wrapper                  | The pile's box, which owns the hover area                |
| stack.item                     | The positioned card inside the pile                      |
| stack.content                  | Opacity transition for what the pile hides               |
| stack.align.left               | Alignment of the pile for the `-left` positions          |
| stack.align.right              | Alignment of the pile for the `-right` positions         |
| stack.align.center             | Alignment of the pile for the `-center` positions        |
| wrapper.position.top-on-mobile | Vertical alignment below `md` when `top-on-mobile` is on |
| icon.size                      | Type icon dimensions                                     |
| content.wrapper                | Text content flex wrapper                                |
| content.text                   | Title text styles                                        |
| content.description            | Description text styles                                  |
| buttons.wrapper.first          | Confirm/cancel buttons container                         |
| buttons.wrapper.second         | Close and expand buttons container                       |
| buttons.confirm                | Confirm button text styles                               |
| buttons.cancel                 | Cancel button text styles                                |
| buttons.close.wrapper          | Close button container                                   |
| buttons.close.class            | Close button styles                                      |
| buttons.close.size             | Close icon dimensions                                    |
| buttons.expand.wrapper         | Expand button container                                  |
| buttons.expand.class           | Expand button styles                                     |
| buttons.expand.size            | Expand icon dimensions                                   |
| progress.wrapper               | Progress bar background container                        |
| progress.bar                   | Progress bar fill styles                                 |
