# Layout Component Improvements

Summary of changes made to the Layout component system.

## 1. Collapse Button Moved to Header

The sidebar collapse toggle button has been moved from inside the sidebar to the header component. This prevents conflicts with the sidebar `brand` slot and provides a cleaner UX.

The collapse button appears automatically in the header when the sidebar has the `collapsible` attribute. No additional configuration is needed on the header.

### How It Works

The sidebar sets `$store['tsui.side-bar'].collapsible = true` on the Alpine store, and the header reads this reactively via `x-show`. Communication happens entirely through the Alpine store — no PHP props or `@aware` chains needed.

### Default Icon

The collapse button now uses the `bars-4` icon (same as the mobile hamburger) for visual consistency. This replaces the previous `chevron-left`/`chevron-right` icons.

### Soft Customization

The collapse button customization keys are now on the **Header** component (previously on SideBar):

```php
TallStackUi::personalize()
    ->layout('header')
    ->block('collapse.class', 'hidden md:block cursor-pointer')
    ->block('collapse.icon', 'bars-4')
    ->block('collapse.icon.size', 'h-6 w-6 text-gray-500 dark:text-white');
```

The following SideBar customization keys have been **removed**:
- `desktop.collapse.wrapper`
- `desktop.collapse.buttons.expanded.icon`
- `desktop.collapse.buttons.expanded.class`
- `desktop.collapse.buttons.collapsed.icon`
- `desktop.collapse.buttons.collapsed.class`

## 2. Sidebar Footer Slot

A new `footer` slot has been added to the sidebar component. It renders at the bottom of the sidebar, separated from the menu items by a border.

The footer is always visible — it does not scroll with the items. The items area scrolls independently while the footer stays pinned at the bottom.

When the sidebar is collapsed, the footer content is clipped via `overflow-hidden` to prevent text from overflowing outside the narrow sidebar.

### Usage

```blade
<x-side-bar collapsible>
    <x-side-bar.item text="Home" icon="home" route="/" />
    <x-side-bar.item text="Settings" icon="cog-6-tooth" route="/settings" />

    <x-slot:footer>
        <div class="flex items-center gap-2">
            <x-avatar sm />
            <span>John Doe</span>
        </div>
    </x-slot:footer>
</x-side-bar>
```

### Soft Customization

```php
TallStackUi::personalize()
    ->sideBar()
    ->block('desktop.footer', 'shrink-0 overflow-hidden border-t border-gray-200 dark:border-dark-600 px-2 py-4')
    ->block('mobile.footer', 'shrink-0 border-t border-gray-200 dark:border-dark-600 px-2 py-4');
```

## 3. Smooth Collapse Animation

The sidebar collapse/expand animation has been improved. Previously, the sidebar width snapped instantly while text faded separately, causing a visible flash of partially-visible text.

### Changes

- **Sidebar width**: Added `transition-[width] duration-300` to the desktop sidebar wrapper for smooth width animation
- **Main content padding**: Added `transition-[padding] duration-300` to the layout wrapper so content padding animates in sync
- **Item/group text**: Replaced Alpine `x-show`/`x-transition` with CSS class-based transitions (`opacity` + `max-width`) at `duration-150`
- **Separator text**: Same CSS transition pattern applied to separator labels
- **Group chevron**: The expand/collapse chevron icon on group items is now hidden when the sidebar is collapsed
- **Group sub-items**: Nested items (inside groups) are hidden when the sidebar is collapsed, preventing empty space with only the vertical border line visible

The text transitions at 150ms (faster) while the sidebar width transitions at 300ms. This means text disappears before the sidebar shrinks, and appears as the sidebar expands — eliminating the flash.

### New Customization Keys (SideBar Item)

```php
TallStackUi::personalize()
    ->sideBar('item')
    ->block('item.text.visible', 'opacity-100 max-w-48')
    ->block('item.text.hidden', 'opacity-0 max-w-0')
    ->block('group.text.visible', 'opacity-100 max-w-48')
    ->block('group.text.hidden', 'opacity-0 max-w-0');
```

### New Customization Keys (SideBar Separator)

```php
TallStackUi::personalize()
    ->sideBar('separator')
    ->block('simple.base.visible', 'opacity-100 max-w-48')
    ->block('simple.base.hidden', 'opacity-0 max-w-0');
```

## 4. Sidebar Scroll Structure

The sidebar layout has been restructured so that only the items area scrolls, while the brand and footer remain fixed.

### Structure

```
sidebar container (flex col, no overflow)
  brand (shrink-0, fixed at top)
  items wrapper (flex-1, min-h-0, overflow-y-auto)  <- only this scrolls
  footer (shrink-0, fixed at bottom)
```

The `min-h-0` on the items wrapper is essential — without it, the flex item won't shrink below its content size and `overflow-y-auto` won't activate.

Horizontal padding (`px-2`) is applied to the items list (`<ul>`) rather than the sidebar container. This ensures:
- The scrollbar sits flush against the sidebar border
- The footer `border-t` spans edge-to-edge
- Items still have horizontal padding

### New Customization Keys (SideBar)

```php
TallStackUi::personalize()
    ->sideBar()
    ->block('desktop.wrapper.items', 'flex-1 min-h-0 overflow-y-auto overflow-x-hidden gap-y-5')
    ->block('mobile.wrapper.items', 'flex-1 min-h-0 overflow-y-auto overflow-x-hidden gap-y-5');
```

## 5. Tooltips on Collapsed Sidebar Items

When the sidebar is collapsed, hovering over an item shows a tooltip with the item text. Tooltips use `position: fixed` to avoid clipping by the sidebar's `overflow-x-hidden` container.

### How It Works

Each item/group `<li>` gets an Alpine `x-data` with `tip` and `pos` properties when `$collapsible` is true. On `mouseenter`, the item's bounding rect is measured and the tooltip is positioned to the right. The tooltip only appears when the sidebar is collapsed (`!$store['tsui.side-bar'].open`) and not on mobile.

### Soft Customization

```php
TallStackUi::personalize()
    ->sideBar('item')
    ->block('item.tooltip', 'z-50 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-xs font-medium text-white shadow-lg dark:bg-dark-600')
    ->block('group.tooltip', 'z-50 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-xs font-medium text-white shadow-lg dark:bg-dark-600');
```

## 6. Brand Collapsed Slot

A new `brand-collapsed` slot allows showing an alternate compact brand when the sidebar is collapsed (e.g., just an icon instead of full logo + text).

### Usage

```blade
<x-side-bar collapsible>
    <x-slot:brand>
        <div class="flex h-16 items-center px-4">
            <img src="/logo.svg" alt="Logo" class="h-8" />
            <span class="ml-2 text-lg font-bold">MyApp</span>
        </div>
    </x-slot:brand>

    <x-slot:brand-collapsed>
        <div class="flex h-16 items-center justify-center">
            <img src="/icon.svg" alt="Logo" class="h-8" />
        </div>
    </x-slot:brand-collapsed>

    <x-side-bar.item text="Dashboard" icon="home" route="/" />
</x-side-bar>
```

When both `brand` and `brand-collapsed` slots are provided with `collapsible`, they toggle via `x-show` based on `$store['tsui.side-bar'].open`. On mobile, the full brand is always shown.

If only `brand` is provided, it renders normally regardless of collapse state (existing behavior).

## 7. Header Scroll Shadow

A `scroll-shadow` prop on the header enables a dynamic shadow that appears when the page is scrolled.

### Usage

```blade
<x-layout.header scroll-shadow>
    <x-slot:left>
        <span class="text-lg font-bold">MyApp</span>
    </x-slot:left>
</x-layout.header>
```

### How It Works

- **Default (no prop):** Header has a static `shadow-sm` always visible
- **With `scroll-shadow`:** Header starts with no shadow. When the user scrolls down (`window.scrollY > 0`), `shadow-md` is added with a `transition-shadow duration-200` for smooth appearance

The Alpine `x-data` and `x-on:scroll.window` listener are only added when the prop is true.

### Soft Customization

```php
TallStackUi::personalize()
    ->layout('header')
    ->block('scroll.shadow', 'shadow-md');
```

## 8. Badge on Sidebar Items

A `badge` prop on sidebar items renders a notification counter badge. Badges hide when the sidebar is collapsed.

### Usage

```blade
<x-side-bar.item text="Messages" icon="envelope" route="/messages" badge="3" />
<x-side-bar.item text="Alerts" icon="bell" route="/alerts" badge="12" />

{{-- Group with badge --}}
<x-side-bar.item text="Settings" icon="cog-6-tooth" badge="!">
    <x-side-bar.item text="Profile" route="/settings/profile" />
    <x-side-bar.item text="Account" route="/settings/account" />
</x-side-bar.item>
```

### Soft Customization

```php
TallStackUi::personalize()
    ->sideBar('item')
    ->block('item.badge', 'ml-auto inline-flex items-center justify-center shrink-0 rounded-full bg-red-500 px-1.5 py-0.5 text-xs font-medium text-white overflow-hidden transition-all duration-150')
    ->block('item.badge.visible', 'opacity-100 scale-100')
    ->block('item.badge.hidden', 'opacity-0 scale-0')
    ->block('group.badge', 'inline-flex items-center justify-center shrink-0 rounded-full bg-red-500 px-1.5 py-0.5 text-xs font-medium text-white overflow-hidden transition-all duration-150')
    ->block('group.badge.visible', 'opacity-100 scale-100')
    ->block('group.badge.hidden', 'opacity-0 scale-0');
```

Note: `group.badge` does not include `ml-auto` because the group chevron icon already uses `ml-auto` to push itself to the far right.

## 9. Customizable Breakpoint

The `breakpoint` prop on `<x-layout>` controls the responsive breakpoint where the sidebar switches between mobile (hamburger menu) and desktop (fixed sidebar) modes.

### Usage

```blade
<x-layout breakpoint="lg">
    {{-- Sidebar and header automatically adapt to lg breakpoint --}}
</x-layout>
```

### Supported Values

| Value | Pixels | Description |
|-------|--------|-------------|
| `sm`  | 640px  | Small screens |
| `md`  | 768px  | Medium screens (default) |
| `lg`  | 1024px | Large screens |
| `xl`  | 1280px | Extra large screens |
| `2xl` | 1536px | 2X extra large screens |

### How It Works

1. **Layout Main Component** stores the breakpoint in a static property (`$resolvedBreakpoint`)
2. **SideBar, Header** read it via `\TallStackUi\Components\Layout\Main\Component::$resolvedBreakpoint` in their `customization()` methods
3. Each `md:` prefixed class is generated dynamically via PHP `match` statements. All breakpoint variants are present as full string literals in the source files, so Tailwind CSS 4 detects them during scanning
4. **Alpine store** (`collapse.js`) uses a configurable media query. If the breakpoint differs from the default `md` (768px), the sidebar calls `$store['tsui.side-bar'].setBreakpoint(px)` via `x-init`

### Affected Components

The breakpoint changes these responsive classes automatically:

- **SideBar:** `md:hidden` (mobile), `md:fixed md:inset-y-0 md:z-40 md:flex md:flex-col` (desktop), `md:w-72` (size)
- **Header:** `md:hidden` (mobile button), `hidden md:block` (collapse button)
- **Layout Main:** `md:pl-72` / `md:pl-22` (content padding)

### Validation

An invalid breakpoint value throws a validation exception:

```php
// This throws: "The [breakpoint] must be one of: sm, md, lg, xl, 2xl."
<x-layout breakpoint="xs">
```

## Full Layout Example

```blade
<body>
    <x-layout breakpoint="lg">
        <x-slot:header>
            <x-layout.header scroll-shadow>
                <x-slot:left>
                    <span class="text-lg font-bold">MyApp</span>
                </x-slot:left>
                <x-slot:right>
                    <x-dropdown text="Hello, {{ auth()->user()->name }}!">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown.items text="Logout"
                                onclick="event.preventDefault(); this.closest('form').submit();" />
                        </form>
                    </x-dropdown>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>

        <x-slot:menu>
            <x-side-bar collapsible>
                <x-slot:brand>
                    <div class="flex h-16 items-center px-4">
                        <img src="/logo.svg" alt="Logo" class="h-8" />
                        <span class="ml-2 text-lg font-bold">MyApp</span>
                    </div>
                </x-slot:brand>

                <x-slot:brand-collapsed>
                    <div class="flex h-16 items-center justify-center">
                        <img src="/icon.svg" alt="Logo" class="h-8" />
                    </div>
                </x-slot:brand-collapsed>

                <x-side-bar.separator text="Navigation" />
                <x-side-bar.item text="Dashboard" icon="home" route="/" />
                <x-side-bar.item text="Documents" icon="document-text" route="/documents" />
                <x-side-bar.item text="Messages" icon="envelope" route="/messages" badge="3" />

                <x-side-bar.separator text="Management" />
                <x-side-bar.item text="Settings" icon="cog-6-tooth" badge="!">
                    <x-side-bar.item text="Profile" route="/settings/profile" />
                    <x-side-bar.item text="Account" route="/settings/account" />
                    <x-side-bar.item text="Billing" route="/settings/billing" />
                </x-side-bar.item>
                <x-side-bar.item text="Users" icon="users" route="/users" />

                <x-slot:footer>
                    <div class="flex items-center gap-3 px-2">
                        <x-avatar sm />
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                </x-slot:footer>
            </x-side-bar>
        </x-slot:menu>

        <div class="p-4">
            <h1 class="text-2xl font-bold">Page Content</h1>
            <p>Your main content goes here.</p>
        </div>
    </x-layout>
</body>
```

## Files Modified

| File | Change |
|------|--------|
| `src/Components/Layout/SideBar/Main/collapse.js` | Added `collapsible` property, `setBreakpoint()` method, refactored media query into `_setupBreakpoint()` |
| `src/Components/Layout/SideBar/Main/Component.php` | Added `$footer`, `$brandCollapsed` slots, `$breakpointPx` property, footer/items customization keys, `transition-[width]`, breakpoint-aware `mobile.wrapper.first` and `desktop.wrapper.first.*` classes |
| `src/Components/Layout/SideBar/Item/Component.php` | Added `$badge` prop, `text.visible`/`text.hidden`, `badge`/`badge.visible`/`badge.hidden`, `tooltip` customization keys for items and groups |
| `src/Components/Layout/SideBar/Separator/Component.php` | Added `base.visible`/`base.hidden` customization keys, transition classes |
| `src/Components/Layout/Header/Component.php` | Added `$scrollShadow` prop, `collapse.*` and `scroll.shadow` customization keys, breakpoint-aware `button.class` and `collapse.class` |
| `src/Components/Layout/Main/Component.php` | Added `$breakpoint` prop, `$resolvedBreakpoint` static, `validate()` method, breakpoint-aware `wrapper.second.*` classes, `transition-[padding] duration-300` |
| `src/resources/views/components/layout/sidebar/main.blade.php` | Removed collapse button, added `x-init` for store flag + breakpoint, restructured scroll layout, added footer slots, added `brand-collapsed` toggle |
| `src/resources/views/components/layout/sidebar/item.blade.php` | Replaced `x-show`/`x-transition` with CSS class-based transitions, hide group sub-items when collapsed, added tooltip rendering, added badge rendering |
| `src/resources/views/components/layout/sidebar/separator.blade.php` | Replaced `x-text` truncation with CSS transitions |
| `src/resources/views/components/layout/header.blade.php` | Added collapse toggle button via Alpine store, added scroll shadow via `x-data`/`x-on:scroll.window` |
