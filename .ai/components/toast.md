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

| Method     | Signature                                                                   | Description                                                                |
|------------|-----------------------------------------------------------------------------|----------------------------------------------------------------------------|
| success    | `success(string $title, ?string $description = null)`                       | Shows a success toast with a check circle icon                             |
| error      | `error(string $title, ?string $description = null)`                         | Shows an error toast with an X circle icon                                 |
| info       | `info(string $title, ?string $description = null)`                          | Shows an info toast with an information circle icon                        |
| warning    | `warning(string $title, ?string $description = null)`                       | Shows a warning toast with an exclamation circle icon                      |
| question   | `question(string $title, ?string $description = null)`                      | Shows a question toast with a question mark circle icon                    |
| confirm    | `confirm(?string $text, ?string $method, array\|string\|int\|null $params)` | Adds a confirm button that calls a Livewire method                         |
| cancel     | `cancel(?string $text, ?string $method, array\|string\|int\|null $params)`  | Adds a cancel button that optionally calls a Livewire method               |
| expandable | `expandable(bool $expand = true)`                                           | Enables the expandable effect for long descriptions                        |
| persistent | `persistent()`                                                              | Removes timeout and progress bar, toast stays until manually closed        |
| position   | `position(string $position)`                                                | Sets position dynamically (top-right, top-left, bottom-right, bottom-left) |
| sole       | `sole(bool $sole = true)`                                                   | When true, flushes all previous toasts and shows only this one             |
| timeout    | `timeout(?int $seconds = null)`                                             | Sets the auto-dismiss timeout in seconds                                   |
| hook       | `hook(array $hooks)`                                                        | Registers lifecycle hooks (allowed: `close`, `timeout`)                    |
| flash      | `flash(bool $dispatch = false)`                                             | Flashes the interaction to session for display after redirect              |
| send       | `send()`                                                                    | Dispatches the toast                                                       |

## Configuration

In `config/tallstackui.php` under `components.toast`:

| Option     | Type   | Default     | Description                                                       |
|------------|--------|-------------|-------------------------------------------------------------------|
| z-index    | string | 'z-50'      | Default z-index class                                             |
| progress   | bool   | true        | Enables the progress bar                                          |
| expandable | bool   | false       | Enables the expandable effect by default                          |
| position   | string | 'top-right' | Default position (top-right, top-left, bottom-right, bottom-left) |
| timeout    | int    | 3           | Default auto-dismiss timeout in seconds                           |

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

// Position: top-left, top-right, bottom-left, bottom-right
$this->toast()->position('top-left')->success('...')->send();

// Sole (only one toast at a time)
$this->toast()->sole()->success('...')->send();
```

### Flash (Redirect Support)

```php
$this->toast()
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

| Block Name             | Purpose                                       |
|------------------------|-----------------------------------------------|
| wrapper.first          | Fixed full-screen positioning container       |
| wrapper.second         | Flex container for individual toast alignment |
| wrapper.third          | Toast card with rounded corners and shadow    |
| wrapper.fourth         | Inner flex container for icon and content     |
| icon.size              | Type icon dimensions                          |
| content.wrapper        | Text content flex wrapper                     |
| content.text           | Title text styles                             |
| content.description    | Description text styles                       |
| buttons.wrapper.first  | Confirm/cancel buttons container              |
| buttons.wrapper.second | Close and expand buttons container            |
| buttons.confirm        | Confirm button text styles                    |
| buttons.cancel         | Cancel button text styles                     |
| buttons.close.wrapper  | Close button container                        |
| buttons.close.class    | Close button styles                           |
| buttons.close.size     | Close icon dimensions                         |
| buttons.expand.wrapper | Expand button container                       |
| buttons.expand.class   | Expand button styles                          |
| buttons.expand.size    | Expand icon dimensions                        |
| progress.wrapper       | Progress bar background container             |
| progress.bar           | Progress bar fill styles                      |
