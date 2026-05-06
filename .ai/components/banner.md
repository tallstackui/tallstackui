# TallStackUI: Banner

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A top-of-page banner component for announcements or notifications. Supports static text, randomized messages from arrays, animated entrance/exit transitions, scheduled display via an `until` date, and Livewire-driven wire mode for dynamic dispatching from the backend.

## Basic Usage

```blade
<x-banner text="We are currently undergoing scheduled maintenance." />
```

```blade
<x-banner text="New feature available!" color="green" close light animated />
```

```blade
<x-banner :text="['Tip: Use keyboard shortcuts.', 'Tip: Try dark mode.']"
           color="blue"
           until="2026-12-31" />
```

```blade
{{-- Wire mode: controlled via Livewire interaction trait --}}
<x-banner wire close />
```

## Attributes

| Attribute | Type                            | Default   | Description                                                               |
|-----------|---------------------------------|-----------|---------------------------------------------------------------------------|
| text      | string\|array\|Collection\|null | null      | Banner message text; arrays/collections pick a random entry               |
| color     | string\|array\|null             | 'primary' | Color theme, or array with `background` and `text` keys for custom colors |
| close     | bool                            | false     | Shows a dismiss button                                                    |
| animated  | bool                            | false     | Enables slide-down entrance and exit transitions                          |
| enter     | int\|null                       | 3         | Delay in seconds before the banner enters (used with animation)           |
| leave     | int\|null                       | null      | Delay in seconds before the banner auto-hides                             |
| until     | string\|Carbon\|null            | null      | Date after which the banner stops displaying                              |
| wire      | bool                            | false     | Enables Livewire-driven banner mode (controlled via `$this->banner()`)    |
| light     | bool                            | false     | Uses the light color style variant                                        |
| show      | bool                            | true      | Controls initial visibility                                               |
| size      | string\|null                    | 'sm'      | Vertical padding size: 'sm', 'md', or 'lg'                                |
| rotate    | bool\|string                    | false     | Marquee mode (right→left infinite scroll). See "Rotate" section           |
| separator | string\|null                    | ' • '     | Joiner used when `rotate` is on and `text` is an array                    |

## Slots

| Slot      | Description                                         |
|-----------|-----------------------------------------------------|
| (default) | Main content, used when `text` is not provided      |
| left      | Content rendered at the absolute left of the banner |

## Validation Constraints

- The `size` attribute must be one of: `sm`, `md`, `lg`.
- When `color` is an array, it must contain both `background` and `text` keys.
- The `until` attribute must be a valid date string or Carbon instance.
- The `rotate` attribute, when a string, must be one of `slow`, `normal`, `fast`.
- The `rotate` attribute cannot be combined with `wire` mode.

## Rotate (Marquee Mode)

`rotate` turns the banner text into a right-to-left marquee, useful for promotional ribbons or rolling announcements. The animation pauses on hover and is fully disabled when the user has `prefers-reduced-motion: reduce` set.

Each loop has a clear cycle: the text **enters from the far right** of the banner, crosses the viewport, **exits to the left**, the banner stays empty briefly, and the text **reappears from the right**. It is not a continuous flow without pause.

When `text` is an array, the behavior depends on `rotate`:

- **Without `rotate`** — one item is picked randomly at render (unchanged historical behavior).
- **With `rotate`** — all items are joined into a single rolling string using `separator` (default `" • "`).

```blade
{{-- Default speed (normal — 18s per loop) --}}
<x-banner rotate text="Free shipping nationwide!" color="green" />

{{-- Named speeds: slow (40s), normal (18s), fast (8s) --}}
<x-banner rotate="slow" text="Take your time reading this." />
<x-banner rotate="fast" text="Limited time offer!" color="red" />

{{-- Array of messages joined with the default separator " • " --}}
<x-banner rotate :text="['Free shipping', '15% off', 'Use code XYZ']" color="indigo" />

{{-- Custom separator --}}
<x-banner rotate :text="['One', 'Two', 'Three']" separator=" — " />

{{-- Combined with the existing `animated` slide-down entrance --}}
<x-banner animated rotate text="I slide in, then I roll." color="blue" />
```

`rotate` is only available in static mode. It cannot be combined with `wire` mode (the validation throws an exception). Both `<x-slot:left>` and `close` continue to work alongside `rotate`: when either is present, the rolling viewport reserves a lateral margin (`rotate.spacing.left` / `rotate.spacing.right`) so the text never travels beneath them.

### Speed normalization across viewports

Because the rolling distance scales with the banner width while the duration is fixed in seconds, the *perceived* speed (px/second) would naturally change between mobile and desktop. To compensate, the viewport is wrapped in a named container query (`tsui-banner-rotate`) and the durations shrink in narrower banners:

| Speed    | Desktop (`> 768px`) | Tablet (`≤ 768px`) | Mobile (`≤ 480px`) |
|----------|---------------------|--------------------|--------------------|
| `slow`   | 40s                 | 25s                | 16s                |
| `normal` | 18s                 | 11s                | 7s                 |
| `fast`   | 8s                  | 5s                 | 3s                 |

Result: the text covers a similar number of pixels per second on every device, but the cycle frequency is naturally higher on mobile (the loop completes more often). This is mathematically unavoidable when both viewport width and duration vary together.

## Livewire Programmatic Usage

```php
use TallStackUi\Traits\Interactions;

class CreateUser extends Component
{
    use Interactions;

    public function save(): void
    {
        $this->banner()->success('Operation completed!')->send();
        $this->banner()->error('Something went wrong.')->send();
        $this->banner()->warning('Please review your input.')->send();
        $this->banner()->info('System maintenance scheduled.')->send();
    }
}
```

### Options

```php
$this->banner()
    ->close()              // Add close button
    ->enter(seconds: 3)    // Delay before entering
    ->leave(seconds: 10)   // Auto-dismiss after seconds
    ->success('...')
    ->send();
```

### Flash (Redirect Support)

```php
$this->banner()
    ->success('Done!', 'Your money has been sent!')
    ->flash()
    ->send();

return $this->redirect(route('dashboard'));
```

### Controller Usage

```php
use TallStackUi\Traits\Interactions;

class PaymentController extends Controller
{
    use Interactions;

    public function update(Request $request)
    {
        $this->banner()->success('Updated!')->close()->leave(seconds: 10)->send();
    }
}
```

Unlike Dialog/Toast, Banner does **not** support `confirm()`, `cancel()`, or `question()`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->banner()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name           | Purpose                                                               |
|----------------------|-----------------------------------------------------------------------|
| wire                 | Sticky positioning classes for wire mode                              |
| wrapper              | Main flex container with padding and alignment                        |
| sizes.sm             | Small vertical padding                                                |
| sizes.md             | Medium vertical padding                                               |
| sizes.lg             | Large vertical padding                                                |
| slot.left            | Left slot absolute positioning and font styles                        |
| text                 | Centered text styles                                                  |
| icon                 | Icon dimensions for wire mode status icons                            |
| close                | Close button icon dimensions                                          |
| rotate.viewport      | Overflow-hidden container with container query + edge fade mask       |
| rotate.track         | Inline-block track that translates from `100cqi` to `-100%` of itself |
| rotate.item          | The rolling text node                                                 |
| rotate.spacing.left  | Margin reserved on the viewport when the `<x-slot:left>` is present   |
| rotate.spacing.right | Margin reserved on the viewport when the `close` button is present    |
| rotate.speeds.slow   | Animation utility for the slow rotation (40s per loop)                |
| rotate.speeds.normal | Animation utility for the normal rotation (18s per loop)              |
| rotate.speeds.fast   | Animation utility for the fast rotation (8s per loop)                 |
