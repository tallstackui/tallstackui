# TallStackUI: Dialog

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A modal dialog component for displaying alerts, confirmations, and question prompts with icon, title, description, and action buttons. Dispatched programmatically from Livewire components or controllers via the Interactions trait.

## Basic Usage

Place the component tag once in your layout file:

```blade
<x-dialog />
```

Dispatch from a Livewire component:

```php
use TallStackUi\Traits\Interactions;

class MyComponent extends Component
{
    use Interactions;

    public function save(): void
    {
        $this->dialog()->success('Saved!', 'Your record has been updated.')->send();
    }

    public function delete(int $id): void
    {
        $this->dialog()
            ->question('Delete Record?', 'This action cannot be undone.')
            ->confirm('Yes, delete', 'confirmDelete', $id)
            ->cancel('Cancel')
            ->send();
    }

    public function confirmDelete(int $id): void
    {
        // perform deletion
    }
}
```

Dispatch from a Controller (auto-flashes to session):

```php
use TallStackUi\Traits\Interactions;

class ItemController extends Controller
{
    use Interactions;

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        $this->dialog()->success('Deleted!', 'The item has been removed.')->send();

        return redirect()->back();
    }
}
```

## Interaction Methods

| Method     | Signature                                                                   | Description                                                   |
|------------|-----------------------------------------------------------------------------|---------------------------------------------------------------|
| success    | `success(string $title, ?string $description = null)`                       | Shows a success dialog with a check circle icon               |
| error      | `error(string $title, ?string $description = null)`                         | Shows an error dialog with an X circle icon                   |
| info       | `info(string $title, ?string $description = null)`                          | Shows an info dialog with an information circle icon          |
| warning    | `warning(string $title, ?string $description = null)`                       | Shows a warning dialog with an exclamation circle icon        |
| question   | `question(string $title, ?string $description = null)`                      | Shows a question dialog with a question mark circle icon      |
| confirm    | `confirm(?string $text, ?string $method, array\|string\|int\|null $params)` | Adds a confirm button that calls a Livewire method            |
| cancel     | `cancel(?string $text, ?string $method, array\|string\|int\|null $params)`  | Adds a cancel button that optionally calls a Livewire method  |
| persistent | `persistent()`                                                              | Prevents cl``osing the dialog by clicking outside             |
| hook       | `hook(array $hooks)`                                                        | Registers lifecycle hooks (allowed: `ok`, `close`, `dismiss`) |
| flash      | `flash(bool $dispatch = false)`                                             | Flashes the interaction to session for display after redirect |
| send       | `send()`                                                                    | Dispatches the dialog                                         |

## Configuration

In `config/tallstackui.php` under `components.dialog`:

| Option     | Type   | Default | Description                                                |
|------------|--------|---------|------------------------------------------------------------|
| z-index    | string | 'z-50'  | Default z-index class                                      |
| overflow   | bool   | false   | When true, avoids hiding body overflow                     |
| blur       | bool   | false   | Enables background blur effect                             |
| persistent | bool   | false   | When true, prevents closing by clicking outside by default |

## Confirm/Cancel Method Signatures

The `confirm()` and `cancel()` methods accept: text, method name, and params:

```php
public function save(): void
{
    $this->dialog()
        ->question('Warning!', 'Are you sure?')
        ->confirm('Confirm', 'confirmed', 'Confirmed Successfully')
        ->cancel('Cancel', 'cancelled', 'Cancelled Successfully')
        ->send();
}

public function confirmed(string $message): void
{
    $this->dialog()->success('Success', $message)->send();
}

public function cancelled(string $message): void
{
    $this->dialog()->error('Cancelled', $message)->send();
}
```

Both methods are optional. You can use only one, make them static (text only), or pass only method/params:

```php
// Static buttons (no method call)
->confirm('Confirm')
->cancel('Cancel')

// Named params only
->confirm(method: 'confirmed', params: 'data')
->cancel(method: 'cancelled', params: 'data')

// Confirmation with non-question types
$this->dialog()
    ->success('Done!', 'Process completed.')
    ->confirm('Undo', 'undo')
    ->cancel('Ok')
    ->send();
```

### Flash (Redirect Support)

Use `.flash()` to display after a redirect:

```php
$this->dialog()
    ->success('Done!', 'Your money has been sent!')
    ->flash()
    ->send();

return $this->redirect(route('dashboard'));
```

### Controller Usage

When using in Controllers, `confirm()` and `cancel()` are **not available** (no Livewire context). Only simple notifications work:

```php
use TallStackUi\Traits\Interactions;

class PaymentController extends Controller
{
    use Interactions;

    public function update(Request $request)
    {
        $this->dialog()->success('Updated!')->send();
    }
}
```

### Lifecycle Hooks

```php
$this->dialog()
    ->success('...')
    ->hook([
        'ok' => ['method' => 'onOk', 'params' => ['param1', 'param2']],
        'close' => ['method' => 'onClose', 'params' => ['param1']],
        'dismiss' => ['method' => 'onDismiss', 'params' => ['param1']],
    ])
    ->send();
```

Hook params can also be callables: `'params' => fn () => ['param1', 'param2']`

### Window Events

```blade
<div x-on:dialog:accepted.window="alert($event.detail.description)"
     x-on:dialog:rejected.window="alert($event.detail.description)"
     x-on:dialog:dismissed.window="alert($event.detail.description)">
    ...
</div>
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->dialog()
    ->block('background', 'your-tailwind-classes');
```

### Available Blocks

| Block Name               | Purpose                                     |
|--------------------------|---------------------------------------------|
| background               | Fixed overlay backdrop behind the dialog    |
| wrapper.first            | Fixed full-screen container                 |
| wrapper.second           | Centering flex container                    |
| wrapper.third            | Dialog card with rounded corners and shadow |
| icon.wrapper             | Circular icon background container          |
| icon.size                | Icon dimensions                             |
| text.wrapper             | Title and description container             |
| text.title               | Title heading styles                        |
| text.description.wrapper | Description text container                  |
| text.description.text    | Description paragraph styles                |
| buttons.wrapper          | Action buttons grid container               |
| buttons.confirm          | Confirm button styles                       |
| buttons.close.wrapper    | Close button container                      |
| buttons.close.icon       | Close X icon styles                         |
