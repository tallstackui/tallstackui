# TallStackUI: Sidebar Separator

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A sidebar section separator component that visually divides navigation groups. Available in three styles: simple text label, centered line with text, and right-aligned line with text. Respects the collapsible sidebar state by animating visibility.

## Basic Usage

Simple text separator (default):

```blade
<x-layout.sidebar.separator text="Main Navigation" />
```

Centered line separator:

```blade
<x-layout.sidebar.separator text="Settings" line />
```

Right-aligned line separator:

```blade
<x-layout.sidebar.separator text="Admin" line-right />
```

Using slot content instead of text attribute:

```blade
<x-layout.sidebar.separator>General</x-layout.sidebar.separator>
```

## Attributes

| Attribute  | Type         | Default | Description                                                   |
|------------|--------------|---------|---------------------------------------------------------------|
| text       | string\|null | null    | Label text displayed in the separator                         |
| simple     | bool\|null   | null    | Simple text label style (default when no style is specified)  |
| line       | bool\|null   | null    | Centered horizontal line with text overlay                    |
| line-right | bool\|null   | null    | Left-aligned text with horizontal line extending to the right |

## Slots

| Slot      | Description                                        |
|-----------|----------------------------------------------------|
| (default) | Content used when `text` attribute is not provided |

## Inherited Attributes

| Attribute   | Source                                                                              |
|-------------|-------------------------------------------------------------------------------------|
| collapsible | `<x-layout.sidebar collapsible>` enables visibility transitions for collapsed state |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->sideBar('separator')
    ->block('simple.wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                | Purpose                                        |
|---------------------------|------------------------------------------------|
| simple.wrapper            | Wrapper for the simple text separator          |
| simple.base               | Text styling for simple separator              |
| simple.base.visible       | Visible state (sidebar expanded)               |
| simple.base.hidden        | Hidden state (sidebar collapsed)               |
| line.wrapper.first        | Outer wrapper for centered line separator      |
| line.wrapper.second       | Absolute-positioned line container             |
| line.wrapper.third        | Centered text wrapper                          |
| line.border               | Horizontal line border styling                 |
| line.base                 | Text styling for centered line separator       |
| line.base.visible         | Visible state (sidebar expanded)               |
| line.base.hidden          | Hidden state (sidebar collapsed)               |
| line-right.wrapper.first  | Outer wrapper for right-aligned line separator |
| line-right.wrapper.second | Absolute-positioned line container             |
| line-right.wrapper.third  | Left-aligned text wrapper                      |
| line-right.border         | Horizontal line border styling                 |
| line-right.base           | Text styling for right-aligned line separator  |
| line-right.base.visible   | Visible state (sidebar expanded)               |
| line-right.base.hidden    | Hidden state (sidebar collapsed)               |
