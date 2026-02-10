# Breadcrumbs

A component for displaying navigation breadcrumb trails with support for icons, tooltips, custom separators, sizes, and a route-aware builder API.

## Basic Usage

Pass an array of items with `label` and `link`:

```blade
<x-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/'],
    ['label' => 'Users', 'link' => '/users'],
    ['label' => 'John Doe'],
]" />
```

The last item without a `link` renders as the current page (non-clickable, bold style).

## Item Fields

| Field     | Type     | Required | Description                                  |
|-----------|----------|----------|----------------------------------------------|
| `label`   | `string` | Yes      | Text displayed for the item                  |
| `link`    | `string` | No       | URL or named route for clickable items       |
| `icon`    | `string` | No       | Icon name (e.g. `heroicon-o-home`)           |
| `tooltip` | `string` | No       | Tooltip text on hover                        |

### Named Routes

The `link` field accepts both URLs and Laravel named routes. Named routes are resolved automatically:

```blade
<x-breadcrumbs :items="[
    ['label' => 'Home', 'link' => 'home'],
    ['label' => 'Users', 'link' => 'users.index'],
    ['label' => 'John Doe'],
]" />
```

Regular URLs (starting with `/` or `http`) are kept as-is. Any other string is treated as a named route and resolved via `route()`.

### Icons

```blade
<x-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/', 'icon' => 'heroicon-o-home'],
    ['label' => 'Settings', 'link' => '/settings', 'icon' => 'heroicon-o-cog-6-tooth'],
    ['label' => 'Profile'],
]" />
```

### Tooltips

```blade
<x-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/', 'tooltip' => 'Back to homepage'],
    ['label' => 'Users', 'link' => '/users', 'tooltip' => 'View all users'],
    ['label' => 'John Doe'],
]" />
```

## Sizes

The default size is `md`. Available sizes: `xs`, `sm`, `md`, `lg`.

```blade
<x-breadcrumbs xs :items="$items" />
<x-breadcrumbs sm :items="$items" />
<x-breadcrumbs :items="$items" />      {{-- md (default) --}}
<x-breadcrumbs lg :items="$items" />
```

| Size | Text        | Item Icon      | Separator Icon  | Gap       |
|------|-------------|----------------|-----------------|-----------|
| `xs` | `text-xs`   | `w-3 h-3`      | `w-3 h-3`      | `gap-0.5` |
| `sm` | `text-xs`   | `w-3.5 h-3.5`  | `w-3.5 h-3.5`  | `gap-0.5` |
| `md` | `text-sm`   | `w-4 h-4`      | `w-4 h-4`      | `gap-1`   |
| `lg` | `text-base` | `w-5 h-5`      | `w-5 h-5`      | `gap-1.5` |

## Separator

The default separator is `/`. You can customize it with text or an icon.

### Custom Text

```blade
<x-breadcrumbs separator="»" :items="$items" />
<x-breadcrumbs separator=">" :items="$items" />
<x-breadcrumbs separator="|" :items="$items" />
```

### Icon Separator

Prefix the icon name with `icon:` to use an icon as separator:

```blade
<x-breadcrumbs separator="icon:heroicon-o-chevron-right" :items="$items" />
```

## Separator Class

Apply additional CSS classes to all separator elements:

```blade
<x-breadcrumbs separator-class="text-red-500 font-bold" :items="$items" />
```

## Slots

Add custom content to the left or right of the breadcrumb trail:

```blade
<x-breadcrumbs :items="$items">
    <x-slot:left>
        <x-icon icon="heroicon-o-home" class="w-5 h-5 mr-2 text-gray-400" />
    </x-slot:left>
    <x-slot:right>
        <span class="text-xs text-gray-400 ml-2">3 levels</span>
    </x-slot:right>
</x-breadcrumbs>
```

## Properties

| Prop              | Type                | Default | Description                                              |
|-------------------|---------------------|---------|----------------------------------------------------------|
| `items`           | `array\|Collection` | `null`  | Breadcrumb items (auto-resolved if null)                 |
| `separator`       | `string`            | `/`     | Separator text or icon (use `icon:` prefix for icons)    |
| `separator-class` | `string`            | `null`  | Additional CSS classes for separator elements            |
| `xs`              | `bool`              | `false` | Extra small size                                         |
| `sm`              | `bool`              | `false` | Small size                                               |
| `lg`              | `bool`              | `false` | Large size                                               |

## Soft Customization

All visual blocks can be customized via the soft personalization API. Each size-dependent block uses the `{block}.class` + `{block}.sizes.{size}` pattern:

```php
TallStackUi::personalize()
    ->breadcrumbs()
    ->block('wrapper', '...')
    ->block('list.class', '...')
    ->block('list.sizes.md', '...')
    ->block('separator.wrapper', '...')
    ->block('separator.text.class', '...')
    ->block('separator.text.sizes.md', '...')
    ->block('separator.icon.class', '...')
    ->block('separator.icon.sizes.md', '...')
    ->block('item.wrapper', '...')
    ->block('item.link.class', '...')
    ->block('item.link.sizes.md', '...')
    ->block('item.current.class', '...')
    ->block('item.current.sizes.md', '...')
    ->block('item.icon.class', '...')
    ->block('item.icon.sizes.md', '...');
```

### Available Blocks

| Block                     | Default Classes                                                                                                            |
|---------------------------|----------------------------------------------------------------------------------------------------------------------------|
| `wrapper`                 | `flex items-center`                                                                                                        |
| `list.class`              | `flex items-center`                                                                                                        |
| `list.sizes.xs`           | `gap-0.5`                                                                                                                  |
| `list.sizes.sm`           | `gap-0.5`                                                                                                                  |
| `list.sizes.md`           | `gap-1`                                                                                                                    |
| `list.sizes.lg`           | `gap-1.5`                                                                                                                  |
| `separator.wrapper`       | `flex items-center`                                                                                                        |
| `separator.text.class`    | `text-gray-400 dark:text-dark-400 select-none`                                                                             |
| `separator.text.sizes.xs` | `text-xs mx-0.5`                                                                                                           |
| `separator.text.sizes.sm` | `text-xs mx-0.5`                                                                                                           |
| `separator.text.sizes.md` | `text-sm mx-1`                                                                                                             |
| `separator.text.sizes.lg` | `text-base mx-1`                                                                                                           |
| `separator.icon.class`    | `text-gray-400 dark:text-dark-400 shrink-0`                                                                                |
| `separator.icon.sizes.xs` | `w-3 h-3 mx-0.5`                                                                                                           |
| `separator.icon.sizes.sm` | `w-3.5 h-3.5 mx-0.5`                                                                                                       |
| `separator.icon.sizes.md` | `w-4 h-4 mx-0.5`                                                                                                           |
| `separator.icon.sizes.lg` | `w-5 h-5 mx-1`                                                                                                             |
| `item.wrapper`            | `flex items-center`                                                                                                        |
| `item.link.class`         | `inline-flex items-center text-gray-500 dark:text-dark-300 transition-colors hover:text-gray-700 dark:hover:text-dark-100` |
| `item.link.sizes.xs`      | `text-xs`                                                                                                                  |
| `item.link.sizes.sm`      | `text-xs`                                                                                                                  |
| `item.link.sizes.md`      | `text-sm`                                                                                                                  |
| `item.link.sizes.lg`      | `text-base`                                                                                                                |
| `item.current.class`      | `inline-flex items-center font-medium text-gray-700 dark:text-dark-200`                                                    |
| `item.current.sizes.xs`   | `text-xs`                                                                                                                  |
| `item.current.sizes.sm`   | `text-xs`                                                                                                                  |
| `item.current.sizes.md`   | `text-sm`                                                                                                                  |
| `item.current.sizes.lg`   | `text-base`                                                                                                                |
| `item.icon.class`         | `shrink-0`                                                                                                                 |
| `item.icon.sizes.xs`      | `w-3 h-3 mr-0.5`                                                                                                           |
| `item.icon.sizes.sm`      | `w-3.5 h-3.5 mr-0.5`                                                                                                       |
| `item.icon.sizes.md`      | `w-4 h-4 mr-1`                                                                                                             |
| `item.icon.sizes.lg`      | `w-5 h-5 mr-1.5`                                                                                                           |

### Scoped Customization

```php
TallStackUi::personalize('breadcrumbs', scope: 'admin')
    ->block('item.link.class', 'inline-flex items-center text-blue-500 hover:text-blue-700');
```

```blade
<x-breadcrumbs scope="admin" :items="$items" />
```

## Route-Aware Builder (PoC)

The breadcrumbs component includes a builder API that lets you define breadcrumbs per route name and render them automatically based on the current route.

### Registering Breadcrumbs

#### Via Breadcrumb File (Recommended)

Publish the breadcrumb file:

```bash
php artisan vendor:publish --tag=tallstackui.breadcrumbs
```

This creates `routes/breadcrumbs.php`. Define your breadcrumbs there:

```php
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Breadcrumbs\BreadcrumbTrail;

TallStackUi::breadcrumbs()
    ->for('home', fn (BreadcrumbTrail $trail) => $trail
        ->add('Home', '/', icon: 'heroicon-o-home')
    )
    ->for('users.index', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add('Users', 'users.index')
    )
    ->for('users.show', fn (BreadcrumbTrail $trail, User $user) => $trail
        ->parent('users.index')
        ->add($user->name)
    );
```

The file is loaded automatically during boot. You can configure additional files in `config/tallstackui.php`:

```php
'breadcrumbs' => [
    Components\Breadcrumbs\Component::class,
    [
        'files' => [
            'routes/breadcrumbs.php',
            'routes/admin-breadcrumbs.php', // additional files
        ],
    ],
],
```

Paths are relative to `base_path()`. Non-existent files are silently skipped.

#### Via Service Provider

Alternatively, register breadcrumbs in any service provider's `boot()` method:

```php
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Breadcrumbs\BreadcrumbTrail;

TallStackUi::breadcrumbs()
    ->for('home', fn (BreadcrumbTrail $trail) => $trail
        ->add('Home', '/')
    );
```

The `link` parameter in `add()` supports both URLs and named routes, just like the `items` array.

### Rendering

Simply use the component without passing `items` — it auto-resolves from the current route:

```blade
<x-breadcrumbs />
```

When visiting `/users/5` (route `users.show`), this renders:

```
Home / Users / John Doe
```

### Parent Chaining

Use `->parent('route.name')` to inherit breadcrumb items from a parent route. Parents are resolved recursively, so you can build deep hierarchies:

```php
TallStackUi::breadcrumbs()
    ->for('home', fn (BreadcrumbTrail $trail) => $trail
        ->add('Home', '/')
    )
    ->for('settings.index', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add('Settings', 'settings.index')
    )
    ->for('settings.profile', fn (BreadcrumbTrail $trail) => $trail
        ->parent('settings.index')
        ->add('Profile')
    );
```

Visiting `settings.profile` renders: `Home / Settings / Profile`

### Route Model Binding

Callback parameters are automatically injected from the current route's model bindings via Laravel's container. Type-hint any route-bound model to receive it:

```php
TallStackUi::breadcrumbs()
    ->for('posts.show', fn (BreadcrumbTrail $trail, Post $post) => $trail
        ->parent('posts.index')
        ->add($post->title)
    )
    ->for('posts.comments.show', fn (BreadcrumbTrail $trail, Post $post, Comment $comment) => $trail
        ->parent('posts.show')
        ->add("Comment #{$comment->id}")
    );
```

### BreadcrumbTrail Methods

| Method   | Signature                                                                      | Description                           |
|----------|--------------------------------------------------------------------------------|---------------------------------------|
| `add`    | `add(string $label, ?string $link, ?string $icon, ?string $tooltip): self`     | Add a breadcrumb item to the trail    |
| `parent` | `parent(string $route): self`                                                  | Set parent route for item inheritance |

### BreadcrumbRegistry Methods

| Method    | Signature                                      | Description                                    |
|-----------|-------------------------------------------------|------------------------------------------------|
| `for`     | `for(string $route, Closure $callback): self`  | Register breadcrumb definition for a route     |
| `has`     | `has(string $route): bool`                     | Check if a definition exists for a route       |
| `resolve` | `resolve(?string $route = null): array`        | Resolve the full breadcrumb items for a route  |
