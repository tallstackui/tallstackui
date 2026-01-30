# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

TallStackUI is a suite of Blade components for Laravel TALL Stack applications (Tailwind CSS, Alpine.js, Laravel, Livewire). It provides 40+ reusable components for building modern web interfaces. Our current goal is prepare the release of v3.0.

**Stack:**

- PHP 8.1+,
- Laravel 10/11/12,
- Livewire 3.5+,
- Tailwind CSS 4,
- Alpine.js,
- Vite 7

## Essential Commands

```bash
# Build & Development
npm run build              # Build JS + Tailwind CSS
npm run dev                # Watch mode

# Testing
composer test                    # Run all Pest tests
composer test:feature --parallel # Feature tests only (parallel)
composer test:browser            # Browser/Dusk tests only
composer type                    # Type coverage check

# Code Quality
./vendor/bin/pint --parallel     # Format PHP
npm run lint:fix                 # Fix ESLint issues
npm run format                   # Format JS with Prettier
composer format                  # Run all formatters

# CI Pipeline
composer ci                # Full CI: pint, feature tests, browser tests
```

## Development Workflow

After completing any code changes (PHP, JS, or CSS), always run:

```bash
npm run build
```

This ensures the built assets in `dist/` are updated and reflect your changes.

## Architecture

### Component Structure

Components live in `src/View/Components/` and extend `TallStackUiComponent`. Each component has:

- A PHP class defining properties and customization
- A Blade template in `src/resources/views/components/`
- Optional color classes in `src/Support/Colors/Components/`

### Soft Customization System

Components use `#[SoftCustomization('component-name')]` attribute for class-based customization. The `customization()` method returns a dot-notation array of Tailwind classes that can be overridden.

```php
#[SoftCustomization('alert')]
class Alert extends TallStackUiComponent
{
   public function customization(): array
   {
      return [
         'wrapper' => 'flex rounded-lg p-4',
         'icon.wrapper' => 'flex-shrink-0',
      ];
   }
}
```

#### Usage

In `app/Providers/AppServiceProvider.php` (or any other provider):

```php
use TallStackUi\Facades\TallStackUi;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        TallStackUi::personalize()
            ->form('input')
            ->block('input.base', 'w-full rounded-full');
    }
}
```

#### Customization Methods

`CustomizationFactory` provides chainable methods:

- `block(name, code)` - Target a customization block
- `append(content)` - Add classes to end of block
- `prepend(content)` - Add classes to beginning of block
- `replace(from, to)` - Replace class patterns
- `remove(class)` - Remove classes from block

#### Scoped Customization

For component-specific customizations:

```php
TallStackUi::personalize('input', scope: 'search')->block('input.base', 'rounded-full');
```

Then in Blade: `<x-input scope="search" />`

#### Internal Flow

1. `src/Attributes/SoftCustomization.php` - Attribute marking customizable components
2. `src/TallStackUiComponent.php` - Base component with trait composition
3. `src/Support/Concerns/BaseComponent/ManagesClasses.php` - Resolves customizations via `classes()` method
4. `src/TallStackUiServiceProvider.php` - Registers singleton `CustomizationFactory` per component
5. `src/Customization/CustomizationFactory.php` - Compiles customizations with Blade view composers

### Deep Customization

Override component classes by extending the original:

1. Create component: `php artisan make:component Input`
2. Update config to use your class:

```php
'components' => [
    'input' => \App\View\Components\Input::class,
],
```

3. Extend the original:

```php
class Input extends \TallStackUi\View\Components\Form\Input
{
    // Override methods as needed
}
```

### Color Personalization

Components with color support use the `#[ColorsThroughOf(ColorClass::class)]` attribute to link to color definitions.

**Structure:**

- `src/Support/Colors/CompileColors.php` - Compiles colors via `CompileColors::of($component)`
- `src/Support/Colors/Concerns/SetupColors.php` - Trait for color class implementations
- `src/Support/Colors/Components/` - Color classes (AlertColors, ButtonColors, BadgeColors, etc.)
- `src/Support/Colors/Stubs/` - Templates for user-published color classes

**Color Class Pattern:**

```php
class AlertColors
{
    use SetupColors;

    public function colors(): array
    {
        [$background, $text] = $this->get('background', 'text');
        $getter = $this->format($this->component->style, $this->component->color);

        return [
            'background' => data_get($background, $getter) ?? data_get($this->background(), $getter),
            'text' => data_get($text, $getter) ?? data_get($this->text(), $getter),
        ];
    }

    private function background(): array { /* color palettes */ }
    private function text(): array { /* color palettes */ }
}
```

**Available Styles:** solid, light, outline

**User Customization:** `php artisan tallstackui:setup-color` publishes customizable color stubs.

### Component Configurations

`src/Support/Configurations/CompileConfigurations.php` handles runtime configuration for special components.

**Supported Components:**

| Component | Configuration Options                                         |
|-----------|---------------------------------------------------------------|
| Modal     | z-index, size, overflow, blur, persistent, center, scrollable |
| Slide     | z-index, size, position, blur, persistent                     |
| Dialog    | Delegates to config                                           |
| Toast     | Delegates to config                                           |
| Loading   | z-index, overflow, blur, opacity                              |
| Color     | Initializes color palettes                                    |
| Select    | Unfiltered flag                                               |

**Size Mapping:**

- Modal: `sm` → `sm:max-w-sm`, `2xl` → `sm:max-w-2xl` (default)
- Slide horizontal: `sm` → `sm:max-w-sm`
- Slide vertical: `sm` → `h-[24rem] sm:max-h-[12rem]`

**Configuration Source:** `config('tallstackui.components.{component}.*')`

### Runtime System

`src/Support/Runtime/` compiles runtime properties for Alpine.js integration.

**Structure:**

- `AbstractRuntime.php` - Base class for all runtime implementations
- `CompileRuntime.php` - Factory/dispatcher
- `Components/` - 30+ component-specific runtimes

**AbstractRuntime Methods:**

- `bind()` - Returns property binding info (property, error, id, entangle)
- `change()` - Compiles `wire:change` event handlers
- `wireable()` - Checks Livewire context
- `sanitize()` - Converts stringified values to correct PHP types
- `value()` - Gets value from Livewire property or attribute

**Attribute:** Components declare runtime via `#[PassThroughRuntime(InputRuntime::class)]`

**Example Runtime:**

```php
class InputRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $bind = $this->bind();
        return [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'ref' => $property ?? uniqid(),
        ];
    }
}
```

### Support Directory

#### Blade Utilities (`src/Support/Blade/`)

| Class             | Purpose                                                                               |
|-------------------|---------------------------------------------------------------------------------------|
| `BindProperty`    | Extracts wire:model bindings, validates against error bag                             |
| `ComponentPrefix` | Manages component name prefixing (`add()`, `remove()`)                                |
| `Directives`      | Registers `@tallStackUiScript`, `@tallStackUiStyle`, `@tallStackUiSetup`, `@interact` |
| `Wireable`        | Generates `$wire.entangle()` directives, handles JSON encoding                        |

#### Component Concerns (`src/Support/Concerns/BaseComponent/`)

| Trait                | Purpose                                               |
|----------------------|-------------------------------------------------------|
| `ManagesClasses`     | Resolves soft/scoped customizations via `classes()`   |
| `ManagesCompilation` | Compiles colors and configurations into data array    |
| `ManagesRender`      | Orchestrates render pipeline with runtime compilation |
| `ManagesOutput`      | Wraps output with debug information in dev mode       |

#### Icons (`src/Support/Icons/`)

- `IconGuide.php` - Centralized icon type/style registry (Heroicons support)
- `IconGuideMap.php` - Runtime icon path builder with custom icon support

#### Miscellaneous (`src/Support/Miscellaneous/`)

- `ReflectComponent.php` - PHP reflection wrapper for attribute discovery
- `UploadComponentFileAdapter.php` - Normalizes upload file data

### Core Files

| File                                         | Purpose                                                                  |
|----------------------------------------------|--------------------------------------------------------------------------|
| `src/config.php`                             | Component registry with 40+ components, settings per component           |
| `src/helpers.php`                            | Global `__ts_*` functions (see below)                                    |
| `src/TallStackUi.php`                        | Facade: `blade()`, `personalize()`, `directives()`, `icon()`, `prefix()` |
| `src/TallStackUiComponent.php`               | Abstract base using 4 traits                                             |
| `src/TallStackUiServiceProvider.php`         | Registers components, singletons, commands, directives                   |
| `src/Customization/Customization.php`        | Entry point with fluent component methods                                |
| `src/Customization/CustomizationFactory.php` | Customization engine with block manipulation                             |

**Helper Functions (`src/helpers.php`):**

- `__ts_get_component_configuration()` - Gets component config from `tallstackui.components.*`
- `__ts_class_collection()` - Creates color class metadata
- `__ts_validation_exception()` - Throws formatted validation errors
- `__ts_filter_components_using_attribute()` - Finds components by attribute
- `__ts_search_component()` - Maps class to config key
- `__ts_soft_customization_components()` - Gets all customizable components
- `__ts_scope_container_key()` - Generates scoped customization keys

### JavaScript/Alpine Integration

**Entry Point:** `js/tallstackui.js`

- Registers 30+ Alpine.data components
- Registers Alpine stores (`tsui.side-bar`)
- Initializes tooltip plugin

**Helpers (`js/helpers.js`):**

- `warning(message)` / `error(message)` - Console output with TallStackUI prefix
- `event(name, params, prefix)` - Dispatches custom events
- `overflow(status, component, skip)` - Manages body overflow for modals
- `wireChange(change, model)` - Calls Livewire methods
- `unique()` - Generates 15-char unique IDs
- `register_ui_element(id, type)` / `unregister_ui_element(id)` - Tracks open UI elements
- `top_ui_element(id)` - Checks if element is topmost

**Global Functions (`js/globals/globals.js`):**

- `$modalOpen(name)` / `$modalClose(name)` - Modal control
- `$slideOpen(name)` / `$slideClose(name)` - Slide control
- `$interaction(type)` - Creates interaction handler
- `$focusOn(name, time)` - Focuses element by ID or data-focus

**Component Pattern:**

```javascript
export default (options) => ({
  show: false,
  init() { /* setup */ },
  get computed() { /* ... */ },
  method() { /* ... */ },
})
```

**Plugins (`js/plugins/`):**

- `custom-scrollbar.css` - Custom scrollbar styling
- `soft-scrollbar.css` - Soft scrollbar variant
- `number-appearance-none.css` - Removes number input spinners

### CSS Structure

**`css/v4.css`** - Tailwind CSS 4 with CSS Cascade Layers:

```css
@import 'tailwindcss';
@import '../js/plugins/custom-scrollbar.css';

[x-cloak] { display: none; }

@custom-variant dark (&:where(.dark, .dark *));
@plugin '@tailwindcss/forms' { strategy: 'class'; }

@source '../js/';
@source '../src/';

@theme {
    --color-primary-*: /* Indigo palette */;
    --color-secondary-*: /* Slate palette */;
    --color-dark-*: /* Slate palette */;
    --z-index-*: /* Z-index tokens 0-50 */;
    --animate-progress: /* Progress animation */;
}
```

**Features:**

- Primary (indigo), secondary (slate), dark color palettes
- Z-index tokens (0-50)
- Progress bar animation
- Dark mode via CSS custom variant
- Alpine x-cloak support
- Tailwind Forms plugin integration

**`css/v3.css`** - Legacy Tailwind CSS 3 support

## Testing Patterns

**Feature Tests** in `tests/Feature/Components/{Component}/IndexTest.php`:

```php
expect('<x-alert title="Foo" />')->render()->toContain('Foo');
```

**Browser Tests** in `tests/Browser/{Component}/IndexTest.php` use Livewire with `Livewire::visit()` for interactive testing.

## Code Style

**PHP:** Laravel Pint with custom `ordered_class_elements` rule - traits, constants, properties, methods ordered by visibility (public → protected → private), alphabetically sorted within each group.

**JavaScript:** ESLint with single quotes, 130 char line length. Prettier for formatting.

## Code Comments Guidelines

**Do NOT add comments unless explicitly requested.** When requested:

- Comments must be in English
- Break long comments into multiple lines

❌ Wrong:

```php
// Adipisicing laborum sit reprehenderit adipisicing irure ex sunt et occaecat. Ex officia amet do cupidatat duis.
```

✅ Correct:

```php
// Adipisicing laborum sit reprehenderit adipisicing irure
// ex sunt et occaecat. Ex officia amet do cupidatat duis.
```

## Creating a New Component

1. Create PHP class in `src/View/Components/ComponentName.php` extending `TallStackUiComponent`
2. Add `#[SoftCustomization('component-name')]` attribute
3. Implement `blade()` returning view and `customization()` returning Tailwind classes
4. Create Blade view at `src/resources/views/components/component-name.blade.php`
5. Add to a component list in `src/config.php`
6. Write feature tests in `tests/Feature/Components/ComponentName/IndexTest.php`
7. For interactive components, add browser tests in `tests/Browser/`
8. Run `npm run build` to compile assets

## Environment Variables

- `TALLSTACKUI_PREFIX` - Component prefix (e.g., `ts-` makes usage `<x-ts-alert />`)
- `TALLSTACKUI_ICON_TYPE` - Icon type: `heroicons` or BladeUI
- `TALLSTACKUI_ICON_STYLE` - Icon style: `solid` or `outline`
- `TALLSTACKUI_DEBUG_MODE` - Enable debug mode

## Branch Strategy

- `3.x` - Active development (current)
- `2.x` - Main stable release branch
