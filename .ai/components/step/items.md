# TallStackUI: Step Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A child component of `<x-step>` that defines a single step's content panel and metadata (title, description, completion state). The content is shown or hidden based on the currently selected step.

## Basic Usage

```blade
<x-step selected="1" simple helpers>
    <x-step.items step="1" title="Account" description="Set up your account">
        <p>Enter your account details here.</p>
    </x-step.items>
    <x-step.items step="2" title="Profile" description="Complete your profile">
        <p>Add your profile information.</p>
    </x-step.items>
    <x-step.items step="3" title="Done" completed>
        <p>You are all set!</p>
    </x-step.items>
</x-step>
```

With a custom ID for direct referencing:

```blade
<x-step.items step="1" title="Upload" id="upload-step">
    <p>Upload your documents.</p>
</x-step.items>
```

## Attributes

| Attribute   | Type         | Default    | Description                                                       |
|-------------|--------------|------------|-------------------------------------------------------------------|
| step        | int          | (required) | The step number identifying this item                             |
| title       | string\|null | null       | Title displayed in the step indicator                             |
| description | string\|null | null       | Description displayed below the title in the step indicator       |
| completed   | bool         | false      | Marks the step as completed (shows check icon and active styling) |
| id          | string\|null | null       | Optional HTML id attribute for the step panel container           |

## Slots

| Slot      | Description                                                  |
|-----------|--------------------------------------------------------------|
| (default) | Content displayed when this step is the active/selected step |
