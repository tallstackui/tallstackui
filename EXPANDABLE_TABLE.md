# Expandable Table (Sub-Tables)

This documentation covers the **expandable rows** feature for the TallStackUI Table component, allowing infinite nested sub-tables.

---

## Basic Usage

Add `expandable` property to enable row expansion:

```blade
<x-table :rows="$products" :headers="$headers" expandable>
    @interact('sub_table', $row)
        {{-- Your sub-table content here --}}
    @endinteract
</x-table>
```

---

## Simple Example (Static Sub-Table)

```blade
<x-table :rows="$products" :headers="[
    ['index' => 'name', 'label' => 'Product'],
    ['index' => 'price', 'label' => 'Price'],
]" expandable striped>

    @interact('sub_table', $row)
        @if (!empty($row['categories']))
            <x-table :rows="$row['categories']" :headers="[
                ['index' => 'name', 'label' => 'Category'],
                ['index' => 'description', 'label' => 'Description'],
            ]" striped />
        @else
            <p class="text-gray-500 italic">No categories</p>
        @endif
    @endinteract

</x-table>
```

---

## Advanced Example (Livewire Component)

For full functionality (actions, filters, pagination), use a Livewire component:

### 1. Create the Sub-Table Component

```php
// app/Livewire/CategoryTable.php
namespace App\Livewire;

use Livewire\Component;

class CategoryTable extends Component
{
    public array $categories = [];
    public int $productId;

    public function mount(int $productId, array $categories): void
    {
        $this->productId = $productId;
        $this->categories = $categories;
    }

    public function edit(int $index): void
    {
        // Handle edit action
        $this->dispatch('category-edit', [
            'category' => $this->categories[$index],
        ]);
    }

    public function delete(int $index): void
    {
        unset($this->categories[$index]);
        $this->categories = array_values($this->categories);
        
        $this->dispatch('category-deleted');
    }

    public function render()
    {
        return view('livewire.category-table');
    }
}
```

### 2. Create the View

```blade
{{-- resources/views/livewire/category-table.blade.php --}}
<div>
    <x-table :rows="$categories" :headers="[
        ['index' => 'name', 'label' => 'Category'],
        ['index' => 'description', 'label' => 'Description'],
        ['index' => 'action', 'label' => 'Actions'],
    ]" striped>

        @interact('column_action', $row)
            <div class="flex gap-2">
                <x-button.circle 
                    color="primary" 
                    icon="pencil" 
                    sm
                    wire:click="edit({{ $loop->index }})"
                />
                <x-button.circle 
                    color="red" 
                    icon="trash" 
                    sm
                    wire:click="delete({{ $loop->index }})"
                    wire:confirm="Are you sure?"
                />
            </div>
        @endinteract

    </x-table>
</div>
```

### 3. Use in Parent Table

```blade
<x-table :rows="$products" :headers="$headers" expandable>

    @interact('sub_table', $row)
        <livewire:category-table 
            :product-id="$row['id']" 
            :categories="$row['categories']" 
            :key="'cat-'.$row['id']" 
        />
    @endinteract

</x-table>
```

---

## Infinite Nesting

Sub-tables can contain other expandable tables:

```blade
<x-table :rows="$products" :headers="$productHeaders" expandable>
    @interact('sub_table', $row)
        <x-table :rows="$row['categories']" :headers="$categoryHeaders" expandable>
            @interact('sub_table', $row)
                <x-table :rows="$row['items']" :headers="$itemHeaders" />
            @endinteract
        </x-table>
    @endinteract
</x-table>
```

---

## Personalization

Customize sub-table styling via personalization:

```php
'subTable' => [
    'wrapper' => 'bg-gray-50 dark:bg-dark-800',
    'expandButton' => 'text-gray-500 hover:text-gray-700',
    'table' => 'min-w-full divide-y divide-gray-200 ml-4',
    'th' => 'px-3 py-2 text-left text-xs font-semibold',
    'tbody' => 'divide-y divide-gray-200 bg-white',
    'td' => 'px-3 py-2 text-xs text-gray-500',
    'tr' => '',
],
```

---

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `expandable` | `bool` | `false` | Enable expandable rows |

---

## Important Notes

- Use `:key` when rendering Livewire components in loops
- Static arrays don't support filtering/pagination (use Livewire components)
- Each nested table maintains independent expand state
