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

## Slots

| Slot      | Description                                         |
|-----------|-----------------------------------------------------|
| (default) | Main content, used when `text` is not provided      |
| left      | Content rendered at the absolute left of the banner |

## Validation Constraints

- The `size` attribute must be one of: `sm`, `md`, `lg`.
- When `color` is an array, it must contain both `background` and `text` keys.
- The `until` attribute must be a valid date string or Carbon instance.

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

| Block Name | Purpose                                        |
|------------|------------------------------------------------|
| wire       | Sticky positioning classes for wire mode       |
| wrapper    | Main flex container with padding and alignment |
| sizes.sm   | Small vertical padding                         |
| sizes.md   | Medium vertical padding                        |
| sizes.lg   | Large vertical padding                         |
| slot.left  | Left slot absolute positioning and font styles |
| text       | Centered text styles                           |
| icon       | Icon dimensions for wire mode status icons     |
| close      | Close button icon dimensions                   |
