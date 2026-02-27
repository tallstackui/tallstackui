# TallStackUI: Table

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

A full-featured data table component with server-side sorting, search filtering, pagination, row selection, expandable rows, row highlighting, and clickable row links. Designed to work with Livewire properties for reactive updates.

## Basic Usage

Minimal table with headers and rows:

```blade
<x-table :headers="$headers" :rows="$rows" />
```

In your Livewire component:

```php
class UsersTable extends Component
{
    public array $headers = [
        ['index' => 'name', 'label' => 'Name'],
        ['index' => 'email', 'label' => 'Email'],
        ['index' => 'action', 'label' => 'Actions', 'sortable' => false],
    ];

    public array $sort = ['column' => 'name', 'direction' => 'asc'];

    public int $quantity = 10;
    public ?string $search = null;

    public function render(): View
    {
        $users = User::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy($this->sort['column'], $this->sort['direction'])
            ->paginate($this->quantity);

        return view('livewire.users-table', ['rows' => $users]);
    }
}
```

```blade
<x-table :headers="$headers"
         :rows="$rows"
         :sort="$sort"
         :filter="['quantity' => 'quantity', 'search' => 'search']"
         striped
         paginate
         loading />
```

With custom column rendering:

```blade
<x-table :headers="$headers" :rows="$rows" :sort="$sort" filter loading paginate>
    @interact('column_action', $row)
        <x-button text="Edit" xs wire:click="edit({{ $row->id }})" />
    @endinteract
</x-table>
```

With selectable rows:

```blade
<x-table :headers="$headers"
         :rows="$rows"
         selectable
         selectable-property="id"
         wire:model="selectedIds" />
```

With expandable rows:

```blade
<x-table :headers="$headers" :rows="$rows" expandable>
    @interact('sub_table', $row)
        <p>Expanded content for {{ $row->name }}</p>
    @endinteract
</x-table>
```

With clickable row links:

```blade
<x-table :headers="$headers" :rows="$rows" link="/users/{id}" />
```

With row highlighting:

```blade
<x-table :headers="$headers" :rows="$rows" highlight />
```

Rows should include a `highlight` property (or custom property via `highlight-property`) with a color name (e.g., `'green'`, `'red'`).

## Attributes

| Attribute           | Type                                               | Default           | Description                                                                                                   |
|---------------------|----------------------------------------------------|-------------------|---------------------------------------------------------------------------------------------------------------|
| headers             | Collection\|array                                  | []                | Array of column definitions with `index`, `label`, and optional `sortable` and `unescaped` keys               |
| rows                | LengthAwarePaginator\|Paginator\|Collection\|array | []                | Data rows to display                                                                                          |
| headerless          | bool                                               | false             | Hides the table header row                                                                                    |
| striped             | bool                                               | false             | Applies alternating row background colors                                                                     |
| sort                | array\|null                                        | []                | Current sort state with `column` and `direction` keys (bind to a Livewire property)                           |
| filter              | bool\|array\|null                                  | null              | Enables filter controls. `true` for defaults, or `['quantity' => 'propertyName', 'search' => 'propertyName']` |
| loading             | bool                                               | false             | Shows a loading spinner overlay during Livewire updates                                                       |
| quantity            | array\|null                                        | [10, 25, 50, 100] | Options for the per-page quantity select                                                                      |
| paginate            | bool                                               | false             | Enables pagination links below the table                                                                      |
| persistent          | bool                                               | false             | Scrolls to table top after pagination                                                                         |
| simple-pagination   | bool                                               | false             | Uses simple (previous/next) pagination instead of full pagination                                             |
| selectable          | bool\|null                                         | null              | Enables row selection checkboxes (bind to a Livewire property via `wire:model`)                               |
| selectable-property | string\|null                                       | 'id'              | Row property used as the value for selection                                                                  |
| expandable          | bool                                               | false             | Enables expandable row sub-content via `@interact('sub_table', $row)`                                         |
| highlight           | bool                                               | false             | Enables row highlighting based on a color property in each row                                                |
| highlight-property  | string\|null                                       | 'highlight'       | Row property name containing the highlight color                                                              |
| link                | string\|null                                       | null              | URL template for clickable rows. Use `{column}` tokens (e.g., `/users/{id}`)                                  |
| blank               | bool                                               | false             | Opens row links in a new tab                                                                                  |
| on-each-side        | int\|null                                          | 1                 | Number of pagination links on each side of the current page                                                   |

## Slots

| Slot           | Description                                                                                                                         |
|----------------|-------------------------------------------------------------------------------------------------------------------------------------|
| header         | Content displayed above the table                                                                                                   |
| footer         | Content displayed below the table                                                                                                   |
| empty          | Custom empty state message                                                                                                          |
| column_{index} | Custom column renderer via `@interact('column_{index}', $row)` where `{index}` is the header index (dots replaced with underscores) |
| sub_table      | Expandable row content via `@interact('sub_table', $row)`                                                                           |

## Header Definition

Each header in the `headers` array supports these keys:

| Key       | Type   | Required | Description                                                                      |
|-----------|--------|----------|----------------------------------------------------------------------------------|
| index     | string | Yes      | Property name on the row object (use `'action'` for non-sortable action columns) |
| label     | string | No       | Column header display text                                                       |
| sortable  | bool   | No       | Whether the column is sortable (default: true, except `'action'` index)          |
| unescaped | bool   | No       | When true, renders the label as raw HTML                                         |

## Validation Constraints

- The `empty` message must be provided either via the `empty` slot/attribute or the `ts-ui::messages.table.empty` translation.
- The `quantity` and `search` translation keys must be present in `ts-ui::messages.table`.
- When `selectable` is true, `selectable-property` must not be blank.
- When `highlight` is true, `highlight-property` must not be blank.

## Selectable Rows

Bind selected rows to a Livewire array property:

```blade
<x-table :$headers :$rows selectable wire:model="selected" />
```

`$selected` will be an array of selected row data.

## Clickable Rows (Link)

Make rows clickable with dynamic URL interpolation using column values:

```blade
<x-table :$headers :$rows link="https://example.com/users/{id}" />

<!-- Using relationship data with dot notation -->
<x-table :$headers :$rows link="https://example.com/?postcode={address.postcode}" />

<!-- Open in new tab -->
<x-table :$headers :$rows link="https://example.com/users/{id}" blank />
```

## Expandable with Nested Tables

Use `@interact` directive to render sub-tables inside expandable rows:

```blade
<x-table :$headers :$rows expandable>
    @interact('sub_table', $row)
        <x-table :headers="[
            ['index' => 'property', 'label' => 'Property'],
            ['index' => 'value', 'label' => 'Value'],
        ]" :rows="[
            ['property' => 'Email', 'value' => $row->email],
            ['property' => 'Created', 'value' => $row->created_at->format('Y-m-d')],
        ]" />
    @endinteract
</x-table>
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->table()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name          | Purpose                                              |
|---------------------|------------------------------------------------------|
| wrapper             | Outer container with rounded corners and ring border |
| table.wrapper       | Scrollable table container                           |
| table.base          | Table element with dividers                          |
| table.sort          | Sort icon dimensions                                 |
| table.th            | Table header cell padding and text styling           |
| table.tbody         | Table body background and row dividers               |
| table.td            | Table data cell padding and text styling             |
| table.tr            | Table row base classes                               |
| table.thead.normal  | Default header row background                        |
| table.thead.striped | Header background when striped is enabled            |
| loading.table       | Loading state overlay opacity and cursor             |
| loading.icon        | Loading spinner positioning and animation            |
| empty               | Empty state cell text styling                        |
| filter.wrapper      | Filter controls container flex layout                |
| filter.quantity     | Quantity select width                                |
| filter.search       | Search input width                                   |
| slots.header        | Header slot text styling                             |
| slots.footer        | Footer slot text styling                             |
| expandable.wrapper  | Expandable row background                            |
| expandable.button   | Expand toggle button styling                         |
| expandable.content  | Expanded content padding                             |
