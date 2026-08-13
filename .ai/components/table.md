# TallStackUI: Table

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A full-featured data table component with server-side sorting, search filtering, pagination, row selection, expandable rows, row highlighting, and clickable row links. Inside Livewire it drives everything through wire directives; outside it, sorting, filtering and pagination travel through the query string. See [Outside Livewire](#outside-livewire).

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

| Attribute           | Type                                               | Default           | Description                                                                                                                                                                 |
|---------------------|----------------------------------------------------|-------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| headers             | Collection\|array                                  | []                | Array of column definitions with `index`, `label`, and optional `sortable` and `unescaped` keys                                                                             |
| rows                | LengthAwarePaginator\|Paginator\|Collection\|array | []                | Data rows to display                                                                                                                                                        |
| headerless          | bool                                               | false             | Hides the table header row                                                                                                                                                  |
| striped             | bool                                               | false             | Applies alternating row background colors                                                                                                                                   |
| compact             | bool                                               | false             | Tightens the vertical padding of the header, the rows, the empty message and the expandable content. Globally configurable                                                  |
| sort                | array\|null                                        | []                | Current sort state with `column` and `direction` keys (bind to a Livewire property)                                                                                         |
| filter              | bool\|array\|null                                  | null              | Enables filter controls. `true` for defaults, or `['quantity' => 'propertyName', 'search' => 'propertyName']`. Globally configurable                                        |
| loading             | bool                                               | false             | Shows a loading overlay during Livewire updates                                                                                                                             |
| indicator           | string\|null                                       | null              | Loading overlay look. Null keeps the original icon. `spinner` or `spinner.{type}` renders a Spinner. Falls back to the table `indicator` config                             |
| quantity            | array\|null                                        | [10, 25, 50, 100] | Options for the per-page quantity select. Globally configurable                                                                                                             |
| paginate            | bool                                               | false             | Enables pagination links below the table. Globally configurable                                                                                                             |
| persistent          | bool\|string                                       | false             | Keeps the table in view after paginating or filtering. A bare flag anchors on the table itself; a string anchors on the element with that id. See [Persistent](#persistent) |
| simple-pagination   | bool                                               | false             | Uses simple (previous/next) pagination instead of full pagination. Globally configurable                                                                                    |
| paginator           | string\|null                                       | 'simple'          | The look of the pagination: `simple`, `minimal` or `compact`, or a view path of your own. See [Paginator](#paginator)                                                       |
| selectable          | bool\|null                                         | null              | Enables row selection checkboxes (bind to a Livewire property via `wire:model`)                                                                                             |
| selectable-property | string\|null                                       | 'id'              | Row property used as the value for selection                                                                                                                                |
| expandable          | bool                                               | false             | Enables expandable row sub-content via `@interact('sub_table', $row)`                                                                                                       |
| highlight           | bool                                               | false             | Enables row highlighting based on a color property in each row                                                                                                              |
| highlight-property  | string\|null                                       | 'highlight'       | Row property name containing the highlight color                                                                                                                            |
| link                | string\|null                                       | null              | URL template for clickable rows. Use `{column}` tokens (e.g., `/users/{id}`)                                                                                                |
| blank               | bool                                               | false             | Opens row links in a new tab                                                                                                                                                |
| on-each-side        | int\|null                                          | 1                 | Number of pagination links on each side of the current page                                                                                                                 |
| skeleton            | bool\|int\|null                                    | null              | Renders a structural placeholder instead of the rows. A bare flag draws 5 rows; an integer sets the count. See [Skeleton](#skeleton)                                        |

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
- When `persistent` is a string, it must not be empty.
- `paginator` must be one of `simple`, `minimal`, `compact`, or contain a dot (a view path).

## Events

| Event      | Payload    | Fires on                                              |
|------------|------------|-------------------------------------------------------|
| `select`   | `{ row }`  | a row checkbox only — **not** the select-all checkbox |
| `selected` | `{ rows }` | any change to the selection, including select-all     |

`row` is the whole row object; `rows` is an array of `selectable-property` values.

```blade
<div x-data="{ rows: [] }" x-on:selected="rows = $event.detail.rows">
    <x-table :$headers :$rows selectable />
</div>
```

Prefer `selected` when you need the current selection: `select` never fires for
select-all, so listening to it alone misses that path. Inside Livewire, `selected` also
fires when the server pushes a new value into the entangled property, so it can run on a
re-render and not only on a click.

## Selectable Rows

Bind selected rows to a Livewire array property:

```blade
<x-table :$headers :$rows selectable wire:model="selected" />
```

`$selected` will be an array of selected row data.

Outside Livewire there is nothing to entangle; the selection lives in Alpine and is
reported through the events above.

## Outside Livewire

The table renders in a plain Blade view or from a controller. Sorting, filtering and
pagination move to the query string, and the application reads them back:

```php
class UserController
{
    public function index(Request $request): View
    {
        $sort = $request->query('sort', ['column' => 'id', 'direction' => 'desc']);

        return view('users', [
            'sort' => $sort,
            'rows' => User::query()
                ->when($request->query('search'), fn (Builder $query, string $search) => $query->whereAny(['name', 'email'], 'like', "%{$search}%"))
                ->orderBy($sort['column'], $sort['direction'])
                ->paginate((int) $request->query('quantity', 10)),
        ]);
    }
}
```

```blade
<x-table :$headers :$rows :$sort filter paginate />
```

```
?search=foo&quantity=25&sort[column]=name&sort[direction]=asc&page=2
```

The `search` and `quantity` parameter names come from `filter`, so you control them.
Filtering or sorting resets `page`; every other parameter is preserved.

| Feature    | Inside Livewire         | Outside                  |
|------------|-------------------------|--------------------------|
| pagination | `wire:click="gotoPage"` | `<a href>`               |
| sorting    | `wire:click="$set"`     | `<a href>`               |
| filter     | `wire:model.live`       | Alpine rewriting the URL |
| loading    | `wire:loading`          | not rendered             |
| selectable | entangled array         | Alpine array plus events |

Two caveats:

- `loading` needs `wire:loading` and is not rendered.
- Always validate `sort[column]` against a whitelist before it reaches `orderBy`. It
  comes from the URL, and the table does not sanitize it for you.

## Compact

`compact` tightens the vertical padding so more rows fit on a screen. It reaches the
header cells, the data cells, the empty message and the expandable content; the
horizontal padding, the type scale and the colors are untouched.

```blade
<x-table :$headers :$rows compact />
```

It works with everything else — `striped`, `selectable`, `expandable`, `paginate` — and
the skeleton follows it, so a lazy table does not jump when the real rows arrive:

```blade
<x-table :$headers :$rows compact skeleton />
```

Each affected block has a `-compact` twin, and `compact` swaps the whole string rather
than layering on top of it. Customizing `table.td` therefore leaves a compact table
alone; customize `table.td-compact` as well when both modes are in use.

An application that wants tight rows everywhere sets it once, in the configuration —
see [Global Defaults](#global-defaults) — and `:compact="false"` gives a single table
the roomy padding back.

> Not to be confused with `paginator="compact"`, which is one of the pagination looks
> below and says nothing about row density. The two combine freely.

## Paginator

`paginator` names the look of the pagination. The same name styles both the numbered mode
and `simple-pagination`.

```blade
<x-table :$headers :$rows paginate />                      {{-- the configured default --}}
<x-table :$headers :$rows paginate paginator="compact" />  {{-- this table only --}}
```

| Variant   | Numbered                                          | `simple-pagination`               |
|-----------|---------------------------------------------------|-----------------------------------|
| `simple`  | rail with a floating pill, chevrons outside it    | two tinted `rounded-full` buttons |
| `minimal` | no surfaces at all, current page ruled underneath | two underline-on-hover text links |
| `compact` | one bordered shell holding `‹ 3 / 12 ›`           | the same shell, page number only  |

The numbered mode needs a total and a last page, which only a `LengthAwarePaginator`
carries. Rows coming from `simplePaginate()` have neither, so they render the simple
mode whether or not the flag was given:

```blade
{{-- both render previous/next --}}
<x-table :$headers :rows="User::simplePaginate(10)" paginate />
<x-table :$headers :rows="User::paginate(10)" paginate simple-pagination />
```

`compact` collapses the page list into an indicator, so one control serves every width.
It needs `lastPage()`, which a simple paginator does not have — in that mode it shows the
current page alone.

A value containing a dot is treated as a view path, for a paginator of your own:

```blade
<x-table :$headers :$rows paginate paginator="components.my-paginator" />
```

Such a view receives `paginator`, `elements`, and `livewire`, `simple`, `name`, `dusk`,
`fragment` and `scroll` already resolved.

## Global Defaults

Seven props can be set once for every table, in `config/ts-ui.php`:

```php
'table' => [
    TallStackUi\Components\Table\Component::class,
    [
        'paginator' => 'simple',
        'paginate' => true,
        'simple-pagination' => false,
        'filter' => true,
        'quantity' => [5, 10, 25],
        'compact' => true,
        'indicator' => null, // 'spinner' or 'spinner.bars'
    ],
],
```

Each is a default, not a lock — passing the prop inline always wins, including turning a
global default back off:

```blade
<x-table :$headers :$rows :paginate="false" :filter="false" :compact="false" />
```

## Persistent

Keeps the reader in place after paginating or filtering.

```blade
{{-- anchors on the table itself --}}
<x-table :$headers :$rows paginate persistent />

{{-- anchors on an element you own, so the card header stays in view --}}
<div id="users">
    <x-card>
        <x-table :$headers :$rows paginate persistent="users" />
    </x-card>
</div>
```

| Value                | Inside Livewire      | Outside                                                |
|----------------------|----------------------|--------------------------------------------------------|
| `false`              | nothing              | nothing                                                |
| `persistent`         | scrolls to the table | `id` on the wrapper, `#table-{pageName}` on every link |
| `persistent="users"` | scrolls to `#users`  | `#users` on every link, no `id` on the wrapper         |

Inside Livewire nothing reloads, so the scroll is done in script. Outside, every link is
a full page load and the URL fragment is what survives it.

When the table anchors itself it uses the `id` you passed, falling back to
`table-{pageName}` derived from the paginator. Without a paginator and without an `id`
there is nothing stable to derive from, and `persistent` has no effect outside Livewire —
pass an `id` in that case.

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

## Skeleton

Renders a placeholder shaped like the table, for the first paint before any rows
exist.

```blade
<x-table :$headers skeleton />                        {{-- 5 rows --}}
<x-table :$headers skeleton="8" selectable paginate />
```

Everything is derived from props the table already has: columns and their real
labels from `$headers`, a checkbox column when `selectable`, a toggle column when
`expandable`, a filter bar when `filter`, a pagination footer when `paginate`.
Without headers it falls back to four generic columns.

Only the cells become bars — the header row keeps its real labels, since it is
already known and serves as the visual anchor while the data loads.

### With `#[Lazy]`

```php
#[Lazy]
class UsersTable extends Component
{
    // A class-level default, which is what survives into the placeholder.
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

Livewire skips `mount()` when rendering a placeholder but hands the component's
class-level property defaults to that view. Headers declared as a class default
therefore reach the skeleton; headers assigned inside `mount()` do not, and the
fallback column count applies.

The messages normally required by `validate()` (`empty`, `quantity`, `search`)
are not checked in skeleton mode, because a skeleton renders no text. Every other
validation still runs. Any integer below `1` throws.

`skeleton` is not `loading`: `loading` dims rows already on screen during a sort,
search or pagination round trip, `skeleton` stands in for rows that do not exist yet.

### Customizations Carry Over

The `skeleton.*` blocks are only the bars. Everything structural is resolved
from this component's **own, existing blocks**, because the skeleton view calls
the same `classes()` as the normal one — customization is resolved on the
component, not on the view. Whatever you already changed applies to the
placeholder too, so the box keeps matching the box it stands in for. Scopes
work the same, including when they target the placeholder alone.

Table reuses `wrapper`, the `table.*` set, `row.striped` and `filter.*`.

Blocks the placeholder does not render have nothing to act on there.
Customizing them is not an error; it simply has no effect while the skeleton
is on screen.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->table()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                 | Purpose                                                               |
|----------------------------|-----------------------------------------------------------------------|
| wrapper                    | Outer container with rounded corners and ring border                  |
| table.wrapper              | Scrollable table container                                            |
| table.base                 | Table element with dividers                                           |
| table.sort                 | Sort icon dimensions                                                  |
| table.th                   | Table header cell padding and text styling                            |
| table.th-compact           | Header cell used instead of `table.th` under `compact`                |
| table.tbody                | Table body background and row dividers                                |
| table.td                   | Table data cell padding and text styling                              |
| table.td-compact           | Data cell used instead of `table.td` under `compact`                  |
| table.tr                   | Table row base classes                                                |
| table.thead.normal         | Default header row background                                         |
| table.thead.striped        | Header background when striped is enabled                             |
| loading.table              | Loading state overlay opacity and cursor                              |
| loading.icon               | Default loading icon positioning and animation                        |
| loading.indicator          | Wrapper around a Spinner `indicator`                                  |
| empty                      | Empty state cell text styling                                         |
| empty-compact              | Empty state cell used instead of `empty` under `compact`              |
| filter.wrapper             | Filter controls container flex layout                                 |
| filter.quantity            | Quantity select width                                                 |
| filter.search              | Search input width                                                    |
| slots.header               | Header slot text styling                                              |
| slots.footer               | Footer slot text styling                                              |
| expandable.wrapper         | Expandable row background                                             |
| expandable.button          | Expand toggle button styling                                          |
| expandable.content         | Expanded content padding                                              |
| expandable.content-compact | Expanded content used instead of `expandable.content` under `compact` |
| skeleton.animation         | Pulse animation applied to the whole placeholder                      |
| skeleton.bar               | Base look of every placeholder bar                                    |
| skeleton.cell              | Body cell bar dimensions                                              |
| skeleton.checkbox          | Selection checkbox placeholder dimensions                             |
| skeleton.expand            | Expand toggle placeholder dimensions                                  |
| skeleton.header            | Header bar dimensions, used only without `$headers`                   |
| skeleton.filter.quantity   | Quantity filter placeholder dimensions                                |
| skeleton.filter.search     | Search filter placeholder dimensions                                  |
| skeleton.paginate.wrapper  | Pagination footer alignment                                           |
| skeleton.paginate.bar      | Pagination placeholder dimensions                                     |
