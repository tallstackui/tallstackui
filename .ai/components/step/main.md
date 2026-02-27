# TallStackUI: Step

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A multi-step wizard component with three visual variations: simple bar indicators, numbered circles with dividers, and bordered panels. Supports navigation helpers (next/previous/finish buttons), Livewire property binding, and step-change events.

## Basic Usage

Simple steps with navigation helpers:

```blade
<x-step selected="1" helpers navigate navigate-previous>
    <x-step.items step="1" title="Account" description="Create your account">
        <p>Step 1 content goes here.</p>
    </x-step.items>
    <x-step.items step="2" title="Profile" description="Set up your profile">
        <p>Step 2 content goes here.</p>
    </x-step.items>
    <x-step.items step="3" title="Review" description="Review and submit">
        <p>Step 3 content goes here.</p>
    </x-step.items>
</x-step>
```

Circle variation with Livewire binding:

```blade
<x-step wire:model="currentStep" circles helpers>
    <x-step.items step="1" title="Details">
        <p>Enter your details.</p>
    </x-step.items>
    <x-step.items step="2" title="Confirm">
        <p>Confirm your information.</p>
    </x-step.items>
</x-step>
```

Panel variation with finish button:

```blade
<x-step selected="1" panels helpers navigate x-on:finish="alert('Done!')">
    <x-slot:finish>
        <x-button text="Submit" color="green" />
    </x-slot:finish>
    <x-step.items step="1" title="Step One">
        <p>First step content.</p>
    </x-step.items>
    <x-step.items step="2" title="Step Two">
        <p>Second step content.</p>
    </x-step.items>
</x-step>
```

## Attributes

| Attribute         | Type         | Default | Description                                                               |
|-------------------|--------------|---------|---------------------------------------------------------------------------|
| selected          | int\|null    | null    | Initially selected step number (or use `wire:model` for Livewire binding) |
| panels            | bool         | false   | Uses the bordered panels variation                                        |
| circles           | bool         | false   | Uses the numbered circles variation                                       |
| simple            | bool         | false   | Uses the simple bar indicators variation (default)                        |
| helpers           | bool         | false   | Shows next/previous/finish navigation buttons                             |
| navigate          | bool         | false   | Allows forward navigation by clicking step indicators                     |
| navigate-previous | bool         | false   | Shows a "Previous" button in the helpers area                             |
| variation         | string\|null | null    | Visual variation type (automatically set from panels/circles/simple)      |

## Slots

| Slot      | Description                                                                   |
|-----------|-------------------------------------------------------------------------------|
| (default) | `<x-step.items>` children defining each step's content                        |
| finish    | Custom finish button content shown when on the last step (requires `helpers`) |

## Events

| Event       | Detail           | Description                                                              |
|-------------|------------------|--------------------------------------------------------------------------|
| x-on:change | `{step: number}` | Fired when the active step changes via helper buttons                    |
| x-on:finish | `{step: number}` | Fired when the finish button is clicked (string `finish` attribute only) |

## Wireable Mode (Livewire Property Binding)

Bind the current step to a Livewire string property:

```blade
<!-- Livewire string property: $step - initial value: "1" -->
<x-step wire:model="step" helpers previous>
    <x-step.items step="1" title="Starting" description="Step One">
        Step one...
    </x-step.items>
    <x-step.items step="2" title="Advancing" description="Step Two">
        Step two...
    </x-step.items>
    <x-step.items step="3" title="Finishing" description="Step Three">
        Step three...
    </x-step.items>
</x-step>
```

Use `wire:model.live` for real-time server sync on every step change.

## Alpine.js Event Payloads

```blade
<x-step selected="1" helpers
    x-on:change="alert(`Changed: ${$event.detail.step}`)"
    x-on:finish="alert(`Finished: ${$event.detail.step}`)">
    <x-step.items step="1" title="Starting" description="Step One">
        Step one...
    </x-step.items>
    <x-step.items step="2" title="Advancing" description="Step Two">
        Step two...
    </x-step.items>
    <x-step.items step="3" title="Finishing" description="Step Three" completed>
        Step three...
    </x-step.items>
</x-step>
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->step()
    ->block('wrapper.simple', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                  | Purpose                             |
|-----------------------------|-------------------------------------|
| wrapper.panels              | Panels variation list container     |
| wrapper.simple              | Simple variation list container     |
| wrapper.circles             | Circles variation list container    |
| circles.li                  | Circle variation list item          |
| circles.wrapper             | Circle item flex/alignment wrapper  |
| circles.check               | Completed check icon size and color |
| circles.circle.wrapper      | Circle badge container              |
| circles.circle.inactive     | Inactive circle border and text     |
| circles.circle.current      | Current step circle border and text |
| circles.circle.border       | Completed circle border             |
| circles.circle.active       | Completed circle background         |
| circles.highlighter.wrapper | Small dot indicator container       |
| circles.highlighter.current | Current step dot color              |
| circles.highlighter.active  | Completed step dot color            |
| circles.divider.wrapper     | Connecting line between circles     |
| circles.divider.inactive    | Inactive divider color              |
| circles.divider.active      | Completed divider color             |
| circles.text.wrapper        | Step text container                 |
| circles.text.title          | Step title text styling             |
| circles.text.description    | Step description text styling       |
| simple.li                   | Simple variation list item          |
| simple.bar.wrapper          | Simple bar indicator wrapper        |
| simple.bar.inactive         | Inactive bar border                 |
| simple.bar.current          | Current step bar border             |
| simple.bar.active           | Completed bar border                |
| simple.text.title.wrapper   | Simple title text wrapper           |
| simple.text.title.inactive  | Inactive title color                |
| simple.text.title.current   | Current title color                 |
| simple.text.title.active    | Completed title color               |
| simple.text.description     | Simple description text styling     |
| panels.li                   | Panel variation list item           |
| panels.wrapper              | Panel group flex wrapper            |
| panels.check                | Completed check icon size           |
| panels.item                 | Panel item padding and font         |
| panels.circle.wrapper       | Panel circle badge container        |
| panels.circle.inactive      | Inactive panel circle border        |
| panels.circle.current       | Current panel circle background     |
| panels.circle.active        | Completed panel circle background   |
| panels.divider.wrapper      | Panel arrow divider container       |
| panels.divider.svg          | Panel arrow SVG color               |
| panels.text.number.active   | Active step number text color       |
| panels.text.number.inactive | Inactive step number text color     |
| panels.text.title.wrapper   | Panel title text wrapper            |
| panels.text.title.inactive  | Inactive panel title color          |
| panels.text.title.active    | Completed panel title color         |
| panels.text.description     | Panel description text styling      |
| content                     | Step content area margin            |
| helpers.wrapper             | Helper buttons flex container       |
| button.base                 | Navigation button base styling      |
| button.icon                 | Navigation button icon dimensions   |
