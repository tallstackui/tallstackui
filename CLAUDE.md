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
./vendor/bin/pint --parallel          # Format PHP  
npm run lint:fix           # Fix ESLint issues  
npm run format             # Format JS with Prettier  
composer format            # Run all formatters  
  
# CI Pipeline  
composer ci                # Full CI: pint, feature tests, browser tests  
```  

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
         // ...
  
         'wrapper' => 'flex rounded-lg p-4', 'icon.wrapper' => 'flex-shrink-0',

         // ...  
      ];  
   }  
}  
```  

#### In detail:

Soft Customization was a method created by the TallStackUI to allow less knowledgeable developers to customize
components without needing to, for example, extend the base class of the components. With Soft Customization
the  developer customizes the components as follows:

1. In `app/Providers/AppServiceProvider.php` (or any other provider):

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

Explaining it in parts:

1. Initialization

```php
TallStackUi::personalize()
```

This indicates the beginning of a customization.

2. Specifying the component:

```php
->form('input')
```

or

```php
->alert()
```

We specify the component to be customized.

3. Block to be customized:

```php
->block('input.base', 'w-full rounded-full');
```

We interact with blocks defined through the `customize` method of the respective component. In this example:
`\TallStackUi\View\Components\Form\Input::customization`

Internally, the build works as follows:

1. Customizable components implement: `src/Attributes/SoftCustomization.php` with a key indicating the name of the customization.
2. The base component `src/TallStackUiComponent.php` implements traits for better organization.
3. The trait `src/Support/Concerns/BaseComponent/ManagesClasses.php` defines the `classes` method.
4. The service provider: `src/TallStackUiServiceProvider.php` loads the customizations in singleton mode.
5. Finally, the rendering of the blade files calls `$classes()` which "compiles" the customization into blocks.

### Deep Customization

Deep Customization is the most common way to customize a component. The concept behind it allows users—developers — to override the original component classes, pointing to a custom class, such as a Blade component (not anonymous) that will extend the original class. The flow is:

1. Create a custom Blade component: `php artisan make:component Input`
2. Edit the TallStackUI configuration file by replacing the original component class with your component:

```php
// ...

'components' => [
	// ...
	'input' => \App\View\Components\Input::class,
],
```

3. In your component, extends the original TallStackUI component class:

```php
namespace App\View\Components;

use Illuminate\Contracts\View\View;

class Input extends \TallStackUi\View\Components\Form\Input
{
	//
}
```

### Color Personalization

Components with color support use `#[ColorsThroughOf(AlertColors::class)]` attribute. Color classes in  
`src/Support/Colors/Components/` define color palettes (solid, light, outline styles).

Users can publish custom color classes via: `php artisan tallstackui:setup-color`

### JavaScript/Alpine Integration

- Entry point: `js/tallstackui.js` registers Alpine data and plugins
- Component behavior: `js/components/` directory
- Built assets served from `dist/` via routes at `/tallstackui/script/*` and `/tallstackui/style/*`

### Key Files

| File                                  | Purpose                                  |  
|---------------------------------------|------------------------------------------|  
| `src/config.php`                      | Component registry and configuration     |  
| `src/helpers.php`                     | Global `__ts_*` helper functions         |  
| `src/TallStackUiServiceProvider.php`  | Service provider, component registration |  
| `src/Customization/Customization.php` | Customization system core                |  

## Testing Patterns

**Feature Tests** in `tests/Feature/Components/{Component}/IndexTest.php`:

```php  
expect('<x-alert title="Foo" />')->render()->toContain('Foo');  
```  

**Browser Tests** in `tests/Browser/{Component}/IndexTest.php` use Livewire with `Livewire::visit()` for interactive  
testing.

## Code Style

**PHP:** Laravel Pint with custom `ordered_class_elements` rule - traits, constants, properties, methods ordered by  
visibility (public → protected → private), alphabetically sorted within each group.

**JavaScript:** ESLint with single quotes, 130 char line length. Prettier for formatting.

## Creating a New Component

1. Create PHP class in `src/View/Components/ComponentName.php` extending `TallStackUiComponent`
2. Add `#[SoftCustomization('component-name')]` attribute
3. Implement `blade()` returning view and `customization()` returning Tailwind classes
4. Create Blade view at `src/resources/views/components/component-name.blade.php`
5. Add to a component list in `src/config.php`
6. Write feature tests in `tests/Feature/Components/ComponentName/IndexTest.php`
7. For interactive components, add browser tests in `tests/Browser/`

## Environment Variables

- `TALLSTACKUI_PREFIX` - Component prefix (e.g., `ts-` makes usage `<x-ts-alert />`)
- `TALLSTACKUI_ICON_TYPE` - Icon type: `heroicons` or BladeUI
- `TALLSTACKUI_ICON_STYLE` - Icon style: `solid` or `outline`
- `TALLSTACKUI_DEBUG_MODE` - Enable debug mode

## Branch Strategy

- `3.x` - Active development (current)
- `2.x` - Main stable release branch
