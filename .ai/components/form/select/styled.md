# TallStackUI: Select Styled

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A feature-rich styled select component built with Alpine.js, supporting single and multiple selection, searchable filtering, server-side data via API requests, grouped options, images, descriptions, lazy loading, and keyboard navigation. Renders as a custom dropdown instead of a native `<select>`.

## Basic Usage

```blade
<x-select.styled wire:model="color" label="Color" :options="['Red', 'Green', 'Blue']" />
```

```blade
<x-select.styled wire:model="users" label="Users" multiple searchable
    :options="[
        ['label' => 'John', 'value' => 1],
        ['label' => 'Jane', 'value' => 2],
    ]"
    select="label:label|value:value" />
```

```blade
<x-select.styled wire:model="category" label="Category"
    :request="['url' => '/api/categories', 'method' => 'get']"
    select="label:name|value:id" />
```

## Attributes

| Attribute    | Type                | Default | Description                                                                                                       |
|--------------|---------------------|---------|-------------------------------------------------------------------------------------------------------------------|
| id           | string\|null        | null    | Element ID                                                                                                        |
| label        | string\|null        | null    | Label text displayed above the select                                                                             |
| hint         | string\|null        | null    | Hint text displayed below the select                                                                              |
| placeholder  | string\|null        | null    | Placeholder text when no option is selected (defaults to translation)                                             |
| request      | string\|array\|null | null    | API endpoint for server-side options. String URL or array with `url`, `method` (get/post), and optional `params`. |
| multiple     | bool                | false   | Enables multiple selection mode                                                                                   |
| searchable   | bool                | false   | Enables search/filter input (automatically true when `request` is set)                                            |
| select       | string\|null        | null    | Mapping string for option keys in format `label:key\|value:key\|description:key\|image:key`                       |
| selectable   | array\|null         | []      | Resolved selectable keys used internally after parsing `select`                                                   |
| placeholders | array\|null         | null    | Override default placeholder texts (keys: `default`, `search`, `empty`)                                           |
| invalidate   | bool\|null          | null    | Prevents displaying validation error messages                                                                     |
| required     | bool                | false   | Hides the clear button, making a selection mandatory                                                              |
| limit        | int\|null           | null    | Maximum number of selectable items in multiple mode                                                               |
| lazy         | int\|null           | null    | Number of options to display initially before lazy-loading more (minimum 10)                                      |
| grouped      | bool\|null          | null    | Enables grouped option rendering                                                                                  |
| recycle      | bool\|null          | null    | Preserves previous results when reopening the select (overrides config)                                           |
| unfiltered   | bool\|null          | null    | Disables client-side filtering for API mode (overrides config)                                                    |
| options      | Collection\|array   | []      | Array of options for client-side mode                                                                             |
| after        | string\|null        | null    | Custom HTML content displayed when no options match the search                                                    |

## Grouped Options

Options can be organized into groups. When an option's `value` is an array of sub-options, the component automatically detects grouped mode (the `grouped` attribute is auto-set).

```blade
<x-select.styled wire:model="city" label="City"
    :options="[
        [
            'label' => 'Brazil',
            'description' => 'South America',
            'image' => 'https://example.com/br.png',
            'value' => [
                ['label' => 'São Paulo', 'value' => 4],
                ['label' => 'Rio de Janeiro', 'value' => 5],
            ]
        ],
        [
            'label' => 'United States',
            'value' => [
                ['label' => 'New York', 'value' => 7],
                ['label' => 'Los Angeles', 'value' => 8],
            ]
        ],
    ]"
    select="label:label|value:value" />
```

Key behaviors:
- Group headers display label, optional description, and optional image
- Only nested items within groups are selectable (group headers are display-only)
- Pre-selected values (via `wire:model`) are correctly resolved from nested items
- Search filters items within groups and hides groups with no matching items
- Works with both single and multiple selection modes
- The same `select` mapping applies to both groups and their nested items

## Validation Constraints

- The `options` and `request` cannot be defined at the same time.
- The `lazy` attribute must be greater than or equal to 10 (when using client-side options).
- When `request` is an array, the `url` key is required.
- When `request` is an array, the `method` must be `get` or `post`.
- When `request` is an array with `params`, it must be a non-empty array.

## Configuration

Configuration via `config/tallstackui.php` under `components.select.styled`:

| Key        | Default | Description                                                        |
|------------|---------|--------------------------------------------------------------------|
| unfiltered | false   | Allow all select API-styled components to be unfiltered by default |
| recycle    | false   | Preserves previous results when reopening the select               |

## Programmatic Control

Open or close a styled select from JavaScript:

```javascript
$tsui.open.select('name')
$tsui.close.select('name')
```

## API / Server-Side Integration

### Simple API Request

```blade
<x-select.styled :request="route('api.users')" />
```

### Advanced Request Configuration

```blade
<x-select.styled :request="[
    'url' => route('api.users'),
    'method' => 'get',
    'params' => ['library' => 'TallStackUi'],
]" />
```

### Server-Side Implementation

The component sends a `search` query parameter. Your endpoint must return a JSON array of objects:

```php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

Route::get('/users', function (Request $request) {
    $search = $request->get('search');

    return User::query()
        ->when($search, fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
        ->unless($search, fn (Builder $query) => $query->limit(10))
        ->get()
        ->map(fn (User $user): array => [
            'label' => $user->name,
            'value' => $user->id,
        ]);
})->name('api.users');
```

Use `unfiltered` when the server handles all filtering (the component won't filter client-side):

```blade
<x-select.styled :request="route('api.users')" unfiltered />
```

### Disabled Options

Include `disabled: true` in the option array to prevent selection:

```blade
<x-select.styled :options="[
    ['label' => 'Active', 'value' => 1],
    ['label' => 'Inactive', 'value' => 2, 'disabled' => true],
]" />
```

### Image and Description in Options

```blade
<x-select.styled :options="[
    ['label' => 'Taylor Otwell', 'value' => 1, 'image' => 'https://...', 'description' => 'Creator of Laravel'],
]" />

<!-- Custom field names -->
<x-select.styled :options="[
    ['name' => 'Taylor', 'id' => 1, 'avatar' => 'https://...', 'note' => 'Creator of Laravel'],
]" select="label:name|value:id|image:avatar|description:note" />
```

### After Slot (Custom Action When Empty)

```blade
<x-select.styled searchable :options="[1,2,3]">
    <x-slot:after>
        <div class="px-2 mb-2 flex justify-center items-center">
            <x-button x-on:click="show = false; $dispatch('confirmed', { term: search })">
                <span x-html="`Create user <b>${search}</b>`"></span>
            </x-button>
        </div>
    </x-slot:after>
</x-select.styled>
```

### Alpine.js Events

```blade
<x-select.styled :options="[...]"
    x-on:select="alert(`Selected: ${JSON.stringify($event.detail.select)}`)"
    x-on:remove="alert(`Removed: ${JSON.stringify($event.detail.select)}`)"
    multiple />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->select('styled')
    ->block('input.wrapper.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                           | Purpose                                                        |
|--------------------------------------|----------------------------------------------------------------|
| input.wrapper.base                   | Button trigger base styles (cursor, padding, ring, background) |
| input.wrapper.color                  | Default focus ring and text color                              |
| input.wrapper.error                  | Error state ring styles                                        |
| input.wrapper.round.left             | Removes left border radius when used as right-side addon       |
| input.wrapper.round.right            | Removes right border radius when used as left-side addon       |
| input.wrapper.borderless             | Removes ring/border when used as a side addon                  |
| input.content.wrapper.first          | Content area outer wrapper with overflow handling              |
| input.content.wrapper.second         | Content area inner wrapper for items                           |
| buttons.wrapper                      | Clear/chevron button container                                 |
| buttons.size                         | Clear/chevron icon dimensions                                  |
| buttons.base                         | Clear/chevron icon default color and hover                     |
| buttons.error                        | Clear/chevron icon error state color                           |
| floating.default                     | Floating panel wrapper styles                                  |
| floating.class                       | Floating panel overflow and width                              |
| floating.side                        | Floating panel min-width when used as side addon               |
| box.button.class                     | Search clear button positioning                                |
| box.button.icon                      | Search clear button icon styles                                |
| box.list.wrapper                     | Options list scrollable container                              |
| box.list.loading.wrapper             | Loading spinner container                                      |
| box.list.loading.class               | Loading spinner icon styles                                    |
| box.list.grouped.wrapper             | Group header wrapper styles                                    |
| box.list.grouped.options             | Group header flex layout                                       |
| box.list.grouped.base                | Group header content alignment                                 |
| box.list.grouped.image               | Group header image styles                                      |
| box.list.grouped.description.text    | Group header description text                                  |
| box.list.grouped.description.wrapper | Group header description container                             |
| box.list.item.wrapper                | Option item base styles (cursor, padding, hover)               |
| box.list.item.options                | Option item flex layout                                        |
| box.list.item.grouped                | Grouped option item indented layout                            |
| box.list.item.base                   | Option item content alignment                                  |
| box.list.item.selected               | Selected option highlight styles                               |
| box.list.item.disabled               | Disabled option styles                                         |
| box.list.item.image                  | Option item image styles                                       |
| box.list.item.check                  | Checkmark icon for selected items                              |
| box.list.item.description.text       | Option description text styles                                 |
| box.list.item.description.wrapper    | Option description container                                   |
| box.list.empty                       | Empty state text styles                                        |
| box.searchable.wrapper               | Search input container                                         |
| items.wrapper                        | Selected items display container                               |
| items.placeholder.text               | Placeholder text styles                                        |
| items.placeholder.wrapper            | Placeholder wrapper                                            |
| items.single                         | Single selected item text styles                               |
| items.multiple.item                  | Multiple selection tag/chip styles                             |
| items.multiple.label                 | Multiple selection tag label text                              |
| items.multiple.label.wrapper         | Multiple selection tag label container                         |
| items.multiple.icon                  | Multiple selection tag remove icon                             |
| items.multiple.image                 | Multiple selection tag image                                   |
| items.image                          | Selected item image in single mode                             |
