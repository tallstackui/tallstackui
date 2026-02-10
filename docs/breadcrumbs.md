# Breadcrumbs

A component for displaying navigation breadcrumb trails with support for icons, tooltips, custom separators, and a route-aware builder API.

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

| Field     | Type     | Required | Description                        |
|-----------|----------|----------|------------------------------------|
| `label`   | `string` | Yes      | Text displayed for the item        |
| `link`    | `string` | No       | URL for clickable items            |
| `icon`    | `string` | No       | Icon name (e.g. `heroicon-o-home`) |
| `tooltip` | `string` | No       | Tooltip text on hover              |

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

## Separator

The default separator is `/`. You can customize it with text or an icon name.

### Custom Text

```blade
<x-breadcrumbs separator="»" :items="$items" />
<x-breadcrumbs separator=">" :items="$items" />
<x-breadcrumbs separator="|" :items="$items" />
```

### Icon Separator

Pass an icon name (detected automatically by the presence of `-` in the string):

```blade
<x-breadcrumbs separator="heroicon-o-chevron-right" :items="$items" />
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

| Prop              | Type               | Default | Description                                       |
|-------------------|--------------------|---------|---------------------------------------------------|
| `items`           | `array\|Collection` | `null`  | Breadcrumb items (auto-resolved if null)           |
| `separator`       | `string`           | `/`     | Separator text or icon name                        |
| `separator-class` | `string`           | `null`  | Additional CSS classes for separator elements      |

## Soft Customization

All visual blocks can be customized via the soft personalization API:

```php
TallStackUi::personalize()
    ->breadcrumbs()
    ->block('wrapper', '...')
    ->block('list', '...')
    ->block('separator.wrapper', '...')
    ->block('separator.text', '...')
    ->block('separator.icon', '...')
    ->block('item.wrapper', '...')
    ->block('item.link', '...')
    ->block('item.current', '...')
    ->block('item.icon', '...');
```

### Available Blocks

| Block               | Default Classes                                                                               |
|---------------------|-----------------------------------------------------------------------------------------------|
| `wrapper`           | `flex items-center`                                                                           |
| `list`              | `flex items-center gap-1`                                                                     |
| `separator.wrapper` | `flex items-center`                                                                           |
| `separator.text`    | `text-sm text-gray-400 dark:text-dark-400 mx-1 select-none`                                  |
| `separator.icon`    | `w-4 h-4 text-gray-400 dark:text-dark-400 mx-0.5 shrink-0`                                   |
| `item.wrapper`      | `flex items-center`                                                                           |
| `item.link`         | `text-sm text-gray-500 dark:text-dark-300 transition-colors hover:text-gray-700 dark:hover:text-dark-100` |
| `item.current`      | `text-sm font-medium text-gray-700 dark:text-dark-200`                                       |
| `item.icon`         | `w-4 h-4 mr-1 shrink-0`                                                                      |

### Scoped Customization

```php
TallStackUi::personalize('breadcrumbs', scope: 'admin')
    ->block('item.link', 'text-sm text-blue-500 hover:text-blue-700');
```

```blade
<x-breadcrumbs scope="admin" :items="$items" />
```

## Route-Aware Builder (PoC)

The breadcrumbs component includes a builder API that lets you define breadcrumbs per route name and render them automatically based on the current route.

### Registering Breadcrumbs

In your `AppServiceProvider::boot()` (or any service provider):

```php
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Breadcrumbs\BreadcrumbTrail;

TallStackUi::breadcrumbs()
    ->for('home', fn (BreadcrumbTrail $trail) => $trail
        ->add('Home', '/', icon: 'heroicon-o-home')
    )
    ->for('users.index', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add('Users', route('users.index'))
    )
    ->for('users.show', fn (BreadcrumbTrail $trail, User $user) => $trail
        ->parent('users.index')
        ->add($user->name)
    );
```

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
        ->add('Settings', route('settings.index'))
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
| `parent` | `parent(string $routeName): self`                                              | Set parent route for item inheritance |

### BreadcrumbRegistry Methods

| Method    | Signature                                          | Description                                    |
|-----------|----------------------------------------------------|------------------------------------------------|
| `for`     | `for(string $routeName, Closure $callback): self`  | Register breadcrumb definition for a route     |
| `has`     | `has(string $routeName): bool`                     | Check if a definition exists for a route       |
| `resolve` | `resolve(?string $routeName = null): array`        | Resolve the full breadcrumb items for a route  |
